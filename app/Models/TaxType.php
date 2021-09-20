<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Cache;

class TaxType extends Model
{
	public $timestamps = false;

	public function saleTaxes() {
        return $this->hasMany("App\Models\SalesTax",'tax_type_id');
    }

    public function items()
    {
        return $this->hasMany("App\Models\Item",'tax_type_id');
    }

    public function purchaseTaxes()
    {
        return $this->hasMany("App\Models\PurchaseTax",'tax_type_id');
    }

    public static function getAll()
    {
        $data = Cache::get('gb-taxType');
        if (empty($data)) {
            $data = parent::all();
            Cache::put('gb-taxType', $data, 30 * 86400);
        }
        return $data;
    }

    public function calculateTax($tax, $taxType, $discountOn, $itemPrice, $itemDiscount, $itemDiscountType, $otherDiscount, $otherDiscountType, $discountPrice)
    {
        $indDiscount = 0;
        $indOtherDiscount = 0;
        $tempTotal = 0;
        $calculatedTax = 0;
        $itemDiscount = validateNumbers($itemDiscount);
        $otherDiscount = validateNumbers($otherDiscount);
        if ($itemDiscountType == "$") {
            $indDiscount = $itemDiscount;
        } else if ($itemDiscountType == "%") {
            $indDiscount = $itemPrice * $itemDiscount / 100;
        }

        if ($otherDiscountType == "$") {
            if (!empty($discountPrice)) {
                $indOtherDiscount = $otherDiscount * ($itemPrice - $indDiscount) / $discountPrice;
            }
        } else if ($otherDiscountType == "%") {
            $indOtherDiscount = ($itemPrice - $indDiscount) * $otherDiscount / 100;
        }

        if ($discountOn == "after") {
            $tempTotal = $itemPrice - $indDiscount - $indOtherDiscount;
            if ($taxType == "exclusive") {
                $calculatedTax = $tempTotal * $tax / 100;
            } else if ($taxType == "inclusive") {
                $calculatedTax = $tempTotal - ($tempTotal / (($tax / 100) +1));
            }
        } else if ($discountOn == "before") {
            if ($taxType == "exclusive") {
                $calculatedTax = $itemPrice * $tax / 100;
            } else if ($taxType == "inclusive") {
                $calculatedTax = $itemPrice - ( $itemPrice / (($tax / 100) +1));
            }
        }
        return $calculatedTax;
    }

    public function calculateDiscountAmount($discount_type, $tax_type, $unit_price=0, $quantity=0, $discount=0, $taxes = null)
    {
        $unit_price = validateNumbers($unit_price);
        $quantity = validateNumbers($quantity);
        $discount = validateNumbers($discount);
        $discountAmount = 0;
        if (empty($taxes) || ($discount_type == 'before' && $tax_type == 'exclusive') || ($discount_type == 'after' && $tax_type == 'inclusive')) {
            $discountAmount = ($unit_price * $quantity * $discount) / 100;
        } else {
            $tax = TaxType::whereIn('id', $taxes)->pluck('tax_rate')->toArray();
            if ($discount_type == 'before' && $tax_type == 'inclusive') {
                $price = ($unit_price / (1 + (array_sum($tax) / 100))) * $quantity;
            } else {
                $price = ($unit_price * (1 + (array_sum($tax) / 100))) * $quantity;
            }
            $discountAmount = ( $price * $discount ) / 100;
        }
        return round($discountAmount, 8);
    }
}
