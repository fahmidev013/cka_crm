<?php

namespace App\Http\Controllers;

use App\DataTables\StockAdjustmentDataTable;
use App\Exports\stockAdjustmentExport;
use App\Http\Requests;
use App\Http\Start\Helpers;
use App\Models\Preference;
use App\Models\Location;
use App\Models\StockAdjustment;
use App\Models\Item;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Session;
use Illuminate\Support\Facades\Validator;
use App\Rules\ValidateNewItemAdjustment;
use App\Rules\ValidateEditItemAdjustment;
use App\Rules\CheckQuantity;

class StockAdjustmentController extends Controller
{
    public function index(StockAdjustmentDataTable $dataTable)
    {
        $data = ['menu' => 'purchase', 'sub_menu' => 'adjustment', 'page_title' => __('Stock Adjustments')];
        $data['from'] = isset($_GET['from'])?$_GET['from']:null;
        $data['to'] = isset($_GET['to'])?$_GET['to']:null;
        $data['trans_type'] = $trans_type = isset($_GET['trans_type']) ? $_GET['trans_type'] : 'all';
        $data['destination'] = $destination = isset($_GET['destination']) ? $_GET['destination'] : 'all';
        $data['locationList'] = Location::getAll()->where('is_active', 1);

        $row_per_page = Preference::getAll()->where('field', 'row_per_page')->first()->value;

        return $dataTable->with('row_per_page', $row_per_page)->render('admin.adjustment.list',$data); 
    }


    public function create()
    {
        $data = ['menu' => 'purchase', 'sub_menu' => 'adjustment', 'page_title' => __('Create Stock Adjustment')];
        $data['locationList'] = Location::getAll()->where('is_active', 1);

        return view('admin.adjustment.add', $data);
    }

    public function edit($id)
    {
        $data = ['menu' => 'purchase', 'sub_menu' => 'adjustment', 'page_title' => __('Edit Stock Adjustment')];
        $data['locationList'] = Location::getAll();
        $data['info'] = StockAdjustment::with(['location:id,name,is_active', 'stockAdjustmentDetails'])->find($id);
        if (!empty($data['info'])) {
            if ($data['info']->location->is_active == 0) {
                \Session::now('fail', __('Can not perform any action, location inactive.'));
            }
            return view('admin.adjustment.edit', $data);  
        } else {
            return redirect()->back()->withErrors(__('The data you are trying to access is not found.'));
        }
    }

    public function checkOldItemQty(Request $request)
    {
        $data = array();
        $result = DB::table('stock_moves')
                    ->where(['item_id' => $request->item_id, 'location_id' => $request->location, 'transaction_type' => 'STOCKOUT', 'transaction_type_id' => $request->adjustment_id])
                    ->first();
        if (isset($result)) {
            $data['qty'] = abs($result->quantity);
            $data['status_no'] = 1;
        } else {
            $data['qty'] = 0;
            $data['status_no'] = 0;  
        }
        return json_encode($data);        
    }

