<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ItemUnit extends Model
{
    public function stockCategory()
    {
        return $this->hasMany('App\Models\StockCategory', 'item_unit_id');
    }
    
    public function items()
    {
        return $this->hasMany('App\Models\Item', 'item_unit_id');
    }
}
