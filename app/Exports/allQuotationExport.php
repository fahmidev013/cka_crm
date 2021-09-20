<?php

namespace App\Exports;

use App\Models\SaleOrder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class allQuotationExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function query()
    {
        $from     = isset($_GET['from']) ? $_GET['from'] : null;
        $to       = isset($_GET['to']) ? $_GET['to'] : null;
        $location = isset($_GET['location']) ? $_GET['location'] : null;
        $customer = isset($_GET['customerId']) ? $_GET['customerId'] : null;
        $currency = isset($_GET['currency']) ? $_GET['currency'] : null;
        $saleOrders = (new SaleOrder)->getAllQuotation($from, $to, $location, $customer, $currency)->latest('id');
        return $saleOrders;
    }

    public function headings(): array
    {
        return [
            'Quotation No',
            'Customer Name',
            'Quantity',
            'Total',
            'Currency',
            'Location',
            'Quotation Date',
        ];
    }

    public function map($quotation): array
    {
        return [
            $quotation->reference,
            $quotation->customer->first_name .' '. $quotation->customer->last_name,
            !empty($quotation->saleOrderDetails) ? formatCurrencyAmount($quotation->saleOrderDetails->sum('quantity')) : '0',
            formatCurrencyAmount($quotation->total),
            isset($quotation->currency->name) ? $quotation->currency->name : '',
            isset($quotation->location->name) ? $quotation->location->name : '',
            formatDate($quotation->order_date),
        ];
    }

}
