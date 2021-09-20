<?php

namespace App\Exports;

use App\Models\SaleOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class teamMemberQuotationExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        $id = isset($_GET['team_memberId']) ? $_GET['team_memberId'] : null;
        $customer = isset($_GET['customerId']) ? $_GET['customerId'] : null;
        $location = isset($_GET['location']) ? $_GET['location'] : null;
        $from = isset($_GET['from']) ? $_GET['from'] : null ;
        $to = isset($_GET['to']) ? $_GET['to'] : null ;
        
        $userQuotation = (new SaleOrder)->getAllQuotation($from, $to, $location, $customer, null, $id)->get();
        return collect($userQuotation);
    }

    public function headings(): array
    {
        return [
            'Quotation No',
            'Customer Name',
            'Quantity',
            'Total',
            'Currency',
            'Quotation Date',
        ];
    }

    public function map($quotation): array
    {
        return [
            $quotation->reference,
            $quotation->customer->first_name.' '.$quotation->customer->last_name,
            !empty($quotation->saleOrderDetails) ? formatCurrencyAmount($quotation->saleOrderDetails->sum('quantity')) : '0',
            formatCurrencyAmount($quotation->total),
            isset($quotation->currency->name) ? $quotation->currency->name : '',
            formatDate($quotation->order_date),
        ];
    }

}
