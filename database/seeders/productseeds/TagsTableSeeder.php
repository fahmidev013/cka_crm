<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tags')->delete();
        
        \DB::table('tags')->insert(array (
            0 => 
            array (
                'id' => 12,
                'name' => 'Billing',
            ),
            1 => 
            array (
                'id' => 8,
                'name' => 'booking',
            ),
            2 => 
            array (
                'id' => 2,
                'name' => 'Business',
            ),
            3 => 
            array (
                'id' => 1,
                'name' => 'CRM',
            ),
            4 => 
            array (
                'id' => 3,
                'name' => 'eBusiness',
            ),
            5 => 
            array (
                'id' => 5,
                'name' => 'eMoney',
            ),
            6 => 
            array (
                'id' => 4,
                'name' => 'eWallet',
            ),
            7 => 
            array (
                'id' => 6,
                'name' => 'exchange',
            ),
            8 => 
            array (
                'id' => 16,
                'name' => 'NewFeature',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'POS',
            ),
            10 => 
            array (
                'id' => 15,
                'name' => 'Project',
            ),
            11 => 
            array (
                'id' => 14,
                'name' => 'projects',
            ),
            12 => 
            array (
                'id' => 9,
                'name' => 'restZone',
            ),
            13 => 
            array (
                'id' => 11,
                'name' => 'sellProduct',
            ),
            14 => 
            array (
                'id' => 13,
                'name' => 'shopManagement',
            ),
            15 => 
            array (
                'id' => 17,
                'name' => 'UserRegistration',
            ),
            16 => 
            array (
                'id' => 7,
                'name' => 'vRent',
            ),
        ));
        
        
    }
}