<?php

namespace Database\Seeders\ProductSeeds;

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
        
        \DB::table('url_shortner_config')->insert(array (
            0 => 
            array (
                'id' => 3,
                'type' => 'Bitly',
                'status' => 'Active',
                'key' => '',
                'secretkey' => '6d326e4ad38524f8slkdjlwieweowewlkjl',
                'default' => 'Yes',
            ),
        ));
        
        
    }
}