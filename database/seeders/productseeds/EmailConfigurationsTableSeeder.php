<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class EmailConfigurationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('email_configurations')->delete();
        
        \DB::table('email_configurations')->insert(array (
            0 => 
            array (
                'id' => 1,
                'protocol' => 'smtp',
                'encryption' => 'tls',
                'smtp_host' => 'smtp.gmail.com',
                'smtp_port' => '587',
                'smtp_email' => 'example@gmail.com',
                'smtp_username' => 'example@gmail.com',
                'smtp_password' => 'screte',
                'from_address' => 'example@gmail.com',
                'from_name' => 'example@gmail.com',
                'status' => 1,
            ),
        ));
        
        
    }
}