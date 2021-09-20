<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Http\Controllers\DateTime;
use App\Models\Currency;

class SaleReportExport implements FromCollection, WithHeadings, WithMapping 
{
    /**
     * [Here we need to fetch data from data source]
     * @return [Database Object] [Here we are fetching data from User table and also role table through Eloquent Relationship]
     */
    public function collection()
    {
        $data = [];
        $data = app('App\Http\Controllers\ReportController')->generateSaleData(); 
        $list = $data['itemList'];
        $searchType = $data['searchType'];
        unset($data);
        foreach ($list as $key => $value) {
            if ($searchType == 'daily' || $searchType == 'custom') {
              $list[$key]['orderDate'] = formatDate(date('d-m-Y', strtotime($list[$key]['orderDate'])));
            } else if ($searchType == 'monthly' || $searchType == 'yearly' ) {
              $list[$key]['orderDate'] = date('F-Y', strtotime($list[$key]['orderDate']));
            } else {
              $list[$key]['orderDate'] = formatDate(date('d-m-Y', strtotime($list[$key]['orderDate'])));
            }
        }
        return collect($list);

    }

    /**
     * [Here we are putting Headinngs of The CSV]
     * @return [array] [Exel Headings]
     */
    public function headings(): array
    {
        $currency   = Currency::getDefault('currency');
        return[
            __('Date'),
            __('No of Orders'),
            __('Sales Volume'),
            __('Sales Value') . '(' . $currency->name . ')',
            __('Cost') . '(' . $currency->name . ')',
            __('Tax') . '(' . $currency->name . ')',
            __('Discount') . '(' . $currency->name . ')',
            __('Profit') . '(' . $currency->name . ')',
        ];
    }
    /**
     * [map description]
     * @param  [collection] $list [description]
     * @return [array_value]       [description]
     */
    public function map($list): array
    {
        return[
            $list['orderDate'],
            $list['totalInvoice'],
            $list['totalQuantity'],
            $list['totalactualSalePrices'],
            $list['totalPurchase'],
            $list['totalSaleTax'],
            $list['itemDiscountAmount'] + $list['otherDiscountAmount'],
            $list['totalProfitAmount'],
        ];
    }
}
