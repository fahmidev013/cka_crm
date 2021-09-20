<?php

namespace App\Http\Controllers;

use App\Exports\itemCategoriesExport;
use App\Http\Requests;
use App\Http\Start\Helpers;
use App\Models\StockCategory;
use App\Models\ItemUnit;
use App\Models\Item;
use App\Models\Preference;
use DB;
use Session;
use Excel;
use Illuminate\Http\Request;
use Input;
use Validator;
use Cache;

class CategoryController extends Controller
{
    /**
     * Display a listing of the Category.
     *
     * @return Category List page view
     */
    public function index()
    {
        $data['access'] = \Session::all();
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'general';
        $data['page_title'] = __('Item Categories');
        $data['list_menu'] = 'category';
        
        $data['categoryData'] = StockCategory::all();
        $data['unitData'] = ItemUnit::all();
        
        if (Helpers::has_permission(\Auth::user()->id, 'manage_item_category')) {
            return view('admin.category.category_list', $data);
        }
        
        return redirect(getRouteAccordingToPermission([
                'manage_language'                => 'language',
                'manage_income_expense_category' => 'income-expense-category/list',
                'manage_unit'                    => 'unit',
                'manage_db_backup'               => 'backup/list',
                'manage_email_setup'             => 'email/setup',
                'manage_sms_setup'               => 'sms/setup',
                'manage_lead_status'             => 'lead-status',
                'manage_lead_source'             => 'lead-source'
            ]));
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect Category List page view
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'required|unique:stock_categories,name',
            'dflt_units' => 'required',
        ]);

        $newCategory               = new StockCategory();
        $newCategory->item_unit_id = $request->dflt_units;
        $newCategory->name         = $request->description;
        $newCategory->is_active    = $request->status;
        $newCategory->save();
        
        Cache::forget('gb-stock_categories');

        $id = $newCategory->id;

        if (!empty($id)) {
            \Session::flash('success', __('Successfully Saved'));
            return redirect()->intended('item-category');
        } else {
            return back()->withInput()->withErrors(['email' => __("Invalid Request")]);
        }
    }

    /**
     * Show the form for editing the specified Category.
     *
     * @param  int  $id
     * @return Category edit page view
     */
    public function edit(Request $request)
    {
        if (!empty($request->id)) {
            $categoryData             = StockCategory::find($request->id);
            $return_arr['name']       = $categoryData->name;
            $return_arr['dflt_units'] = $categoryData->item_unit_id;
            $return_arr['id']         = $categoryData->id;
            $return_arr['status']     = $categoryData->is_active;

            echo json_encode($return_arr);
        }
    }

    /**
     * Update the specified Category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return redirect Category List page view
     */
    public function update(Request $request)
    {    
        $this->validate($request, [
            'description' => 'required',
            'dflt_units'  => 'required',
            'cat_id'      => 'required',
        ]);

        $categoryToUpdate               = StockCategory::find($request->cat_id);
        $categoryToUpdate->item_unit_id = $request->dflt_units;
        $categoryToUpdate->name         = $request->description;
        $categoryToUpdate->is_active    = $request->status;
        $categoryToUpdate->save();
        Cache::forget('gb-stock_categories');
        
        Session::flash('success', __('Successfully updated'));
            return redirect()->intended('item-category');
    }

    /**
     * destroy method
     * @param  int  $id
     * @return redirect Category List page view
     */

    public function destroy($id)
    {
        $data = [ 
                    'type'    => 'fail',
                    'message' => __('Something went wrong, please try again.')
                ];
        $categoryToDelete = StockCategory::find($id);
        if (!empty($categoryToDelete)) {
            $item_category    = Item::where('stock_category_id', $id)->exists();
            if ($item_category === true) {
                 $data = [ 
                    'type'    => 'fail',
                    'message' => __('Can not be deleted. This category has records.')
                ];
            } else {
                if ($categoryToDelete->delete()) {
                    $data = [ 
                        'type'    => 'success',
                        'message' => __('Deleted Successfully.')
                    ];
                    Cache::forget('gb-stock_categories');
                } 
            }

        }
        \Session::flash($data['type'], $data['message']);
        return redirect()->intended('item-category');
    }

    public function downloadCsv($type)
    {
        $_GET['type'] = $type;        
        return Excel::download(new itemCategoriesExport(), __('item_categories_details') . time() . '.csv');
    }
    
    /**
     * import Method
     * @return view with data array
     */
    public function import()
    {
        $menu = 'setting';
        $sub_menu = 'general';
        $list_menu = 'category';

        $data = ['menu' => $menu, 'sub_menu' => $sub_menu, 'list_menu' => $list_menu, 'page_title' => __('Category Import')];
        
        return view('admin.category.category_import', $data);
    }

    /**
     * [getAbbrId, this function returns id of a abbr of unit]
     * @param  [string] $abbr [abbr of unit]
     * @return [int]          [id of the abbr]
     */
    public function getAbbrId($abbr)
    {
        $id = ItemUnit::where('abbreviation', $abbr)->get(['id']);
        return $id;
    }

    /**
     * [importCsv               it will write categories in system from the uploaded CSV file]
     * @param  Request $request [for get he uploaded file]
     * @return [view]           [if success then catogory list page, if previous page where attempted for import data]
     */
    public function importCsv(Request $request)
    {
        $file = $request->file('item_image');

        $validator = Validator::make(
            [
                'file'      => $file,
                'extension' => strtolower($file->getClientOriginalExtension()),
            ],
            [
                'file'          => 'required',
                'extension'      => 'required|in:csv',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors(__("File type Invalid"));
        }

        if (Input::hasFile('item_image')) {
            $path = Input::file('item_image')->getRealPath();
            $data = [];
            
            if (is_uploaded_file($path)) {
                $data = readCSVFile($path, true);
            }

            if (empty($data)) {
                return back()->withErrors(__("Your CSV has no data to import"));
            }

            $requiredHeader = ["Category", "Abbr"];
            $header = array_keys($data[0]);

            // Check if required headers are available or not
            if (!empty(array_diff($requiredHeader, $header))) {
                return back()->withErrors( __("Please Check CSV Header Name."));
            }

            // Get units
            $units   = DB::table('item_units')->pluck('abbreviation', 'id')->toArray();
            if (empty($units)) {
                return back()->withErrors(__("No unit found. Please add unit first."));
            }
            $units = array_flip(array_map('strtolower', $units));

            // Get categories
            $categories   = DB::table('stock_categories')->pluck('name')->toArray();
            if (!empty($categories)) {
                $categories = array_map('strtolower', $categories);
            }

            $insertedRow = 0;
            $itemCategories = $errorMessages = [];
            foreach ($data as $key => $value) {
                $errorFails = [];
                $categoryName = strtolower(trim($value['Category']));
                $unit = strtolower(trim($value['Abbr']));

                // check for category name
                if (empty($categoryName)) {
                    $errorFails[] = __(':? is required.', ['?' => __('Name')]);
                } else if (in_array($categoryName, $categories)) {
                    $errorFails[] = __(':? is already taken.', ['?' => __('Name')]);
                }

                // check for unit
                if (empty($unit)) {
                    $errorFails[] = __(':? is required.', ['?' => __('Unit')]);
                } else if (!array_key_exists($unit, $units)) {
                    $errorFails[] = __(':? not found', ['?' => __('Unit')]);
                }
                
                if (empty($errorFails)) {
                    try {
                        DB::beginTransaction();
                        $newCategory = new StockCategory();
                        $newCategory->name = $categoryName;
                        $newCategory->item_unit_id = $units[$unit];
                        $newCategory->save();

                        // Push to categories for duplicate check
                        array_push($categories, $newCategory->name);

                        // commit now
                        DB::commit();
                    } catch (\Exception $e) {

                        // rollBack on fails
                        DB::rollBack();

                        // get the error message
                        $errorFails[] = $e->getMessage();
                    }
                }

                // set the error messages
                if (!empty($errorFails)) {
                    $errorMessages[$key] = ['fails' => $errorFails, 'data' => $value];
                }
            }

            if (empty($errorMessages)) {
                \Session::flash('success', __('Total Imported row: :?', ['?' => count($data)]));
                return redirect()->intended('item-category');
            } else {
                $itemCategories['menu'] = 'setting';
                $itemCategories['sub_menu'] = 'general';
                $data['page_title'] = __('Category Import Issues');
                $itemCategories['list_menu'] = 'category';
                $itemCategories['totalRow'] = count($data);

                return view('layouts.includes.csv_import_errors', $itemCategories)->with('errorMessages', $errorMessages);
            }
        } else {
            return back()->withErrors(['fail' => __("Please Upload a CSV File.")]);
        }
    }

    public function validCategoryName(Request $request)
    {
        $catName = $_GET['description'];
        if (isset($_GET['cat_id'])) {
            $id = $_GET['cat_id'];
            $v  = StockCategory::where('id', '!=', $id)
                ->where('name', $catName)
                ->exists();
        } else {
            $v = StockCategory::where('name', $catName)
                ->exists();
        }
        if ($v == "true") {
            echo json_encode( __('That Category Name is already taken.') );
        } else {
            return "true";
        }
    }

    public function editValidCategoryName(Request $request)
    {
        $id = $request->id;
        $catName = $request->catName;
        if ($catName != null && $id != null) {
            $v = StockCategory::where('id', '!=', $id)
                ->where('name', $catName)
                ->exists();
            if ($v == "true") {
                echo json_encode( __('Category name is already taken.') );
            } else {
                echo "true";
            }
        }
    }
}
