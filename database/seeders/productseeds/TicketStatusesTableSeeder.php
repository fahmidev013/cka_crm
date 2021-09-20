<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class TicketStatusesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('ticket_statuses')->delete();
        
        \DB::table('ticket_statuses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Open',
                'is_default' => 1,
                'color' => '#ff2d42',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'In progress',
                'is_default' => 0,
                'color' => '#84c529',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'On Hold',
                'is_default' => 0,
                'color' => '#848484',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Closed',
                'is_default' => 0,
                'color' => '#03a9f4',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Answered',
                'is_default' => 0,
                'color' => '#0000ff',
            ),
        ));
        
        
    }
}