<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TaskAssignsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('task_assigns')->delete();
        
        \DB::table('task_assigns')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 3,
                'task_id' => 131,
                'assigned_by' => 1,
                'is_assigned_by_customer' => 0,
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 4,
                'task_id' => 131,
                'assigned_by' => 1,
                'is_assigned_by_customer' => 0,
            ),
            2 => 
            array (
                'id' => 3,
                'user_id' => 1,
                'task_id' => 132,
                'assigned_by' => 1,
                'is_assigned_by_customer' => 0,
            ),
            3 => 
            array (
                'id' => 4,
                'user_id' => 2,
                'task_id' => 132,
                'assigned_by' => 1,
                'is_assigned_by_customer' => 0,
            ),
            4 => 
            array (
                'id' => 5,
                'user_id' => 2,
                'task_id' => 133,
                'assigned_by' => 1,
                'is_assigned_by_customer' => 0,
            ),
            5 => 
            array (
                'id' => 6,
                'user_id' => 4,
                'task_id' => 133,
                'assigned_by' => 1,
                'is_assigned_by_customer' => 0,
            ),
            6 => 
            array (
                'id' => 7,
                'user_id' => 5,
                'task_id' => 133,
                'assigned_by' => 1,
                'is_assigned_by_customer' => 0,
            ),
            7 => 
            array (
                'id' => 9,
                'user_id' => 2,
                'task_id' => 134,
                'assigned_by' => 1,
                'is_assigned_by_customer' => 0,
            ),
            8 => 
            array (
                'id' => 10,
                'user_id' => 2,
                'task_id' => 135,
                'assigned_by' => 1,
                'is_assigned_by_customer' => 0,
            ),
            9 => 
            array (
                'id' => 11,
                'user_id' => 4,
                'task_id' => 135,
                'assigned_by' => 1,
                'is_assigned_by_customer' => 0,
            ),
            10 => 
            array (
                'id' => 12,
                'user_id' => 2,
                'task_id' => 136,
                'assigned_by' => 1,
                'is_assigned_by_customer' => 0,
            ),
            11 => 
            array (
                'id' => 13,
                'user_id' => 5,
                'task_id' => 136,
                'assigned_by' => 1,
                'is_assigned_by_customer' => 0,
            ),
            12 => 
            array (
                'id' => 14,
                'user_id' => 1,
                'task_id' => 136,
                'assigned_by' => 1,
                'is_assigned_by_customer' => 0,
            ),
            13 => 
            array (
                'id' => 15,
                'user_id' => 1,
                'task_id' => 137,
                'assigned_by' => 1,
                'is_assigned_by_customer' => 0,
            ),
            14 => 
            array (
                'id' => 16,
                'user_id' => 2,
                'task_id' => 137,
                'assigned_by' => 1,
                'is_assigned_by_customer' => 0,
            ),
            15 => 
            array (
                'id' => 17,
                'user_id' => 4,
                'task_id' => 137,
                'assigned_by' => 1,
                'is_assigned_by_customer' => 0,
            ),
        ));
        
        
    }
}