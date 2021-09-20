<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PurchasePrice extends Model
{
    public $timestamps  = false;
    protected $fillable = ['item_id', 'price'];

    public function item()
    {
      return $this->belongsTo("App\Models\Item", 'item_id');
    }
}
