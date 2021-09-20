<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class SaleTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sale_types')->delete();
        
        \DB::table('sale_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'sale_type' => 'Retail',
                'is_tax_included' => 1,
                'is_default' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'sale_type' => 'Wholesale',
                'is_tax_included' => 0,
                'is_default' => 0,
            ),
        ));
        
        
    }
}