<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class TicketRepliesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('ticket_replies')->delete();
        
        \DB::table('ticket_replies')->insert(array (
            0 => 
            array (
                'id' => 1,
                'ticket_id' => 113,
                'user_id' => 1,
                'customer_id' => NULL,
                'date' => '2020-04-15 12:35:34',
                'message' => '<p>Hello</p>',
                'has_attachment' => 0,
            ),
            1 => 
            array (
                'id' => 2,
                'ticket_id' => 113,
                'user_id' => NULL,
                'customer_id' => 1,
                'date' => '2020-04-15 14:43:03',
                'message' => '<p>Hi</p>',
                'has_attachment' => 0,
            ),
            2 => 
            array (
                'id' => 3,
                'ticket_id' => 113,
                'user_id' => 1,
                'customer_id' => NULL,
                'date' => '2020-04-15 14:43:43',
                'message' => '<p>How can i help you?</p>',
                'has_attachment' => 0,
            ),
            3 => 
            array (
                'id' => 4,
                'ticket_id' => 113,
                'user_id' => NULL,
                'customer_id' => 1,
                'date' => '2020-04-15 14:44:11',
                'message' => '<p>Give instruction how to set up the environment for this project.</p>',
                'has_attachment' => 0,
            ),
            4 => 
            array (
                'id' => 5,
                'ticket_id' => 114,
                'user_id' => 1,
                'customer_id' => NULL,
                'date' => '2020-04-15 14:48:43',
                'message' => '<p>purchase</p>',
                'has_attachment' => 0,
            ),
            5 => 
            array (
                'id' => 6,
                'ticket_id' => 115,
                'user_id' => 1,
                'customer_id' => NULL,
                'date' => '2020-04-15 14:49:54',
                'message' => '<p>details of vRent</p>',
                'has_attachment' => 0,
            ),
        ));
        
        
    }
}