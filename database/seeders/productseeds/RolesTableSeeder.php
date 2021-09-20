<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Admin User',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'project-manager',
                'display_name' => 'Project Manager',
                'description' => 'Project Manager role can manage all the functionality of projects',
                'created_at' => '2020-04-08 00:36:51',
                'updated_at' => '2020-04-08 00:41:01',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'stock-manager',
                'display_name' => 'Stock Manager',
                'description' => 'Stock Manager can manage all the functionality related of stocks.',
                'created_at' => '2020-04-08 00:44:04',
                'updated_at' => '2020-04-08 00:44:04',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'sales-manager',
                'display_name' => 'Sales Manager',
                'description' => 'Sales Manager can sales related functionality.',
                'created_at' => '2020-04-08 00:52:10',
                'updated_at' => '2020-04-08 00:52:10',
            ),
        ));
        
        
    }
}