<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    public $timestamps = false;

    public function sourceLocation() {
	    return $this->belongsTo('App\Models\Location', 'source_location_id');
	}

	public function destinationLocation() {
	    return $this->belongsTo('App\Models\Location', 'destination_location_id');
	}
}
