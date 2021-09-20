<?php

namespace App\Exports;

use App\Models\Deposit;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class bankAccountDepositsExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $to         = isset($_GET['to']) ? $_GET['to'] : null ;
        $from       = isset($_GET['to']) ? $_GET['from'] : null ;
        $account_no = isset($_GET['account_no']) ? $_GET['account_no'] : null ;

        $bankDeposit = Deposit::query()->orderBy('transaction_date', 'desc');
                     if ($from) {
                       $bankDeposit->where('transaction_date', '>=', DbDateFormat($from));
                     }
                     if ($to) {
                       $bankDeposit->where('transaction_date', '<=', DbDateFormat($to));
                     } 
                     if ($account_no && $account_no !='all') {
                       $bankDeposit->where('account_id','=', $account_no);     
                     }

        return $bankDeposit;
    }

    public function headings(): array
    {
        return [
            'A/c Name',
            'A/c Number',
            'Description',
            'Amount',
            'Currency',
            'Deposite Date',
        ];
    }

    public function map($deposite): array
    {
        return [
            isset($deposite->account) ? $deposite->account->name : '',
            isset($deposite->account) ? $deposite->account->account_number : '',
            $deposite->description,
            formatCurrencyAmount($deposite->amount),
            isset($deposite->account->currency) ? $deposite->account->currency->name : '',
            formatDate($deposite->transaction_date),
        ];
    }
}
