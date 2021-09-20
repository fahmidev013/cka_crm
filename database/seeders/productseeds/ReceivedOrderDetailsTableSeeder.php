<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class ReceivedOrderDetailsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('received_order_details')->delete();
        
        \DB::table('received_order_details')->insert(array (
            0 => 
            array (
                'id' => 1,
                'purchase_order_id' => 1,
                'purchase_order_detail_id' => 1,
                'received_order_id' => 1,
                'item_id' => 2,
                'item_name' => 'iPhone 11 pro',
                'unit_price' => 999.0,
                'quantity' => 3.0,
            ),
            1 => 
            array (
                'id' => 2,
                'purchase_order_id' => 1,
                'purchase_order_detail_id' => 2,
                'received_order_id' => 1,
                'item_id' => 5,
                'item_name' => 'Huawei P30 Pro',
                'unit_price' => 546.0,
                'quantity' => 2.0,
            ),
            2 => 
            array (
                'id' => 3,
                'purchase_order_id' => 1,
                'purchase_order_detail_id' => 3,
                'received_order_id' => 1,
                'item_id' => 12,
                'item_name' => 'Oats',
                'unit_price' => 4.0,
                'quantity' => 6.0,
            ),
            3 => 
            array (
                'id' => 4,
                'purchase_order_id' => 3,
                'purchase_order_detail_id' => 6,
                'received_order_id' => 2,
                'item_id' => 8,
                'item_name' => 'Havit Alarm Clock Wireless Speaker',
                'unit_price' => 46.2,
                'quantity' => 10.0,
            ),
            4 => 
            array (
                'id' => 5,
                'purchase_order_id' => 3,
                'purchase_order_detail_id' => 7,
                'received_order_id' => 2,
                'item_id' => 4,
                'item_name' => 'Samsung Galaxy S10',
                'unit_price' => 1234.2,
                'quantity' => 5.0,
            ),
            5 => 
            array (
                'id' => 6,
                'purchase_order_id' => 9,
                'purchase_order_detail_id' => 18,
                'received_order_id' => 3,
                'item_id' => 3,
                'item_name' => 'iPhone 11',
                'unit_price' => 695.0,
                'quantity' => 3.0,
            ),
            6 => 
            array (
                'id' => 7,
                'purchase_order_id' => 9,
                'purchase_order_detail_id' => 19,
                'received_order_id' => 3,
                'item_id' => 2,
                'item_name' => 'iPhone 11 pro',
                'unit_price' => 999.0,
                'quantity' => 2.0,
            ),
            7 => 
            array (
                'id' => 8,
                'purchase_order_id' => 9,
                'purchase_order_detail_id' => 20,
                'received_order_id' => 3,
                'item_id' => 4,
                'item_name' => 'Samsung Galaxy S10',
                'unit_price' => 748.0,
                'quantity' => 1.0,
            ),
            8 => 
            array (
                'id' => 9,
                'purchase_order_id' => 12,
                'purchase_order_detail_id' => 27,
                'received_order_id' => 4,
                'item_id' => 9,
                'item_name' => 'Blender',
                'unit_price' => 1500.5,
                'quantity' => 4.0,
            ),
            9 => 
            array (
                'id' => 10,
                'purchase_order_id' => 12,
                'purchase_order_detail_id' => 28,
                'received_order_id' => 4,
                'item_id' => 13,
                'item_name' => 'Corn Flakes',
                'unit_price' => 2500.04,
                'quantity' => 3.0,
            ),
            10 => 
            array (
                'id' => 11,
                'purchase_order_id' => 12,
                'purchase_order_detail_id' => 29,
                'received_order_id' => 4,
                'item_id' => 12,
                'item_name' => 'Oats',
                'unit_price' => 1500.04,
                'quantity' => 2.0,
            ),
            11 => 
            array (
                'id' => 12,
                'purchase_order_id' => 13,
                'purchase_order_detail_id' => 30,
                'received_order_id' => 5,
                'item_id' => 8,
                'item_name' => 'Havit Alarm Clock Wireless Speaker',
                'unit_price' => 46.2,
                'quantity' => 1.0,
            ),
            12 => 
            array (
                'id' => 13,
                'purchase_order_id' => 13,
                'purchase_order_detail_id' => 31,
                'received_order_id' => 5,
                'item_id' => 4,
                'item_name' => 'Samsung Galaxy S10',
                'unit_price' => 1234.2,
                'quantity' => 0.0,
            ),
            13 => 
            array (
                'id' => 14,
                'purchase_order_id' => 13,
                'purchase_order_detail_id' => 32,
                'received_order_id' => 5,
                'item_id' => 5,
                'item_name' => 'Huawei P30 Pro',
                'unit_price' => 900.9,
                'quantity' => 3.0,
            ),
            14 => 
            array (
                'id' => 15,
                'purchase_order_id' => 13,
                'purchase_order_detail_id' => 30,
                'received_order_id' => 6,
                'item_id' => 8,
                'item_name' => 'Havit Alarm Clock Wireless Speaker',
                'unit_price' => 46.2,
                'quantity' => 0.0,
            ),
            15 => 
            array (
                'id' => 16,
                'purchase_order_id' => 13,
                'purchase_order_detail_id' => 31,
                'received_order_id' => 6,
                'item_id' => 4,
                'item_name' => 'Samsung Galaxy S10',
                'unit_price' => 1234.2,
                'quantity' => 0.0,
            ),
            16 => 
            array (
                'id' => 17,
                'purchase_order_id' => 13,
                'purchase_order_detail_id' => 32,
                'received_order_id' => 6,
                'item_id' => 5,
                'item_name' => 'Huawei P30 Pro',
                'unit_price' => 900.9,
                'quantity' => 4.0,
            ),
            17 => 
            array (
                'id' => 18,
                'purchase_order_id' => 14,
                'purchase_order_detail_id' => 33,
                'received_order_id' => 7,
                'item_id' => 9,
                'item_name' => 'Blender',
                'unit_price' => 1500.5,
                'quantity' => 0.0,
            ),
            18 => 
            array (
                'id' => 19,
                'purchase_order_id' => 14,
                'purchase_order_detail_id' => 34,
                'received_order_id' => 7,
                'item_id' => 13,
                'item_name' => 'Corn Flakes',
                'unit_price' => 2500.04,
                'quantity' => 0.0,
            ),
            19 => 
            array (
                'id' => 20,
                'purchase_order_id' => 14,
                'purchase_order_detail_id' => 35,
                'received_order_id' => 7,
                'item_id' => 12,
                'item_name' => 'Oats',
                'unit_price' => 1500.04,
                'quantity' => 0.0,
            ),
            20 => 
            array (
                'id' => 21,
                'purchase_order_id' => 14,
                'purchase_order_detail_id' => 36,
                'received_order_id' => 7,
                'item_id' => 8,
                'item_name' => 'Havit Alarm Clock Wireless Speaker',
                'unit_price' => 9.28,
                'quantity' => 16.0,
            ),
            21 => 
            array (
                'id' => 22,
                'purchase_order_id' => 8,
                'purchase_order_detail_id' => 17,
                'received_order_id' => 8,
                'item_id' => 5,
                'item_name' => 'Huawei P30 Pro',
                'unit_price' => 546.0,
                'quantity' => 2.0,
            ),
            22 => 
            array (
                'id' => 23,
                'purchase_order_id' => 8,
                'purchase_order_detail_id' => 17,
                'received_order_id' => 9,
                'item_id' => 5,
                'item_name' => 'Huawei P30 Pro',
                'unit_price' => 546.0,
                'quantity' => 1.0,
            ),
            23 => 
            array (
                'id' => 24,
                'purchase_order_id' => 6,
                'purchase_order_detail_id' => 13,
                'received_order_id' => 10,
                'item_id' => 4,
                'item_name' => 'Samsung Galaxy S10',
                'unit_price' => 1047.2,
                'quantity' => 3.0,
            ),
            24 => 
            array (
                'id' => 25,
                'purchase_order_id' => 6,
                'purchase_order_detail_id' => 14,
                'received_order_id' => 10,
                'item_id' => 6,
                'item_name' => 'Apple Watch Series 4',
                'unit_price' => 910.0,
                'quantity' => 2.0,
            ),
            25 => 
            array (
                'id' => 26,
                'purchase_order_id' => 6,
                'purchase_order_detail_id' => 13,
                'received_order_id' => 11,
                'item_id' => 4,
                'item_name' => 'Samsung Galaxy S10',
                'unit_price' => 1047.2,
                'quantity' => 4.0,
            ),
            26 => 
            array (
                'id' => 27,
                'purchase_order_id' => 6,
                'purchase_order_detail_id' => 14,
                'received_order_id' => 11,
                'item_id' => 6,
                'item_name' => 'Apple Watch Series 4',
                'unit_price' => 910.0,
                'quantity' => 7.0,
            ),
            27 => 
            array (
                'id' => 28,
                'purchase_order_id' => 2,
                'purchase_order_detail_id' => 4,
                'received_order_id' => 12,
                'item_id' => 9,
                'item_name' => 'Blender',
                'unit_price' => 70.0,
                'quantity' => 2.0,
            ),
            28 => 
            array (
                'id' => 29,
                'purchase_order_id' => 2,
                'purchase_order_detail_id' => 5,
                'received_order_id' => 12,
                'item_id' => 6,
                'item_name' => 'Apple Watch Series 4',
                'unit_price' => 910.0,
                'quantity' => 3.0,
            ),
        ));
        
        
    }
}