<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class CustomItemOrdersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('custom_item_orders')->delete();
        
        
        
    }
}