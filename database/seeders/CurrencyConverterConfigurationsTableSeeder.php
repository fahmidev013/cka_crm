<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CurrencyConverterConfigurationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('currency_converter_configurations')->delete();
        
    }
}