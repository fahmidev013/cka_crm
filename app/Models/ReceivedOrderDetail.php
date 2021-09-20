<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceivedOrderDetail extends Model
{
    public $timestamps = false;
    public function receivedOrder()
    {
    	return $this->belongsTo('App\Models\ReceivedOrder', 'received_order_id');
    }

    public function purchaseOrder()
    {
    	return $this->belongsTo('App\Models\PurchaseOrder', 'purchase_order_id');
    }
}
