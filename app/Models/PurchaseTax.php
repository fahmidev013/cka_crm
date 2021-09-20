<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseTax extends Model
{
    public $timestamps = false;

    public function purchaseOrderDetail()
    {
    	return $this->belongsTo('App\Models\PurchaseOrderDetail', 'purchase_order_detail_id');
    }

    public function taxType()
    {
    	return $this->belongsTo('App\Models\TaxType', 'tax_type_id');
    }

	public function getPurchaseTaxes($purch_details_id)
	{
		$taxes = $this->where([ 'purchase_order_detail_id' => $purch_details_id ])->pluck('tax_type_id')->toArray();
	    return json_encode($taxes);
	}

    public function getAllPurchaseTaxesInPercentage($purch_details_id)
    {
        $purchaseOrderDetail = PurchaseOrderDetail::with(['purchaseTaxes:id,purchase_order_detail_id,tax_type_id', 'purchaseOrder:id,tax_type'])->find($purch_details_id);
        $result = [];
        if ($purchaseOrderDetail->purchaseTaxes) {
            foreach ($purchaseOrderDetail->purchaseTaxes as $key => $tax) {
                $result[] = [
                            "id" => $tax->id,
                            "name" => $tax->taxType->name,
                            "rate" => $tax->taxType->tax_rate,
                        ];
            }
        }
        return json_encode($result);
    }
}
