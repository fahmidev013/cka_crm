<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class PurchaseTaxesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('purchase_taxes')->delete();
        
        \DB::table('purchase_taxes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'purchase_order_detail_id' => 1,
                'tax_type_id' => 1,
                'tax_amount' => 44.955,
            ),
            1 => 
            array (
                'id' => 2,
                'purchase_order_detail_id' => 2,
                'tax_type_id' => 2,
                'tax_amount' => 25.116,
            ),
            2 => 
            array (
                'id' => 3,
                'purchase_order_detail_id' => 3,
                'tax_type_id' => 1,
                'tax_amount' => 0.36,
            ),
            3 => 
            array (
                'id' => 4,
                'purchase_order_detail_id' => 4,
                'tax_type_id' => 1,
                'tax_amount' => 2.1,
            ),
            4 => 
            array (
                'id' => 5,
                'purchase_order_detail_id' => 4,
                'tax_type_id' => 2,
                'tax_amount' => 3.22,
            ),
            5 => 
            array (
                'id' => 6,
                'purchase_order_detail_id' => 5,
                'tax_type_id' => 2,
                'tax_amount' => 83.72,
            ),
            6 => 
            array (
                'id' => 7,
                'purchase_order_detail_id' => 6,
                'tax_type_id' => 1,
                'tax_amount' => 6.93,
            ),
            7 => 
            array (
                'id' => 8,
                'purchase_order_detail_id' => 7,
                'tax_type_id' => 1,
                'tax_amount' => 92.565,
            ),
            8 => 
            array (
                'id' => 9,
                'purchase_order_detail_id' => 7,
                'tax_type_id' => 2,
                'tax_amount' => 141.933,
            ),
            9 => 
            array (
                'id' => 10,
                'purchase_order_detail_id' => 8,
                'tax_type_id' => 1,
                'tax_amount' => 0.39,
            ),
            10 => 
            array (
                'id' => 11,
                'purchase_order_detail_id' => 8,
                'tax_type_id' => 2,
                'tax_amount' => 0.598,
            ),
            11 => 
            array (
                'id' => 12,
                'purchase_order_detail_id' => 9,
                'tax_type_id' => 1,
                'tax_amount' => 0.5519999999999999,
            ),
            12 => 
            array (
                'id' => 13,
                'purchase_order_detail_id' => 10,
                'tax_type_id' => 1,
                'tax_amount' => 8.34,
            ),
            13 => 
            array (
                'id' => 14,
                'purchase_order_detail_id' => 11,
                'tax_type_id' => 1,
                'tax_amount' => 15.6,
            ),
            14 => 
            array (
                'id' => 15,
                'purchase_order_detail_id' => 11,
                'tax_type_id' => 2,
                'tax_amount' => 23.92,
            ),
            15 => 
            array (
                'id' => 16,
                'purchase_order_detail_id' => 12,
                'tax_type_id' => 1,
                'tax_amount' => 1.0079999999999998,
            ),
            16 => 
            array (
                'id' => 17,
                'purchase_order_detail_id' => 13,
                'tax_type_id' => 1,
                'tax_amount' => 109.956,
            ),
            17 => 
            array (
                'id' => 18,
                'purchase_order_detail_id' => 14,
                'tax_type_id' => 2,
                'tax_amount' => 188.37,
            ),
            18 => 
            array (
                'id' => 19,
                'purchase_order_detail_id' => 15,
                'tax_type_id' => 1,
                'tax_amount' => 109.956,
            ),
            19 => 
            array (
                'id' => 20,
                'purchase_order_detail_id' => 16,
                'tax_type_id' => 2,
                'tax_amount' => 188.37,
            ),
            20 => 
            array (
                'id' => 21,
                'purchase_order_detail_id' => 17,
                'tax_type_id' => 2,
                'tax_amount' => 62.78999999999999,
            ),
            21 => 
            array (
                'id' => 22,
                'purchase_order_detail_id' => 18,
                'tax_type_id' => 1,
                'tax_amount' => 30.812807881773097,
            ),
            22 => 
            array (
                'id' => 23,
                'purchase_order_detail_id' => 19,
                'tax_type_id' => 1,
                'tax_amount' => 29.527093596058876,
            ),
            23 => 
            array (
                'id' => 24,
                'purchase_order_detail_id' => 20,
                'tax_type_id' => 1,
                'tax_amount' => 11.054187192118206,
            ),
            24 => 
            array (
                'id' => 25,
                'purchase_order_detail_id' => 20,
                'tax_type_id' => 2,
                'tax_amount' => 16.817204301075208,
            ),
            25 => 
            array (
                'id' => 34,
                'purchase_order_detail_id' => 27,
                'tax_type_id' => 2,
                'tax_amount' => 138.046,
            ),
            26 => 
            array (
                'id' => 35,
                'purchase_order_detail_id' => 28,
                'tax_type_id' => 1,
                'tax_amount' => 112.5018,
            ),
            27 => 
            array (
                'id' => 36,
                'purchase_order_detail_id' => 29,
                'tax_type_id' => 1,
                'tax_amount' => 45.0012,
            ),
            28 => 
            array (
                'id' => 37,
                'purchase_order_detail_id' => 29,
                'tax_type_id' => 2,
                'tax_amount' => 69.00183999999999,
            ),
            29 => 
            array (
                'id' => 45,
                'purchase_order_detail_id' => 30,
                'tax_type_id' => 1,
                'tax_amount' => 7.623000000000001,
            ),
            30 => 
            array (
                'id' => 46,
                'purchase_order_detail_id' => 31,
                'tax_type_id' => 1,
                'tax_amount' => 92.565,
            ),
            31 => 
            array (
                'id' => 47,
                'purchase_order_detail_id' => 31,
                'tax_type_id' => 2,
                'tax_amount' => 141.933,
            ),
            32 => 
            array (
                'id' => 48,
                'purchase_order_detail_id' => 32,
                'tax_type_id' => 2,
                'tax_amount' => 145.04489999999998,
            ),
            33 => 
            array (
                'id' => 53,
                'purchase_order_detail_id' => 33,
                'tax_type_id' => 2,
                'tax_amount' => 138.046,
            ),
            34 => 
            array (
                'id' => 54,
                'purchase_order_detail_id' => 34,
                'tax_type_id' => 1,
                'tax_amount' => 112.5018,
            ),
            35 => 
            array (
                'id' => 55,
                'purchase_order_detail_id' => 35,
                'tax_type_id' => 1,
                'tax_amount' => 45.0012,
            ),
            36 => 
            array (
                'id' => 56,
                'purchase_order_detail_id' => 35,
                'tax_type_id' => 2,
                'tax_amount' => 69.00183999999999,
            ),
            37 => 
            array (
                'id' => 57,
                'purchase_order_detail_id' => 36,
                'tax_type_id' => 1,
                'tax_amount' => 2.2272,
            ),
        ));
        
        
    }
}