<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleUsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('role_users')->delete();
        
        \DB::table('role_users')->insert(array (
            0 => 
            array (
                'user_id' => 1,
                'role_id' => 1,
            ),
            1 => 
            array (
                'user_id' => 2,
                'role_id' => 1,
            ),
            2 => 
            array (
                'user_id' => 3,
                'role_id' => 4,
            ),
            3 => 
            array (
                'user_id' => 4,
                'role_id' => 3,
            ),
            4 => 
            array (
                'user_id' => 5,
                'role_id' => 2,
            ),
        ));
        
        
    }
}