    public function store(Request $request)
    {   
        $userId = Auth::user()->id;
        $rules = array(
            'location' => 'required',
            'date' => 'required',
            'type' => 'required',
            'quantity' => 'required',
        );
        $fieldNames = array(
            'location' => 'Location',
            'date' => 'Date',
            'type' => 'Adjustment type',
            'quantity' => 'Product(s) quantity',
        );
        if (isset($request->quantity)) {                
            foreach ($request->quantity as $key => $value) {
                if (validateNumbers($value) < 0) {
                    return back()->withErrors(([__('Item Quantity Should Not be Less Than Zero.')]));
                }
            }
        }

        if ($request->type == 'STOCKOUT') {
           $rules['id'] =  new ValidateNewItemAdjustment;
           $fieldNames['id'] = 'is_new_item_adjustable';
        }

        $validator = Validator::make($request->all(), $rules);
        $validator->setAttributeNames($fieldNames);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            $itemQuantity = $request->quantity;        
            $itemIds = $request->id;
            $stockIds = $request->stock;
            $description = $request->description;
            $date = DbDateFormat($request->date);
            $type = $request->type;
            $total = 0;
            foreach ($itemQuantity as $index => $qty) {
                $total += validateNumbers($qty);
            }
            $adjustment['location_id'] = $request->location;
            $adjustment['user_id'] = $userId;
            $adjustment['transaction_date'] = $date;
            $adjustment['note'] = stripBeforeSave($request->comments);
            $adjustment['total_quantity'] = $total;
            $adjustment['transaction_type'] = ($type == 'STOCKIN' ) ? 'STOCKIN' : 'STOCKOUT';

            $adjustment_id = DB::table('stock_adjustments')->insertGetId($adjustment);

            if (!empty($itemIds)) {
                foreach ($itemIds as $key => $item) {
                    if (validateNumbers($itemQuantity[$key]) > 0) {
                        $adjustmentDetails[$key]['stock_adjustment_id'] = $adjustment_id;
                        $adjustmentDetails[$key]['item_id'] = $item;
                        $adjustmentDetails[$key]['description'] = $description[$key];
                        $adjustmentDetails[$key]['quantity'] = validateNumbers($itemQuantity[$key]);
                        DB::table('stock_adjustment_details')->insertGetId($adjustmentDetails[$key]);

                        $stockTrans[$key]['item_id'] = $item;
                        $stockTrans[$key]['transaction_type'] = ($type == 'STOCKIN' ) ? 'STOCKIN' : 'STOCKOUT';
                        $stockTrans[$key]['location_id'] = $request->location;
                        $stockTrans[$key]['transaction_date'] = $date;
                        $stockTrans[$key]['user_id'] = $userId;
                        if ($type == 'STOCKIN') {
                            $stockTrans[$key]['reference'] = 'adjustment_in_'. $adjustment_id;
                            $stockTrans[$key]['quantity'] = validateNumbers($itemQuantity[$key]);
                        } else {
                            $stockTrans[$key]['reference'] = 'adjustment_out_'. $adjustment_id;
                            $stockTrans[$key]['quantity'] = '-'. validateNumbers($itemQuantity[$key]);                
                        }
                        $stockTrans[$key]['transaction_type_id'] = $adjustment_id;
                        $stockTrans[$key]['note'] = $request->comments;  
                        DB::table('stock_moves')->insertGetId($stockTrans[$key]);
                    }
                }
            }
            DB::commit();
            Session::flash('success', __('Successfully Saved'));
            return redirect()->intended('adjustment/list');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        $userId = Auth::user()->id;
        $adjustment_id = $request->adjustment_id;
        $idArr = [];
        $date = DbDateFormat($request->transfer_date);
        $type = $request->trans_type;
        $itemQty = isset($request->item_quantity) ? $request->item_quantity : null;


        $rules = array(
            'trans_location' => 'required',
            'transfer_date' => 'required',
            'trans_type' => 'required',
            'item_quantity' => new CheckQuantity,
            'new_item_quantity' => new CheckQuantity,
        );
        $fieldNames = array(
            'trans_location' => 'Location',
            'transfer_date' => 'Date',
            'trans_type' => 'Adjustment Type',
            'item_quantity' => 'checkQuantity',
            'new_item_quantity' => 'checkQuantity',
        );

        if ($request->trans_type == 'STOCKIN') {
            if (!empty($itemQty)) {
                foreach ($itemQty as $key => $value) {
                    if (validateNumbers($value) < 0) {
                        return back()->withErrors(([__('Item Quantity Should Not be Less Than Zero.')]));
                    }
                }
            }
            if (isset($request->new_item_quantity) && !empty($request->new_item_quantity)) {                
                foreach ($request->new_item_quantity as $key => $value) {
                    if (validateNumbers($value) <= 0) {
                        return back()->withErrors(([__('Item Quantity Should Not be Less Than Zero.')]));
                    }
                }
            }
        }

        $validator = Validator::make($request->all(), $rules);
        $validator->setAttributeNames($fieldNames);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            if ($request->trans_type == 'STOCKOUT') {
               $rules['id'] = new ValidateEditItemAdjustment;
               $fieldNames['id'] = 'edit_item_adjustable';
            }
            $adjustment['location_id'] = $request->trans_location;
            $adjustment['transaction_date'] = $date;
            $adjustment['note'] = stripBeforeSave($request->comments);
            $adjustment['total_quantity'] = validateNumbers($request->total_quantity);
            $adjustment['transaction_type'] = ($type == 'STOCKIN' ) ? 'STOCKIN' : 'STOCKOUT';

            DB::table('stock_adjustments')->where(['id' => $adjustment_id])->update($adjustment);      
            
            $stkDetilsIds = DB::table('stock_adjustment_details')
                                ->where(['stock_adjustment_id' => $adjustment_id])
                                ->select('id')
                                ->get();
            
            foreach ($stkDetilsIds as $key => $value) {
                $idArr[] = $stkDetilsIds[$key]->id;
            }

            // String array to Integer array of Row ID (according to stock_adjustment_details Table)
            if (!empty($request->id)) {
                $itemRowIdForm = array_map(function($value) {
                    return intval($value);
                }, $request->id);
            }

            if (!empty($idArr) || !empty($itemRowIdForm)) {
                if (empty($itemRowIdForm)) {
                    $itemRowIdForm = array(0);
                }
                $deletalbeIDs =  array_unique(array_values(array_diff($idArr, $itemRowIdForm)));
            }

            if (!empty($deletalbeIDs)) {
                foreach ($deletalbeIDs as $deletalbeID) {
                    $record = DB::table('stock_adjustment_details')->where('id', $deletalbeID)->first();
                    $stockMoveDetils = DB::table('stock_adjustments')->where('id', $record->stock_adjustment_id)->first();
                    if ($record) {
                        DB::table('stock_moves')->where(['item_id' => $record->item_id, 'transaction_type_id' => $stockMoveDetils->id, 'transaction_type' => $stockMoveDetils->transaction_type])->delete();
                        DB::table('stock_adjustment_details')->where(['stock_adjustment_id'=>$stockMoveDetils->id, 'item_id' => $record->item_id])->delete();
                    }
                }
            } 

            // Old items
            $itemQuantity = $request->item_quantity;        
            $itemRowIds = $request->id;
            $itemIds = $request->item_id;
            
            // new Items
            $new_itemIds = $request->new_item_id;
            $new_itemQuantity = $request->new_item_quantity;        
            $new_description = $request->new_description;  

            // Add new item
            if (!empty($new_itemIds)) {

                foreach ($new_itemIds as $key => $item_id) {
                    $newItem[$key]['stock_adjustment_id'] = $adjustment_id;
                    $newItem[$key]['item_id'] = $item_id;
                    $newItem[$key]['description'] = $new_description[$key];
                    $newItem[$key]['quantity'] = validateNumbers($new_itemQuantity[$key]);
                    $newItem[$key]['created_at'] = date("Y-m-d h:i:s");

                    $result =  DB::table('stock_adjustment_details')->insertGetId($newItem[$key]);

                    $newStockTrans[$key]['item_id'] = $item_id;
                    $newStockTrans[$key]['transaction_type'] = ($type == 'STOCKIN' ) ? 'STOCKIN' : 'STOCKOUT';
                    $newStockTrans[$key]['location_id'] = $request->trans_location;
                    $newStockTrans[$key]['transaction_date'] = date("Y-m-d h:i:s");
                    $newStockTrans[$key]['user_id'] = $userId;
                    if ($type == 'STOCKIN') {
                        $newStockTrans[$key]['reference'] = 'adjustment_in_'. $adjustment_id;
                        $newStockTrans[$key]['quantity'] = validateNumbers($new_itemQuantity[$key]);
                    } else {
                        $newStockTrans[$key]['reference'] = 'adjustment_out_'. $adjustment_id;
                        $newStockTrans[$key]['quantity'] = '-'. validateNumbers($new_itemQuantity[$key]);                
                    }
                    $newStockTrans[$key]['transaction_type_id'] = $adjustment_id;
                    $newStockTrans[$key]['note'] = $request->comments; 
                    DB::table('stock_moves')->insertGetId($newStockTrans[$key]);
                }
            }

            // Update old Items
            if (!empty($itemIds)) {            
                foreach ($itemIds as $key => $item_id) {
                    if (validateNumbers($itemQuantity[$key]) > 0) {
                        $itemQty = abs(validateNumbers($itemQuantity[$key]));
                        
                        $result =  DB::table('stock_adjustment_details')
                                        ->where(['stock_adjustment_id' => $adjustment_id, 'item_id' => $item_id])
                                        ->update(['quantity' => $itemQty]);
                        // Movement table
                        $stockTrans[$key]['transaction_type'] = ($type == 'STOCKIN' ) ? 'STOCKIN' : 'STOCKOUT';

                        if ($type == 'STOCKIN') {
                           $stockTrans[$key]['quantity'] = abs(validateNumbers($itemQuantity[$key]));
                           $stockTrans[$key]['reference'] = 'adjustment_in_'. $adjustment_id;
                        } else {
                           $stockTrans[$key]['quantity'] = '-'. abs(validateNumbers($itemQuantity[$key])); 
                           $stockTrans[$key]['reference'] = 'adjustment_out_'. $adjustment_id;
                        }
                                  
                        DB::table('stock_moves')->where(['transaction_type_id' => $adjustment_id, 'transaction_type' => $type, 'item_id' => $item_id])->update($stockTrans[$key]); 
                    }
                }
            }
            
            DB::commit();
            Session::flash('success', __('Successfully updated'));
            return redirect()->intended('adjustment/list');
        } catch (Exception $e) {
            DB::roolBack();
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }
   
