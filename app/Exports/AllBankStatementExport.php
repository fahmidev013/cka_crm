<?php

namespace App\Exports;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\BankTransaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

class AllBankStatementExport implements FromQuery, WithHeadings, WithMapping
{
	public function query()
	{
		$banks = Account::with([
                           'accountType' => function ($query) {
                                $query->select('id','name');
                            },
                            'currency' => function ($query) {
                                $query->select('id','name');
                            }
                          ])->where('is_deleted','!=', 1)->select('accounts.*')->orderBy('id', 'desc');        

        return $banks;

	}

	public function headings(): array
	{
		return [
            'A/c Name',
            'A/c Number',
            'Bank Name',
            'Currency',
            'Bank Address',
            'Balance',
        ];
	}

	public function map($allBankStatement): array
	{		
		return [
            $allBankStatement->name,
            $allBankStatement->account_number,
            $allBankStatement->bank_name,
            $allBankStatement->currency->name,
            $allBankStatement->bank_address,
            formatCurrencyAmount($allBankStatement->transactions->sum('amount')),
        ];

	}
}