<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class PaymentTermsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payment_terms')->delete();
        
        \DB::table('payment_terms')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Net 7',
                'days_before_due' => 7,
                'is_default' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Net 10',
                'days_before_due' => 10,
                'is_default' => 0,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Net 30',
                'days_before_due' => 30,
                'is_default' => 0,
            ),
        ));
        
        
    }
}