    public function itemSearch(Request $request)
    {
        $location_id = $request->location;
        $data = array();
        $data['status_no'] = 0;
        $data['message'] = __('No Item Found');
        $data['items'] = array();

        $items = Item::with(['available' => function ($query) use($location_id) {
                    $query->where('location_id', $location_id);
                }])->where('name', 'LIKE', '%' . $request->search . '%')
                    ->where('item_type', 'product')
                    ->where('is_active', 1)
                    ->limit(10)
                    ->get();

        if (!empty($items)) {
            $data['status_no'] = 1;
            $data['message'] = __('Item Found');
            $i = 0;
            foreach ($items as $key => $value) {
                if ($value->is_stock_managed == 0 || ($value->is_stock_managed == 1 && !is_null($value->available))) {
                    $return_arr[$i]['id'] = $value->id;
                    $return_arr[$i]['name'] = $value->name;
                    $return_arr[$i]['is_stock_managed'] = $value->is_stock_managed;
                    $return_arr[$i]['available'] = 0;
                    if ($value->available) {
                        $return_arr[$i]['available'] = $value->available->quantity;
                    }
                    $i++;
                }
            }
            $data['items'] = $return_arr;
        }
        echo json_encode($data);
        exit();
    }

    public function checkItemQty(Request $request)
    {
        $data = array();
        $location = $request['source'];
        $item_id = $request['item_id'];
        
        $result = DB::table('stock_moves')
                     ->select(DB::raw('sum(quantity) as total'))
                     ->where(['item_id' => $item_id, 'location_id' => $location])
                     ->groupBy('location_id')
                     ->first();
              
        if (isset($result)) {
            $data['qty'] = $result->total;
            $data['status_no'] = 1;
        } else {
            $data['qty'] = 0;
            $data['status_no'] = 0;  
        }
        $data['item_id'] = $item_id;
        $data['message'] = __('Available quantity is :?', ['?' =>  $data['qty']]);
        return json_encode($data);        

    }


