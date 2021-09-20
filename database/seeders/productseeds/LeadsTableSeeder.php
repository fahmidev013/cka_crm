<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class LeadsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('leads')->delete();
        
        
        
    }
}