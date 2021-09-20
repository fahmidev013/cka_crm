<?php

namespace App\Exports;

use App\Models\SaleOrder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class projectInvoicesExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $from = isset($_GET['from']) ? $_GET['from'] : null ;
        $to = isset($_GET['to']) ? $_GET['to'] : null ;
        $project = isset($_GET['project']) ? $_GET['project'] : null ;

        $saleInvoice = (new SaleOrder)->getAllSaleOrderByProject($from, $to, $project)->orderBy('id','desc');
        return $saleInvoice;
    }

    public function headings(): array
    {
        return [
            'Invoice No',
            'Customer  Name',
            'Total Price',
            'Paid Amount',
            'Currency',
            'Status',
            'Invoice Date'
        ];
    }
    
    public function map($invoice): array
    {  
        return [
            $invoice->reference,
            $invoice->first_name.' '.$invoice->last_name,
            $invoice->total,
            $invoice->paid_amount,
            $invoice->currency_name,
            $this->statusCheck($invoice->paid_amount,  $invoice->total),
            formatDate($invoice->order_date),
        ];
    }

    public function statusCheck($paid_amount, $total){

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
