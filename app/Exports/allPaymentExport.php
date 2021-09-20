<?php

namespace App\Exports;

use App\Models\CustomerTransaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class allPaymentExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $customer     = isset($_GET['customer']) ? $_GET['customer'] : null;
        $method       = isset($_GET['method']) ? $_GET['method'] : null;
        $from         = isset($_GET['from']) ? $_GET['from'] : null;
        $to           = isset($_GET['to']) ? $_GET['to'] : null;
        $currency = isset($_GET['currency']) ? $_GET['currency'] : null;
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $salesPayment = CustomerTransaction::orderBy('id', 'desc');
        if (! empty($from) && ! empty($to)) {
          $salesPayment->where('transaction_date', '>=', DbDateFormat($from));
          $salesPayment->where('transaction_date', '<=', DbDateFormat($to));
        }
        if (!empty($customer)) {
          $salesPayment->where('customer_id', '=', $customer);
        }
        if (!empty($method)) {
          $salesPayment->where('payment_method_id', '=', $method);
        }
        if (!empty($currency)) {
            $salesPayment->where('currency_id', '=', $currency);
        }
        if (!empty($status)) {
            $salesPayment->where('status', '=', $status);
        }   

        return $salesPayment;
    }

    public function headings(): array
    {
        return [
            'Payment No',
            'Invoice No',
            'Customer Name',
            'Payment Method',
            'Amount',
            'Currency',
            'Status',
            'Payment Date',
        ];
    }

    public function map($payment): array
    {
        return [
            sprintf("%04d", $payment->id),
            $payment->saleOrder->reference,
            $payment->customer->name,
            isset($payment->paymentMethod) ? $payment->paymentMethod->name : 'N/A',
            formatCurrencyAmount($payment->amount),
            $payment->currency->name,
            $payment->status,
            formatDate($payment->transaction_date),
        ];
    }
}
