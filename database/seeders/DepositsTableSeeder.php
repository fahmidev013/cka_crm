<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DepositsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('deposits')->delete();
        
        \DB::table('deposits')->insert(array (
            0 => 
            array (
                'id' => 45,
                'account_id' => 1,
                'user_id' => 1,
                'income_expense_category_id' => 8,
                'transaction_reference_id' => 5,
                'payment_method_id' => 2,
                'transaction_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                'amount' => 2000.0,
            ),
            1 => 
            array (
                'id' => 46,
                'account_id' => 1,
                'user_id' => 1,
                'income_expense_category_id' => 6,
                'transaction_reference_id' => 6,
                'payment_method_id' => 2,
                'transaction_date' => '2020-03-21',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                'amount' => 3000.0,
            ),
            2 => 
            array (
                'id' => 47,
                'account_id' => 2,
                'user_id' => 1,
                'income_expense_category_id' => 7,
                'transaction_reference_id' => 7,
                'payment_method_id' => 3,
                'transaction_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                'amount' => 3000.0,
            ),
            3 => 
            array (
                'id' => 48,
                'account_id' => 2,
                'user_id' => 1,
                'income_expense_category_id' => 7,
                'transaction_reference_id' => 8,
                'payment_method_id' => 2,
                'transaction_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                'amount' => 6000.0,
            ),
            4 => 
            array (
                'id' => 49,
                'account_id' => 3,
                'user_id' => 1,
                'income_expense_category_id' => 8,
                'transaction_reference_id' => 9,
                'payment_method_id' => 1,
                'transaction_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                'amount' => 5000.0,
            ),
            5 => 
            array (
                'id' => 50,
                'account_id' => 3,
                'user_id' => 1,
                'income_expense_category_id' => 7,
                'transaction_reference_id' => 10,
                'payment_method_id' => 2,
                'transaction_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                'amount' => 2000.0,
            ),
            6 => 
            array (
                'id' => 51,
                'account_id' => 4,
                'user_id' => 1,
                'income_expense_category_id' => 7,
                'transaction_reference_id' => 11,
                'payment_method_id' => 2,
                'transaction_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                'amount' => 7000.0,
            ),
            7 => 
            array (
                'id' => 52,
                'account_id' => 4,
                'user_id' => 1,
                'income_expense_category_id' => 7,
                'transaction_reference_id' => 12,
                'payment_method_id' => 3,
                'transaction_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                'amount' => 8000.0,
            ),
        ));
        
        
    }
}