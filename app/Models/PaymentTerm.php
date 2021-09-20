<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Cache;

class PaymentTerm extends Model
{
	public $timestamps = false;
	
	public function saleOrders()
	{
		return $this->hasMany('App\Models\SaleOrder', 'payment_term_id');
	}

	public function purchaseOrders()
	{
		return $this->hasMany('App\Models\PurchaseOrder', 'payment_term_id');
	}

    public static function getAll()
    {
        $data = Cache::get('gb-payment_terms');
        if (empty($data)) {
            $data = parent::all();
            Cache::put('gb-payment_terms', $data, 30 * 86400);
        }
        return $data;
    }
}
