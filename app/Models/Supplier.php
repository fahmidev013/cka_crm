<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    public $timestamps = false;

    public function currency()
    {
        return $this->belongsTo("App\Models\Currency",'currency_id');
    }
    public function country()
    {
    	return $this->belongsTo("App\Models\Country", 'country_id');
    }
    public function purchaseOrders()
    {
        return $this->hasMany('App\Models\PurchaseOrder', 'supplier_id');
    }
}
