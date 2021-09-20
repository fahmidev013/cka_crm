<?php

namespace App\Exports;

use App\Models\GeneralLedger;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class glTransactionExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $i=1;
    public function query()
    {
        
        $from          = $_GET['from'];
        $to            = $_GET['to'];
        $gl_account_no = $_GET['gl_account_no'];
        
        $general_ledgers = GeneralLedger::query();
        
        if ($from) {
          $general_ledgers->where('transaction_date', '>=', DbDateFormat($from));
        }
        
        if ($to) {
          $general_ledgers->where('transaction_date', '<=', DbDateFormat($to));
        }
        
        if ($gl_account_no) {
          $general_ledgers->where('gl_account_id', '=', $gl_account_no);
        }
        $general_ledgers = $general_ledgers->orderBy('transaction_date','DESC');

        return $general_ledgers;
    }

    public function headings(): array
    {
        return [
            'Reference',
            'GL A/C',
            'Note ',
            'Debit',
            'Credit',
            'Date'
        ];
    }
    
    public function map($transaction): array
    {  
        
        return [
            $transaction->reference->reference,
            $transaction->incomeExpenseCategory->name,
            $transaction->comment,
            $this->debitCheck($transaction->amount),
            $this->creditCheck($transaction->amount),
            formatDate($transaction->transaction_date),
        ];
    }

    public function debitCheck($amount){
        if($amount > 0){
            return $amount;
        }
        else{
            return '0';
        }
    }

    public function creditCheck($amount){
        if($amount < 0){
            return abs($amount);
        }
        else{
            return '0';
        }
    }

    
}
