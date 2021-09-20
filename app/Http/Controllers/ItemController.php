<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Start\Helpers;
use Intervention\Image\ImageManagerStatic as Image;
use Session;
use Excel;
use Input;
use DB;
use Validator;
use App\Models\Currency;
use App\Models\StockCategory;
use App\Models\Item;
use App\Models\ItemCustomVariant;
use App\Models\Location;
use App\Models\Preference;
use App\Models\Report;
use App\Models\StockMove;
use App\Models\TaxType;
use App\Models\File;
use \Milon\Barcode\DNS1D;
use Datatables;
use App\DataTables\ItemDataTable;
use App\DataTables\VariantDatatable;
use PDF;
use App\Exports\ItemListExport;
use App\Rules\CheckValidFile;

class ItemController extends Controller
{
    /**
     * [__construct description]
     * @param Report $report [description]
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    /**
     * Display a listing of the Item.
     *
     * @return Item list page view
     */
    public function index(ItemDataTable $dataTable)
    {
        $data = ['menu' => 'item', 'header' => 'item', 'page_title' => __('Items')];
        $data['stockMoves'] = DB::table('items')
                          ->select('items.*',DB::raw('SUM(stock_moves.quantity) as qty'))
                          ->leftJoin('stock_moves', 'stock_moves.item_id', '=', 'items.id')
                          ->where('items.alert_quantity', '>=', DB::raw('quantity'))
                          ->groupBy('items.id')
                          ->get();
        $data['itemData'] = DB::table('items')->count();
        $data['itemQuantity'] = DB::table('stock_moves')->select(DB::raw('SUM(quantity) as total_item'))->first();
        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;
        $currency = Currency::getDefault();
        return $dataTable->with('row_per_page', $row_per_page)->with('currency', $currency)->render('admin.item.view', $data);
    }

    /**
     * Show the form for creating a new Item.
     *
     * @return Item create page view
     */
    public function create()
    {
        $data = ['menu' => 'item', 'page_title' => __('Create Item')];

        $unit = DB::table('item_units')->get();
        $unit_name = array();
        foreach ($unit as $value) {
            $unit_name[$value->id] = $value->name;
        }

        $data['unit_name']    = $unit_name;
        $data['locData']      = Location::getAll();
        $data['taxTypes']     = TaxType::getAll();
        $data['saleTypes']    = DB::table('sale_types')->get();
        $data['categoryData'] = StockCategory::where('is_active', 1)->get();
        $data['unitData']     = DB::table('item_units')->get();

        return view('admin.item.add', $data);
    }

