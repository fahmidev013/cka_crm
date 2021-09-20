<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

class SupplierExport implements FromQuery, WithHeadings, WithMapping
{
	public function __construct($type)
	{
		$this->type = $type;
	}
	public function query()
	{
		
        if ( $this->type == 'sample' ) {
            $supplierdata[0]['supplier'] = 'John De'; 
            $supplierdata[0]['email'] = 'example@exmample.com';
            $supplierdata[0]['phone'] = '1235678';
            $supplierdata[0]['tax_id'] = '12378';
            $supplierdata[0]['stree'] = 'North America';
            $supplierdata[0]['city'] = 'Washington';
            $supplierdata[0]['state'] = 'North America';
            $supplierdata[0]['zipcode'] = '1235678';
            $supplierdata[0]['country'] = 'US';
        	$data = collect($supplierdata);
        	return $data;
        }


	}

	public function headings(): array
	{
		return [
            'Name',
            'Email',
            'Phone',
            'Tax Id',
            'Street',
            'City',
            'State',
            'Zipcode',
            'Country',
        ];
	}

	public function map($data): array
	{		
		return [
            $data->supplier,
            $data->email,
            $data->phone,
            $data->tax_id,
            $data->street,
            $data->city,
            $data->state,
            $data->zipcode,
            $data->country,
        ];

	}
}
