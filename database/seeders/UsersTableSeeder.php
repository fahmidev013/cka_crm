<?php

namespace Database\Seeders;

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
                'first_name' => 'Mr.',
                'last_name' => 'Admin',
                'added_by' => 1,
                'password' => bcrypt('123456'),
                'role_id' => 1,
                'phone' => '404-469-1274',
                'email' => 'admin@techvill.net',
                'remember_token' => '',
                'is_active' => 1,
                'created_at' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'full_name' => 'Willa Cooper',
                'first_name' => 'Willa',
                'last_name' => 'Cooper',
                'added_by' => 1,
                'password' => '$2y$10$.Plfs.qb6SIA8gdF/w8i8OB8jMX6TPKe8SNRkv8nsBP2t6LmbS73.',
                'role_id' => 1,
                'phone' => '404-469-1274',
                'email' => 'cooper@techvill.net',
                'remember_token' => '',
                'is_active' => 1,
                'created_at' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'full_name' => 'Frank M Bushnell',
                'first_name' => 'Frank M',
                'last_name' => 'Bushnell',
                'added_by' => 1,
                'password' => bcrypt('123456'),
                'role_id' => 4,
                'phone' => '863-245-4411',
                'email' => 'pos@techvill.net',
                'remember_token' => '',
                'is_active' => 1,
                'created_at' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'updated_at' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'full_name' => 'Ronald Longwell',
                'first_name' => 'Ronald',
                'last_name' => 'Longwell',
                'added_by' => 1,
                'password' => '$2y$10$isvaplf4DEMFrAeyblTwMe6sofCOmKLI3ZXB8fCa8HXn0YwrQwpKi',
                'role_id' => 3,
                'phone' => '507-348-0413',
                'email' => 'longwell@techvill.net',
                'remember_token' => '',
                'is_active' => 1,
                'created_at' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'updated_at' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'full_name' => 'Luke Wise',
                'first_name' => 'Luke',
                'last_name' => 'Wise',
                'added_by' => 1,
                'password' => '$2y$10$2.L65AWGh.LNrKvNAXrm3.enMDSJ.OuF.aqc3B.5Mr4NVAROIEaBK',
                'role_id' => 2,
                'phone' => '913-815-5094',
                'email' => 'wise@techvill.net',
                'remember_token' => '',
                'is_active' => 1,
                'created_at' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'updated_at' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}