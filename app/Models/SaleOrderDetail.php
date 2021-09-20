<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\TaxType;

class SaleOrderDetail extends Model
{
    public $timestamps = false;
    
    public function saleOrder() {
        return $this->belongsTo('App\Models\SaleOrder','sale_order_id');
    }
    
    public function saleTaxes() {
       return $this->hasMany("App\Models\SaleTax",'sale_order_detail_id');
    }

    public function taxType() {
       return $this->belongsTo("App\Models\TaxType",'tax_type_id');
    }

    public function item()
    {
        return $this->belongsTo("App\Models\Item", 'item_id');
    }

    public function store($sale_order_id, $item_id, $description, $item_name, $unit_price, $quantity_sent, $quantity, $discount_amount, $discount, $discount_type, $hsn, $sorting_no)
    {
        $saleOrderDetail                = new SaleOrderDetail();
        $saleOrderDetail->sale_order_id = $sale_order_id;
        $saleOrderDetail->item_id       = !empty($item_id) ? $item_id : null;
        $saleOrderDetail->description   = stripBeforeSave($description);
        $saleOrderDetail->item_name     = stripBeforeSave($item_name);
        $saleOrderDetail->unit_price    = validateNumbers($unit_price);
        $saleOrderDetail->quantity_sent = validateNumbers($quantity_sent);
        $saleOrderDetail->quantity      = validateNumbers($quantity);
        $saleOrderDetail->discount_amount = $discount_amount;
        $saleOrderDetail->discount      = validateNumbers($discount);
        $saleOrderDetail->discount_type = $discount_type;
        $saleOrderDetail->hsn           = $hsn;
        $saleOrderDetail->sorting_no    = !empty($sorting_no) ? $sorting_no : 0;
        $saleOrderDetail->created_at    = date("Y-m-d h:i:s");
        $saleOrderDetail->save();
        return $saleOrderDetail;
    }

    public function storeMass($request, $sale_order_id, $item_id, $description, $item_name, $unit_price, $quantity_sent, $quantity, $discount_amount, $discount, $discount_type, $hsn, $sorting_no, $item_tax)
    {
        $saleOrderDetails = [];
        $sorting_no = [];
        foreach ($item_id as $key => $item) {
            $sorting_no[$key] = $key+1;
            if (validateNumbers($quantity[$key]) > 0) {
                $itemDescription= $request->has_description == 'on' ? $description[$key] : "";
                $discount[$key] = $discount[$key] ? $discount[$key] : 0;
                $discountAmount = 0;
                if ($request->has_item_discount == 'on') {
                    if ($discount_type[$key] == '$') {
                        $discountAmount = validateNumbers($discount[$key]);
                    } else {
                        $discountAmount = validateNumbers($discount[$key]) * validateNumbers($unit_price[$key]) * validateNumbers($quantity[$key]) / 100;
                    }
                }
                $itemDiscount      = $request->has_item_discount == 'on' ? $discount[$key] : 0;
                $itemDiscount_type = $request->has_item_discount == 'on' ? $discount_type[$key] : '%';
                $itemHsn           = $request->has_hsn == 'on' ? $hsn[$key] : "";
                $saleOrderDetails[] = $this->store($sale_order_id, $item_id[$key], $itemDescription, $item_name[$key], $unit_price[$key], 0, $quantity[$key], $discountAmount, $itemDiscount, $itemDiscount_type, $itemHsn, $sorting_no[$key]);
            }
        }
        return $saleOrderDetails;
    }

    public function storeCustomItems($request, $sale_order_id, $item_id, $description, $item_name, $unit_price, $quantity_sent, $quantity, $discount_amount, $discount, $discount_type, $hsn, $sorting_no, $item_tax, $row_no)
    {
        $saleOrderDetails = [];
        foreach ($item_name as $key => $item) {
            if ($item_name[$key] != null && $quantity[$key] > 0) {
                $itemDescription    = $request->has_description == 'on' ? $description[$key] : "";
                $discount[$key]     = $discount[$key] ? $discount[$key] : 0;
                $discountAmount     = 0;
                if ($request->has_item_discount == 'on') {
                    if ($discount_type[$key] == '$') {
                        $discountAmount = validateNumbers($discount[$key]);
                    } else {
                        $discountAmount = validateNumbers($discount[$key]) * validateNumbers($unit_price[$key]) * validateNumbers($quantity[$key]) / 100;
                    }
                }
                $itemDiscount      = $request->has_item_discount == 'on' ? $discount[$key] : 0;
                $itemDiscount_type = $request->has_item_discount == 'on' ? $discount_type[$key] : '%';
                $itemHsn           = $request->has_hsn == 'on' ? $hsn[$key] : "";
                $itemId = isset($item_id[$key]) ? $item_id[$key] : null;
                $saleOrderDetails[] = $this->store($sale_order_id, $itemId, $itemDescription, $item_name[$key], $unit_price[$key], 0, $quantity[$key], $discountAmount, $itemDiscount, $itemDiscount_type, $itemHsn, $sorting_no[$key]);
            }
        }
        return $saleOrderDetails;
    }

