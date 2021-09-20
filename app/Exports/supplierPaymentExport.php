<?php

namespace App\Exports;

use App\Models\Purchase;
use App\Models\SupplierTransaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class supplierPaymentExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $from = isset($_GET['from']) ? $_GET['from'] : null;
        $to   = isset($_GET['to']) ? $_GET['to'  ] : null;
        $id  = isset($_GET['supplier_id']) ? $_GET['supplier_id'] : null;

        $purchasesPayment = SupplierTransaction::with('supplier','currency','paymentMethod')
                          ->where('supplier_id',$id)
                          ->select('supplier_transactions.*')->orderBy('supplier_transactions.id', 'desc');
        if (!empty($from)) {
             $purchasesPayment->where('transaction_date', '>=', DbDateFormat($from));
        }
        if (!empty($to)) {
             $purchasesPayment->where('transaction_date', '<=', DbDateFormat($to));
        }
        return $purchasesPayment;
    }

    public function headings(): array
    {
        return [
            'Payment No',
            'Purchase No',
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
            !empty($payment->purchaseOrder) ? $payment->purchaseOrder->reference : '',
            !empty($payment->paymentMethod) ? $payment->paymentMethod->name : '',
            formatCurrencyAmount($payment->amount),
            $payment->currency->name,
            formatDate($payment->transaction_date),
        ];
    }
}
