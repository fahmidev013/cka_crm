<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MonthsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('months')->delete();
        
        \DB::table('months')->insert(array (
            0 => 
            array (
                'id' => 4,
                'name' => 'Appril',
            ),
            1 => 
            array (
                'id' => 8,
                'name' => 'August',
            ),
            2 => 
            array (
                'id' => 12,
                'name' => 'December',
            ),
            3 => 
            array (
                'id' => 2,
                'name' => 'February',
            ),
            4 => 
            array (
                'id' => 1,
                'name' => 'January',
            ),
            5 => 
            array (
                'id' => 7,
                'name' => 'July',
            ),
            6 => 
            array (
                'id' => 6,
                'name' => 'June',
            ),
            7 => 
            array (
                'id' => 3,
                'name' => 'March',
            ),
            8 => 
            array (
                'id' => 5,
                'name' => 'May',
            ),
            9 => 
            array (
                'id' => 11,
                'name' => 'November',
            ),
            10 => 
            array (
                'id' => 10,
                'name' => 'October',
            ),
            11 => 
            array (
                'id' => 9,
                'name' => 'September',
            ),
        ));
        
        
    }
}