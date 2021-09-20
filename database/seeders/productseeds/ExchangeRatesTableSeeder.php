<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class ExchangeRatesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('exchange_rates')->delete();
        
        
        
    }
}