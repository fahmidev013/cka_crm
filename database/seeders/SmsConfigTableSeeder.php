<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SmsConfigTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sms_config')->delete();
        
        
    }
}