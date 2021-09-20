<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\PurchaseOrder;

class Expense extends Model
{
    public $timestamps = false;

    // Relation
    public function transaction()
    {
       return $this->belongsTo("App\Models\Transaction", 'transaction_id');
    }

    public function transactionReference()
    {
       return $this->belongsTo("App\Models\TransactionReference",'transaction_reference_id');
    }

    public function incomeExpenseCategory() 
    {
      return $this->belongsTo("App\Models\IncomeExpenseCategory", 'income_expense_category_id');
    }

    public function incomeExpenseCurrency() 
    {
      return $this->belongsTo("App\Models\Currency", 'currency_id');
    }

    public function paymentMethod() 
    {
      return $this->belongsTo("App\Models\PaymentMethod", 'payment_method_id');
    }
    // End Relation
    
    public function getAllExpensesById($from, $to, $account_no)
    {
        $from = DbDateFormat($from);
        $to   = DbDateFormat($to);
        if ($account_no != 'all') {
          $data = DB::table('transactions')
                ->leftJoin('accounts', 'accounts.id', '=', 'transactions.account_no' )
                ->where('transactions.transaction_type_name', 'expense')
                ->where('transactions.account_id', $account_no)
                ->whereDate('transactions.transaction_date', '>=', $from)
                ->whereDate('transactions.transaction_date', '<=', $to)
                ->select('transactions.*', 'accounts.name', 'accounts.account_number as acc_no');
        } else {
           $data = DB::table('transactions')
                ->leftJoin('accounts', 'accounts.id', '=', 'transactions.account_no' )
                ->where('transaction_type_name', 'expense')
                ->whereDate('transactions.transaction_date', '>=', $from)
                ->whereDate('transactions.transaction_date', '<=', $to)
                ->select('transactions.*', 'accounts.name', 'accounts.account_number as acc_no');
        }

        return $data;
    }
    
    public function getExpenseStat($from, $to, $currency = null)
    {
      error_reporting(E_ALL - E_NOTICE);

      $expenseStat = [];
      $preference = Preference::getAll()->pluck('value', 'field')->toArray();
      $from = DbDateFormat($from);
      $to   = DbDateFormat($to);
      $currency = isset($currency) && !empty($currency) ? (int) $currency : (int) $preference['dflt_currency_id'];
      $data = DB::select(DB::raw("SELECT ec.id, ec.name as expense_name, sum(e.amount) as expense, DATE_FORMAT(e.transaction_date, '%b %Y') as edate, e.transaction_date as normalDate FROM income_expense_categories as ec LEFT JOIN `expenses` as e on(e.income_expense_category_id = ec.id) WHERE ec.category_type = 'expense' AND e.currency_id = '$currency' AND e.transaction_date > '$from' AND e.transaction_date <= '$to' GROUP BY ec.id, edate ORDER BY e.transaction_date"));

      foreach ($data as $key => $value) {
        $expenseStat[$value->expense_name][$value->edate] += $value->expense;
      }
      if (!empty($expenseStat)) {
        return ['expenses' => $expenseStat];
      }
    }

    public function getGenerelExpenses($from = null, $to = null, $currency = null)
    {
      error_reporting(E_ALL - E_NOTICE);

      $expenseStat = [];
      $preference = Preference::getAll()->pluck('value', 'field')->toArray();
      $currency = isset($currency) && !empty($currency) ? (int) $currency : (int) $preference['dflt_currency_id'];
      if (!empty($from) && !empty($to)) {
        $extra = " AND e.transaction_date > '". $from ."' AND e.transaction_date <= '". $to ."'";
      }
      $data = DB::select(DB::raw("SELECT ec.id, ec.name as expense_name, sum(e.amount) as expense, DATE_FORMAT(e.transaction_date, '%b %Y') as edate, e.transaction_date as normalDate FROM income_expense_categories as ec LEFT JOIN `expenses` as e on(e.income_expense_category_id = ec.id) WHERE ec.category_type = 'expense' AND e.currency_id = " . $currency . $extra . "  GROUP BY ec.id, edate ORDER BY e.transaction_date"));

      foreach ($data as $key => $value) {
        $expenseStat[$value->expense_name][$value->edate] += $value->expense;
      }
      if (!empty($expenseStat)) {
        return ['expenses' => $expenseStat];
      } else {
        return ['expenses' => []];
      }
    }

    public function getPurchaseExpenses($from = null, $to = null, $currency = null)
    {
      $data = [];
      $preference = Preference::getAll()->pluck('value', 'field')->toArray();
      
      $data = PurchaseOrder::with(['currency:id,name,symbol']);

      if (!empty($from) && !empty($to)) {
        $data->where('order_date', '>=', $from)->where('order_date', '<=', $to); 
      }
      if (!empty($currency)) {
        $data->where('currency_id', '=', (int) $currency);
      }
      return $data;
    }
}
