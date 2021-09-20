<?php

namespace App\Models;
use DB;
use Auth;
use Illuminate\Database\Eloquent\Model;

class CustomerTransaction extends Model
{
    protected $table    = 'customer_transactions';
    public $timestamps  = false;
    protected $fillable = ['status'];

    public function paymentMethod()
    {
       return $this->belongsTo("App\Models\PaymentMethod", 'payment_method_id');
    }

    public function currency()
    {
       return $this->belongsTo("App\Models\Currency", 'currency_id');
    }

    public function customer()
    {
       return $this->belongsTo("App\Models\Customer", 'customer_id');
    }

    public function saleOrder()
    {
       return $this->belongsTo("App\Models\SaleOrder", 'sale_order_id');
    }

    public function transactionReference()
    {
        return $this->belongsTo("App\Models\TransactionReference");
    }

    public function createCustomerTransaction($data, $reference_id, $reference_type)
    {
        $customer_transaction                           = new CustomerTransaction;
        $customer_transaction->user_id                  = Auth::user()->id;
        $customer_transaction->payment_method_id        = isset($data['payment_type_id']) && !empty($data['payment_type_id']) ? $data['payment_type_id'] : null;
        $customer_transaction->customer_id              = $data['customer_id'];
        $customer_transaction->sale_order_id            = $data['invoice_no'];
        $customer_transaction->transaction_reference_id = $reference_id;
        $customer_transaction->currency_id              = $data['customerCurrency'];
        $customer_transaction->transaction_date         = DbDateFormat($data['payment_date']);
        $customer_transaction->amount                   = validateNumbers($data['incoming_amount']);
        $customer_transaction->exchange_rate            = validateNumbers($data['exchange_rate']);
        $customer_transaction->status                   = $data['status'];
        $customer_transaction->save();
        return $customer_transaction->id;
    }
}
