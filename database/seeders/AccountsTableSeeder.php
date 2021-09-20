<?php

namespace Database\Seeders;

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
                'name' => 'GoBilling-Savings',
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
            ),
            1 => 
            array (
                'id' => 2,
                'account_type_id' => 2,
                'name' => 'GoBilling-Expenses',
                'account_number' => '323076013',
                'income_expense_category_id' => NULL,
                'currency_id' => 4,
                'bank_name' => 'HSBC',
                'branch_name' => 'HSBC_Upper-Richmond',
                'branch_city' => 'London',
                'swift_code' => '172',
                'bank_address' => '172 Upper Richmond Rd, Putney, London SW15 2SH, United Kingdom',
                'is_default' => 0,
                'is_deleted' => 0,
            ),
            2 => 
            array (
                'id' => 3,
                'account_type_id' => 4,
                'name' => 'GoBilling-Retire',
                'account_number' => '0532013000',
                'income_expense_category_id' => NULL,
                'currency_id' => 2,
                'bank_name' => 'Deutsche Bank',
                'branch_name' => 'DB_Berlin',
                'branch_city' => 'Berlin',
                'swift_code' => '10029',
                'bank_address' => 'Otto-Suhr-Allee 6-16, 10585 Berlin, Germany',
                'is_default' => 0,
                'is_deleted' => 0,
            ),
            3 => 
            array (
                'id' => 4,
                'account_type_id' => 3,
                'name' => 'GoBilling-Misc',
                'account_number' => '100000148',
                'income_expense_category_id' => NULL,
                'currency_id' => 1,
                'bank_name' => 'Sonali Bank',
                'branch_name' => 'SB_Motijheel',
                'branch_city' => 'Dhaka',
                'swift_code' => '10032',
                'bank_address' => '206 Nawabpur Rd, Dhaka',
                'is_default' => 0,
                'is_deleted' => 0,
            ),
        ));
        
        
    }
}