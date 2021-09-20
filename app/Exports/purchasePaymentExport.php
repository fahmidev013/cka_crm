<?php

namespace App\Exports;

use App\Models\SupplierTransaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

class purchasePaymentExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $supplier = isset($_GET['supplier']) ? $_GET['supplier'] : null;
        $method   = isset($_GET['method']) ? $_GET['method'] : null;
        $currency = isset($_GET['currency']) ? $_GET['currency'] : null;
        $from     = isset($_GET['from']) ? $_GET['from'] : null;
        $to       = isset($_GET['to']) ? $_GET['to'] : null;

        $purchasesPayment = (new SupplierTransaction)->getAll($supplier, $method, $currency, $from, $to);

        return $purchasesPayment;
    }

    public function headings(): array
    {
        return [
            'Payment No',
            'Purchase No',
            'Supplier Name',
            'Payment Method',
            'Amount',
            'Currency',
            'Payment Date',
        ];
    }

    public function map($purchase): array
    {
        return [
            sprintf("%04d", $purchase->st_id),
            $purchase->purchaseOrder->reference,
            $purchase->supplier->name,
            isset($purchase->paymentMethod) ? $purchase->paymentMethod->name : 'N/A',
            formatCurrencyAmount($purchase->amount),
            $purchase->currency->name,
            formatDate($purchase->transaction_date),
        ];
    }
}
