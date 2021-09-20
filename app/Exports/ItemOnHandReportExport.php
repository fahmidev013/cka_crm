<?php

namespace App\Exports;

use App\Models\Currency;
use App\Models\Preference;
use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Http\Controllers\DateTime;
use App\Models\Item;

class ItemOnHandReportExport implements FromCollection, WithHeadings, WithMapping 
{
    /**
     * [Here we need to fetch data from data source]
     * @return [Database Object] [Here we are fetching data from User table and also role table through Eloquent Relationship]
     */
    public function collection()
    {
        $type     = isset($_GET['type']) ? $_GET['type'] : 'all';
        $location = isset($_GET['location']) ? $_GET['location'] : 'all';
        
        return (new Report)->getInventoryStockOnHand($type,$location);
    }

    /**
     * [Here we are putting Headinngs of The CSV]
     * @return [array] [Exel Headings]
     */
    public function headings(): array
    {
        return[
            __('Product').' ('. __('Stock ID') .')',
            __('Stock In'),
            __('Purchsase Price'),
            __('Retail Price'),
            __('In Value'),
            __('Retail Value'),
            __('Profit Value'),
        ];
    }
    /**
     * [By adding WithMapping you map the data that needs to be added as row. This way you have control over the actual source for each column. In case of using the Eloquent query builder]
     * @param  [object] $userList [It has users table info and roles table info]
     * @return [array]            [comma separated value will be produced]
     */
    public function map($data): array
    {
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $currency_symbol   = Currency::getDefault($preference)->symbol;
        return[
            $data->description . ' (' . $data->item_id . ')',
            ($data->available_qty != 0 || $data->available_qty != '' || $data->available_qty != null) ? $data->available_qty : '0',
            formatCurrencyAmount($data->purchase_price, $currency_symbol),
            formatCurrencyAmount($data->retail_price, $currency_symbol),
            formatCurrencyAmount($this->getInValue($data), $currency_symbol),
            formatCurrencyAmount($this->getRetailValue($data), $currency_symbol),
            $this->getProfitValue($data, $currency_symbol),
        ];
    }

    private function getInValue($data) 
    {
        $inValue = 0;
        if ($data->available_qty != 0 ) {
          $inValue = $data->purchase_price * $data->available_qty;
        }
        return $inValue;
    }

    private function getRetailValue($data) 
    {
        $retailValue = 0;
        if ($data->available_qty != 0 ) {
          $retailValue = $data->retail_price * $data->available_qty;
        }
        return $retailValue;
    }

    private function getProfitValue($data, $currency_symbol) 
    {
        $inValue = 0;
        $retailValue = 0;
        $profitValue = 0;
        $profitMargin = 0;
        if ($data->available_qty != 0) {
            $retailValue = $data->retail_price * $data->available_qty;
            $inValue = $data->purchase_price * $data->available_qty;
        }

        $profitValue = ($retailValue - $inValue);
        if ($inValue != 0) {
            $profitMargin = ($profitValue*100/$inValue);
        }
        return formatCurrencyAmount($profitValue, $currency_symbol) . ' (' . number_format($profitMargin,2,'.',',') . '%)';
    }
}
