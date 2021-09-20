<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class StockTransfersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('stock_transfers')->delete();
        
        \DB::table('stock_transfers')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 1,
                'source_location_id' => 1,
                'destination_location_id' => 3,
                'note' => NULL,
                'quantity' => 20.0,
                'transfer_date' => '2020-04-08',
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 1,
                'source_location_id' => 2,
                'destination_location_id' => 3,
                'note' => NULL,
                'quantity' => 14.0,
                'transfer_date' => '2020-04-08',
            ),
            2 => 
            array (
                'id' => 3,
                'user_id' => 1,
                'source_location_id' => 1,
                'destination_location_id' => 2,
                'note' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries',
                'quantity' => 3.0,
                'transfer_date' => '2020-04-13',
            ),
            3 => 
            array (
                'id' => 4,
                'user_id' => 1,
                'source_location_id' => 1,
                'destination_location_id' => 3,
                'note' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries',
                'quantity' => 8.0,
                'transfer_date' => '2020-04-13',
            ),
            4 => 
            array (
                'id' => 5,
                'user_id' => 1,
                'source_location_id' => 2,
                'destination_location_id' => 1,
            'note' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).',
                'quantity' => 5.0,
                'transfer_date' => '2020-04-13',
            ),
        ));
        
        
    }
}