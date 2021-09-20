<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ExpensesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('expenses')->delete();
        \DB::table('expenses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'transaction_id' => 37,
                'user_id' => 1,
                'transaction_reference_id' => 49,
                'income_expense_category_id' => 4,
                'currency_id' => 3,
                'payment_method_id' => 2,
                'amount' => 500,
                'note' => 'Rental Expenses',
                'transaction_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'created_at' => '2020-04-08 18:29:26',
            ),
            1 => 
            array (
                'id' => 2,
                'transaction_id' => 38,
                'user_id' => 1,
                'transaction_reference_id' => 50,
                'income_expense_category_id' => 1,
                'currency_id' => 3,
                'payment_method_id' => 1,
                'amount' => 100,
                'note' => 'IT and Internet Expenses',
                'transaction_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'created_at' => '2020-04-08 18:29:26',
            ),
            2 => 
            array (
                'id' => 3,
                'transaction_id' => 39,
                'user_id' => 1,
                'transaction_reference_id' => 51,
                'income_expense_category_id' => 2,
                'currency_id' => 3,
                'payment_method_id' => 1,
                'amount' => 50,
                'note' => 'Telephone Expenses',
                'transaction_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'created_at' => '2020-04-08 18:29:26',
            ),
            3 => 
            array (
                'id' => 4,
                'transaction_id' => 41,
                'user_id' => 1,
                'transaction_reference_id' => 53,
                'income_expense_category_id' => 5,
                'currency_id' => 3,
                'payment_method_id' => NULL,
                'amount' => 500,
                'note' => 'Foods and Meals Expenses',
                'transaction_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'created_at' => '2020-04-08 18:29:26',
            ),
            4 => 
            array (
                'id' => 5,
                'transaction_id' => 40,
                'user_id' => 1,
                'transaction_reference_id' => 52,
                'income_expense_category_id' => 3,
                'currency_id' => 3,
                'payment_method_id' => 2,
                'amount' => 250,
                'note' => 'Insurence Expenses',
                'transaction_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'created_at' => '2020-04-08 18:29:26',
            )
            
        ));
        
        
    }
}