<?php

namespace App\Exports;

use App\Models\PurchaseOrder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class allPurchaseExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $from =  isset($_GET['from']) ? $_GET['from'] : '';
        $to =  isset($_GET['to']) ? $_GET['to'] : '';
        $supplier_id = isset($_GET['supplier']) ? $_GET['supplier'] : '';
        $currency = isset($_GET['currency']) ? $_GET['currency'] : '';
        $location = isset($_GET['location']) ? $_GET['location'] : '';
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        $purchaseList = (new PurchaseOrder)->getAll($supplier_id, $currency, $location, $status, $from, $to)->orderBy('id','DESC');
        return $purchaseList;
    }

    public function headings(): array
    {
        return [
            'Purchase No',
            'Supplier Name',
            'Total',
            'Paid Amount',
            'Currency',
            'Status',
            'Purchase Date',
        ];
    }

    public function map($purchase): array
    {
        return [
            $purchase->reference,
            $purchase->supplier->name,
            $purchase->total == 0 ? formatCurrencyAmount(0) : formatCurrencyAmount($purchase->total),
            $purchase->paid == 0 ? formatCurrencyAmount(0) : formatCurrencyAmount($purchase->paid),
            $purchase->currency->name,
            $this->statusCheck($purchase->paid,  $purchase->total),
            formatDate($purchase->order_date),
        ];
    }

    public function statusCheck($paid_amount, $total)
    {
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
