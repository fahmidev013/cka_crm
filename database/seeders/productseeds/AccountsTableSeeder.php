<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class AccountsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('accounts')->delete();
        
        \DB::table('accounts')->insert(array (
            0 => 
            array (
                'id' => 1,
                'account_type_id' => 1,
                'name' => 'RoverCRM-Savings',
                'account_number' => '323076012',
                'income_expense_category_id' => NULL,
                'currency_id' => 3,
                'bank_name' => 'Bank of America',
                'branch_name' => 'Lexington Ave - Bank of America',
                'branch_city' => 'Lexington Ave, New York',
                'swift_code' => '10028',
                'bank_address' => '1275 Lexington Ave, New York, NY 10028',
                'is_default' => 1,
                'is_deleted' => 0,
            )
        ));
        
        
    }
}