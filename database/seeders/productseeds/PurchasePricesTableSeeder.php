<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class PurchasePricesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('purchase_prices')->delete();
        
        \DB::table('purchase_prices')->insert(array (
            0 => 
            array (
                'id' => 2,
                'item_id' => 2,
                'currency_id' => 1,
                'price' => 999.0,
            ),
            1 => 
            array (
                'id' => 3,
                'item_id' => 3,
                'currency_id' => 1,
                'price' => 695.0,
            ),
            2 => 
            array (
                'id' => 4,
                'item_id' => 4,
                'currency_id' => 1,
                'price' => 748.0,
            ),
            3 => 
            array (
                'id' => 5,
                'item_id' => 5,
                'currency_id' => 1,
                'price' => 546.0,
            ),
            4 => 
            array (
                'id' => 6,
                'item_id' => 6,
                'currency_id' => 1,
                'price' => 650.0,
            ),
            5 => 
            array (
                'id' => 7,
                'item_id' => 7,
                'currency_id' => 1,
                'price' => 50.0,
            ),
            6 => 
            array (
                'id' => 8,
                'item_id' => 8,
                'currency_id' => 1,
                'price' => 28.0,
            ),
            7 => 
            array (
                'id' => 9,
                'item_id' => 9,
                'currency_id' => 1,
                'price' => 50.0,
            ),
            8 => 
            array (
                'id' => 10,
                'item_id' => 10,
                'currency_id' => 1,
                'price' => 0.0,
            ),
            9 => 
            array (
                'id' => 11,
                'item_id' => 11,
                'currency_id' => 1,
                'price' => 0.0,
            ),
            10 => 
            array (
                'id' => 12,
                'item_id' => 12,
                'currency_id' => 1,
                'price' => 4.0,
            ),
            11 => 
            array (
                'id' => 13,
                'item_id' => 13,
                'currency_id' => 1,
                'price' => 4.0,
            ),
            12 => 
            array (
                'id' => 14,
                'item_id' => 14,
                'currency_id' => 1,
                'price' => 1680.0,
            ),
            13 => 
            array (
                'id' => 15,
                'item_id' => 15,
                'currency_id' => 1,
                'price' => 1080.0,
            ),
        ));
        
        
    }
}