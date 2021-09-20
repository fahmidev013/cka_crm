<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class TransfersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('transfers')->delete();
        
        \DB::table('transfers')->insert(array (
            0 => 
            array (
                'id' => 61,
                'from_account_id' => 1,
                'to_account_id' => 2,
                'user_id' => 1,
                'from_currency_id' => 3,
                'to_currency_id' => 4,
                'transaction_date' => '2020-03-06',
                'transaction_reference_id' => 13,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type a',
                'amount' => 5000.0,
                'fee' => 5.0,
                'exchange_rate' => 0.65,
                'incoming_amount' => 3250.0,
            ),
            1 => 
            array (
                'id' => 62,
                'from_account_id' => 1,
                'to_account_id' => 4,
                'user_id' => 1,
                'from_currency_id' => 3,
                'to_currency_id' => 1,
                'transaction_date' => '2020-04-01',
                'transaction_reference_id' => 14,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type a',
                'amount' => 6000.0,
                'fee' => 0.5,
                'exchange_rate' => 0.01,
                'incoming_amount' => 60.0,
            ),
            2 => 
            array (
                'id' => 63,
                'from_account_id' => 2,
                'to_account_id' => 3,
                'user_id' => 1,
                'from_currency_id' => 4,
                'to_currency_id' => 2,
                'transaction_date' => '2020-04-05',
                'transaction_reference_id' => 15,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type a',
                'amount' => 7000.0,
                'fee' => 8.0,
                'exchange_rate' => 1.23,
                'incoming_amount' => 8610.0,
            ),
            3 => 
            array (
                'id' => 64,
                'from_account_id' => 2,
                'to_account_id' => 1,
                'user_id' => 1,
                'from_currency_id' => 4,
                'to_currency_id' => 3,
                'transaction_date' => '2020-04-07',
                'transaction_reference_id' => 16,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type a',
                'amount' => 5300.0,
                'fee' => 1.5,
                'exchange_rate' => 1.35,
                'incoming_amount' => 7155.0,
            ),
            4 => 
            array (
                'id' => 65,
                'from_account_id' => 3,
                'to_account_id' => 1,
                'user_id' => 1,
                'from_currency_id' => 2,
                'to_currency_id' => 3,
                'transaction_date' => '2020-04-13',
                'transaction_reference_id' => 17,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type a',
                'amount' => 8000.0,
                'fee' => 3.0,
                'exchange_rate' => 3.0,
                'incoming_amount' => 24000.0,
            ),
            5 => 
            array (
                'id' => 66,
                'from_account_id' => 3,
                'to_account_id' => 4,
                'user_id' => 1,
                'from_currency_id' => 2,
                'to_currency_id' => 1,
                'transaction_date' => '2020-04-13',
                'transaction_reference_id' => 18,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type a',
                'amount' => 5600.0,
                'fee' => 0.0,
                'exchange_rate' => 95.0,
                'incoming_amount' => 532000.0,
            ),
            6 => 
            array (
                'id' => 67,
                'from_account_id' => 4,
                'to_account_id' => 1,
                'user_id' => 1,
                'from_currency_id' => 1,
                'to_currency_id' => 3,
                'transaction_date' => '2020-03-20',
                'transaction_reference_id' => 19,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type a',
                'amount' => 3000.0,
                'fee' => 0.0,
                'exchange_rate' => 85.0,
                'incoming_amount' => 255000.0,
            ),
            7 => 
            array (
                'id' => 68,
                'from_account_id' => 4,
                'to_account_id' => 3,
                'user_id' => 1,
                'from_currency_id' => 1,
                'to_currency_id' => 2,
                'transaction_date' => '2020-03-27',
                'transaction_reference_id' => 20,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type a',
                'amount' => 50000.0,
                'fee' => 13.0,
                'exchange_rate' => 0.09,
                'incoming_amount' => 4500.0,
            ),
        ));
        
        
    }
}