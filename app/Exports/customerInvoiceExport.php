<?php

namespace App\Exports;

use App\Models\SaleOrder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class customerInvoiceExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $to     = isset($_GET['to']) ? $_GET['to'] : null ;
        $from   = isset($_GET['from']) ? $_GET['from'] : null ;
        $id     = isset($_GET['customer']) ? $_GET['customer'] : null;
        $status = isset($_GET['pay_status_type']) ? $_GET['pay_status_type'] : null ;        
        $data   = (new SaleOrder)->getAllInvoices($from, $to, $id, null, null, $status, null, 'customerPanel')->orderBy('created_at', 'desc');

        return $data;
    }

    public function headings(): array
    {
        return [
            'Invoice No',
            'Total Price',
            'Paid Amount',
            'Currency',
            'Status',
            'Invoice Date',
        ];
    }

    public function map($invoice): array
    {
        return [
            $invoice->reference,
            formatCurrencyAmount($invoice->total),
            $invoice->paid != 0 ? formatCurrencyAmount($invoice->paid) : formatCurrencyAmount(0),
            isset($invoice->customer->currency->name) && !empty($invoice->customer->currency->name) ? $invoice->customer->currency->name : '',
            $this->statusCheck($invoice->paid,  $invoice->total),
            formatDate($invoice->order_date),
        ];
    }

    public function statusCheck($paid, $total)
    {
        if ($paid == 0) {
           $status = 'Unpaid';
        } else if ($paid > 0 && $total > $paid ) {
           $status = 'Partially Paid';
        } else if ($total<=$paid) {
           $status = 'Paid';
        }
        return $status;
    }
}
