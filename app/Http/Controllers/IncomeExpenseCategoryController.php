<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\IncomeExpenseCategory;
use App\Models\GeneralLedgerTransaction;
use App\Models\Preference;
use App\Http\Start\Helpers;
use Session;
use Input;
use DB;
use Cache;
use Validator;

class IncomeExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the Category.
     *
     * @return Category List page view
     */
    public function index()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'general';
        $data['page_title'] = __('Income Expense Category');
        $data['list_menu'] = 'income-expense-category';
        $data['types']     = array('income'=>'Income','expense'=>'Expense');
        $data['categoryList'] = IncomeExpenseCategory::all();
        
        return view('admin.IncomeExpenseCategory.category_list', $data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:income_expense_categories,name',
            'type' => 'required',
        ]);

        
        $newIncomeExpenseCategory       = new IncomeExpenseCategory();
        $newIncomeExpenseCategory->name = $request->name;
        $newIncomeExpenseCategory->category_type = $request->type;
        $newIncomeExpenseCategory->save();

        Session::flash('success', __('Successfully Saved'));
        return redirect()->intended('income-expense-category/list');

    }

    public function edit(Request $request)
    {
        if (!empty($request->id)) {
            $categoryData = IncomeExpenseCategory::find($request->id);
            $return_arr['name'] = $categoryData->name;
            $return_arr['type'] = $categoryData->category_type;
            $return_arr['id'] = $categoryData->id;

            echo json_encode($return_arr);
        }
    }

    public function update(Request $request)
    {    
        $this->validate($request, [
            'name' => 'required',
            'type' => 'required'
        ]);

        $check  = IncomeExpenseCategory::where('id', '!=', $request->id)->where(['name' => $request->name, 'category_type' => $request->type])->exists();
        if ( $check === true) {
             $data = [ 
                    'type' => 'fail', 
                    'message' => __('That Category Name is already taken.')
                ];
        } else {
                $id                                  = $request->id;
                $categoryDataToUpdate                = IncomeExpenseCategory::find($id);
                $categoryDataToUpdate->name          = $request->name;
                if (!empty($categoryDataToUpdate)) {
                    $glTransCategory = GeneralLedgerTransaction::where('gl_account_id', $id)->exists();
                    $defaultCategory = Preference::where('category', '=', 'gl_account')->where('value', $id)->exists();
                    if ($glTransCategory === true || $defaultCategory === true) {
                         $data = [ 
                            'type'    => 'fail',
                            'message' => __('Type can not be change. This category has records.')
                        ];
                    } else {
                        $categoryDataToUpdate->category_type = $request->type;
                        $categoryDataToUpdate->save();

                        $data = [ 
                            'type' => 'success', 
                            'message' => __('Successfully updated')
                        ]; 
                    }
                }
                
        }
        
        Session::flash($data['type'], $data['message']);
        return redirect()->intended('income-expense-category/list');
    }


    public function destroy($id)
    {
        $data = [ 
                    'type'    => 'fail',
                    'message' => __('Something went wrong, please try again.')
                ];
       
        $categoryDataToDelete = IncomeExpenseCategory::find($id);
        if (!empty($categoryDataToDelete)) {
            $glTransCategory = GeneralLedgerTransaction::where('gl_account_id', $id)->exists();
            $defaultCategory = Preference::where('category', '=', 'gl_account')->where('value', $id)->exists();
            $expenseCategory = DB::table('expenses')->where('income_expense_category_id', $id)->exists();
            $depositCategory = DB::table('deposits')->where('income_expense_category_id', $id)->exists();
            if ($glTransCategory === true || $defaultCategory === true || $expenseCategory === true || $depositCategory === true) {
                 $data = [ 
                    'type'    => 'fail',
                    'message' => __('Can not be deleted. This category has records.')
                ];
            } else {
                if ($categoryDataToDelete->delete()) {
                    $data = [ 
                        'type'    => 'success',
                        'message' => __('Deleted Successfully.')
                    ];
                } 
            }
        }
        \Session::flash($data['type'],$data['message']);
        return redirect()->intended('income-expense-category/list');
    }

    public function validCategoryName(Request $request)
    {
        $catName      = $request->catName;
        $catType      = $request->catType;
        if ($catName != null && $catType != null) {
            $v  = IncomeExpenseCategory::where('name', $catName)->where('category_type', $catType)->exists();
            if ($v === true) {
                return "true";
            } else {
                return "false";
            }
                
        } 
    }

    public function editValidCategoryName(Request $request)
    {
        $id          = $request->id;
        $editCatName = $request->edit_catName;
        $editCatType = $request->edit_catType;
        if ($editCatName != null && $editCatType != null) {
            $v  = IncomeExpenseCategory::where('name', $editCatName)
                                        ->where('category_type', $editCatType)->where('id', '!=', $id)->exists();
            if ($v === true) {
                return "true";
            } else {
                return "false";
            }
        }
    }
}
