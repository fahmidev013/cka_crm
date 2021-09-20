<?php

namespace App\Exports;

use App\Models\SupplierTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class teamMemberPurchasePaymentExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {       
      $id       = isset($_GET['team_memberId']) ? $_GET['team_memberId'] : null ;
      $to       = isset($_GET['to']) ? $_GET['to'] : null ;
      $from     = isset($_GET['from']) ? $_GET['from'] : null ;
      $method     = isset($_GET['method']) ? $_GET['method'] : null ;
      $supplier     = isset($_GET['supplier']) ? $_GET['supplier'] : null ;
      $currency     = isset($_GET['currency']) ? $_GET['currency'] : null ;
      $purchasesPayment = SupplierTransaction::with('supplier','currency','paymentMethod')
                        ->select('supplier_transactions.*')->where('user_id', $id);
      if (!empty($from) && !empty($to)) {
        $purchasesPayment->where('transaction_date', '>=', DbDateFormat($from));
        $purchasesPayment->where('transaction_date', '<=', DbDateFormat($to));        
      }
      if (!empty($supplier) && $supplier != 'all') {
           $purchasesPayment->where('supplier_id', '=', $supplier);             
      }
      if (!empty($currency)) {
           $purchasesPayment->where('currency_id', '=', $currency);
      }
      if (!empty($method)) {
           $purchasesPayment->where('payment_method_id', '=', $method);
      }

      $purchasesPayment = $purchasesPayment->orderBy('id','desc')->get();
      return collect($purchasesPayment);
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

    public function map($payment): array
    {
        return [
            sprintf("%04d", $payment->id),
            !empty($payment->purchaseOrder) ? $payment->purchaseOrder->reference : '',
            $payment->supplier->name,
            isset($payment->paymentMethod) ? $payment->paymentMethod->name : '',
            formatCurrencyAmount($payment->amount),
            $payment->currency->name,
            formatDate($payment->transaction_date),
        ];
    }

}
