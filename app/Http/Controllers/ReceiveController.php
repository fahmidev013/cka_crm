<?php

namespace App\Http\Controllers;

use App\DataTables\PurchasesReceiveDataTable;
use App\Exports\purchaseReceiveExport;
use App\Http\Requests;
use App\Http\Start\Helpers;
use App\Models\{
    Preference,
    PurchaseOrder,
    PurchaseOrderDetail,
    ReceivedOrder,
    ReceivedOrderDetail,
    StockMove,
    Supplier
};
use Auth;
use DB;
use Excel;
use Illuminate\Http\Request;
use PDF;
use Session;

class ReceiveController extends Controller
{
    public function index(PurchasesReceiveDataTable $dataTable)
    {
        $data['menu'] = 'purchase';
        $data['sub_menu'] = 'purchase_receive/list';
        $data['page_title'] = __('Purchases Receive');
        $data['suppliers'] = DB::table('suppliers')->select('id', 'name')->get();
        $data['from']       = $from = isset($_GET['from']) ? $_GET['from'] : null;        
        $data['to']         = $from = isset($_GET['to']) ? $_GET['to'] : null;
        $data['supplier']   = $supplier_id = isset($_GET['supplier']) ? $_GET['supplier'] : 'all'; 
        
        $row_per_page = Preference::getAll()->where('field', 'row_per_page')->first()->value;

        return $dataTable->with('row_per_page', $row_per_page)->render('admin.receive.receive_list', $data);
    }

    public function edit($id)
    {
        $data['menu'] = 'purchase';
        $data['sub_menu'] = 'purchase_receive/list';
        $data['page_title'] = __('Edit Purchases Receive');

        $data['oderInfo'] = DB::table('receive_orders')->where(['id'=>$id])->first();
        $data['itemInfo'] = DB::table('receive_order_details')
                            ->leftJoin('purch_order_details','receive_order_details.po_details_id', '=', 'purch_order_details.id')
                            ->where(['receive_order_details.receive_id' => $id])
                            ->select('receive_order_details.*','purch_order_details.quantity_ordered','quantity_received')
                            ->get();

        return view('admin.receive.receive_edit', $data);
    }

    public function update(Request $request)
    {
        $order_no = $request->order_no;   
        $receive_id = $request->id;        
        $data['receive_date'] = DbDateFormat($request->receive_date);
        $data['order_receive_no'] = $request->order_receive_no;

        try {
            DB::beginTransaction();   
            
            DB::table('receive_orders')->where('id', $receive_id)->update($data);

            $item_id = $request->item_id;
            $po_details_id = $request->po_details_id;
            $quantity = $request->receive_qty;
            $old_remain_qty = $request->old_remain_qty;

            // delete item
            $delete_item = $request->delete_item;
            if ($delete_item != '') {

                $deleted_po_details_id = explode(",", $delete_item); 
                if (count($deleted_po_details_id) > 0 ) {
                 foreach ($deleted_po_details_id as $key => $val) {
                    $receive_data = DB::table('receive_order_details')->where(['po_details_id'=> $val, 'receive_id'=> $receive_id])->first();
                    if ($receive_data->item_id != NULL) {
                        DB::table('stock_moves')->where(['receive_id'=> $receive_id, 'item_id'=> $receive_data->item_id, 'transaction_reference_id'=> $order_no])->delete();
                    }
                    $purch_data = DB::table('purch_order_details')
                                ->where(['purch_order_id' => $order_no, 'id' => $val])
                                ->first();
                    
                    DB::table('purch_order_details')
                            ->where(['purch_order_id' => $order_no, 'id' => $val])
                            ->update(['quantity_received'=> $purch_data->quantity_received - $receive_data->quantity]);

                    $receive_data = DB::table('receive_order_details')->where(['po_details_id'=> $val, 'receive_id'=> $receive_id])->delete();

                 }
              }
            }
             
            // update item
            foreach ($po_details_id as $key => $value) {
                 $itemDetails['quantity'] = $quantity[$key];
                 DB::table('receive_order_details')
                        ->where(['po_details_id'=>$value,'receive_id'=>$receive_id])
                        ->update($itemDetails);
                 if ($item_id[$key] != '') {
                    $stockMove['qty'] = $quantity[$key];
                    DB::table('stock_moves')
                        ->where(['item_id' => $item_id[$key], 'receive_id' => $receive_id])
                        ->update($stockMove);
                 }

                 DB::table('purch_order_details')
                        ->where(['purch_order_id' => $order_no, 'id' => $value])
                        ->update(['quantity_received'=> ($old_remain_qty[$key] + $quantity[$key])]);
            } 

            DB::commit();
        } catch (\Exception $e) {
                DB::rollBack();
        }   

        Session::flash('success', __('Saved Successfully'));
        return redirect()->intended('purchase_receive/details/'.$receive_id);
    }

