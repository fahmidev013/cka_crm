<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UrlShortnerConfigTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('url_shortner_config')->delete();
        
    }
}