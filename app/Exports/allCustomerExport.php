<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class allCustomerExport implements WithHeadings, WithMapping, FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $url_components = parse_url(url()->previous()); 
        $url_components = !empty($url_components['query']) ? explode('=', $url_components['query']) : null ;
        $data = Customer::select();

        if (!empty($url_components) && $url_components[1] == "active") {
            $data->where('is_active', 1);
        }
        if (!empty($url_components) && $url_components[1] == "inactive") {
            $data->where('is_active', 0);
        }
        return $data->get();
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Phone',
            'Currency',
            'Status',
            'Created At',
        ];
    }

    public function map($customer): array
    {   
        return [
            $customer->name,
            $customer->email,
            $customer->phone,
            isset($customer->currency->name) && !empty($customer->currency->name) ? $customer->currency->name : "",
            $customer->is_active == 1 ? "Active" : "Inactive" ,
            !empty($customer->created_at) ? timeZoneformatDate($customer->created_at).' '.timeZonegetTime($customer->created_at) : ''
        ];
    }

}
