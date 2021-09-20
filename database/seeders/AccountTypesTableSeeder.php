<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class AccountTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('account_types')->delete();
        
        \DB::table('account_types')->insert(array (
            0 => 
            array (
                'id' => 2,
                'name' => 'Checking Accounts',
            ),
            1 => 
            array (
                'id' => 3,
                'name' => 'Money Market Accounts',
            ),
            2 => 
            array (
                'id' => 4,
                'name' => 'Retirement Accounts',
            ),
            3 => 
            array (
                'id' => 1,
                'name' => 'Savings Accounts',
            ),
        ));
        
        
    }
}