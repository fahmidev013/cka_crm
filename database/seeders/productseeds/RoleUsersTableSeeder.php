<?php

namespace Database\Seeders\ProductSeeds;

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
            )
        ));
        
        
    }
}