<?php

namespace App\Exports;



use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class allSupplierExport implements WithHeadings, WithMapping, FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $url_components = parse_url(url()->previous()); 
        $url_components = !empty($url_components['query']) ? explode('=', $url_components['query']) : null ;
        $data = Supplier::select();

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
            'Address',
            'Currency',
            'Country',
            'Status',
            'Created At',
        ];
    }

    public function map($supplier): array
    {           
        return [
            $supplier->name,
            $supplier->email,
            $supplier->contact,
            $supplier->street,
            isset($supplier->currency->name) ? $supplier->currency->name : '',
            isset($supplier->country->name) ? $supplier->country->name : '',
            $supplier->is_active == 1 ? "Active" : "Inactive", 
            !empty($supplier->created_at) ? timeZoneformatDate($supplier->created_at).' '.timeZonegetTime($supplier->created_at) : ''
        ];
    }

}
