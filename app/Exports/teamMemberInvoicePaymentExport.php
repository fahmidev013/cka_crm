<?php

namespace App\Exports;

use App\Models\CustomerTransaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class teamMemberInvoicePaymentExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function query()
    {
        
        $to       = isset($_GET['to']) ? $_GET['to'] : formatDate(date('d-m-Y'));
        $from     = isset($_GET['from']) ? $_GET['from'] :formatDate(date('Y-m-d', strtotime("-30 days")));
        $customerId = isset($_GET['customerId']) ? $_GET['customerId'] : 'all';
        $team_memberId = isset($_GET['team_memberId']) ? $_GET['team_memberId'] : 'all';
        $method   = isset($_GET['method']) ? $_GET['method'] : null;
        $currency   = isset($_GET['currency']) ? $_GET['currency'] : null;

        $InvoicePayment = CustomerTransaction::with('paymentMethod','currency','customer','saleOrder')->where('user_id',$team_memberId)
                      ->select('customer_transactions.*');
                      if ($from) {
                        $InvoicePayment->where('transaction_date', '>=', DbDateFormat($from));  
                      }
                      if ($to) {
                        $InvoicePayment->where('transaction_date', '<=', DbDateFormat($to));
                      }
                      if ($customerId && $customerId !='all') {
                        $InvoicePayment->where('customer_id', '=', $customerId);
                      }
                      if (!empty($method) && $method != 'all') {
                          $InvoicePayment->where('payment_method_id', '=', $method);
                      }
                      if (!empty($currency) && $currency != 'all') {
                        $InvoicePayment->where('customer_transactions.currency_id', '=', $currency);
                      }
        return $InvoicePayment;
        

       
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
            'Payment Date',
        ];
    }

    public function map($payment): array
    {
        return [
            sprintf("%04d", $payment->id),
            $payment->saleOrder->reference,
            $payment->customer->name,
            !empty($payment->paymentMethod) ? $payment->paymentMethod->name : '',
            formatCurrencyAmount($payment->amount),
            $payment->currency->name,
            formatDate($payment->transaction_date),
        ];
    }

}
