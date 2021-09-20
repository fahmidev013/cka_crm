<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SalePricesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sale_prices')->delete();
        
        \DB::table('sale_prices')->insert(array (
            0 => 
            array (
                'id' => 3,
                'item_id' => 2,
                'sale_type_id' => 1,
                'currency_id' => 1,
                'price' => 999.0,
            ),
            1 => 
            array (
                'id' => 4,
                'item_id' => 2,
                'sale_type_id' => 2,
                'currency_id' => 1,
                'price' => 0.0,
            ),
            2 => 
            array (
                'id' => 5,
                'item_id' => 3,
                'sale_type_id' => 1,
                'currency_id' => 1,
                'price' => 705.0,
            ),
            3 => 
            array (
                'id' => 6,
                'item_id' => 3,
                'sale_type_id' => 2,
                'currency_id' => 1,
                'price' => 0.0,
            ),
            4 => 
            array (
                'id' => 7,
                'item_id' => 4,
                'sale_type_id' => 1,
                'currency_id' => 1,
                'price' => 757.0,
            ),
            5 => 
            array (
                'id' => 8,
                'item_id' => 4,
                'sale_type_id' => 2,
                'currency_id' => 1,
                'price' => 0.0,
            ),
            6 => 
            array (
                'id' => 9,
                'item_id' => 5,
                'sale_type_id' => 1,
                'currency_id' => 1,
                'price' => 550.0,
            ),
            7 => 
            array (
                'id' => 10,
                'item_id' => 5,
                'sale_type_id' => 2,
                'currency_id' => 1,
                'price' => 0.0,
            ),
            8 => 
            array (
                'id' => 11,
                'item_id' => 6,
                'sale_type_id' => 1,
                'currency_id' => 1,
                'price' => 700.0,
            ),
            9 => 
            array (
                'id' => 12,
                'item_id' => 6,
                'sale_type_id' => 2,
                'currency_id' => 1,
                'price' => 0.0,
            ),
            10 => 
            array (
                'id' => 13,
                'item_id' => 7,
                'sale_type_id' => 1,
                'currency_id' => 1,
                'price' => 55.0,
            ),
            11 => 
            array (
                'id' => 14,
                'item_id' => 7,
                'sale_type_id' => 2,
                'currency_id' => 1,
                'price' => 0.0,
            ),
            12 => 
            array (
                'id' => 15,
                'item_id' => 8,
                'sale_type_id' => 1,
                'currency_id' => 1,
                'price' => 30.0,
            ),
            13 => 
            array (
                'id' => 16,
                'item_id' => 8,
                'sale_type_id' => 2,
                'currency_id' => 1,
                'price' => 0.0,
            ),
            14 => 
            array (
                'id' => 17,
                'item_id' => 9,
                'sale_type_id' => 1,
                'currency_id' => 1,
                'price' => 52.0,
            ),
            15 => 
            array (
                'id' => 18,
                'item_id' => 9,
                'sale_type_id' => 2,
                'currency_id' => 1,
                'price' => 0.0,
            ),
            16 => 
            array (
                'id' => 19,
                'item_id' => 10,
                'sale_type_id' => 1,
                'currency_id' => 1,
                'price' => 0.0,
            ),
            17 => 
            array (
                'id' => 20,
                'item_id' => 10,
                'sale_type_id' => 2,
                'currency_id' => 1,
                'price' => 0.0,
            ),
            18 => 
            array (
                'id' => 21,
                'item_id' => 11,
                'sale_type_id' => 1,
                'currency_id' => 1,
                'price' => 0.0,
            ),
            19 => 
            array (
                'id' => 22,
                'item_id' => 11,
                'sale_type_id' => 2,
                'currency_id' => 1,
                'price' => 0.0,
            ),
            20 => 
            array (
                'id' => 23,
                'item_id' => 12,
                'sale_type_id' => 1,
                'currency_id' => 1,
                'price' => 5.0,
            ),
            21 => 
            array (
                'id' => 24,
                'item_id' => 12,
                'sale_type_id' => 2,
                'currency_id' => 1,
                'price' => 0.0,
            ),
            22 => 
            array (
                'id' => 25,
                'item_id' => 13,
                'sale_type_id' => 1,
                'currency_id' => 1,
                'price' => 5.0,
            ),
            23 => 
            array (
                'id' => 26,
                'item_id' => 13,
                'sale_type_id' => 2,
                'currency_id' => 1,
                'price' => 0.0,
            ),
            24 => 
            array (
                'id' => 27,
                'item_id' => 14,
                'sale_type_id' => 1,
                'currency_id' => 1,
                'price' => 1700.0,
            ),
            25 => 
            array (
                'id' => 28,
                'item_id' => 14,
                'sale_type_id' => 2,
                'currency_id' => 1,
                'price' => 0.0,
            ),
            26 => 
            array (
                'id' => 29,
                'item_id' => 15,
                'sale_type_id' => 1,
                'currency_id' => 1,
                'price' => 1090.0,
            ),
            27 => 
            array (
                'id' => 30,
                'item_id' => 15,
                'sale_type_id' => 2,
                'currency_id' => 1,
                'price' => 0.0,
            ),
            28 => 
            array (
                'id' => 31,
                'item_id' => 16,
                'sale_type_id' => 1,
                'currency_id' => 1,
                'price' => 190.0,
            ),
            29 => 
            array (
                'id' => 32,
                'item_id' => 17,
                'sale_type_id' => 1,
                'currency_id' => 1,
                'price' => 250.0,
            ),
            30 => 
            array (
                'id' => 33,
                'item_id' => 18,
                'sale_type_id' => 1,
                'currency_id' => 1,
                'price' => 595.0,
            ),
            31 => 
            array (
                'id' => 34,
                'item_id' => 19,
                'sale_type_id' => 1,
                'currency_id' => 1,
                'price' => 299.0,
            ),
            32 => 
            array (
                'id' => 35,
                'item_id' => 20,
                'sale_type_id' => 1,
                'currency_id' => 1,
                'price' => 20.0,
            ),
            33 => 
            array (
                'id' => 36,
                'item_id' => 21,
                'sale_type_id' => 1,
                'currency_id' => 1,
                'price' => 1.0,
            ),
            34 => 
            array (
                'id' => 37,
                'item_id' => 22,
                'sale_type_id' => 1,
                'currency_id' => 1,
                'price' => 120.0,
            ),
            35 => 
            array (
                'id' => 38,
                'item_id' => 23,
                'sale_type_id' => 1,
                'currency_id' => 1,
                'price' => 85.0,
            ),
            36 => 
            array (
                'id' => 39,
                'item_id' => 24,
                'sale_type_id' => 1,
                'currency_id' => 1,
                'price' => 15.0,
            ),
            37 => 
            array (
                'id' => 40,
                'item_id' => 25,
                'sale_type_id' => 1,
                'currency_id' => 1,
                'price' => 8.0,
            ),
            38 => 
            array (
                'id' => 41,
                'item_id' => 26,
                'sale_type_id' => 1,
                'currency_id' => 1,
                'price' => 5.0,
            )
        ));
        
        
    }
}