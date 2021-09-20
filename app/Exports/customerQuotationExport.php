<?php

namespace App\Exports;

use App\Models\SaleOrder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Http\Controllers\DateTime;

class customerQuotationExport implements FromCollection, WithHeadings, WithMapping 
{    
    public function collection()
    {
        $from    = !empty($_GET['from']) ? $_GET['from'] : null ;
        $to      = !empty($_GET['to']) ? $_GET['to'] : null ;
        $customerId = isset($_GET['debtor_no']) ? $_GET['debtor_no'] : null ;
        if (!empty($from) && !empty($to)) {
            $data = SaleOrder::with(['customer:id,name', 'currency:id,name,symbol', 'saleOrderDetails:id,sale_order_id,quantity'])
                        ->where([ 'transaction_type' => 'SALESORDER', 'customer_id' => $customerId, 'order_type'=>'Direct Order'])
                        ->where('order_date', '>=', DbDateFormat($from))
                        ->where('order_date', '<=' , DbDateFormat($to))
                        ->orderBy('created_at', 'desc')->get();
        } else {
            $data = SaleOrder::with(['customer:id,name', 'currency:id,name,symbol', 'saleOrderDetails:id,sale_order_id,quantity'])
                        ->where([ 'transaction_type' => 'SALESORDER', 'customer_id' => $customerId, 'order_type'=>'Direct Order'])
                        ->orderBy('created_at', 'desc')->get();
        }
        foreach ($data as $singleData) {
            $checkInvoice = (new SaleOrder)->checkConversion($singleData->id)->first();
            if (isset($checkInvoice->reference) && !empty($checkInvoice->reference)) {
               $singleData->flag = 'Yes';
            } else {
                $singleData->flag = 'No';
            }
        }        
        
        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Quotation',
            'Total Quantity',
            'Total Amount',
            'Currency',
            'Quotation Converted',
            'Quotation Date',
        ];
    }

    public function map($quotation): array
    {
        return [
            $quotation->reference,
            !empty($quotation->saleOrderDetails) ? $quotation->saleOrderDetails->sum('quantity') : 0,
            formatCurrencyAmount($quotation->total),
            isset($quotation->currency->name) && !empty($quotation->currency->name) ? $quotation->currency->name : '',
            $quotation->flag,
            formatDate($quotation->order_date),
        ];
    }
}
