<?php

namespace App\Exports;

use App\Models\CustomerTransaction;
use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class customerPaymentExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $from = isset($_GET['from']) ? $_GET['from'] : null;
        $to = isset($_GET['to']) ? $_GET['to'] : null;
        $method = isset($_GET['method']) ? $_GET['method'] : null;
        $currency = isset($_GET['currency']) ? $_GET['currency'] : null;
    
        $customerId = isset($_GET['customerId']) ? $_GET['customerId'] : null;
        $customerPayment = CustomerTransaction::where('customer_id', $customerId);
        if ($from && $to) {
           $customerPayment->whereDate('transaction_date','>=', DbDateFormat($from));
           $customerPayment->whereDate('transaction_date','<=', DbDateFormat($to));
        }
        if (!empty($method) && $method != 'all') {
            $customerPayment->where('payment_method_id', '=', $method);
        } 
        if (!empty($currency) && $currency != 'all') {
            $customerPayment->where('customer_transactions.currency_id', '=', $currency);
        }  
        return $customerPayment;
    }

    public function headings(): array
    {
        return [
            'Payment No',
            'Invoice No',
            'Payment Type',
            'Amount',
            'Currency',
            'Payment Date',
        ];
    }

    public function map($payment): array
    {
        return [
            sprintf("%04d", $payment->id),
            $payment->saleOrder->reference,
            isset($payment->paymentMethod->name) ? $payment->paymentMethod->name : '-',
            !empty($payment->amount) ? formatCurrencyAmount($payment->amount) : '0',
            $payment->currency->name,
            formatDate($payment->transaction_date),
        ];
    }
}