    /**
     * Store a newly created Item in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return redirection Item list page view
     */
    public function store(Request $request)
    {
        $data = [];
        $this->validate($request, [
            'item_name'   => 'required',
            'item_id'     => 'required|unique:items,stock_id',
            'item_type'   => 'required',
            'units'       => 'required',
            'tax_type_id' => 'required',
            'item_image'  => [new CheckValidFile(getFileExtensions(2))],
        ]);

        if (isset($request->manage_stock) && $request->manage_stock=='on') {
           Validator::make($request->all(), [
            'initial_stock' => 'required',
            'cost_price'  => 'required'
            ]);
        }

        try {
            \DB::beginTransaction();
            $data['stock_id']    = strtoupper(trim($request->item_id));
            $data['stock_category_id'] = stripBeforeSave($request->category_id);
            $data['item_type']    = stripBeforeSave($request->item_type);
            $data['name']         = stripBeforeSave($request->item_name);
            $data['item_unit_id'] = stripBeforeSave($request->units);
            $data['description']  = !empty($request->description) ? $request->description : '';
            $data['hsn']          = stripBeforeSave($request->hsn);
            $data['tax_type_id']  = stripBeforeSave($request->tax_type_id);
            $data['is_active']    = $request->inactive;
            if ($request->item_type == 'service') {
                $request['multi_variants'] = null;
            }
            if ($request->item_type == 'product') {
                $isColor            = $request->variant_color == 'on' ? 1 : 0;
                $isSize             = $request->variant_size == 'on' ? 1 : 0;
                $isWeight           = $request->variant_weight == 'on' ? 1 : 0;
                $isMultiVariant     = $request->multi_variants == 'on' ? 1 : 0;
                $canManageStore     = $request->manage_stock == 'on' ? 1 : 0;
                $pic                = ($request->hasFile('item_image')) ? $request->file('item_image') : null;
                $variantSize        = stripBeforeSave($request->size);
                $variantColor       = stripBeforeSave($request->color);
                $variantWeight      = validateNumbers($request->weight);
                $variantWeightUnit  = stripBeforeSave($request->weight_unit);
                $customVariantTitle = stripBeforeSave($request->variant_title);
                $customVariantValue = stripBeforeSave($request->variant_value);
                $initialStock       = validateNumbers($request->initial_stock);
                $costPrice          = validateNumbers($request->cost_price);
                $stockLocation      = stripBeforeSave($request->stock_location);
                // For item alert quantity
                if (isset($request->alert_quantity) && !empty($request->alert_quantity)) {
                    $data['alert_quantity'] = validateNumbers($request->alert_quantity);
                }
                $availableVariant = [];
                if ($isMultiVariant) {
                    $availableData = $data['name'];
                    if ($isSize) {
                        $availableVariant["size"] = $variantSize;
                        $availableData .= " - ". $variantSize;
                    } else {
                        $availableVariant["size"] = null;
                    }

                    if ($isColor) {
                        $availableVariant["color"] = $variantColor;
                        $availableData .= " - ". $variantColor;
                    } else {
                        $availableVariant["color"] = null;
                    }

                    if ($isWeight) {
                        $availableVariant["weight"] = $variantWeight;
                        $availableData .= " - ". $variantWeight;
                    } else {
                        $availableVariant["weight"] = null;
                    }

                    $data['available_variant'] = $availableData;
                    if ($isSize) {
                        $data['size'] = $variantSize;
                    }
                    if ($isColor) {
                        $data['color'] = $variantColor;
                    }
                    if (!empty($request->weight)) {
                        $data['weight'] = validateNumbers($request->weight);
                        $data['weight_unit_id'] = $request->weight_unit;
                    }
                }
                $data['is_stock_managed'] = 0;
                if ($canManageStore) {
                    $data['is_stock_managed'] = 1;
                }
            }
            $id = DB::table('items')->insertGetId($data);

            if (isset($isMultiVariant) && !empty($customVariantTitle)) {
                $customVariant = [];
                foreach ($customVariantTitle as $key => $variant) {
                    if (isset($variant) && !empty(trim($variant))) {
                        $customVariant[$key]['item_id']       = $id;
                        $customVariant[$key]['variant_title'] = $customVariantTitle[$key];
                        $customVariant[$key]['variant_value'] = $customVariantValue[$key];
                    }
                }
                DB::table('item_custom_variants')->insert($customVariant);
            }
            if (!empty($id)) {
                $data3[0]['item_id']        = $id;
                $data3[0]['sale_type_id']   = 1;
                $data3[0]['currency_id']    = Currency::getDefault()->id;
                $data3[0]['price']          = isset($request->retail_price) ? validateNumbers($request->retail_price) : 0;

                $data3[1]['item_id']        = $id;
                $data3[1]['sale_type_id']   = 2;
                $data3[1]['currency_id']    = Currency::getDefault()->id;
                if ($request->wholesalePrice == 'on') {
                    $data3[1]['price']      = isset($request->retail_price) ? validateNumbers($request->retail_price) : 0;
                } else {
                    $data3[1]['price']      = isset($request->wholesale_price) ? validateNumbers($request->wholesale_price) : 0;
                }

                DB::table('sale_prices')->insert($data3);

                $purchaseInfos['item_id']       = $id;
                $purchaseInfos['currency_id']   = Currency::getDefault()->id;
                $purchaseInfos['price']         = isset($request->purchase_price) ? validateNumbers($request->purchase_price) : 0;
                DB::table('purchase_prices')->insert($purchaseInfos);

                if (isset($canManageStore) && $canManageStore === 1) {
                    $stockMoves['item_id']          = $id;
                    $stockMoves['transaction_type'] = 'INITIALSTOCKIN';
                    $stockMoves['location_id']      = $stockLocation;
                    $stockMoves['transaction_date'] = date('Y-m-d H:i:s');
                    $stockMoves['user_id']          = \Auth::user()->id;
                    $stockMoves['reference']        = "store_in_".$id;
                    $stockMoves['quantity']         = $initialStock;
                    $stockMoves['price']            = $costPrice;
                    $stockMoves['note']             = "Added from initial stock";

                    DB::table('stock_moves')->insert($stockMoves);
                }
            }

            # region store files
            if (isset($pic) && !empty($pic)) {
                if (isset($id) && !empty($id)) {
                    $path = createDirectory("public/uploads/items");
                    $fileIdList = (new File)->store([$pic], $path, 'Item', $id, ['isUploaded' => false, 'isOriginalNameRequired' => true, 'resize' => false]);

                    if (isset($fileIdList[0]) && !empty($fileIdList[0])) {
                        $uploadedFileName = File::find($fileIdList[0])->file_name;
                        $uploadedFilePath = asset($path . '/' . $uploadedFileName);
                        $thumbnailPath = createDirectory("public/uploads/items/thumbnail");
                        (new File)->resizeImageThumbnail($uploadedFilePath, $uploadedFileName, $thumbnailPath);
                    }
                }
            }
            # end region

            \DB::commit();

            \Session::flash('success', __('Successfully Added'));
            return redirect('edit-item/item-info/'. $id);

        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * [itemNameValidationRemote description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function itemNameValidationRemote(Request $request)
    {
        if (isset($request->description)) {
            $result = DB::table('items')
                ->where(["name" => $request->description])
                ->where('id', "!=", $request->id)
                ->count();
        } else {
            $result = DB::table('items')
                ->where(["name" => $request->description])
                ->count();
        }

        if ($result) {
            echo json_encode(__('This name is used already.'));
        } else {
            echo 'true';
        }
    }

    /**
     * [stockIdValidationRemote description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function stockIdValidationRemote(Request $request)
    {
        $stockID = stripBeforeSave($request->stock_id);
        if (isset($request->stock_id)) {
            $result = DB::table('items')
                ->where(["stock_id" => $stockID])
                ->where('id', "!=", $request->id)
                ->count();
        } else {
            $result = DB::table('items')
                ->where(["stock_id" => $stockID])
                ->count();
        }
        if ($result) {
            echo json_encode($stockID . ' ' . __('is used already.'));
        } else {
            echo 'true';
        }
    }

    /**
     * Show the form for editing the specified Item.
     *
     * @param  int $id
     * @return Item edit page view
     */
    public function edit($tab, $id, VariantDatatable $dataTable)
    {
        $data = ['files' => [], 'menu' => 'item', 'header' => 'item', 'breadcrumb' => 'additem', 'page_title' => __('Item variants')];
        $data['locData'] = $loc = Location::getAll();
        $data['taxTypes']  = TaxType::getAll();
        $data['saleTypes'] = DB::table('sale_types')->get();
        $data['categoryData'] = StockCategory::where('is_active', 1)->get();
        $data['unitData'] = DB::table('item_units')->get();
        $data['suppliers'] = DB::table('suppliers')->get();

        $loc_name = array();
        foreach ($loc as $value) {
            $loc_name[$value->code] = $value->name;
        }

        $data['loc_name'] = $loc_name;

        $salesTypeName = array();
        foreach ($data['saleTypes'] as $value) {
            $salesTypeName[$value->id] = $value->sale_type;
        }

        $data['salesTypeName'] = $salesTypeName;

        $data['primaryItem'] = DB::table('items')->where('id', $id)->first();

        $data['parentItem'] = Item::where('id', $data['primaryItem']->parent_id)->first();

        $data['itemInfo'] = $itemInfo = DB::table('items')
            ->leftJoin(DB::raw("(SELECT sale_prices.item_id, sale_prices.price as retail_price FROM sale_prices where sale_type_id = 1) as sp"), 'sp.item_id', 'items.id')
            ->leftJoin(DB::raw("(SELECT sale_prices.item_id, sale_prices.price as wholesale_price FROM sale_prices where sale_type_id = 2) as hsp"), 'hsp.item_id', 'items.id')
            ->leftjoin('purchase_prices', 'purchase_prices.item_id', 'items.id')
            ->where('items.id', $id)
            ->select('items.*', 'sp.retail_price', 'hsp.wholesale_price', 'purchase_prices.price as purchase_price')
            ->first();

        if (!$itemInfo) {
            Session::flash('fail', __('Item not found'));
            return redirect()->to('item');
        }

        $data['files'] = (new File)->getFiles('Item', $id, ['limit' => 1]);

        $data['itemQuantity'] = DB::table('stock_moves')
            ->select(DB::raw('SUM(quantity) as total_item'), 'location_id')
            ->where('item_id', strtoupper($data['itemInfo']->id))
            ->groupBy('location_id')
            ->get();

        $data['tab'] = $tab;

        $data['transations'] = StockMove::with(['item:id,name', 'location:id,name', 'saleOrder:id,order_reference_id'])->where('item_id', $data['itemInfo']->id)->orderBy('id', 'ASC')->get();
        $availableVariant = json_decode($itemInfo->available_variant);
        $availableVariantArray = [];
        if ($availableVariant) {
            if (isset($availableVariant->color)) {
                array_push($availableVariantArray, 'color');
            }
            if (isset($availableVariant->weight)) {
                array_push($availableVariantArray, 'weight');
            }
            if (isset($availableVariant->size)) {
                array_push($availableVariantArray, 'size');
            }
        }
        $data['availableVariantArray'] = $availableVariantArray;
        $data['customVariants'] = DB::table('item_custom_variants')->where('item_id', $itemInfo->id)->get();

        $checkVariantArray = false;
        if (isset($data['itemInfo']) && ! empty($data['itemInfo'])) {
            foreach ($data['itemInfo'] as $key => $value) {
                if ($key == 'size') {
                    if ($value != null) {
                        $checkVariantArray = true;
                    }
                }
                if ($key == 'color') {
                    if ($value != null) {
                        $checkVariantArray = true;
                    }
                }
                if ($key == 'weight') {
                    if ($value != null) {
                        $checkVariantArray = true;
                    }
                }
            }
        }

        if (count($data['customVariants']) != 0) {
            $checkVariantArray = true;
        }

        $data['checkVariantArray'] = $checkVariantArray;

        $data['variants'] = DB::table('items')
                                ->leftJoin(DB::raw("(SELECT sale_prices.item_id, sale_prices.price as retail_price FROM sale_prices where sale_type_id = 1) as sp"), 'sp.item_id', 'items.id')
                                ->leftJoin(DB::raw("(SELECT sale_prices.item_id, sale_prices.price as wholesale_price FROM sale_prices where sale_type_id = 2) as hsp"), 'hsp.item_id', 'items.id')
                                ->leftJoin('purchase_prices', 'items.id', 'purchase_prices.item_id')
                                ->leftJoin(DB::raw("(SELECT item_id,sum(quantity) as item_qty FROM stock_moves GROUP BY item_id) as sm"), 'sm.item_id', 'items.id')
                                ->where(function($query) use ($id) {
                                    $query->where('items.id', $id)
                                    ->orWhere('items.parent_id', $id);
                                })
                                ->select('items.id', 'items.name', 'retail_price', 'wholesale_price', 'purchase_prices.price as purchase_price', 'sm.item_qty')
                                ->get();

        $data['breadcrumbs'] = json_decode(json_encode([
            ['title' => 'Items', 'url' => url('item'), 'class' => null],
            ['title' => $itemInfo->name, 'url' => null, 'class' => 'active'],
        ]));

        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;
        $currency = Currency::getDefault();

        return $dataTable->with('row_per_page', $row_per_page)->with('currency', $currency)->render('admin.item.item_edit', $data);
    }

    /**
     * Update the specified Item in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return redirection Item list page view
     */
    public function updateItemInfo(Request $request)
    {
        $data = [];
        $this->validate($request, [
            'stock_id'  => 'required',
            'item_name' => 'required',
            'units'     => 'required',
            'item_image' => [new CheckValidFile(getFileExtensions(2))],
        ]);

        $data['stock_category_id']  = stripBeforeSave($request->category_id);
        $data['name']               = stripBeforeSave($request->item_name);
        $data['item_unit_id']       = stripBeforeSave($request->units);
        $data['hsn']                = stripBeforeSave($request->hsn);
        $data['tax_type_id']        = stripBeforeSave($request->tax_type_id);
        $data['description']        = $request->description;
        $data['is_active']          = $request->inactive;
        $data['is_stock_managed']   = isset($request->statusManage) ? 1 : 0;
        $data['updated_at']         = date('Y-m-d H:i:s');
        $pic                        = ($request->hasFile('item_image')) ? $request->file('item_image') : null;

        DB::table('items')->where('id', $request->id)->update($data);

        $itemInfo = Item::find($request->id);
        if (! empty($itemInfo->available_variant)) {
            $itemInfo->color          = stripBeforeSave($request->color);
            $itemInfo->size           = stripBeforeSave($request->size);
            $itemInfo->weight         = validateNumbers($request->weight);
            $itemInfo->weight_unit_id = stripBeforeSave($request->weight_unit);
            $itemInfo->save();
        }
        $customVariants = stripBeforeSave($request->custom_variant_id);
        $variant_title  = stripBeforeSave($request->variant_title);
        $variant_value  = stripBeforeSave($request->variant_value);
        if (isset($customVariants) && ! empty($customVariants)) {
            foreach ($customVariants as $key => $variant) {
                $newCustomVariant['variant_title'] = $variant_title[$key];
                $newCustomVariant['variant_value'] = $variant_value[$key];
                DB::table('item_custom_variants')
                    ->where(['item_id' => $itemInfo->id, 'id' => $variant])
                    ->update($newCustomVariant);
            }
        }

        $sales_id = $request->sales_id;
        if (empty($request->retail_price)) {
            $request->retail_price = 0;
        }

        if (empty($request->wholesalePriceCheck) && empty($request->wholesale_price)) {
            $request->wholesale_price = 0;
        }

        if (! empty($request->wholesalePriceCheck)) {
            $request->wholesale_price = validateNumbers($request->retail_price);
        }

        DB::table('sale_prices')
            ->where('item_id', $request->id)
            ->where('sale_type_id', 1)
            ->update(['price' => validateNumbers($request->retail_price)]);

        DB::table('sale_prices')
            ->where('item_id', $request->id)
            ->where('sale_type_id', 2)
            ->update(['price' => validateNumbers($request->wholesale_price)]);

        $data_purch['price'] = validateNumbers($request->purchase_price);
        $data_purch['item_id'] = $request->id;
        $priceInfo = DB::table('purchase_prices')->where('item_id', $request->id)->count();
        if ($priceInfo == 0) {
            DB::table('purchase_prices')->insert($data_purch);
        } else {
            DB::table('purchase_prices')->where('item_id', $request->id)->update($data_purch);
        }

        //** File Related Task Will be Added later
        if (isset($pic) && ! empty($pic)) {
            # delete file region
            $fileIds = array_column(json_decode(json_encode(File::Where(['object_type' => 'Item', 'object_id' => $request->id])->get(['id'])), true), 'id');
            $oldFileName = isset($fileIds) && !empty($fileIds) ? File::find($fileIds[0])->file_name : null;
            if (!empty($fileIds)) {
                (new File)->deleteFiles('Item', $request->id, ['ids' => [$fileIds], 'isExceptId' => false], $path = 'public/uploads/items');
            }
            # end region

            # region store files
            if (isset($request->id) && !empty($request->id)) {
                $path = createDirectory("public/uploads/items");
                $fileIdList = (new File)->store([$pic], $path, 'Item', $request->id, ['isUploaded' => false, 'isOriginalNameRequired' => true, 'resize' => false]);

                if (isset($fileIdList[0]) && !empty($fileIdList[0])) {
                    $uploadedFileName = File::find($fileIdList[0])->file_name;
                    $uploadedFilePath = asset($path . '/' . $uploadedFileName);
                    $thumbnailPath = createDirectory("public/uploads/items/thumbnail");
                    (new File)->resizeImageThumbnail($uploadedFilePath, $uploadedFileName, $thumbnailPath, $oldFileName);
                }
            }
            # end region
        }

        \Session::flash('success', __('Successfully updated'));
        return redirect()->intended('edit-item/item-info/' . $request->id);
    }

    /**
     * Remove the specified Item from storage.
     *
     * @param  int $id
     * @return redirect Item list page view
     */
    public function destroy(Request $request)
    {
        $record = Item::find($request->id);
        if (count($record->variants) > 0) {
            \Session::flash('fail', __('Can not be deleted. This item has variant.'));
            return redirect()->back();
        } elseif (count($record->stockMoves) > 0) {
           \Session::flash('fail', __('Can not be deleted. This item has transaction records.'));
            return redirect()->back();
        } elseif (count($record->purchaseOrderDetail) > 0) {
           \Session::flash('fail', __('Can not be deleted. This item has pending purchase.'));
            return redirect()->back();
        } elseif (count($record->salesOrderDetails) > 0) {
           \Session::flash('fail', __('Can not be deleted. This item has sale records.'));
            return redirect()->back();
        } else {
            try {
                DB::beginTransaction();
                $record->delete();
                DB::commit();
                # delete file region
                $fileIds = array_column(json_decode(json_encode(File::Where(['object_type' => 'Item', 'object_id' => $request->id])->get(['id'])), true), 'id');
                if (!empty($fileIds)) {
                    (new File)->deleteFiles('Item', $request->id, ['ids' => [$fileIds], 'isExceptId' => false], $path = 'public/uploads/items');
                }
                # end region
                \Session::flash('success', __('Deleted Successfully.'));
                return redirect()->intended('item');
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
            }
        }
        return back();
    }

    /**
     * [ajaxStockManage description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function ajaxStockManage(Request $request)
    {
        $flag = DB::table('items')->where('id', $request->id)->update(['is_stock_managed' => $request->flag]);

        return json_encode($request->flag);
    }

    /**
     * [downloadCsv description]
     * @param  [type] $type [description]
     * @return [type]       [description]
     */
    public function downloadCsv($type)
    {
        return Excel::download(new ItemListExport(), 'item_list'. time() .'.csv');
    }

    /**
     * [downloadVariantCsv description]
     * @param  [type] $type [description]
     * @return [type]       [description]
     */
    public function downloadVariantCsv($type)
    {
        return Excel::download(new ItemListExport(), 'item_list'. time() .'.csv');
    }

    /**
     * [downloadVariantPdf description]
     * @return [type] [description]
     */
    public function downloadVariantPdf()
    {
        $data = [];
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['currency'] = Currency::getDefault($preference);
        $data['item'] = (new Item)->getAllItemCsv();
        return printPDF($data, 'item_list' . time() . '.pdf', 'admin.item.item_pdf', view('admin.item.item_pdf', $data), 'pdf', 'domPdf');
    }

    /**
     * [import description]
     * @return [type] [description]
     */
    public function import()
    {
        $data = ['menu' => 'item'];
        return view('admin.item.item_import', $data);
    }

    /**
     * [importCsv description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function importCsv(Request $request)
    {
        $file = $request->file('import_file');

        $validator = Validator::make(
            [
                'file' => $file,
                'extension' => strtolower($file->getClientOriginalExtension()),
            ],
            [
                'file' => 'required',
                'extension' => 'required|in:csv',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors(['email' => __("File type Invalid")]);
        }

        if (Input::hasFile('import_file')) {
            $path = Input::file('import_file')->getRealPath();

            $csv = array_map('str_getcsv', file($path));
            $unMatch = [];
            $header = array("Item ID", "Item Name", "Category Name", "Unit", "Tax Type", "Purchase Price", "Retail Price");

            for ($i = 0; $i < sizeof($csv[0]); $i++) {
                if (! in_array(trim($csv[0][$i]), $header)) {
                    $unMatch[] = trim($csv[0][$i]);
                }
            }

            if (! empty($unMatch)) {
                return back()->withErrors(['email' => __("Please check CSV header name.")]);
            }

            $data = [];
            foreach ($csv as $key => $value) {
                if ($key != 0) {
                    $data[$key]['item_id'] = trim($value[0]);
                    $data[$key]['item_name'] = trim($value[1]);
                    $data[$key]['item_category'] = trim($value[2]);
                    $data[$key]['unit'] = trim($value[3]);
                    $data[$key]['tax_type'] = trim($value[4]);
                    $data[$key]['purch_price'] = trim($value[5]);
                    $data[$key]['retail_price'] = trim($value[6]);
                }
            }
            $data = array_values($data);


            $cat_id = array();
            $cat = DB::table('stock_category')->get();

            foreach ($cat as $value) {
                $cat_id[$value->description] = $value->id;
            }

            $tax_id = array();
            $tax = DB::table('item_tax_types')->get();

            foreach ($tax as $value) {
                $tax_id[$value->name] = $value->id;
            }


            if (!empty($data)) {

                $item = uniqueMultidimArray($data, 'item_id');
                sort($item);
                foreach ($item as $key => $value) {

                    $itemCount = DB::table('items')->where('item_id', '=', $value['item_id'])->count();
                    $categoryCount = DB::table('stock_category')->where('description', '=', $value['item_category'])->count();
                    $unitCount = DB::table('item_unit')->where('name', '=', $value['unit'])->count();

                    if ($itemCount == 0 && $categoryCount > 0 && $unitCount > 0) {
                        $itemCode[] = [
                            'item_id' => $value['item_id'],
                            'description' => $value['item_name'],
                            'category_id' => $cat_id[$value['item_category']]
                        ];

                        $stockMaster[] = [
                            'item_id' => strtoupper($value['item_id']),
                            'description' => $value['item_name'],
                            'units' => $value['unit'],
                            'tax_type_id' => $tax_id[$value['tax_type']],
                            'category_id' => $cat_id[$value['item_category']]
                        ];

                        $purchPrice[] = [
                            'item_id' => strtoupper($value['item_id']),
                            'price' => $value['purch_price'],
                        ];

                        $salePrice[] = [
                            'item_id' => strtoupper($value['item_id']),
                            'r_price' => $value['retail_price'],
                            'w_price' => 0
                        ];
                    } else {
                        return back()->withErrors(['email' => __("Please check CSV file.")]);
                    }
                }

                $k = 0;
                foreach ($salePrice as $key => $value) {
                    $retailPrice[$key]['item_id'] = strtoupper($value['item_id']);
                    $retailPrice[$key]['sale_type_id'] = 1;
                    $retailPrice[$key]['price'] = $value['r_price'] ? $value['r_price'] : 0;
                    $retailPrice[$key]['curr_abrev'] = 'USD';

                    $wholePrice[$key]['item_id'] = strtoupper($value['item_id']);
                    $wholePrice[$key]['sale_type_id'] = 2;
                    $wholePrice[$key]['price'] = $value['w_price'] ? $value['w_price'] : 0;
                    $wholePrice[$key]['curr_abrev'] = 'USD';
                }


                if (!empty($itemCode)) {
                    DB::table('items')->insert($itemCode);
                    DB::table('stock_master')->insert($stockMaster);
                    DB::table('purchase_prices')->insert($purchPrice);
                    DB::table('sale_prices')->insert($retailPrice);
                    DB::table('sale_prices')->insert($wholePrice);

                    \Session::flash('success', __('Successfully Imported'));
                    return redirect()->intended('item');
                } else {
                    return back()->withErrors(['email' => __("Please check CSV file.")]);
                }
            }
        }
        return back();
    }

    /**
     * [downloadPdf description]
     * @return [type] [description]
     */
    public function downloadPdf()
    {
        $data = [];
        $data['item'] = (new Item)->getAllItemCsv();
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['currency'] = Currency::getDefault($preference);
        return printPDF($data, 'item_list' . time() . '.pdf', 'admin.item.item_pdf', view('admin.item.item_pdf', $data), 'pdf', 'domPdf');
    }

    /**
     * [addVariant description]
     * @param [type] $id [description]
     */
    public function addVariant($id)
    {
        $data = ['menu' => 'item', 'header' => 'item', 'breadcrumb' => 'addvariant', 'page_title' => __('Create Variant')];
        $data['saleTypes']  = DB::table('sale_types')->get();
        $data['itemInfo']   = $itemInfo = Item::find($id);

        if (empty($data['itemInfo'])) {
            return redirect()->back()->with('fail', __('The data you are trying to access is not found.'));
        }

        $check['customVariantItem']     = $itemInfo->itemCustomVariants ? $itemInfo->itemCustomVariants->all() : null;
        $check['size']                  = $itemInfo->size ? $itemInfo->size : null;
        $check['color']                 = $itemInfo->color ? $itemInfo->color : null;
        $check['weight']                = $itemInfo->weight ? $itemInfo->weight : null;
        $check['item_unit_id']          = $itemInfo->item_unit_id ? $itemInfo->item_unit_id : null;
        $check['is_stock_managed']    = ($itemInfo->is_stock_managed === 1) ? 1 : 0;

        $unit_name  = array();
        $unit       = DB::table('item_units')->get();
        foreach ($unit as $value) {
            $unit_name[$value->id] = $value->name;
        }

        if (! empty($check['customVariantItem']) || isset($check['size']) || isset($check['color']) || isset($check['weight']) ) {
            $check['hasVariants'] = true;
        } else {
            $check['hasVariants'] = false;
        }
        $data['unit_name']  = $unit_name;
        $data['locData']    = DB::table('locations')->get();
        $data['unitData']   = DB::table('item_units')->get();

        return view('admin.variant.add', $data, $check);
    }

    /**
     * [storeVariant description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function storeVariant(Request $request)
    {
        $data = [];
        $validator = Validator::make($request->all(), [
            'item_id'        => 'required|unique:items,stock_id',
            'item_name'      => 'required',
            'stock_id'       => 'required',
            'purchase_price' => 'required',
        ]);

        if (isset($request->manage_stock) && $request->manage_stock=='on') {
           Validator::make($request->all(), [
            'initial_stock' => 'required',
            'cost_price'    => 'required'
            ]);
        }

        if (isset($request->item_image)) {
            $validator->after(function ($validator) use ($request) {
                $files  = $request->file('item_image');
                if (empty($files)) {
                    return true;
                }
                if (checkFileValidationThree($files->getClientOriginalExtension()) == false) {
                    // return validator with error by file input name
                    $validator->errors()->add('upload_File', __('Allowed File Extensions: jpg, png, gif, bmp'));
                }
            });

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            }
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            \DB::beginTransaction();
            $variantTitle  = $request->variant_title;
            $variantValue  = $request->variant_value;
            $initialStock  = validateNumbers($request->initial_stock);
            $stockLocation = $request->stock_location;
            $costPrice     = validateNumbers($request->cost_price);
            $pic           = ($request->hasFile('item_image')) ? $request->file('item_image') : null;


            $item = Item::find($request->item_id);
            $data['stock_id']           = stripBeforeSave(strtoupper(trim($request->stock_id)));
            $data['name']               = $request->item_name;
            $data['hsn']                = $request->hsn;
            $data['description']        = empty($request->description) ? "" : $request->description;
            $data['stock_category_id']  = $item->stock_category_id;
            $data['item_type']          = $item->item_type;
            $data['parent_id']          = $item->id;
            $data['item_unit_id']       = $item->item_unit_id;
            $data['tax_type_id']        = $item->tax_type_id;
            $data['available_variant']  = $request->item_name;
            if (!empty($request->size)) {
                $data['size'] = stripBeforeSave($request->size);
                $data['available_variant'] .= " - ". $data['size'];
            }
            if (!empty($request->color)) {
                $data['color'] = stripBeforeSave($request->color);
                $data['available_variant'] .= " - ". $data['color'];
            }
            if (!empty($request->weight)) {
                $data['weight'] = stripBeforeSave(validateNumbers($request->weight));
                $data['weight_unit_id'] = $request->weight_unit;
                $data['available_variant'] .= " - ". $data['weight'] ." - ". $data['weight_unit_id'];
            }

            $data['is_stock_managed'] = 0;
            if ($request->manage_stock === "on") {
                $data['is_stock_managed'] = 1;
            }

            $newVariantId = DB::table('items')->insertGetId($data);

            if ( isset($request->wholesalePrice)) {
                if  ($request->wholesalePrice == 'on') {
                        $request->wholesale_price =  validateNumbers($request->retail_price);
                }
            }

            $salePriceData[0]['item_id']      = $newVariantId;
            $salePriceData[0]['sale_type_id'] = 1;
            $salePriceData[0]['price']        = isset($request->retail_price) ? validateNumbers($request->retail_price) : 0;
            $salePriceData[0]['currency_id']  = Currency::getDefault()->id;

            $salePriceData[1]['item_id']      = $newVariantId;
            $salePriceData[1]['sale_type_id'] = 2;
            $salePriceData[1]['price']        = isset($request->wholesale_price) ? validateNumbers($request->wholesale_price) : 0;
            $salePriceData[1]['currency_id']  = Currency::getDefault()->id;

            DB::table('sale_prices')->insert($salePriceData);

            $purchaseInfos['item_id']         = $newVariantId;
            $purchaseInfos['currency_id']     = Currency::getDefault()->id;
            $purchaseInfos['price']           = isset($request->purchase_price) ? validateNumbers($request->purchase_price) : 0;

            DB::table('purchase_prices')->insert($purchaseInfos);
            $i = 0;
            $customVariant = [];
            if ($variantTitle) {
                foreach ($variantTitle as $key => $title) {
                    $customVariant[$i]['variant_title'] = $variantTitle[$key];
                    $customVariant[$i]['variant_value'] = $variantValue[$key];
                    $customVariant[$i]['item_id'] = $newVariantId;
                    $i++;
                }
                DB::table('item_custom_variants')->insert($customVariant);
            }
            if ($request->manage_stock==="on") {
                $stockMoves['item_id'] = $newVariantId;
                $stockMoves['transaction_type'] = 'INITIALSTOCKIN';
                $stockMoves['location_id'] = $stockLocation;
                $stockMoves['user_id'] = auth()->user()->id;
                $stockMoves['quantity'] = $initialStock;
                $stockMoves['price'] = $costPrice;
                $stockMoves['reference'] = "store_in_".$newVariantId;
                $stockMoves['note'] = "Added from initial stock";
                $stockMoves['transaction_date'] = date('Y-m-d H:i:s');

                DB::table('stock_moves')->insert($stockMoves);
            }

            # region store files
            if (isset($pic) && ! empty($pic)) {
                if (isset($newVariantId) && !empty($newVariantId)) {
                    $path = createDirectory("public/uploads/items");
                    $fileIdList = (new File)->store([$pic], $path, 'Item', $newVariantId, ['isUploaded' => false, 'isOriginalNameRequired' => true, 'resize' => false]);

                    if (isset($fileIdList[0]) && !empty($fileIdList[0])) {
                        $uploadedFileName = File::find($fileIdList[0])->file_name;
                        $uploadedFilePath = asset($path . '/' . $uploadedFileName);
                        $thumbnailPath = createDirectory("public/uploads/items/thumbnail");
                        (new File)->resizeImageThumbnail($uploadedFilePath, $uploadedFileName, $thumbnailPath);
                    }
                }
            }
            # end region

            \DB::commit();

            Session::flash('success', __('Successfully Added'));
            return redirect()->to('edit-item/variant/' . $item->id);
        } catch (Exception $e) {
            \DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function itemNotifications()
    {
        $itemNotifications = Item::getItemNotifications();
        return json_encode($itemNotifications);
    }
}
