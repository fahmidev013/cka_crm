<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class CaptchaConfigurationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('captcha_configurations')->delete();
        
        \DB::table('captcha_configurations')->insert(array (
            0 => 
            array (
                'id' => 1,
                'site_key' => 'fill with your site key',
                'secret_key' => 'fill with your secret key',
                'site_verify_url' => 'https://www.google.com/recaptcha/api/siteverify',
                'plugin_url' => 'https://www.google.com/recaptcha/api.js',
            ),
        ));
        
        
    }
}