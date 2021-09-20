<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LocationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('locations')->delete();
        
        \DB::table('locations')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => 'PL',
                'name' => 'Jackson Av',
                'delivery_address' => '125 Hayes St, San Francisco, CA 94102, USA',
                'email' => 'maxy@gmail.com',
                'phone' => '01521209953',
                'fax' => '+880 431177',
                'contact' => 'Primary Location',
                'is_active' => 1,
                'is_default' => 0,
                'created_at' => '2020-04-07 09:06:34',
                'updated_at' => '2020-04-15 12:40:00',
            ),
            1 => 
            array (
                'id' => 2,
                'code' => '94016',
                'name' => 'San Francisco',
                'delivery_address' => '1882 Gerard Street, San Francisco, California',
                'email' => '',
                'phone' => '+12013828901',
                'fax' => '',
                'contact' => '',
                'is_active' => 1,
                'is_default' => 1,
                'created_at' => '2020-04-07 09:07:58',
                'updated_at' => '2020-04-07 09:07:58',
            ),
            2 => 
            array (
                'id' => 3,
                'code' => '91905',
                'name' => 'Hill Valley',
                'delivery_address' => '1640 Riverside Drive, Hill Valley, California',
                'email' => '',
                'phone' => '',
                'fax' => '',
                'contact' => '',
                'is_active' => 1,
                'is_default' => 0,
                'created_at' => '2020-04-07 09:08:57',
                'updated_at' => '2020-04-07 09:08:57',
            ),
        ));
        
        
    }
}