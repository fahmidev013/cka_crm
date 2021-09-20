<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StockAdjustmentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('stock_adjustments')->delete();
        
        \DB::table('stock_adjustments')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 1,
                'location_id' => 1,
                'transaction_type' => 'STOCKIN',
                'total_quantity' => 10.0,
            'note' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).',
                'transaction_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 1,
                'location_id' => 2,
                'transaction_type' => 'STOCKIN',
                'total_quantity' => 31.0,
            'note' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).',
                'transaction_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
            ),
            2 => 
            array (
                'id' => 3,
                'user_id' => 1,
                'location_id' => 2,
                'transaction_type' => 'STOCKOUT',
                'total_quantity' => 22.0,
                'note' => 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.',
                'transaction_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
            ),
            3 => 
            array (
                'id' => 4,
                'user_id' => 1,
                'location_id' => 3,
                'transaction_type' => 'STOCKIN',
                'total_quantity' => 3.0,
                'note' => 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.',
                'transaction_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
            ),
            4 => 
            array (
                'id' => 5,
                'user_id' => 1,
                'location_id' => 1,
                'transaction_type' => 'STOCKOUT',
                'total_quantity' => 5.0,
                'note' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'transaction_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
            ),
        ));
        
        
    }
}