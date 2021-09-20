<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
	public $timestamps = false;

	public function location()
	{
		return $this->belongsTo("App\Models\Location");
	}

	public function stockAdjustmentDetails()
	{
		return $this->hasMany("App\Models\StockAdjustmentDetail");
	}
}