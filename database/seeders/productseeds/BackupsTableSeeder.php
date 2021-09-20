<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class BackupsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('backups')->delete();
        
        
        
    }
}