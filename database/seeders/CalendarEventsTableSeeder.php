<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CalendarEventsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('calendar_events')->delete();
        
        
        
    }
}