    public function details($id)
    {
        $data = ['menu' => 'purchase', 'sub_menu' => 'adjustment', 'page_title' => __('View Stock Adjustment')];
        $data['Info'] = $Info = DB::table('stock_adjustments')
                              ->leftjoin('locations', 'locations.id', '=', 'stock_adjustments.location_id')
                              ->where('stock_adjustments.id', '=', $id)
                              ->select('stock_adjustments.*', 'locations.name')
                              ->first(); 

        $data['List'] = DB::table('stock_adjustment_details')
                              ->where(['stock_adjustment_id' => $id])
                              ->orderBy('quantity', 'DESC')
                              ->get();
        if (!empty($data['Info'])) {
            return view('admin.adjustment.detail', $data);  
        } else {
            return redirect()->back()->withErrors(__('The data you are trying to access is not found.'));
        }  
    }

    public function destroy(Request $request)
    {
        try {
            $id = $request->id;
            if (isset($id)) {
                $record = DB::table('stock_adjustments')->where('id', $id)->first();
                if ($record) {
                    DB::beginTransaction();
                    DB::table('stock_moves')->where(['transaction_type_id' => $id,'transaction_type' => $request->trans_type])->delete();
                    DB::table('stock_adjustment_details')->where(['stock_adjustment_id' => $id])->delete();
                    DB::table('stock_adjustments')->where(['id' => $id])->delete();
                    DB::commit();
                    Session::flash('success', __('Deleted Successfully.'));
                    return redirect()->intended('adjustment/list');
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
        
    }

    public function stock_adjustment_csv()
    {
      return Excel::download(new stockAdjustmentExport(), 'stock_adjustment_details'. time() .'.csv');
    }

    public function stock_adjustment_pdf()
    {
        $data = [];
        $to   = isset( $_GET['to'] ) ? $_GET['to'] : formatDate(date('d-m-Y'));
        $from = isset( $_GET['from'] ) ? $_GET['from'] : formatDate(date('Y-m-d', strtotime("-30 days")));
        $data['trans_type'] = $trans_type = isset( $_GET['trans_type'] ) ? $_GET['trans_type'] : null;
        $data['location'] = $location = isset( $_GET['location'] ) ? $_GET['location'] : null;
        $data['adjustmentLocation'] = Location::find($location);
        $stockTransfer = StockAdjustment::with('location:id,name');
        if ($from) {
            $stockTransfer->where('transaction_date', '>=', DbDateFormat($from));
        }
        if ($to) {
            $stockTransfer->where('transaction_date', '<=', DbDateFormat($to));
        }             
        if ($trans_type) {
            $stockTransfer->where('transaction_type', '=', $trans_type);
        }
        if ($location) {
            $stockTransfer->where('location_id', '=', $location);
        }

        $data['stocks'] = $stockTransfer->orderBy('id')->get();

        if ($from && $to) {
            $data['date_range'] =  formatDate($from) . __('To') . formatDate($to);
        } else {
            $data['date_range'] = __('No Date Selected');
        }
        return printPDF($data, 'stock_adjustment_' . time() . '.pdf', 'admin.adjustment.stock_adjustment_pdf', view('admin.adjustment.stock_adjustment_pdf', $data), 'pdf', 'domPdf');
    }
}
