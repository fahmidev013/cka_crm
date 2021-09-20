<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class ChecklistItemsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('checklist_items')->delete();
        
        \DB::table('checklist_items')->insert(array (
            0 => 
            array (
                'id' => 4,
                'title' => 'calculation',
                'task_id' => 131,
                'is_checked' => 0,
                'created_at' => '2020-04-13 20:39:43',
                'checked_at' => NULL,
            ),
            1 => 
            array (
                'id' => 5,
                'title' => 'design',
                'task_id' => 131,
                'is_checked' => 0,
                'created_at' => '2020-04-13 20:39:43',
                'checked_at' => NULL,
            ),
            2 => 
            array (
                'id' => 6,
                'title' => 'responsive',
                'task_id' => 131,
                'is_checked' => 0,
                'created_at' => '2020-04-13 20:39:43',
                'checked_at' => NULL,
            ),
            3 => 
            array (
                'id' => 7,
                'title' => 'Design',
                'task_id' => 132,
                'is_checked' => 0,
                'created_at' => '2020-04-13 20:42:38',
                'checked_at' => NULL,
            ),
            4 => 
            array (
                'id' => 8,
                'title' => 'Responsive',
                'task_id' => 132,
                'is_checked' => 0,
                'created_at' => '2020-04-13 20:42:38',
                'checked_at' => NULL,
            ),
            5 => 
            array (
                'id' => 9,
                'title' => 'Object-Pattern',
                'task_id' => 132,
                'is_checked' => 0,
                'created_at' => '2020-04-13 20:42:38',
                'checked_at' => NULL,
            ),
            6 => 
            array (
                'id' => 10,
                'title' => 'Feature',
                'task_id' => 135,
                'is_checked' => 0,
                'created_at' => '2020-04-13 20:54:48',
                'checked_at' => NULL,
            ),
            7 => 
            array (
                'id' => 11,
                'title' => 'Currency',
                'task_id' => 135,
                'is_checked' => 0,
                'created_at' => '2020-04-13 20:54:48',
                'checked_at' => NULL,
            ),
            8 => 
            array (
                'id' => 12,
                'title' => 'Registration',
                'task_id' => 135,
                'is_checked' => 0,
                'created_at' => '2020-04-13 20:54:48',
                'checked_at' => NULL,
            ),
            9 => 
            array (
                'id' => 13,
                'title' => 'booking',
                'task_id' => 136,
                'is_checked' => 0,
                'created_at' => '2020-04-13 20:58:11',
                'checked_at' => NULL,
            ),
            10 => 
            array (
                'id' => 14,
                'title' => 'property formation',
                'task_id' => 136,
                'is_checked' => 0,
                'created_at' => '2020-04-13 20:58:11',
                'checked_at' => NULL,
            ),
            11 => 
            array (
                'id' => 15,
                'title' => 'user error',
                'task_id' => 136,
                'is_checked' => 0,
                'created_at' => '2020-04-13 20:58:11',
                'checked_at' => NULL,
            ),
            12 => 
            array (
                'id' => 16,
                'title' => 'message bug',
                'task_id' => 137,
                'is_checked' => 0,
                'created_at' => '2020-04-13 21:01:11',
                'checked_at' => NULL,
            ),
            13 => 
            array (
                'id' => 17,
                'title' => 'admin panel',
                'task_id' => 137,
                'is_checked' => 0,
                'created_at' => '2020-04-13 21:01:11',
                'checked_at' => NULL,
            ),
            14 => 
            array (
                'id' => 18,
                'title' => 'push update',
                'task_id' => 137,
                'is_checked' => 0,
                'created_at' => '2020-04-13 21:01:11',
                'checked_at' => NULL,
            ),
        ));
        
        
    }
}