<?php

namespace Database\Seeders;

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
            ),
            1 => 
            array (
                'id' => 2,
                'customer_id' => 2,
                'name' => 'Agatha Williams',
                'contact' => NULL,
                'billing_street' => '2316  Mahlon Street',
                'billing_city' => 'BRADLEY',
                'billing_state' => 'West Virginia',
                'billing_zip_code' => '25818',
                'billing_country_id' => 1,
                'shipping_street' => '2316  Mahlon Street',
                'shipping_city' => 'BRADLEY',
                'shipping_state' => 'West Virginia',
                'shipping_zip_code' => '25818',
                'shipping_country_id' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'customer_id' => 3,
                'name' => 'Ben Wilkinson',
                'contact' => NULL,
                'billing_street' => '852  Chandler Drive',
                'billing_city' => 'Springfield',
                'billing_state' => 'Missouri',
                'billing_zip_code' => '65806',
                'billing_country_id' => 1,
                'shipping_street' => '852  Chandler Drive',
                'shipping_city' => 'Springfield',
                'shipping_state' => 'Missouri',
                'shipping_zip_code' => '65806',
                'shipping_country_id' => 1,
            ),
            3 => 
            array (
                'id' => 4,
                'customer_id' => 4,
                'name' => 'Harvey Morrison',
                'contact' => NULL,
                'billing_street' => '79 Stroud Rd',
                'billing_city' => 'OKEHAMPTON',
                'billing_state' => NULL,
                'billing_zip_code' => 'EX20 6TP',
                'billing_country_id' => 226,
                'shipping_street' => '79 Stroud Rd',
                'shipping_city' => 'OKEHAMPTON',
                'shipping_state' => NULL,
                'shipping_zip_code' => 'EX20 6TP',
                'shipping_country_id' => 226,
            ),
            4 => 
            array (
                'id' => 5,
                'customer_id' => 5,
                'name' => 'Anke Kaiser',
                'contact' => NULL,
                'billing_street' => 'Feldstrasse 47',
                'billing_city' => 'Hausneindorf',
                'billing_state' => 'Sachsen Anhal',
                'billing_zip_code' => '06458',
                'billing_country_id' => 82,
                'shipping_street' => 'Feldstrasse 47',
                'shipping_city' => 'Hausneindorf',
                'shipping_state' => 'Sachsen Anhal',
                'shipping_zip_code' => '06458',
                'shipping_country_id' => 82,
            ),
            5 => 
            array (
                'id' => 6,
                'customer_id' => 6,
                'name' => 'Anke Kaiser',
                'contact' => NULL,
                'billing_street' => 'Feldstrasse 47',
                'billing_city' => 'Hausneindorf',
                'billing_state' => 'Sachsen Anhal',
                'billing_zip_code' => '06458',
                'billing_country_id' => 82,
                'shipping_street' => 'Feldstrasse 47',
                'shipping_city' => 'Hausneindorf',
                'shipping_state' => 'Sachsen Anhal',
                'shipping_zip_code' => '06458',
                'shipping_country_id' => 82,
            ),
        ));
        
        
    }
}