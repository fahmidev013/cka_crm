<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Cache;

class PurchaseReceiveType extends Model
{
    public $timestamps = false;

    public function purchaseOrder()
    {
    	return $this->hasOne('App\Models\PurchaseOrder', 'purchase_receive_type_id');
    }

    public static function getAll()
    {
        $data = Cache::get('gb-purchase_receive_types');
        if (empty($data)) {
            $data = parent::all();
            Cache::put('gb-purchase_receive_types', $data, 30 * 86400);
        }
        return $data;
    }
}
