<?php

namespace Database\Seeders\ProductSeeds;

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
                'id' => 3,
                'name' => 'Insurance',
                'category_type' => 'expense',
            ),
            1 => 
            array (
                'id' => 4,
                'name' => 'Rent',
                'category_type' => 'income',
            )
        ));
        
        
    }
}