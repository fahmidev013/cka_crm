<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalePrice extends Model
{
	public $timestamps  = false;
	protected $fillable = ['item_id', 'sales_type_id', 'curr_abrev', 'price'];

    public function item(){
      return $this->belongsTo("App\Models\Item");
  	}

}
