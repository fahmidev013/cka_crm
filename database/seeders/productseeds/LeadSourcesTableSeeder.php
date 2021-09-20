<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class LeadSourcesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('lead_sources')->delete();
        
        \DB::table('lead_sources')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Facebook',
                'status' => 'active',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Skype',
                'status' => 'active',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Google Plus',
                'status' => 'active',
            ),
        ));
        
        
    }
}