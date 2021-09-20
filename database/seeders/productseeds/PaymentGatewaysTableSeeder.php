<?php

namespace Database\Seeders\ProductSeeds;

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
                'value' => 'example_business_api1.gmail.com',
                'site' => 'PayPal',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'password',
                'value' => 'xxxyyyzzz',
                'site' => 'PayPal',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'signature',
                'value' => 'AAAA-BBBB-CCCC-DDDD',
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