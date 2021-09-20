<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProjectMembersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('project_members')->delete();
        
        \DB::table('project_members')->insert(array (
            0 => 
            array (
                'id' => 1,
                'project_id' => 1,
                'user_id' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'project_id' => 1,
                'user_id' => 2,
            ),
            2 => 
            array (
                'id' => 3,
                'project_id' => 1,
                'user_id' => 3,
            ),
            3 => 
            array (
                'id' => 4,
                'project_id' => 1,
                'user_id' => 5,
            ),
            4 => 
            array (
                'id' => 5,
                'project_id' => 2,
                'user_id' => 2,
            ),
            5 => 
            array (
                'id' => 6,
                'project_id' => 2,
                'user_id' => 4,
            ),
            6 => 
            array (
                'id' => 7,
                'project_id' => 2,
                'user_id' => 5,
            ),
            7 => 
            array (
                'id' => 8,
                'project_id' => 3,
                'user_id' => 2,
            ),
            8 => 
            array (
                'id' => 9,
                'project_id' => 3,
                'user_id' => 5,
            ),
            9 => 
            array (
                'id' => 10,
                'project_id' => 4,
                'user_id' => 2,
            ),
            10 => 
            array (
                'id' => 11,
                'project_id' => 4,
                'user_id' => 4,
            ),
            11 => 
            array (
                'id' => 12,
                'project_id' => 5,
                'user_id' => 3,
            ),
            12 => 
            array (
                'id' => 13,
                'project_id' => 5,
                'user_id' => 4,
            ),
            13 => 
            array (
                'id' => 14,
                'project_id' => 3,
                'user_id' => 1,
            ),
        ));
        
        
    }
}