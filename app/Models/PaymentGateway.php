<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class PaymentGateway extends Model
{
    public $timestamps = false;
    protected $fillable = ['value'];

    public static function getAll()
    {
        $data = Cache::get('gb-payment_gateways');
        if (empty($data)) {
            $data = parent::all();
            Cache::put('gb-payment_gateways', $data, 30 * 86400);
        }
        return $data;
    }
}
