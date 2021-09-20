<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Helpers;
use Session;

class SupplierTransaction extends Model
{
    public $timestamps = false;

    public function paymentMethod()
    {
       return $this->belongsTo("App\Models\PaymentMethod"); 
    }

    public function currency()
    {
       return $this->belongsTo("App\Models\Currency"); 
    }

    public function supplier()
    {
       return $this->belongsTo("App\Models\Supplier"); 
    }

    public function purchaseOrder()
    {
       return $this->belongsTo('App\Models\PurchaseOrder', 'purchase_order_id'); 
    }

    public function transaction()
    {
        return $this->belongsTo("App\Models\Transaction", 'transaction_reference_id', 'transaction_reference_id');
    }

    public function getAll($supplier = null, $method = null, $currency = null, $from = null, $to = null) 
    {
        $purchasesPayment = $this->select('supplier_transactions.id as st_id', 'supplier_transactions.supplier_id', 'supplier_transactions.payment_method_id', 'supplier_transactions.currency_id', 'supplier_transactions.purchase_order_id', 'supplier_transactions.amount', 'supplier_transactions.transaction_date')->with(['supplier:id,name', 'paymentMethod:id,name', 'currency:id,name', 'purchaseOrder:id,reference']);

        if (!empty($from)) {
             $purchasesPayment->where('transaction_date', '>=', DbDateFormat($from));
        }
        if (!empty($to)) {
             $purchasesPayment->where('transaction_date', '<=', DbDateFormat($to));
        }
        if (!empty($supplier)) {
             $purchasesPayment->where('supplier_id', '=', $supplier);
        }
        if (!empty($currency)) {
             $purchasesPayment->where('currency_id', '=', $currency);
        }
        if (!empty($method)) {
             $purchasesPayment->where('payment_method_id', '=', $method);
        }
        if (Helpers::has_permission(Auth::user()->id, 'own_purchase_payment') && !Helpers::has_permission(Auth::user()->id, 'manage_purch_payment')) {
            $id = Auth::user()->id;
            $purchasesPayment->where('user_id', $id);
        }

        return $purchasesPayment;
    }
}
