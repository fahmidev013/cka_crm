<?php

namespace Database\Seeders;

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
                'last_name' => 'customer',
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
                'created_at' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'updated_at' => \Carbon::now()->subDays(6)->toDateTimeString(),
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'William Jones',
                'first_name' => 'William',
                'last_name' => 'Jones',
                'email' => 'jones@techvill.net',
                'password' => '$2y$10$VJwK3pDqNJFXvtA8VJpaeeWAtKXV6/EzhIc61QZ6C.CyN1tojQaqa',
                'phone' => '254-424-8310',
                'tax_id' => NULL,
                'currency_id' => 3,
                'customer_type' => NULL,
                'timezone' => 'Asia/Dhaka',
                'remember_token' => 'IbpxS2sBHaUClyJnVEO44P0StMe1sHK66ncU3kQhn0Nt8NFV522QX65rNKMu',
                'is_active' => 1,
                'is_verified' => 0,
                'created_at' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'updated_at' => \Carbon::now()->subDays(6)->toDateTimeString(),
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Agatha Williams',
                'first_name' => 'Agatha',
                'last_name' => 'Williams',
                'email' => 'customer@techvill.net',
                'password' => bcrypt('123456'),
                'phone' => '734-223-8992',
                'tax_id' => NULL,
                'currency_id' => 3,
                'customer_type' => NULL,
                'timezone' => 'Asia/Dhaka',
                'remember_token' => '',
                'is_active' => 1,
                'is_verified' => 1,
                'created_at' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'updated_at' => \Carbon::now()->subDays(6)->toDateTimeString(),
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Ben Wilkinson',
                'first_name' => 'Ben',
                'last_name' => 'Wilkinson',
                'email' => 'wilkinson@techvill.net',
                'password' => '',
                'phone' => '417-594-7640',
                'tax_id' => NULL,
                'currency_id' => 3,
                'customer_type' => NULL,
                'timezone' => 'Asia/Dhaka',
                'remember_token' => 'ePKVE5gy1TdPveZfeIJxaNxmOxDONvWR78pBzRWFaBCPYG8KYSq5oqxsXn9W',
                'is_active' => 1,
                'is_verified' => 0,
                'created_at' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'updated_at' => \Carbon::now()->subDays(6)->toDateTimeString(),
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Harvey Morrison',
                'first_name' => 'Harvey',
                'last_name' => 'Morrison',
                'email' => 'morrison@techvill.net',
                'password' => '$2y$10$fjaYMAVg2waBoaPm0MLHAu1WrKn4QCOX4bV6A0UcM0DxMnSQv7GeG',
                'phone' => '070 8416 6783',
                'tax_id' => NULL,
                'currency_id' => 4,
                'customer_type' => NULL,
                'timezone' => 'Asia/Dhaka',
                'remember_token' => '',
                'is_active' => 1,
                'is_verified' => 0,
                'created_at' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'updated_at' => \Carbon::now()->subDays(7)->toDateTimeString(),
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Anke Kaiser',
                'first_name' => 'Anke',
                'last_name' => 'Kaiser',
                'email' => 'kaiser@techvill.net',
                'password' => '',
                'phone' => '039481700',
                'tax_id' => NULL,
                'currency_id' => 4,
                'customer_type' => NULL,
                'timezone' => 'Asia/Dhaka',
                'remember_token' => 'HS4LZVYvB7Imnz9OYOjroVLr45bro6sHskDuhOKRCe35L932jh193juF16qB',
                'is_active' => 1,
                'is_verified' => 0,
                'created_at' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'updated_at' => \Carbon::now()->subDays(6)->toDateTimeString(),
            ),
        ));
        
        
    }
}