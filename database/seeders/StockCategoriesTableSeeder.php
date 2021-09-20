<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StockCategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('stock_categories')->delete();
        
        \DB::table('stock_categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Electronics',
                'item_unit_id' => 1,
                'is_active' => 1,
                'created_at' => '2020-04-07 09:15:41',
                'updated_at' => '2020-04-07 09:15:41',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Home & Lifestyle',
                'item_unit_id' => 1,
                'is_active' => 1,
                'created_at' => '2020-04-07 09:16:29',
                'updated_at' => '2020-04-07 09:16:29',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Grocery',
                'item_unit_id' => 2,
                'is_active' => 1,
                'created_at' => '2020-04-07 09:17:51',
                'updated_at' => '2020-04-07 09:17:51',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Cloths',
                'item_unit_id' => 1,
                'is_active' => 1,
                'created_at' => '2020-04-07 09:17:51',
                'updated_at' => '2020-04-07 09:17:51',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Food & Beverage',
                'item_unit_id' => 1,
                'is_active' => 1,
                'created_at' => '2020-04-07 09:17:51',
                'updated_at' => '2020-04-07 09:17:51',
            )
        ));
        
        
    }
}