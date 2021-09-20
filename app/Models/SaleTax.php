<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class SaleTax extends Model
{
    public $timestamps = false;

    public function saleorderDetails()
    {
        return $this->belongsTo("App\Models\SaleOrderDetail", 'sale_order_detail_id');
    } 

    public function taxType()
    {
       return $this->belongsTo("App\Models\TaxType", 'tax_type_id');
    }

    public function getSaleTaxes($sale_order_detail_id)
	{
	    $taxes = $this->where([ 'sale_order_detail_id' => $sale_order_detail_id ])->pluck('tax_type_id')->toArray();
	    return json_encode($taxes);
	}

	public function getTaxesDetails($sale_id, $sale_details_id)
	{
	    return $this->where(['sales_id' => $sale_id, 'sales_details_id' => $sale_details_id])->get();
	}

	public function getAllSaleTaxesInPercentage($sale_id, $sale_details_id)
	{
		$taxes = $this->with(['tax_types'])->where(['sale_order_detail_id' => $sale_details_id])->get();
	    $res = [];
	    foreach ($taxes as $tax) {
	        $res[] = [
	            "id" => $tax->tax_type_id,
	            "name" => $tax->name,
	            "rate" => $tax->tax_rate
	        ];
	    }
	    return json_encode($res);
	}

	public function getSaleTaxesInPercentage($sale_order_details_id)
	{
	    $saleOrderDetail = SaleOrderDetail::with(['saleTaxes:id,sale_order_detail_id,tax_type_id', 'saleOrder:id,tax_type'])->find($sale_order_details_id);
	    $result = [];
	    if ($saleOrderDetail->saleTaxes) {
	        foreach ($saleOrderDetail->saleTaxes as $key => $tax) {
	            $result[] = [
	                        "id" => $tax->id,
	                        "name" => $tax->taxType->name,
	                        "rate" => $tax->taxType->tax_rate,
	                    ];
	        }
	    }
	    return json_encode($result);
	}

    public function getAllTaxes($sale_id, $sale_details_id)
	{
	    $taxes = DB::table('sale_taxes')->where(['sales_id' => $sale_id, 'sale_order_detail_id' => $sale_details_id])->get();
	    $res = [];
	    foreach ($taxes as $tax) {
	        $res[] = $tax->tax_type_id;
	    }
	    return json_encode($res);
	}

}
