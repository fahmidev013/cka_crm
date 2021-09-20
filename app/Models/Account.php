<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public $timestamps = false;

    // Relation Start
    public function deposits()
    {
        return $this->hasMany("App\Models\Deposit", 'account_id');
    }

    public function transactions()
    {
        return $this->hasMany("App\Models\Transaction", 'account_id');
    }

    public function fromBankTransfers()
    {
        return $this->hasMany("App\Models\BankTransfer", 'from_account_id');
    }

    public function toBankTransfers()
    {
        return $this->hasMany("App\Models\BankTransfer", 'to_account_id');
    }
    
    public function accountType()
    {
        return $this->belongsTo("App\Models\AccountType", 'account_type_id');
    }

    public function currency()
    {
       return $this->belongsTo("App\Models\Currency", 'currency_id');
    }

    public function incomeExpenseCategory()
    {
       return $this->belongsTo("App\Models\IncomeExpenseCategory", 'gl_account_id');
    }
    // Relation End

    public function getAccounts()
    {
        $data = DB::select("select ba.*, bat.name as account_type, iec.name as gl_account_name,currency.name as currency_name, SUM(bt.amount) as balance from bank_accounts as ba
                LEFT JOIN bank_transaction as bt
                  ON ba.id = bt.account_no
                LEFT JOIN bank_account_type as bat
                  ON bat.id = ba.account_type_id
                LEFT JOIN income_expense_categories as iec
                  ON iec.id = ba.gl_account_id
                LEFT JOIN currency
                  ON currency.id = ba.currency_id
                where ba.deleted = 0
                GROUP BY bt.account_no");
        return collect($data);
    }
}
