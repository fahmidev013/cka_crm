<?php

namespace App\Exports;

use App\Models\PurchaseOrder;
use App\Models\SupplierTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class supplierLedgerCsv implements FromCollection, WithHeadings, WithMapping
{
    public $balance = 0;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $id = isset($_GET['supplier_id']) ? $_GET['supplier_id'] : null ;
        $_GET['from'] = ($_GET['from'] == "" || $_GET['from'] == null) ? DbDateFormat($_GET['from']) : $_GET['from'];
        $_GET['to'] = ($_GET['to'] == "" || $_GET['to'] == null) ? DbDateFormat(date('Y-m-d')) : $_GET['to'];

        $po = PurchaseOrder::with('currency')->where('supplier_id', $id);
            if (isset($_GET['from']) && isset($_GET['to'])) {
             $po->where('order_date', '>=', DbDateFormat($_GET['from'])); 
             $po->where('order_date', '<=', DbDateFormat($_GET['to'])); 
            }
        $po = $po->select('reference','order_date as transaction_date','total', 'currency_id')
            ->get()
            ->toArray();
        $payment = SupplierTransaction::with(['purchaseOrder:id,reference', 'currency'])->where('supplier_id',$id);
                if (isset($_GET['from']) && isset($_GET['to'])) {
                     $payment->where('transaction_date', '>=', DbDateFormat($_GET['from'])); 
                     $payment->where('transaction_date', '<=', DbDateFormat($_GET['to'])); 
                    }
        $payment = $payment->select('purchase_order_id','transaction_date','amount','currency_id')
                 ->get()
                 ->toArray();

        $merge   = array_merge($po, $payment);
        
        usort($merge, "custom_sort");

        return collect($merge);

    }

    public function headings(): array
    {
        return [
            'Purchase Order',
            'Purchase Date',
            'Paid Amount',
            'Bill Amount',
            'Balance',
            'Currency'
        ];
    }

    public function map($payment): array
    {
        $this->balance += isset($payment['total']) ? $payment['total'] : 0;
        $this->balance -= isset($payment['amount']) ? $payment['amount'] : 0;

        if (!empty($payment['purchase_order_id'])) {
            $reference = $payment['purchase_order']['reference'];
        } else {
            $reference = $payment['reference'];
        }

        return [
            $reference,
            formatDate($payment['transaction_date']),
            isset($payment['amount']) ? formatCurrencyAmount($payment['amount']) : '0',
            isset($payment['total']) ? formatCurrencyAmount($payment['total']) : '0',
            formatCurrencyAmount($this->balance),
            $payment['currency']['name']
        ];
    }
}
