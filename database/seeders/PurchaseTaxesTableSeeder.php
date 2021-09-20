<?php

namespace Database\Seeders;

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
                'tax_amount' => '44.95500000',
            ),
            1 => 
            array (
                'id' => 2,
                'purchase_order_detail_id' => 2,
                'tax_type_id' => 2,
                'tax_amount' => '25.11600000',
            ),
            2 => 
            array (
                'id' => 3,
                'purchase_order_detail_id' => 3,
                'tax_type_id' => 1,
                'tax_amount' => '0.36000000',
            ),
            3 => 
            array (
                'id' => 4,
                'purchase_order_detail_id' => 4,
                'tax_type_id' => 1,
                'tax_amount' => '2.10000000',
            ),
            4 => 
            array (
                'id' => 5,
                'purchase_order_detail_id' => 4,
                'tax_type_id' => 2,
                'tax_amount' => '3.22000000',
            ),
            5 => 
            array (
                'id' => 6,
                'purchase_order_detail_id' => 5,
                'tax_type_id' => 2,
                'tax_amount' => '83.72000000',
            ),
            6 => 
            array (
                'id' => 7,
                'purchase_order_detail_id' => 6,
                'tax_type_id' => 1,
                'tax_amount' => '6.93000000',
            ),
            7 => 
            array (
                'id' => 8,
                'purchase_order_detail_id' => 7,
                'tax_type_id' => 1,
                'tax_amount' => '92.56500000',
            ),
            8 => 
            array (
                'id' => 9,
                'purchase_order_detail_id' => 7,
                'tax_type_id' => 2,
                'tax_amount' => '141.93300000',
            ),
            9 => 
            array (
                'id' => 10,
                'purchase_order_detail_id' => 8,
                'tax_type_id' => 1,
                'tax_amount' => '0.39000000',
            ),
            10 => 
            array (
                'id' => 11,
                'purchase_order_detail_id' => 8,
                'tax_type_id' => 2,
                'tax_amount' => '0.59800000',
            ),
            11 => 
            array (
                'id' => 12,
                'purchase_order_detail_id' => 9,
                'tax_type_id' => 1,
                'tax_amount' => '0.55200000',
            ),
            12 => 
            array (
                'id' => 13,
                'purchase_order_detail_id' => 10,
                'tax_type_id' => 1,
                'tax_amount' => '8.34000000',
            ),
            13 => 
            array (
                'id' => 14,
                'purchase_order_detail_id' => 11,
                'tax_type_id' => 1,
                'tax_amount' => '15.60000000',
            ),
            14 => 
            array (
                'id' => 15,
                'purchase_order_detail_id' => 11,
                'tax_type_id' => 2,
                'tax_amount' => '23.92000000',
            ),
            15 => 
            array (
                'id' => 16,
                'purchase_order_detail_id' => 12,
                'tax_type_id' => 1,
                'tax_amount' => '1.00800000',
            ),
            16 => 
            array (
                'id' => 17,
                'purchase_order_detail_id' => 13,
                'tax_type_id' => 1,
                'tax_amount' => '109.95600000',
            ),
            17 => 
            array (
                'id' => 18,
                'purchase_order_detail_id' => 14,
                'tax_type_id' => 2,
                'tax_amount' => '188.37000000',
            ),
            18 => 
            array (
                'id' => 19,
                'purchase_order_detail_id' => 15,
                'tax_type_id' => 1,
                'tax_amount' => '109.95600000',
            ),
            19 => 
            array (
                'id' => 20,
                'purchase_order_detail_id' => 16,
                'tax_type_id' => 2,
                'tax_amount' => '188.37000000',
            ),
            20 => 
            array (
                'id' => 21,
                'purchase_order_detail_id' => 17,
                'tax_type_id' => 2,
                'tax_amount' => '62.79000000',
            ),
            21 => 
            array (
                'id' => 22,
                'purchase_order_detail_id' => 18,
                'tax_type_id' => 1,
                'tax_amount' => '30.81280788',
            ),
            22 => 
            array (
                'id' => 23,
                'purchase_order_detail_id' => 19,
                'tax_type_id' => 1,
                'tax_amount' => '29.52709360',
            ),
            23 => 
            array (
                'id' => 24,
                'purchase_order_detail_id' => 20,
                'tax_type_id' => 1,
                'tax_amount' => '11.05418719',
            ),
            24 => 
            array (
                'id' => 25,
                'purchase_order_detail_id' => 20,
                'tax_type_id' => 2,
                'tax_amount' => '16.81720430',
            ),
            25 => 
            array (
                'id' => 34,
                'purchase_order_detail_id' => 27,
                'tax_type_id' => 2,
                'tax_amount' => '138.04600000',
            ),
            26 => 
            array (
                'id' => 35,
                'purchase_order_detail_id' => 28,
                'tax_type_id' => 1,
                'tax_amount' => '112.50180000',
            ),
            27 => 
            array (
                'id' => 36,
                'purchase_order_detail_id' => 29,
                'tax_type_id' => 1,
                'tax_amount' => '45.00120000',
            ),
            28 => 
            array (
                'id' => 37,
                'purchase_order_detail_id' => 29,
                'tax_type_id' => 2,
                'tax_amount' => '69.00184000',
            ),
            29 => 
            array (
                'id' => 45,
                'purchase_order_detail_id' => 30,
                'tax_type_id' => 1,
                'tax_amount' => '7.62300000',
            ),
            30 => 
            array (
                'id' => 46,
                'purchase_order_detail_id' => 31,
                'tax_type_id' => 1,
                'tax_amount' => '92.56500000',
            ),
            31 => 
            array (
                'id' => 47,
                'purchase_order_detail_id' => 31,
                'tax_type_id' => 2,
                'tax_amount' => '141.93300000',
            ),
            32 => 
            array (
                'id' => 48,
                'purchase_order_detail_id' => 32,
                'tax_type_id' => 2,
                'tax_amount' => '145.04490000',
            ),
            33 => 
            array (
                'id' => 53,
                'purchase_order_detail_id' => 33,
                'tax_type_id' => 2,
                'tax_amount' => '138.04600000',
            ),
            34 => 
            array (
                'id' => 54,
                'purchase_order_detail_id' => 34,
                'tax_type_id' => 1,
                'tax_amount' => '112.50180000',
            ),
            35 => 
            array (
                'id' => 55,
                'purchase_order_detail_id' => 35,
                'tax_type_id' => 1,
                'tax_amount' => '45.00120000',
            ),
            36 => 
            array (
                'id' => 56,
                'purchase_order_detail_id' => 35,
                'tax_type_id' => 2,
                'tax_amount' => '69.00184000',
            ),
            37 => 
            array (
                'id' => 57,
                'purchase_order_detail_id' => 36,
                'tax_type_id' => 1,
                'tax_amount' => '2.22720000',
            ),
        ));
        
        
    }
}