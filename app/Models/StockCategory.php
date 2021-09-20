<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Cache;

class StockCategory extends Model
{
    public function itemUnit()
    {
        return $this->belongsTo('App\Models\ItemUnit','item_unit_id');
    }

    public function item()
    {
      return $this->hasOne('App\Models\Item','stock_category_id');
    }

    public static function getAll()
    {
        $data = Cache::get('gb-stock_categories');
        if (empty($data)) {
            $data = parent::all();
            Cache::put('gb-stock_categories', $data, 30 * 86400);
        }
        return $data;
    }
}
