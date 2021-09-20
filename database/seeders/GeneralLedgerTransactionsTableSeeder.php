<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GeneralLedgerTransactionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('general_ledger_transactions')->delete();
        
        
        
    }
}