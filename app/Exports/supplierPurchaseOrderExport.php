<?php

namespace App\Exports;

use App\Models\PurchaseOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class supplierPurchaseOrderExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $from = isset($_GET['from']) ? $_GET['from'] : null ;
        $to = isset($_GET['to']) ? $_GET['to'] : null ;
        $supplier_id = isset($_GET['supplier_id']) ? $_GET['supplier_id'] : null ;
        $supplierOrder = (new PurchaseOrder)->getPurchFilteringOrderById($from, $to, $supplier_id);

        return collect($supplierOrder);
    }

    public function headings(): array
    {
        return [
            'Purchase No',
            'Total',
            'Currency',
            'Purchase Date',
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->reference,
            formatCurrencyAmount($payment->total),
            $payment->currency_name,
            formatDate($payment->order_date),
        ];
    }
}
