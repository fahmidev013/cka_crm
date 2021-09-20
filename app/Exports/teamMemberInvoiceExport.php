<?php

namespace App\Exports;

use App\Models\CustomerTransaction;
use App\Models\SaleOrder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class teamMemberInvoiceExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        
        $id     = $_GET['team_memberId'];

        $from        =  isset($_GET['from']) ? $_GET['from'] : null;
        $to          =  isset($_GET['to']) ? $_GET['to'] : null;
        $customer    =  isset($_GET['customerId']) ? $_GET['customerId'] : null;
        $location    =  isset($_GET['location']) ? $_GET['location'] : null;
        $status   = isset($_GET['status']) ? $_GET['status'] : null;
        $userInvoice = (new SaleOrder)->getAllInvoices($from, $to, $customer, $location, null, $status, $id)
                       ->orderBy('sale_orders.id', 'desc');

        return $userInvoice;
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
            empty($invoice->total) ? '0' : formatCurrencyAmount($total),
            empty($invoice->paid)  ? '0' : formatCurrencyAmount($paid_amount),
            $invoice->currency->name,
            $location,
            $this->statusCheck($invoice->paid,  $invoice->total),
            formatDate($invoice->order_date),
        ];
    }

    public function statusCheck($paid_amount, $total){

        if ($paid_amount == 0  && $total != 0) {
           $status = 'Unpaid';
        } else if ($paid_amount > 0 && $total > $paid_amount) {
           $status = 'Partially Paid';
        } else if ($paid_amount <= $paid_amount || $paid_amount == 0) {
           $status = 'Paid';
        }
        return $status;
    }
}
