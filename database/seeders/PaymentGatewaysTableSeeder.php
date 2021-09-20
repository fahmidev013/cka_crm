<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaymentGatewaysTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payment_gateways')->delete();
        
        \DB::table('payment_gateways')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'username',
                'value' => 'techvillage_business_api1.gmail.com',
                'site' => 'PayPal',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'password',
                'value' => '9DDYZX2JLA6QL668',
                'site' => 'PayPal',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'signature',
                'value' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31ABayz5pdk84jno7.Udj6-U8ffwbT',
                'site' => 'PayPal',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'mode',
                'value' => 'sandcard',
                'site' => 'PayPal',
            ),
            4 => 
            array (
                'id' => 6,
                'name' => 'bank_account_id',
                'value' => '1',
                'site' => 'PayPal',
            ),
            5 => 
            array (
                'id' => 11,
                'name' => 'bank_account_id',
                'value' => '1',
                'site' => 'Bank',
            ),
        ));
        
        
    }
}