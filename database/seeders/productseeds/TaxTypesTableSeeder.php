<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class TaxTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tax_types')->delete();
        
        \DB::table('tax_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Import',
                'tax_rate' => '1.50000000',
                'is_default' => 0,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Export',
                'tax_rate' => '2.30000000',
                'is_default' => 1,
            ),
        ));
        
        
    }
}