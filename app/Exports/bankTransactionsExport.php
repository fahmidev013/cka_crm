<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class bankTransactionsExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        
        $method = isset($_GET['method']) ? $_GET['method'] : null ;
        $from       = isset($_GET['from']) ? $_GET['from'] : null ;
        $to         = isset($_GET['to']) ? $_GET['to'] : null ;
        $mode      = isset($_GET['mode']) ? $_GET['mode'] : null ;
        $type     = isset($_GET['type']) ? $_GET['type'] : null ;
        
        $transaction = (new Transaction)->getAllTransactionCollection($from, $to, $method, $mode, $type)->orderBy('reference', 'desc');

        return $transaction;
    }

    public function headings(): array
    {
        return [
            'Reference',
            'Payment Method',
            'Type ',
            'Description',
            'Debit',
            'Credit',
            'Currency',
            'Date'
        ];
    }
    
    public function map($transaction): array
    {  
        
        return [
            $transaction->reference,
            $transaction->payment_method,
            ucwords(str_replace ('_',' ', strtolower($transaction->transaction_method))),
            $transaction->description,
            formatCurrencyAmount($this->debitCheck($transaction->amount)),
            formatCurrencyAmount($this->creditCheck($transaction->amount)),
            $transaction->currency_name,
            formatDate($transaction->transaction_date),
        ];
    }

    public function debitCheck($amount){
    	if ($amount > 0) {
            return $amount;
    	} else {
        	return '0';
        }
    }

    public function creditCheck($amount){
        if ($amount < 0) {
        	return abs($amount);
        } else {
        	return '0';
        }
    }

    
}
