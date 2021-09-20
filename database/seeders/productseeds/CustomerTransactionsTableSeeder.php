<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class CustomerTransactionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('customer_transactions')->delete();
        
        \DB::table('customer_transactions')->insert(array (
            0 => 
            array (
                'id' => 65,
                'user_id' => 1,
                'account_id' => NULL,
                'payment_method_id' => NULL,
                'customer_id' => 1,
                'sale_order_id' => 9,
                'transaction_reference_id' => 26,
                'currency_id' => 3,
                'transaction_date' => '2020-04-15',
                'amount' => 10.0,
                'exchange_rate' => 1.0,
                'status' => 'Approved',
                'created_at' => '2020-04-15 18:21:08',
            ),
            1 => 
            array (
                'id' => 66,
                'user_id' => 1,
                'account_id' => NULL,
                'payment_method_id' => 1,
                'customer_id' => 1,
                'sale_order_id' => 9,
                'transaction_reference_id' => 28,
                'currency_id' => 3,
                'transaction_date' => '2020-04-15',
                'amount' => 50.0,
                'exchange_rate' => 1.0,
                'status' => 'Approved',
                'created_at' => '2020-04-15 19:05:33',
            ),
            2 => 
            array (
                'id' => 68,
                'user_id' => 1,
                'account_id' => NULL,
                'payment_method_id' => 2,
                'customer_id' => 2,
                'sale_order_id' => 38,
                'transaction_reference_id' => 30,
                'currency_id' => 4,
                'transaction_date' => '2020-04-15',
                'amount' => 1430.46,
                'exchange_rate' => 1.55,
                'status' => 'Approved',
                'created_at' => '2020-04-15 19:10:21',
            ),
            3 => 
            array (
                'id' => 69,
                'user_id' => 1,
                'account_id' => NULL,
                'payment_method_id' => 3,
                'customer_id' => 4,
                'sale_order_id' => 34,
                'transaction_reference_id' => 31,
                'currency_id' => 4,
                'transaction_date' => '2020-04-15',
                'amount' => 1669.61,
                'exchange_rate' => 1.0,
                'status' => 'Approved',
                'created_at' => '2020-04-15 19:10:58',
            ),
            4 => 
            array (
                'id' => 70,
                'user_id' => 1,
                'account_id' => NULL,
                'payment_method_id' => NULL,
                'customer_id' => 5,
                'sale_order_id' => 18,
                'transaction_reference_id' => 32,
                'currency_id' => 4,
                'transaction_date' => '2020-04-15',
                'amount' => 3735.32,
                'exchange_rate' => 1.0,
                'status' => 'Approved',
                'created_at' => '2020-04-15 19:11:43',
            ),
            5 => 
            array (
                'id' => 71,
                'user_id' => 1,
                'account_id' => NULL,
                'payment_method_id' => 2,
                'customer_id' => 3,
                'sale_order_id' => 36,
                'transaction_reference_id' => 33,
                'currency_id' => 4,
                'transaction_date' => '2020-04-15',
                'amount' => 775.0,
                'exchange_rate' => 1.55,
                'status' => 'Approved',
                'created_at' => '2020-04-15 19:12:59',
            ),
            6 => 
            array (
                'id' => 72,
                'user_id' => NULL,
                'account_id' => 1,
                'payment_method_id' => 2,
                'customer_id' => 3,
                'sale_order_id' => 36,
                'transaction_reference_id' => 42,
                'currency_id' => 3,
                'transaction_date' => '0000-00-00',
                'amount' => 964.7,
                'exchange_rate' => 1.0,
                'status' => 'Pending',
                'created_at' => '2020-04-15 20:10:51',
            ),
            7 => 
            array (
                'id' => 73,
                'user_id' => NULL,
                'account_id' => 1,
                'payment_method_id' => 2,
                'customer_id' => 3,
                'sale_order_id' => 36,
                'transaction_reference_id' => 43,
                'currency_id' => 3,
                'transaction_date' => '0000-00-00',
                'amount' => 964.7,
                'exchange_rate' => 1.0,
                'status' => 'Declined',
                'created_at' => '2020-04-15 20:41:38',
            ),
        ));
        
        
    }
}