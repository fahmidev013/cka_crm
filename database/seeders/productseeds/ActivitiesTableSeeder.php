<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class ActivitiesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('activities')->delete();
        
        \DB::table('activities')->insert(array (
            0 => 
            array (
                'id' => 1,
                'object_id' => 1,
                'object_type' => NULL,
                'user_id' => 1,
                'customer_id' => 0,
                'description' => 'A new project has been created.',
                'params' => NULL,
                'created_at' => '2020-04-13 20:23:24',
            ),
            1 => 
            array (
                'id' => 2,
                'object_id' => 1,
                'object_type' => NULL,
                'user_id' => 1,
                'customer_id' => 0,
                'description' => 'New team member v<strong>Admin TechVill</strong> has been added.',
                'params' => NULL,
                'created_at' => '2020-04-13 20:23:24',
            ),
            2 => 
            array (
                'id' => 3,
                'object_id' => 1,
                'object_type' => NULL,
                'user_id' => 1,
                'customer_id' => 0,
                'description' => 'New team member v<strong>Willa Cooper</strong> has been added.',
                'params' => NULL,
                'created_at' => '2020-04-13 20:23:24',
            ),
            3 => 
            array (
                'id' => 4,
                'object_id' => 1,
                'object_type' => NULL,
                'user_id' => 1,
                'customer_id' => 0,
                'description' => 'New team member v<strong>Frank M Bushnell</strong> has been added.',
                'params' => NULL,
                'created_at' => '2020-04-13 20:23:24',
            ),
            4 => 
            array (
                'id' => 5,
                'object_id' => 1,
                'object_type' => NULL,
                'user_id' => 1,
                'customer_id' => 0,
                'description' => 'New team member v<strong>Luke Wise</strong> has been added.',
                'params' => NULL,
                'created_at' => '2020-04-13 20:23:24',
            ),
            5 => 
            array (
                'id' => 6,
                'object_id' => 2,
                'object_type' => NULL,
                'user_id' => 1,
                'customer_id' => 0,
                'description' => 'A new project has been created.',
                'params' => NULL,
                'created_at' => '2020-04-13 20:26:53',
            ),
            6 => 
            array (
                'id' => 7,
                'object_id' => 2,
                'object_type' => NULL,
                'user_id' => 1,
                'customer_id' => 0,
                'description' => 'New team member v<strong>Willa Cooper</strong> has been added.',
                'params' => NULL,
                'created_at' => '2020-04-13 20:26:53',
            ),
            7 => 
            array (
                'id' => 8,
                'object_id' => 2,
                'object_type' => NULL,
                'user_id' => 1,
                'customer_id' => 0,
                'description' => 'New team member v<strong>Ronald Longwell</strong> has been added.',
                'params' => NULL,
                'created_at' => '2020-04-13 20:26:53',
            ),
            8 => 
            array (
                'id' => 9,
                'object_id' => 2,
                'object_type' => NULL,
                'user_id' => 1,
                'customer_id' => 0,
                'description' => 'New team member v<strong>Luke Wise</strong> has been added.',
                'params' => NULL,
                'created_at' => '2020-04-13 20:26:53',
            ),
            9 => 
            array (
                'id' => 10,
                'object_id' => 3,
                'object_type' => NULL,
                'user_id' => 1,
                'customer_id' => 0,
                'description' => 'A new project has been created.',
                'params' => NULL,
                'created_at' => '2020-04-13 20:30:10',
            ),
            10 => 
            array (
                'id' => 11,
                'object_id' => 3,
                'object_type' => NULL,
                'user_id' => 1,
                'customer_id' => 0,
                'description' => 'New team member v<strong>Willa Cooper</strong> has been added.',
                'params' => NULL,
                'created_at' => '2020-04-13 20:30:10',
            ),
            11 => 
            array (
                'id' => 12,
                'object_id' => 3,
                'object_type' => NULL,
                'user_id' => 1,
                'customer_id' => 0,
                'description' => 'New team member v<strong>Luke Wise</strong> has been added.',
                'params' => NULL,
                'created_at' => '2020-04-13 20:30:10',
            ),
            12 => 
            array (
                'id' => 13,
                'object_id' => 4,
                'object_type' => NULL,
                'user_id' => 1,
                'customer_id' => 0,
                'description' => 'A new project has been created.',
                'params' => NULL,
                'created_at' => '2020-04-13 20:31:41',
            ),
            13 => 
            array (
                'id' => 14,
                'object_id' => 4,
                'object_type' => NULL,
                'user_id' => 1,
                'customer_id' => 0,
                'description' => 'New team member v<strong>Willa Cooper</strong> has been added.',
                'params' => NULL,
                'created_at' => '2020-04-13 20:31:41',
            ),
            14 => 
            array (
                'id' => 15,
                'object_id' => 4,
                'object_type' => NULL,
                'user_id' => 1,
                'customer_id' => 0,
                'description' => 'New team member v<strong>Ronald Longwell</strong> has been added.',
                'params' => NULL,
                'created_at' => '2020-04-13 20:31:41',
            ),
            15 => 
            array (
                'id' => 16,
                'object_id' => 5,
                'object_type' => NULL,
                'user_id' => 1,
                'customer_id' => 0,
                'description' => 'A new project has been created.',
                'params' => NULL,
                'created_at' => '2020-04-13 20:34:20',
            ),
            16 => 
            array (
                'id' => 17,
                'object_id' => 5,
                'object_type' => NULL,
                'user_id' => 1,
                'customer_id' => 0,
                'description' => 'New team member v<strong>Frank M Bushnell</strong> has been added.',
                'params' => NULL,
                'created_at' => '2020-04-13 20:34:20',
            ),
            17 => 
            array (
                'id' => 18,
                'object_id' => 5,
                'object_type' => NULL,
                'user_id' => 1,
                'customer_id' => 0,
                'description' => 'New team member v<strong>Ronald Longwell</strong> has been added.',
                'params' => NULL,
                'created_at' => '2020-04-13 20:34:20',
            ),
            18 => 
            array (
                'id' => 19,
                'object_id' => 3,
                'object_type' => NULL,
                'user_id' => 1,
                'customer_id' => 0,
                'description' => 'New team member v<strong>Admin TechVill</strong> has been added.',
                'params' => NULL,
                'created_at' => '2020-04-13 20:58:28',
            ),
            19 => 
            array (
                'id' => 20,
                'object_id' => 3,
                'object_type' => NULL,
                'user_id' => 1,
                'customer_id' => 0,
                'description' => NULL,
                'params' => NULL,
                'created_at' => '2020-04-13 20:58:51',
            ),
        ));
        
        
    }
}