    public function updateOrder($id, $item_id, $description, $item_name, $unit_price, $quantity, $discount_amount, $discount, $discount_type, $hsn, $sorting_no)
    {
        $orderDetail = $this->find($id);
        if (!empty($orderDetail)) {
            $orderDetail->item_id = $item_id;
            $orderDetail->description = stripBeforeSave($description);
            $orderDetail->item_name = stripBeforeSave($item_name);
            $orderDetail->unit_price = validateNumbers($unit_price);
            $orderDetail->quantity = validateNumbers($quantity);
            $orderDetail->discount_amount = $discount_amount;
            $orderDetail->discount = validateNumbers($discount);
            $orderDetail->discount_type = $discount_type;
            $orderDetail->hsn = $hsn;
            $orderDetail->sorting_no = $sorting_no;
            $orderDetail->save();
        }
        return $orderDetail;
    }

    public function updateMassDetails($request, $ids, $item_id, $description, $item_name, $unit_price, $quantity, $discount_amount, $discount, $discount_type, $hsn, $sorting_no, $tax=null)
    {
        $updatedList = [];
        if (!empty($ids)) {
            foreach ($ids as $key => $id) {
                $discountAmount = 0;
                if ($discount_type[$key] == "$") {
                    $discountAmount = validateNumbers($discount[$key]);
                }
                else if ($discount_type[$key] == "%") {
                    $discountAmount = validateNumbers($discount[$key]) * validateNumbers($unit_price[$key]) * validateNumbers($quantity[$key]) / 100;
                }
                $updatedList[] = $this->updateOrder($id, $item_id[$key], $description[$key], $item_name[$key], $unit_price[$key], $quantity[$key], $discountAmount, $discount[$key], $discount_type[$key], $hsn[$key], $sorting_no[$key]);
            }
        }
        return $updatedList;
    }

    public function storeQuotationOnUpdate($request, $sale_order_id, $ids, $item_id, $description, $item_name, $unit_price, $quantity, $discount_amount, $discount, $discount_type, $hsn, $sorting_no, $tax=null)
    {
        $saleOrderDetails = [];
        foreach ($ids as $key => $details_id) {
            if ($quantity[$key] > 0) {
                $itemDescription= $request->has_description == 'on' ? $description[$key] : "";
                $discount[$key] = $discount[$key] ? $discount[$key] : 0;
                $discountAmount = 0;
                if ($request->has_item_discount == 'on') {
                    if ($discount_type[$key] == '$') {
                        $discountAmount = $discount[$key];
                    } else {
                        if (isset($item_tax) && !empty($item_tax[$details_id])) {
                            $discountAmount = (new TaxType)->calculateDiscountAmount($request->discount_on, $request->tax_type, $unit_price[$key], $quantity[$key], $discount[$key], $item_tax[$details_id]);
                        } else {
                            $discountAmount = (new TaxType)->calculateDiscountAmount($request->discount_on, $request->tax_type, $unit_price[$key], $quantity[$key], $discount[$key]);
                        }
                    }
                }
                $itemDiscount      = $request->has_item_discount == 'on' ? $discount[$key] : 0;
                $itemDiscount_type = $request->has_item_discount == 'on' ? $discount_type[$key] : '%';
                $itemHsn           = $request->has_hsn == 'on' ? $hsn[$key] : "";
                $saleOrderDetails[] = $this->store($sale_order_id, $item_id[$key], $itemDescription, $item_name[$key], $unit_price[$key], 0, $quantity[$key], $discountAmount, $itemDiscount, $itemDiscount_type, $itemHsn, $sorting_no[$key]);
            }
        }
        return $saleOrderDetails;
    }
}
