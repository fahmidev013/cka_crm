<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IncomeExpenseCategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('income_expense_categories')->delete();
        
        \DB::table('income_expense_categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'IT and Internet Expenses',
                'category_type' => 'expense',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Telephone',
                'category_type' => 'expense',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Insurance',
                'category_type' => 'expense',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Rent',
                'category_type' => 'expense',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Foods & Meals',
                'category_type' => 'expense',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Sales',
                'category_type' => 'income',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Customize',
                'category_type' => 'income',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Services',
                'category_type' => 'income',
            ),
        ));
        
        
    }
}