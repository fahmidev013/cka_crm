<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class UserDepartmentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('user_departments')->delete();
        
        \DB::table('user_departments')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 2,
                'department_id' => 2,
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 2,
                'department_id' => 3,
            ),
            2 => 
            array (
                'id' => 3,
                'user_id' => 2,
                'department_id' => 1,
            ),
            3 => 
            array (
                'id' => 5,
                'user_id' => 3,
                'department_id' => 3,
            ),
            4 => 
            array (
                'id' => 6,
                'user_id' => 4,
                'department_id' => 2,
            ),
            5 => 
            array (
                'id' => 7,
                'user_id' => 5,
                'department_id' => 1,
            ),
        ));
        
        
    }
}