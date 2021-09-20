<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class ShipmentDetailsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('shipment_details')->delete();
        
        
        
    }
}