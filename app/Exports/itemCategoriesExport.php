<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class itemCategoriesExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $type = $_GET['type'];

        if ($type == 'csv' ) {
            $categorydata = (new Category)->getAllCategoryCSV();

            return $categorydata;
        }

        if ( $type == 'sample' ) {
           $categorydata = (new Category)->getAllCategoryCSV()->get();
           return $categorydata;
        }

    }

    public function headings(): array
    {
        return [
            'Category Name',
            'Unit'
        ];
    }

    public function map($category): array
    {
        return [
            $category->description,
            $category->dflt_units
        ];
    }
}
