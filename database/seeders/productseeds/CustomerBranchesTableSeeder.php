<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class CustomerBranchesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('customer_branches')->delete();
        
        \DB::table('customer_branches')->insert(array (
            0 => 
            array (
                'id' => 1,
                'customer_id' => 1,
                'name' => 'William Jones',
                'contact' => NULL,
                'billing_street' => '864  Sussex Court',
                'billing_city' => 'Waco',
                'billing_state' => 'Texas',
                'billing_zip_code' => '76706',
                'billing_country_id' => 1,
                'shipping_street' => '864  Sussex Court',
                'shipping_city' => 'Waco',
                'shipping_state' => 'Texas',
                'shipping_zip_code' => '76706',
                'shipping_country_id' => 1,
            )
        ));
        
        
    }
}