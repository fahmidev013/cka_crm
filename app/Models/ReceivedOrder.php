<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\ReceivedOrderDetail;
use App\Models\StockMove;
use Auth;
use DB;
use Helpers;

class ReceivedOrder extends Model
{
    public $timestamps = false;

    public function purchaseOrder()
    {
        return $this->belongsTo('App\Models\PurchaseOrder', 'purchase_order_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier', 'supplier_id');
    }

    public function location()
    {
        return $this->belongsTo('App\Models\Location', 'location_id');
    }

    public function receivedOrderDetails()
    {
        return $this->hasMany('App\Models\ReceivedOrderDetail', 'received_order_id');
    }

    public function getReceivedData($id)
    {
    	$data = DB::table('received_orders as ro')
                    ->leftJoin('received_order_details as rod', 'rod.received_order_id', 'ro.id')
                    ->select('ro.id', 'ro.reference', 'ro.receive_date', DB::raw('SUM(rod.quantity) as qty'))
                    ->where('ro.purchase_order_id', $id)
                    ->groupBy('rod.received_order_id')
                    ->orderBy('ro.id', 'DESC')
                    ->get();
    }

    public function receiveAll($order_no)
    {
        DB::transaction(function () use ($order_no) {
            $orderInfo = PurchaseOrder::find($order_no);
            $receivedOrder                    = new ReceivedOrder();
            $receivedOrder->purchase_order_id = $orderInfo->id;
            $receivedOrder->user_id           = \Auth::user()->id;
            $receivedOrder->reference         = $orderInfo->reference;
            $receivedOrder->location_id       = $orderInfo->location_id;
            $receivedOrder->receive_date      = date('Y-m-d');
            $receivedOrder->supplier_id       = $orderInfo->supplier_id;
            $receivedOrder->save();
            foreach ($orderInfo->purchaseOrderDetails as $key => $item) {
                $receivedDetails                           = new ReceivedOrderDetail();
                $receivedDetails->purchase_order_id        = $orderInfo->id;
                $receivedDetails->purchase_order_detail_id = $item->id;
                $receivedDetails->received_order_id        = $receivedOrder->id;
                $receivedDetails->item_id                  = $item->item_id;
                $receivedDetails->item_name                = $item->item_name;
                $receivedDetails->unit_price               = $item->unit_price;
                $receivedDetails->quantity                 = $item->quantity_ordered - $item->quantity_received;
                $receivedDetails->save();

                if ($item->item_id != NULL) {
                    $stockMove                    = new StockMove();
                    $stockMove->item_id           = $item->item_id;
                    $stockMove->transaction_type_id = $receivedOrder->id;
                    $stockMove->transaction_type  = 'PURCHINVOICE';
                    $stockMove->location_id       = $orderInfo->location_id;
                    $stockMove->transaction_date  = date('Y-m-d');
                    $stockMove->user_id           = \Auth::user()->id;
                    $stockMove->transaction_type_detail_id = $receivedDetails->id;
                    $stockMove->reference         = 'store_in_'. $order_no;
                    $stockMove->quantity          = ($item->quantity_ordered - $item->quantity_received);
                    $stockMove->price             = $item->unit_price;
                    $stockMove->save();
                }

                $orderItemDetails = PurchaseOrderDetail::where(['purchase_order_id' => $order_no, 'id' => $item->id])
                                    ->first(['quantity_received']);

                $receivedQty = ($orderItemDetails->quantity_received + ($item->quantity_ordered - $item->quantity_received));

                DB::table('purchase_order_details')
                    ->where(['purchase_order_id' => $order_no, 'id' => $item->id])
                    ->update(['quantity_received' => $receivedQty]);
            }
        });
    }

    public function getAllPurchaseReceiveOrder($from, $to, $supplier){
        $data = $this->select('received_orders.id', 'received_orders.id as ro_id', 'received_orders.supplier_id', 'received_orders.receive_date', 'received_orders.reference', 'received_orders.order_receive_no', 'received_orders.purchase_order_id')->with(['receivedOrderDetails:id,received_order_id,quantity', 'supplier:id,name']);
        if ( !empty($from) && !empty($to) ) {
            $from = DbDateFormat($from);
            $to   = DbDateFormat($to);
            $data->where('received_orders.receive_date', '>=', $from)
                ->where('received_orders.receive_date', '<=', $to); 
        }
        if ( !empty($supplier) ) {
            $data->where('received_orders.supplier_id', $supplier);
        }
        if (Helpers::has_permission(Auth::user()->id, 'own_purchase_receive') && !Helpers::has_permission(Auth::user()->id, 'manage_purch_receive')) {
            $id = Auth::user()->id;
            $data->where('received_orders.user_id', $id);
        } 
        return $data;
    }
}
