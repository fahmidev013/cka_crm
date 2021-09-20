<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMove extends Model
{
	public $timestamps = false;
    
    public function item()
    {
    	return $this->belongsTo("App\Models\Item", 'item_id');
  	}

  	public function location()
  	{
  		return $this->belongsTo("App\Models\Location");
  	}

  	public function saleOrder()
  	{
  		return $this->belongsTo("App\Models\SaleOrder", 'transaction_type_id');
  	}

	public function getItemQtyByLocationName($location_id, $item_id)
	{
		$qty = $this->where(['location_id' => $location_id, 'item_id' => $item_id])->sum('quantity');
	    if (empty($qty)) {
	        $qty = 0;
	    }
	    return $qty;
	}

}
