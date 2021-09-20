<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ItemCustomVariantsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('item_custom_variants')->delete();
        
        \DB::table('item_custom_variants')->insert(array (
            0 => 
            array (
                'id' => 2,
                'item_id' => 4,
                'variant_title' => 'Heart-Rate',
                'variant_value' => 'Yes',
            ),
        ));
        
        
    }
}