    public function updateDate(Request $request)
    {        
        $date = DbDateFormat($request->date);
        $receiveData = DB::table('received_orders')
                        ->where('id', $request->id)
                        ->update(['receive_date' => $date]);
        $data['status'] = true;
        Session::flash('success', __('Successfully updated'));
        return redirect()->intended('purchase_receive/details/'.$request->id);
    }

    public function destroy($id)
    {
        $receive_id  = $id;
        $receiveData = ReceivedOrder::find($id);
        if (empty($receiveData)) {
            Session::flash("fail", __('Receive data not found'));
            return redirect('purchase_receive/list');
        }
        $order_no    = $receiveData->purchase_order_id;
        $itemList = ReceivedOrderDetail::where(['received_order_id' => $receive_id])->get();
        try {
            DB::beginTransaction();  
            if (!empty($itemList)) {
                foreach ($itemList as $value) {
                    $orderItemDetails = PurchaseOrderDetail::find($value->purchase_order_detail_id, ['quantity_received']);
                    $receiveQty = ($orderItemDetails->quantity_received - $value->quantity);
                    DB::table('purchase_order_details')
                        ->where(['purchase_order_id' => $order_no, 'id' => $value->purchase_order_detail_id])
                        ->update(['quantity_received' => $receiveQty]);   
                }
            }
            DB::table('stock_moves')->where(['transaction_type_id' => $receive_id])->delete();
            DB::table('received_order_details')->where(['received_order_id' => $receive_id])->delete();
            DB::table('received_orders')->where('id', '=', $receive_id)->delete();
            DB::commit();
            Session::flash('success', __('Deleted Successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('fail', __('Delete Failed'));
        }   
        return redirect()->intended('purchase_receive/list');
    }

    public function receiveDetails($id)
    {
        $data['menu'] = 'purchase';
        $data['sub_menu'] = 'purchase_receive/list';   
        $data['page_title'] = __('Purchases Receive Details');

        $data['receivedData'] = ReceivedOrder::with(['receivedOrderDetails', 'supplier:id,name,email,street,contact,city,state,zipcode,country_id', 'location:id,name', 'purchaseOrder:id,invoice_type'])->find($id);
        if (empty($data['receivedData'])) {
            Session::flash("fail", __('Receive data not found'));
            return redirect('purchase_receive/list');
        }
        return view('admin.receive.details', $data);
    }
    
    public function receivePdf($id)
    {
        $data = [];
        $data['receivedData'] = ReceivedOrder::with([
                                                    'supplier:id,name,street,city,state,country_id,zipcode',
                                                    'receivedOrderDetails:id,received_order_id,item_name,quantity'
                                                ])->find($id);
        $data['companyInfo']['company'] = Preference::getAll()->where('category', 'company')->pluck('value', 'field')->toArray();
        return printPDF($data, 'order_received_' . time() . '.pdf', 'admin.receive.pdf', view('admin.receive.pdf', $data), 'pdf', '');            
    }
    
    public function receivePrint($id)
    {
        $data = [];
        $data['receivedData'] = ReceivedOrder::with([
                                                    'supplier:id,name,street,city,state,country_id,zipcode',
                                                    'receivedOrderDetails:id,received_order_id,item_name,quantity'
                                                ])->find($id);
        $data['companyInfo']['company'] = Preference::getAll()->where('category', 'company')->pluck('value', 'field')->toArray();
        return printPDF($data, 'order_received_' . time() . '.pdf', 'admin.receive.pdf', view('admin.receive.pdf', $data), 'print', '');    
    }

    public function allReceive($order_no)
    {
        $allReceive =  (new ReceivedOrder)->receiveAll($order_no);
        Session::flash('success', __('Saved Successfully'));
        if (isset($_GET['supplier'])) {
            return redirect()->intended('purchase/view-purchase-details/'.$order_no."?supplier");
        }else if (isset($_GET['users'])) {
            return redirect()->intended('purchase/view-purchase-details/'.$order_no."?users");
        } else {
            return redirect()->intended('purchase/view-purchase-details/'.$order_no);
        }
    }

    public function manualReceive($order_no)
    {
        $data['menu']     = 'purchase';
        $data['sub_menu'] = 'purchase_receive/list';
        $data['page_title'] = __('Manual Receive');

        $data['orderInfo'] = PurchaseOrder::with('purchaseOrderDetails:id,item_id,item_name,unit_price,purchase_order_id,quantity_ordered,quantity_received')->find($order_no,['id', 'invoice_type', 'location_id', 'reference', 'supplier_id', ]);
        if (empty($data['orderInfo'])) {
            Session::flash("fail", __('Purchaes data not found'));
            return redirect('purchase_receive/list');
        }
        return view('admin.receive.add', $data); 
    }

    public function manualStore(Request $request)
    {
        $this->validate($request, [
            'receive_date' => 'required',
            'purchase_order_detail_id.*' => 'required',
            'quantity.*' => 'required',
        ]);
        foreach ($request->purchase_order_detail_id as $key => $value) {
            $order = PurchaseOrderDetail:: where('id', $value)->first(['quantity_ordered', 'quantity_received']);
                if (($order->quantity_ordered - $order->quantity_received) < $request->quantity[$key]) {
                    Session::flash('danger', __('Received quantity can not be greater than ordered quantity.'));
                    return redirect()->back();
                } else if ($request->quantity[$key] == 0) {
                    Session::flash('danger', __('Received quantity can not be zero.'));
                    return redirect()->back();
                }
        }

        try {
            DB::beginTransaction();
            $receiveOrder                    = new ReceivedOrder();
            $receiveOrder->purchase_order_id = $request->order_no;
            $receiveOrder->user_id           = Auth::user()->id;
            $receiveOrder->supplier_id       = $request->supplier_id;
            $receiveOrder->reference         = $request->order_reference;
            $receiveOrder->location_id       = $request->location;
            $receiveOrder->order_receive_no  = $request->order_receive_no;
            $receiveOrder->receive_date      = date('Y-m-d', strtotime($request->receive_date));
            $receiveOrder->save();

            $items         = $request->item_id;
            $quantity      = $request->quantity;
            $unit_price    = $request->unit_price;
            $purchase_order_detail_id = $request->purchase_order_detail_id;

            foreach ($purchase_order_detail_id as $key => $value) {
                if (($quantity[$key]) > 0) {
                    $receivedDetail = new ReceivedOrderDetail();
                    $receivedDetail->purchase_order_id = $request->order_no;
                    $receivedDetail->received_order_id = $receiveOrder->id;
                    $receivedDetail->item_id           = $items[$key] != '' ? $items[$key] : NULL ;
                    $receivedDetail->item_name         = $request->item_name[$key];
                    $receivedDetail->unit_price        = $unit_price[$key];
                    $receivedDetail->quantity          = validateNumbers($quantity[$key]);
                    $receivedDetail->purchase_order_detail_id = $value;
                    $receivedDetail->save();
               
                    if ($items[$key] !='') {
                        $stockMove                   = new StockMove();
                        $stockMove->item_id          = $items[$key];
                        $stockMove->transaction_type_id = $receiveOrder->id;
                        $stockMove->transaction_type = 'PURCHINVOICE';
                        $stockMove->location_id      = $request->location;
                        $stockMove->transaction_date = date('Y-m-d');
                        $stockMove->user_id          = \Auth::user()->id;
                        $stockMove->reference        = 'store_in_'. $request->order_no;
                        $stockMove->transaction_type_detail_id = $receivedDetail->id;
                        $stockMove->quantity         = validateNumbers($quantity[$key]);
                        $stockMove->price            = $unit_price[$key];
                        $stockMove->save();
                    }
                    $orderItemDetails = PurchaseOrderDetail::find($value);
                    $orderItemDetails->quantity_received = ( $orderItemDetails->quantity_received + validateNumbers($quantity[$key]));
                    $orderItemDetails->save();
                }
            }
            DB::commit();
            Session::flash('success', __('Saved Successfully'));
        }
        catch(Exception $e) {
            DB::rollBack();
            Session::flash('fail', __('Save Failed'));
        }
        return redirect()->intended('purchase/view-purchase-details/'. $request->order_no);
    }


    public function pdfList()
    {
        $data = [];
        $to     = isset($_GET['to']) ? $_GET['to'] : "";
        $from   = isset($_GET['from']) ? $_GET['from'] : "";
        $supplier = isset($_GET['supplier']) ? $_GET['supplier'] : "";
        if (!empty($supplier)) {
            $data['supplierData'] = Supplier::find($supplier);
        }
        $data['receiveData'] = (new ReceivedOrder)->getAllPurchaseReceiveOrder($from, $to, $supplier)->latest('id')->get();
        if ( !empty($from) && !empty($to) ) {
            $data['date_range'] =  formatDate($from) . __('To') . formatDate($to);   
        } else {
            $data['date_range'] = __('No date selected');   
        }
        return printPDF($data, 'order_receive_list_' . time() . '.pdf', 'admin.receive.receive_list_pdf', view('admin.receive.receive_list_pdf', $data), 'pdf', 'domPdf');
    }

    public function ReceiveCsv()
    {
        return Excel::download(new purchaseReceiveExport(), 'purchase_receive_details'.time().'.csv');
    }
}
