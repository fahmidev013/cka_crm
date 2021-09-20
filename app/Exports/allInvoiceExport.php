<?php

namespace App\Exports;

use App\Models\SaleOrder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class allInvoiceExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $from = isset($_GET['from']) ? $_GET['from'] : null;
        $to = isset($_GET['to']) ? $_GET['to'] : null;
        $customer = isset($_GET['customer']) ? $_GET['customer'] : null;
        $location = isset($_GET['location']) ? $_GET['location'] : null;
        $currency = isset($_GET['currency']) ? $_GET['currency'] : null;
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $saleInvoices = (new SaleOrder)->getAllInvoices($from, $to, $customer, $location, $currency, $status);
        return $saleInvoices;
    }

    public function headings(): array
    {
        return [
            'Invoice No',
            'Customer Name',
            'Total Price',
            'Paid Amount',
            'Currency',
            'Location',
            'Status',
            'Invoice Date',
        ];
    }

    public function map($invoice): array
    {
        $total = $invoice->total == 0 ? 0 : $invoice->total;
        $paid_amount = $invoice->paid == 0 ? 0 : $invoice->paid;
        $customer = isset($invoice->customer->name) ? $invoice->customer->name . '(' . $invoice->currency->name . ')' : "Walking Customer ". $invoice->currency->name . ')';
        $location = isset($invoice->location->name) ? $invoice->location->name : "-";
        return [
            $invoice->reference,
            $customer,
            formatCurrencyAmount($total),
            formatCurrencyAmount($paid_amount),
            $invoice->currency->name,
            $location,
            $this->statusCheck($paid_amount,  $total),
            formatDate($invoice->order_date),
        ];
    }

    public function statusCheck($paid_amount, $total) {
        if ($paid_amount == 0) {
           $status = 'Unpaid';
        } elseif ($paid_amount > 0 && $total > $paid_amount ) {
           $status = 'Partially Paid';
        } elseif ($total<=$paid_amount) {
           $status = 'Paid';
        }
        return $status;
    }
}
