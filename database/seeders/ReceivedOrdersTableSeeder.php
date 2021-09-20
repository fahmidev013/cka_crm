<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ReceivedOrdersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('received_orders')->delete();
        
        \DB::table('received_orders')->insert(array (
            0 => 
            array (
                'id' => 1,
                'purchase_order_id' => 1,
                'user_id' => 1,
                'supplier_id' => 5,
                'reference' => 'PO-0001',
                'location_id' => 1,
                'receive_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'order_receive_no' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'purchase_order_id' => 3,
                'user_id' => 1,
                'supplier_id' => 3,
                'reference' => 'PO-0003',
                'location_id' => 2,
                'receive_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'order_receive_no' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'purchase_order_id' => 9,
                'user_id' => 1,
                'supplier_id' => 5,
                'reference' => 'PO-0009',
                'location_id' => 3,
                'receive_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'order_receive_no' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'purchase_order_id' => 12,
                'user_id' => 1,
                'supplier_id' => 4,
                'reference' => 'PO-0010',
                'location_id' => 3,
                'receive_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'order_receive_no' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'purchase_order_id' => 13,
                'user_id' => 1,
                'supplier_id' => 3,
                'reference' => 'PO-0011',
                'location_id' => 2,
                'receive_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'order_receive_no' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'purchase_order_id' => 13,
                'user_id' => 1,
                'supplier_id' => 3,
                'reference' => 'PO-0011',
                'location_id' => 2,
                'receive_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'order_receive_no' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'purchase_order_id' => 14,
                'user_id' => 1,
                'supplier_id' => 4,
                'reference' => 'PO-0012',
                'location_id' => 3,
                'receive_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'order_receive_no' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'purchase_order_id' => 8,
                'user_id' => 1,
                'supplier_id' => 5,
                'reference' => 'PO-0008',
                'location_id' => 2,
                'receive_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'order_receive_no' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'purchase_order_id' => 8,
                'user_id' => 1,
                'supplier_id' => 5,
                'reference' => 'PO-0008',
                'location_id' => 2,
                'receive_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'order_receive_no' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'purchase_order_id' => 6,
                'user_id' => 1,
                'supplier_id' => 2,
                'reference' => 'PO-0006',
                'location_id' => 1,
                'receive_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'order_receive_no' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'purchase_order_id' => 6,
                'user_id' => 1,
                'supplier_id' => 2,
                'reference' => 'PO-0006',
                'location_id' => 1,
                'receive_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'order_receive_no' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'purchase_order_id' => 2,
                'user_id' => 1,
                'supplier_id' => 1,
                'reference' => 'PO-0002',
                'location_id' => 3,
                'receive_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'order_receive_no' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}