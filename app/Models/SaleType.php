<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class SaleType extends Model
{
    public $timestamps = false;

    public static function getAll()
    {
        $data = Cache::get('gb-sale_types');
        if (empty($data)) {
            $data = parent::all();
            Cache::put('gb-sale_types', $data, 30 * 86400);
        }
        return $data;
    }
}
