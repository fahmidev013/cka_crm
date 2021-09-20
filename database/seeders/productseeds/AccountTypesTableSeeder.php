<?php

namespace Database\Seeders\ProductSeeds;

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
            0  => 
            array (
                'id' => 1,
                'name' => 'Savings',
            )
        ));
        
        
    }
}