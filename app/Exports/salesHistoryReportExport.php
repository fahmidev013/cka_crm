<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Session;
use App\Models\Currency;

class salesHistoryReportExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($report)
    {
    	$this->report = $report;
    }

    public function collection()
    {
        $currency = Currency::getDefault();
        $to = DbDateFormat($_GET['to']);
        $from = DbDateFormat($_GET['from']);
        $user = $_GET['customer'];

        $itemList =  $this->report->getSalesHistoryReport($from,$to,$user);

        foreach ($itemList as $key => $item) {
            $profit = ($item->sale_price_excl_tax-$item->purchase_price_incl_tax);
            if ($item->purchase_price_incl_tax<=0) {

            $profit_margin = 100;
            } else {
                $profit_margin = ($profit*100)/$item->purchase_price_incl_tax;
            }
            $data[$key]['Order No'] = $item->reference;
            $data[$key]['Date'] = formatDate($item->ord_date);
            $data[$key]['Customer'] = $item->name;
            $data[$key]['Qty'] = $item->quantity;
            $data[$key]['Sales Value('.$currency->symbol.')'] = $item->sale_price_excl_tax;
            $data[$key]['Cost Value('.$currency->symbol.')'] = $item->purchase_price_incl_tax;
            $data[$key]['Tax('.$currency->symbol.')'] = $item->sale_price_incl_tax-$item->sale_price_excl_tax;
            $data[$key]['Profit('.$currency->symbol.')'] = $profit;
            $data[$key]['Profit Margin(%)'] = number_format(($profit_margin),2,'.',',');
        }
        return $data;
    }

    public function headings(): array
    {
        return [
            'Order No',
            'Date',
            'Customer',
            'Qty',
            'Cost Value',
            'Tax',
            'Profit',
            'Profit Margin',
        ];
    }
}
