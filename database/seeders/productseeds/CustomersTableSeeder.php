<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('customers')->delete();
        
        \DB::table('customers')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Walking customer',
                'first_name' => 'Walking',
                'last_name' => 'Customer',
                'email' => '',
                'password' => '$2y$10$VJwK3pDqNJFXvtA8VJpaeeWAtKXV6/EzhIc61QZ6C.CyN1tojQaqa',
                'phone' => '',
                'tax_id' => NULL,
                'currency_id' => 3,
                'customer_type' => NULL,
                'timezone' => 'Asia/Dhaka',
                'remember_token' => '',
                'is_active' => 1,
                'is_verified' => 0,
                'created_at' => '2020-04-07 04:10:36',
                'updated_at' => '2020-04-08 05:57:51',
            )
        ));
        
        
    }
}