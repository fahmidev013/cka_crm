<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class ItemUnitsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('item_units')->delete();
        
        \DB::table('item_units')->insert(array (
            0 => 
            array (
                'id' => 1,
                'abbreviation' => 'each',
                'name' => 'Each',
                'created_at' => '2020-04-07 09:14:34',
                'updated_at' => '2020-04-07 09:14:34',
            ),
            1 => 
            array (
                'id' => 2,
                'abbreviation' => 'Kg',
                'name' => 'Kilogram',
                'created_at' => '2020-04-07 09:14:57',
                'updated_at' => '2020-04-07 09:14:57',
            ),
            2 => 
            array (
                'id' => 4,
                'abbreviation' => 'gm',
                'name' => 'Gram',
                'created_at' => '2020-04-08 07:18:21',
                'updated_at' => '2020-04-08 07:18:21',
            ),
        ));
        
        
    }
}