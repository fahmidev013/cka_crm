<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MilestonesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('milestones')->delete();
        
        
        
    }
}