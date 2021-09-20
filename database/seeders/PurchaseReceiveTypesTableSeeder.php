<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PurchaseReceiveTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('purchase_receive_types')->delete();
        
        \DB::table('purchase_receive_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'receive_type' => 'auto',
                'is_default' => 0,
            ),
            1 => 
            array (
                'id' => 2,
                'receive_type' => 'manually',
                'is_default' => 0,
            ),
        ));
        
        
    }
}