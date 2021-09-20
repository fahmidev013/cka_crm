<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StockAdjustmentDetail extends Model
{
	public $timestamps = false;

	public function stockAdjustment()
	{
		return $this->belongsTo("App\Models\StockAdjustment");
	}

	public function item()
	{
		return $this->belongsTo("App\Models\Item");
	}
}