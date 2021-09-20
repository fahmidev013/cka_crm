<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'full_name' => 'Mr Admin',
                'first_name' => 'Mr',
                'last_name' => 'Admin',
                'added_by' => 1,
                'password' => bcrypt('123456'),
                'role_id' => 1,
                'phone' => '404-469-1274',
                'email' => 'admin@techvill.net',
                'remember_token' => '',
                'is_active' => 1,
                'created_at' => '2020-04-08 06:12:06',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            )
        ));
        
        
    }
}