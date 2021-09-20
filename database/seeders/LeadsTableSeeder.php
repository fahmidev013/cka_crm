<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LeadsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('leads')->delete();
        
        \DB::table('leads')->insert(array (
            0 => 
            array (
                'id' => 1,
                'first_name' => 'Amanda',
                'last_name' => 'Alverez',
                'email' => 'amandajalverez@gmail.com',
                'street' => '1077 Goodwin Avenue',
                'city' => 'Pullman',
                'state' => 'Georgia',
                'zip_code' => '99163',
                'country_id' => 1,
                'phone' => '509-334-9658',
                'website' => 'https://www.leadfeeder.com/',
                'company' => 'Schumm LLC',
                'description' => 'Needed a software.',
                'lead_status_id' => 2,
                'lead_source_id' => 3,
                'assignee_id' => 1,
                'user_id' => 1,
                'last_contact' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'last_status_change' => null,
                'last_lead_status' => null,
                'date_converted' => null,
                'date_assigned' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'is_lost' => 0,
                'is_public' => 0,
            ),
            1 => 
            array (
                'id' => 2,
                'first_name' => 'David',
                'last_name' => 'Butler',
                'email' => 'davidmbutler@teleworm.us',
                'street' => '2211 Westwood Avenue',
                'city' => 'Huntington',
                'state' => 'Florida',
                'zip_code' => '11743',
                'country_id' => 1,
                'phone' => '516-819-7160',
                'website' => 'https://www.leadfeeder.com/',
                'company' => 'Hegmann Inc',
                'description' => null,
                'lead_status_id' => 1,
                'lead_source_id' => 2,
                'assignee_id' => 1,
                'user_id' => 1,
                'last_contact' => \Carbon::now()->subDays(3)->toDateTimeString(),
                'last_status_change' => null,
                'last_lead_status' => null,
                'date_converted' => null,
                'date_assigned' => \Carbon::now()->subDays(3)->toDateTimeString(),
                'is_lost' => 0,
                'is_public' => 0,
            )
        ));
        
    }
}