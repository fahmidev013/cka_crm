<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TaskTimersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('task_timers')->delete();
        
        \DB::table('task_timers')->insert(array (
            0 => 
            array (
                'id' => 27,
                'user_id' => 1,
                'task_id' => 136,
                'start_time' => '1586962251',
                'end_time' => '1586962339',
                'hourly_rate' => 0.0,
                'note' => 'Complete',
            ),
            1 => 
            array (
                'id' => 28,
                'user_id' => 1,
                'task_id' => 137,
                'start_time' => '1586962491',
                'end_time' => '1586962509',
                'hourly_rate' => 0.0,
                'note' => 'Complete',
            ),
            2 => 
            array (
                'id' => 29,
                'user_id' => 1,
                'task_id' => 132,
                'start_time' => '1586962584',
                'end_time' => '1586962604',
                'hourly_rate' => 0.0,
                'note' => 'Complete',
            ),
        ));
        
        
    }
}