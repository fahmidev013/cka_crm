<?php

namespace Database\Seeders;

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
                'price' => '999.00000000',
            ),
            1 => 
            array (
                'id' => 3,
                'item_id' => 3,
                'currency_id' => 1,
                'price' => '695.00000000',
            ),
            2 => 
            array (
                'id' => 4,
                'item_id' => 4,
                'currency_id' => 1,
                'price' => '748.00000000',
            ),
            3 => 
            array (
                'id' => 5,
                'item_id' => 5,
                'currency_id' => 1,
                'price' => '546.00000000',
            ),
            4 => 
            array (
                'id' => 6,
                'item_id' => 6,
                'currency_id' => 1,
                'price' => '650.00000000',
            ),
            5 => 
            array (
                'id' => 7,
                'item_id' => 7,
                'currency_id' => 1,
                'price' => '50.00000000',
            ),
            6 => 
            array (
                'id' => 8,
                'item_id' => 8,
                'currency_id' => 1,
                'price' => '28.00000000',
            ),
            7 => 
            array (
                'id' => 9,
                'item_id' => 9,
                'currency_id' => 1,
                'price' => '50.00000000',
            ),
            8 => 
            array (
                'id' => 10,
                'item_id' => 10,
                'currency_id' => 1,
                'price' => '0.00000000',
            ),
            9 => 
            array (
                'id' => 11,
                'item_id' => 11,
                'currency_id' => 1,
                'price' => '0.00000000',
            ),
            10 => 
            array (
                'id' => 12,
                'item_id' => 12,
                'currency_id' => 1,
                'price' => '4.00000000',
            ),
            11 => 
            array (
                'id' => 13,
                'item_id' => 13,
                'currency_id' => 1,
                'price' => '4.00000000',
            ),
            12 => 
            array (
                'id' => 14,
                'item_id' => 14,
                'currency_id' => 1,
                'price' => '1680.00000000',
            ),
            13 => 
            array (
                'id' => 15,
                'item_id' => 15,
                'currency_id' => 1,
                'price' => '1080.00000000',
            ),
            14 => 
            array (
                'id' => 16,
                'item_id' => 16,
                'currency_id' => 1,
                'price' => '180.00000000',
            ),
            15 => 
            array (
                'id' => 17,
                'item_id' => 17,
                'currency_id' => 1,
                'price' => '240.00000000',
            ),
            16 => 
            array (
                'id' => 18,
                'item_id' => 18,
                'currency_id' => 1,
                'price' => '575.00000000',
            ),
            17 => 
            array (
                'id' => 19,
                'item_id' => 19,
                'currency_id' => 1,
                'price' => '275.00000000',
            ),
            18 => 
            array (
                'id' => 20,
                'item_id' => 20,
                'currency_id' => 1,
                'price' => '17.00000000',
            ),
            19 => 
            array (
                'id' => 21,
                'item_id' => 21,
                'currency_id' => 1,
                'price' => '0.75',
            ),
            20 => 
            array (
                'id' => 22,
                'item_id' => 22,
                'currency_id' => 1,
                'price' => '115.0',
            ),
            21 => 
            array (
                'id' => 23,
                'item_id' => 23,
                'currency_id' => 1,
                'price' => '75.0',
            ),
            22 => 
            array (
                'id' => 24,
                'item_id' => 24,
                'currency_id' => 1,
                'price' => '12.0',
            ),
            23 => 
            array (
                'id' => 25,
                'item_id' => 25,
                'currency_id' => 1,
                'price' => '6.0',
            ),
            24 => 
            array (
                'id' => 26,
                'item_id' => 26,
                'currency_id' => 1,
                'price' => '4.0',
            )
        ));
        
        
    }
}