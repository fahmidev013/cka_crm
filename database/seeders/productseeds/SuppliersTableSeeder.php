<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class SuppliersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('suppliers')->delete();
        
        \DB::table('suppliers')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Dominik Meister',
                'email' => 'meister@demo.com',
                'address' => 'Nuernbergerstrasse 11',
                'contact' => '04421626012',
                'tax_id' => NULL,
                'currency_id' => 2,
                'city' => 'Wilhelmshaven',
                'state' => 'Niedersachsen',
                'zipcode' => '26386',
                'country_id' => 82,
                'is_active' => 1,
                'created_at' => '2020-04-07 06:43:35',
                'updated_at' => '2020-04-08 06:00:13',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'David Beckenbauer',
                'email' => 'beckenbauer@demo.com',
                'address' => 'Neuer Jungfernstieg 48',
                'contact' => '08751492559',
                'tax_id' => NULL,
                'currency_id' => 2,
                'city' => 'Rudelzhausen',
                'state' => 'Freistaat Bayern',
                'zipcode' => '84104',
                'country_id' => 82,
                'is_active' => 1,
                'created_at' => '2020-04-07 21:54:16',
                'updated_at' => '2020-04-08 06:00:04',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Zara S Hicks',
                'email' => 'hicks@demo.com',
                'address' => '67  Souterhead Road',
                'contact' => '07988986906',
                'tax_id' => NULL,
                'currency_id' => 4,
                'city' => 'LOW HESLEYHURST',
                'state' => NULL,
                'zipcode' => 'NE65 7LG',
                'country_id' => 226,
                'is_active' => 1,
                'created_at' => '2020-04-07 21:56:20',
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Joseph E Findley',
                'email' => 'findley@demo.com',
                'address' => '4434  Fittro Street',
                'contact' => '870-351-5716',
                'tax_id' => NULL,
                'currency_id' => 1,
                'city' => 'Little Rock',
                'state' => 'Arkansas',
                'zipcode' => '72212',
                'country_id' => 1,
                'is_active' => 1,
                'created_at' => '2020-04-07 21:57:45',
                'updated_at' => '2020-04-08 05:56:37',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Clifton M Watkins',
                'email' => 'watkins@demo.com',
                'address' => '3164  Oak Ridge Drive',
                'contact' => '573-598-7156',
                'tax_id' => NULL,
                'currency_id' => 3,
                'city' => 'Annapolis',
                'state' => 'Missouri',
                'zipcode' => '63620',
                'country_id' => 1,
                'is_active' => 1,
                'created_at' => '2020-04-07 21:59:21',
                'updated_at' => '2020-04-13 08:26:22',
            ),
        ));
        
        
    }
}