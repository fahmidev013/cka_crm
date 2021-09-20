<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class LanguagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('languages')->delete();
        
        \DB::table('languages')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'English',
                'short_name' => 'en',
                'flag' => 'en.jpg',
                'status' => 'Active',
                'is_default' => 1,
                'direction' => 'ltr',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'عربى',
                'short_name' => 'ar',
                'flag' => 'ar.png',
                'status' => 'Active',
                'is_default' => 0,
                'direction' => 'rtl',
            ),
            2 =>
            array (
                'id' => 3,
                'name' => 'French',
                'short_name' => 'fr',
                'flag' => '',
                'status' => 'Active',
                'is_default' => 0,
                'direction' => 'ltr',
            ),
            3 =>
            array (
                'id' => 4,
                'name' => 'Spanish',
                'short_name' => 'es',
                'flag' => '',
                'status' => 'Active',
                'is_default' => 0,
                'direction' => 'ltr',
            ),
            4 =>
            array (
                'id' => 5,
                'name' => 'Russian',
                'short_name' => 'ru',
                'flag' => '',
                'status' => 'Active',
                'is_default' => 0,
                'direction' => 'ltr',
            ),
            5 =>
            array (
                'id' => 6,
                'name' => 'Turkish',
                'short_name' => 'tr',
                'flag' => '',
                'status' => 'Active',
                'is_default' => 0,
                'direction' => 'ltr',
            ),
            6 =>
            array (
                'id' => 7,
                'name' => 'Chinese',
                'short_name' => 'zh',
                'flag' => '',
                'status' => 'Active',
                'is_default' => 0,
                'direction' => 'ltr',
            ),
            7 =>
            array (
                'id' => 8,
                'name' => 'Portuguese',
                'short_name' => 'pt',
                'flag' => '',
                'status' => 'Active',
                'is_default' => 0,
                'direction' => 'ltr',
            )
        ));
        
        
    }
}