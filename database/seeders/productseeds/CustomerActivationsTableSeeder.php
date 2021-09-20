<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class CustomerActivationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('customer_activations')->delete();
        
        
        
    }
}