<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaymentMethodsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payment_methods')->delete();
        
        \DB::table('payment_methods')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Paypal',
                'mode' => 'sandbox',
                'client_id' => 'AavnhZLU2Z3AKlVkdDCftpvOAWaH8acCzsBciHQz0Cif1aE-K0-BTmcean862hlYlnSeGwRvV6Dnjr6N',
                'consumer_key' => NULL,
                'consumer_secret' => 'EC5gE_BS6oHlQUvCM9HW_KPPT8CW2tSsf4VbWj7hdKcGISfu2teTH3OmNpyoRslFUplOnDGwNKUQI7DA',
                'approve' => NULL,
                'is_default' => 0,
                'is_active' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Bank',
                'mode' => NULL,
                'client_id' => '1',
                'consumer_key' => NULL,
                'consumer_secret' => NULL,
                'approve' => 'manual',
                'is_default' => 1,
                'is_active' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Stripe',
                'mode' => NULL,
                'client_id' => NULL,
                'consumer_key' => 'pk_test_F6LAUawJNJFrdtKWPOcV60VV00NGAE6hgK',
                'consumer_secret' => 'sk_test_oJJYy5TYtZNxr4erRlxIUE0K00vMlXcl4E',
                'approve' => NULL,
                'is_default' => 0,
                'is_active' => 1,
            ),
        ));
        
        
    }
}