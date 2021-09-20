<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TransactionReferencesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('transaction_references')->delete();
        
        \DB::table('transaction_references')->insert(array (
            0 => 
            array (
                'id' => 1,
                'object_id' => NULL,
                'reference_type' => 'OPENING_BALANCE',
                'code' => '001/2020',
            ),
            1 => 
            array (
                'id' => 2,
                'object_id' => NULL,
                'reference_type' => 'OPENING_BALANCE',
                'code' => '002/2020',
            ),
            2 => 
            array (
                'id' => 3,
                'object_id' => NULL,
                'reference_type' => 'OPENING_BALANCE',
                'code' => '003/2020',
            ),
            3 => 
            array (
                'id' => 4,
                'object_id' => NULL,
                'reference_type' => 'OPENING_BALANCE',
                'code' => '004/2020',
            ),
            4 => 
            array (
                'id' => 5,
                'object_id' => NULL,
                'reference_type' => 'DEPOSIT',
                'code' => '001/2020',
            ),
            5 => 
            array (
                'id' => 6,
                'object_id' => NULL,
                'reference_type' => 'DEPOSIT',
                'code' => '002/2020',
            ),
            6 => 
            array (
                'id' => 7,
                'object_id' => NULL,
                'reference_type' => 'DEPOSIT',
                'code' => '003/2020',
            ),
            7 => 
            array (
                'id' => 8,
                'object_id' => NULL,
                'reference_type' => 'DEPOSIT',
                'code' => '004/2020',
            ),
            8 => 
            array (
                'id' => 9,
                'object_id' => NULL,
                'reference_type' => 'DEPOSIT',
                'code' => '005/2020',
            ),
            9 => 
            array (
                'id' => 10,
                'object_id' => NULL,
                'reference_type' => 'DEPOSIT',
                'code' => '006/2020',
            ),
            10 => 
            array (
                'id' => 11,
                'object_id' => NULL,
                'reference_type' => 'DEPOSIT',
                'code' => '007/2020',
            ),
            11 => 
            array (
                'id' => 12,
                'object_id' => NULL,
                'reference_type' => 'DEPOSIT',
                'code' => '008/2020',
            ),
            12 => 
            array (
                'id' => 13,
                'object_id' => NULL,
                'reference_type' => 'TRANSFER',
                'code' => '001/2020',
            ),
            13 => 
            array (
                'id' => 14,
                'object_id' => NULL,
                'reference_type' => 'TRANSFER',
                'code' => '002/2020',
            ),
            14 => 
            array (
                'id' => 15,
                'object_id' => NULL,
                'reference_type' => 'TRANSFER',
                'code' => '003/2020',
            ),
            15 => 
            array (
                'id' => 16,
                'object_id' => NULL,
                'reference_type' => 'TRANSFER',
                'code' => '004/2020',
            ),
            16 => 
            array (
                'id' => 17,
                'object_id' => NULL,
                'reference_type' => 'TRANSFER',
                'code' => '005/2020',
            ),
            17 => 
            array (
                'id' => 18,
                'object_id' => NULL,
                'reference_type' => 'TRANSFER',
                'code' => '006/2020',
            ),
            18 => 
            array (
                'id' => 19,
                'object_id' => NULL,
                'reference_type' => 'TRANSFER',
                'code' => '007/2020',
            ),
            19 => 
            array (
                'id' => 20,
                'object_id' => NULL,
                'reference_type' => 'TRANSFER',
                'code' => '008/2020',
            ),
            20 => 
            array (
                'id' => 21,
                'object_id' => 0,
                'reference_type' => '001/2020',
                'code' => '001/2020',
            ),
            21 => 
            array (
                'id' => 22,
                'object_id' => 0,
                'reference_type' => '001/2020',
                'code' => '002/2020',
            ),
            22 => 
            array (
                'id' => 23,
                'object_id' => 0,
                'reference_type' => '001/2020',
                'code' => '002/2020',
            ),
            23 => 
            array (
                'id' => 24,
                'object_id' => 0,
                'reference_type' => '001/2020',
                'code' => '002/2020',
            ),
            24 => 
            array (
                'id' => 25,
                'object_id' => 0,
                'reference_type' => '001/2020',
                'code' => '002/2020',
            ),
            25 => 
            array (
                'id' => 26,
                'object_id' => 9,
                'reference_type' => 'INVOICE_PAYMENT',
                'code' => '001/2020',
            ),
            26 => 
            array (
                'id' => 27,
                'object_id' => 42,
                'reference_type' => 'POS_PAYMENT',
                'code' => '001/2020',
            ),
            27 => 
            array (
                'id' => 28,
                'object_id' => 9,
                'reference_type' => 'INVOICE_PAYMENT',
                'code' => '002/2020',
            ),
            28 => 
            array (
                'id' => 29,
                'object_id' => 38,
                'reference_type' => 'INVOICE_PAYMENT',
                'code' => '002/2020',
            ),
            29 => 
            array (
                'id' => 30,
                'object_id' => 38,
                'reference_type' => 'INVOICE_PAYMENT',
                'code' => '002/2020',
            ),
            30 => 
            array (
                'id' => 31,
                'object_id' => 34,
                'reference_type' => 'INVOICE_PAYMENT',
                'code' => '002/2020',
            ),
            31 => 
            array (
                'id' => 32,
                'object_id' => 18,
                'reference_type' => 'INVOICE_PAYMENT',
                'code' => '002/2020',
            ),
            32 => 
            array (
                'id' => 33,
                'object_id' => 36,
                'reference_type' => 'INVOICE_PAYMENT',
                'code' => '002/2020',
            ),
            33 => 
            array (
                'id' => 42,
                'object_id' => 36,
                'reference_type' => 'INVOICE_PAYMENT',
                'code' => '003/2020',
            ),
            34 => 
            array (
                'id' => 43,
                'object_id' => 36,
                'reference_type' => 'INVOICE_PAYMENT',
                'code' => '004/2020',
            ),
            35 => 
            array (
                'id' => 44,
                'object_id' => 1,
                'reference_type' => 'PURCHASE_PAYMENT',
                'code' => '001/2020',
            ),
            36 => 
            array (
                'id' => 45,
                'object_id' => 6,
                'reference_type' => 'PURCHASE_PAYMENT',
                'code' => '002/2020',
            ),
            37 => 
            array (
                'id' => 46,
                'object_id' => 12,
                'reference_type' => 'PURCHASE_PAYMENT',
                'code' => '003/2020',
            ),
            38 => 
            array (
                'id' => 47,
                'object_id' => 4,
                'reference_type' => 'PURCHASE_PAYMENT',
                'code' => '004/2020',
            ),
            39 => 
            array (
                'id' => 48,
                'object_id' => 14,
                'reference_type' => 'PURCHASE_PAYMENT',
                'code' => '005/2020',
            ),
            40 => 
            array (
                'id' => 49,
                'object_id' => null,
                'reference_type' => 'expense',
                'code' => '001/2020',
            ),
            41 => 
            array (
                'id' => 50,
                'object_id' => null,
                'reference_type' => 'expense',
                'code' => '002/2020',
            ),
            42 => 
            array (
                'id' => 51,
                'object_id' => null,
                'reference_type' => 'expense',
                'code' => '003/2020',
            ),
            43 => 
            array (
                'id' => 52,
                'object_id' => null,
                'reference_type' => 'expense',
                'code' => '004/2020',
            ),
            44 => 
            array (
                'id' => 53,
                'object_id' => null,
                'reference_type' => 'expense',
                'code' => '005/2020',
            ),
        ));
        
        
    }
}