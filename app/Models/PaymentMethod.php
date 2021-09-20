<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Cache;
use DB;

class PaymentMethod extends Model
{
    public $timestamps = false;

    public function expense() 
    {
      return $this->hasOne("App\Models\Expense", 'payment_method_id');
    }

    public function customerTransactions()
    {
        return $this->hasMany('App\Models\CustomerTransaction', 'payment_method_id');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction', 'payment_method_id');
    }

    public static function getAll()
    {
        $data = Cache::get('gb-payment_methods');
        if (empty($data)) {
            $data = parent::all();
            Cache::put('gb-payment_methods', $data, 30 * 86400);
        }
        return $data;
    }

    public static function getPaymentMethodName($id)
    {
        $term = \DB::table('payment_methods')
            ->where('id', $id)
            ->select('name')
            ->first();
        return $term->name;
    }
}
