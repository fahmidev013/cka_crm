<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    public $timestamps = false;

    public function account()
    {
        return $this->belongsTo("App\Models\Account", 'account_id');
    }

    public function incomeExpenseCategory() 
    {
      return $this->belongsTo("App\Models\IncomeExpenseCategory", 'income_expense_category_id');
    }

    public function transactionReference()
    {
      return $this->belongsTo("App\Models\TransactionReference",'transaction_reference_id');
    }

    public function getAllDeposits()
    {
          $data = DB::table('bank_transaction')
          			->leftJoin('bank_accounts', 'bank_accounts.id', '=', 'bank_transaction.account_no' )
          			->where('trans_type', 'deposit')
          			->select('bank_transaction.*', 'bank_accounts.account_name', 'bank_accounts.account_no as acc_no')
          			->orderBy('bank_transaction.created_at', 'DESC')
          			->get();
          return $data;
    }

    public function getGenerelIncome($from, $to, $currency = null, $mode)
    {
      error_reporting(E_ALL - E_NOTICE);

      $incomeStat = [];
      $months = getMonths($from, $to);
      $preference = Preference::getAll()->pluck('value', 'field')->toArray();
      $from = DbDateFormat($from);
      $to   = DbDateFormat($to);
      $currency = isset($currency) && !empty($currency) ? (int) $currency : (int) $preference['dflt_currency_id'];
      $data = DB::select(DB::raw("SELECT ic.id, ic.name as income_name, sum(d.amount) as income, DATE_FORMAT(d.transaction_date, '%b %Y') as edate, d.transaction_date as normalDate FROM income_expense_categories as ic LEFT JOIN `deposits` as d on(d.income_expense_category_id = ic.id) LEFT JOIN `accounts` as ac on(d.account_id = ac.id) WHERE ic.category_type = 'income' AND ac.currency_id = '$currency' AND d.transaction_date > '$from' AND d.transaction_date <= '$to' GROUP BY ic.id, edate ORDER BY d.transaction_date"));

      if ($mode == 'detail') {
        foreach ($data as $key => $value) {
          $incomeStat[$value->income_name][$value->edate] += $value->income;
        }
        if (!empty($incomeStat)) {
          return $incomeStat;
        }
      }

      if ($mode == 'summery') {
        foreach ($data as $key => $value) {
          $incomeStat[$value->income_name] += $value->income;
        }
        if (!empty($incomeStat)) {
          return $incomeStat;
        }
      }
    }
}
