<?php

namespace App\Exports;

use App\Models\Department;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class departmentsExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
            $categorydata = (new Department)->getdepartmentsCSV();
            return $categorydata;
    }

    public function headings(): array
    {
        return [
            'Department Name'
        ];
    }

    public function map($department): array
    {
        return [
            $department->name
        ];
    }
}
