<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Role;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Http\Controllers\DateTime;
use App\Models\Item;
use App\Models\Preference;
use App\Models\Currency;

class ItemListExport implements FromCollection, WithHeadings, WithMapping 
{
    /**
     * [Here we need to fetch data from data source]
     * @return [Database Object] [Here we are fetching data from User table and also role table through Eloquent Relationship]
     */
    public function collection()
    {
        $itemdata = (new Item)->getAllItemCsv();
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $currency   = Currency::getDefault($preference);
        session()->put('currencySymbolItem', $currency->name);
        return collect($itemdata);
    }

    /**
     * [Here we are putting Headinngs of The CSV]
     * @return [array] [Exel Headings]
     */
    public function headings(): array
    {
        return[
            __('Item Name'),
            __('Category'),
            __('Purchase'),
            __('Retail'),
        ];
    }
    /**
     * [By adding WithMapping you map the data that needs to be added as row. This way you have control over the actual source for each column. In case of using the Eloquent query builder]
     * @param  [object] $userList [It has users table info and roles table info]
     * @return [array]            [comma separated value will be produced]
     */
    public function map($itemdata): array
    {
        $purcashe_price = ((string) formatCurrencyAmount($itemdata->purcashe_price))."";
        $retail_price= (string) formatCurrencyAmount($itemdata->retail_price);
        return[
            $itemdata->name,
            $itemdata->category,
            ((trim($itemdata->purcashe_price)==null || trim($itemdata->purcashe_price)==0) ? formatCurrencyAmount(0) : $purcashe_price) . ' ' . session()->get('currencySymbolItem'),
            ((trim($itemdata->retail_price)==null || trim($itemdata->retail_price)==0) ? formatCurrencyAmount(0) : $retail_price) . ' ' . session()->get('currencySymbolItem'),
        ];
    }
}
