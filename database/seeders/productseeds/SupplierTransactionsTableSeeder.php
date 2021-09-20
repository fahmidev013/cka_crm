<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class SupplierTransactionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('supplier_transactions')->delete();
        
        \DB::table('supplier_transactions')->insert(array (
            0 => 
            array (
                'id' => 21,
                'user_id' => 1,
                'transaction_reference_id' => 0,
                'currency_id' => 3,
                'supplier_id' => 5,
                'purchase_order_id' => 1,
                'payment_method_id' => 1,
                'transaction_date' => '2020-04-13',
                'amount' => 3918.57,
                'exchange_rate' => 1.0,
                'created_at' => '2020-04-13 21:04:53',
            ),
            1 => 
            array (
                'id' => 22,
                'user_id' => 1,
                'transaction_reference_id' => 0,
                'currency_id' => 2,
                'supplier_id' => 2,
                'purchase_order_id' => 7,
                'payment_method_id' => 3,
                'transaction_date' => '2020-04-04',
                'amount' => 9612.88,
                'exchange_rate' => 1.0,
                'created_at' => '2020-04-13 21:06:20',
            ),
            2 => 
            array (
                'id' => 23,
                'user_id' => 1,
                'transaction_reference_id' => 0,
                'currency_id' => 1,
                'supplier_id' => 4,
                'purchase_order_id' => 12,
                'payment_method_id' => NULL,
                'transaction_date' => '2020-04-08',
                'amount' => 16507.68,
                'exchange_rate' => 1.0,
                'created_at' => '2020-04-13 21:08:36',
            ),
            3 => 
            array (
                'id' => 24,
                'user_id' => 1,
                'transaction_reference_id' => 0,
                'currency_id' => 2,
                'supplier_id' => 2,
                'purchase_order_id' => 4,
                'payment_method_id' => NULL,
                'transaction_date' => '2020-03-26',
                'amount' => 81.83,
                'exchange_rate' => 1.0,
                'created_at' => '2020-04-13 21:09:02',
            ),
            4 => 
            array (
                'id' => 25,
                'user_id' => 1,
                'transaction_reference_id' => 0,
                'currency_id' => 1,
                'supplier_id' => 4,
                'purchase_order_id' => 14,
                'payment_method_id' => NULL,
                'transaction_date' => '2020-04-13',
                'amount' => 17042.46,
                'exchange_rate' => 1.53,
                'created_at' => '2020-04-13 21:09:40',
            ),
        ));
        
        
    }
}