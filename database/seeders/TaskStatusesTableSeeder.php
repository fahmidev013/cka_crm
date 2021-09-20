<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TaskStatusesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('task_statuses')->delete();
        
        \DB::table('task_statuses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Not Started',
                'status_order' => 1,
                'color' => '#F22012',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'In Progress',
                'status_order' => 3,
                'color' => '#04a9f5',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Testing',
                'status_order' => 4,
                'color' => '#5a4d4d',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Complete',
                'status_order' => 5,
                'color' => '#00b894',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'On Hold',
                'status_order' => 2,
                'color' => '#fdcb6e',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Re-open',
                'status_order' => 6,
                'color' => '#F22012',
            ),
        ));
        
        
    }
}