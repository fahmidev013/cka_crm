<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StockAdjustmentDetailsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('stock_adjustment_details')->delete();
        
        \DB::table('stock_adjustment_details')->insert(array (
            0 => 
            array (
                'id' => 1,
                'stock_adjustment_id' => 1,
                'item_id' => 2,
                'description' => 'iPhone 11 pro',
                'quantity' => 6.0,
                'created_at' => '2020-04-13 21:12:55',
            ),
            1 => 
            array (
                'id' => 2,
                'stock_adjustment_id' => 2,
                'item_id' => 4,
                'description' => 'Samsung Galaxy S10',
                'quantity' => 9.0,
                'created_at' => '2020-04-13 21:12:55',
            ),
            2 => 
            array (
                'id' => 3,
                'stock_adjustment_id' => 2,
                'item_id' => 12,
                'description' => 'Oats',
                'quantity' => 3.0,
                'created_at' => '2020-04-13 21:12:55',
            ),
            3 => 
            array (
                'id' => 4,
                'stock_adjustment_id' => 3,
                'item_id' => 3,
                'description' => 'iPhone 11',
                'quantity' => 6.0,
                'created_at' => '2020-04-13 21:13:35',
            ),
            4 => 
            array (
                'id' => 5,
                'stock_adjustment_id' => 4,
                'item_id' => 8,
                'description' => 'Havit Alarm Clock Wireless Speaker',
                'quantity' => 3.0,
                'created_at' => '2020-04-13 21:13:35',
            ),
            5 => 
            array (
                'id' => 6,
                'stock_adjustment_id' => 2,
                'item_id' => 5,
                'description' => 'Huawei P30 Pro',
                'quantity' => 4.0,
                'created_at' => '2020-04-13 21:13:35',
            ),
            6 => 
            array (
                'id' => 8,
                'stock_adjustment_id' => 2,
                'item_id' => 8,
                'description' => 'Havit Alarm Clock Wireless Speaker',
                'quantity' => 6.0,
                'created_at' => '2020-04-13 21:14:50',
            ),
            7 => 
            array (
                'id' => 9,
                'stock_adjustment_id' => 2,
                'item_id' => 9,
                'description' => 'Blender',
                'quantity' => 9.0,
                'created_at' => '2020-04-13 21:14:50',
            ),
            8 => 
            array (
                'id' => 10,
                'stock_adjustment_id' => 3,
                'item_id' => 4,
                'description' => 'Samsung Galaxy S10',
                'quantity' => 10.0,
                'created_at' => '2020-04-13 21:15:23',
            ),
            9 => 
            array (
                'id' => 11,
                'stock_adjustment_id' => 3,
                'item_id' => 9,
                'description' => 'Blender',
                'quantity' => 6.0,
                'created_at' => '2020-04-13 21:15:23',
            ),
            10 => 
            array (
                'id' => 13,
                'stock_adjustment_id' => 1,
                'item_id' => 13,
                'description' => 'Corn Flakes',
                'quantity' => 4.0,
                'created_at' => '2020-04-13 21:16:06',
            ),
            11 => 
            array (
                'id' => 14,
                'stock_adjustment_id' => 5,
                'item_id' => 4,
                'description' => 'Samsung Galaxy S10',
                'quantity' => 3.0,
                'created_at' => '2020-04-13 21:39:16',
            ),
            12 => 
            array (
                'id' => 15,
                'stock_adjustment_id' => 5,
                'item_id' => 6,
                'description' => 'Apple Watch Series 4',
                'quantity' => 2.0,
                'created_at' => '2020-04-13 21:39:16',
            ),
        ));
        
        
    }
}