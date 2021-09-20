<?php

namespace App\Models;
use App\Http\Start\Helpers;
use DB;
use Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\IncomeExpenseCategory;

class Transaction extends Model
{
    public $timestamps = false;

    public function account()
    {
       return $this->belongsTo("App\Models\Account", 'account_id');
    }
    public function expenses()
    {
        return $this->hasMany("App\Models\Expense", 'transaction_id');
    }

    public function supplierTransactions()
    {
        return $this->hasMany("App\Models\SupplierTransaction", 'transaction_reference_id', 'transaction_reference_id');
    }

    public function deposit()
    {
        return $this->belongsTo("App\Models\Deposit", 'transaction_reference_id', 'transaction_reference_id');
    }

    public function transactionReference()
    {
        return $this->belongsTo("App\Models\TransactionReference");
    }

    public function paymentMethod()
    {
        return $this->belongsTo('App\Models\PaymentMethod', 'payment_method_id');
    }

    public function currency()
    {
      return $this->belongsTo("App\Models\Currency",'currency_id');
    }

    public function getAllTransactionCollection($from, $to, $method, $mode, $type)
    {

      $result = DB::table('transactions')
      ->leftJoin('accounts', 'accounts.id', '=', 'transactions.account_id')
      ->leftJoin('payment_methods', 'payment_methods.id', '=', 'transactions.payment_method_id')
      ->leftJoin('currencies','transactions.currency_id', '=', 'currencies.id')
      ->leftJoin('transaction_references','transaction_references.id', '=', 'transactions.transaction_reference_id')
      ->select('transactions.*','transaction_references.code as reference', 'payment_methods.name as payment_method', 'accounts.id as account_id', 'accounts.name', 'accounts.account_number as acc_no', 'accounts.is_deleted', 'currencies.name as currency_name', 'currencies.symbol as currency_symbol');

      if (!empty($from) && !empty($to)) {
        $result ->where('transactions.transaction_date', '>=', DbDateFormat($from))
        ->where('transactions.transaction_date', '<=', DbDateFormat($to));
      }
      if (!empty($method)) {
        $result->where('payment_methods.id', '=', $method);
      }
      if (!empty($mode) && $mode == 1) {
        $result->where('transactions.amount', '>', 0);
      }
      if (!empty($mode) && $mode == 2) {
        $result->where('transactions.amount', '<', 0);
      }
      if (!empty($type)) {
        $result->where('transactions.transaction_method', $type);
      }

      if (Helpers::has_permission(Auth::user()->id, 'own_transaction')) {
        $id = Auth::user()->id;
        $result->where('transactions.user_id', $id);
      }

      return $result;
    }

    public function getAllTransfers()
    {
          $data = DB::table('transactions')
                  ->where('transaction_type', 'cash-out-by-transfer')
                  ->orWhere('transaction_type', 'cash-in-by-transfer')
                  ->leftJoin('accounts', 'accounts.id', '=', 'transactions.account_no')
                  ->select('transactions.*', 'bank_accounts.account_name', 'bank_accounts.account_no as acc_no')
                  ->orderBy('transactions.created_at', 'DESC')
                  ->get();

          return $data;
    }

    public function getExpenseReport($year)
    {
      $data = DB::select("SELECT bt.*, iec.name FROM(SELECT transaction_type, month(transaction_date) AS month,SUM(amount) as amount from transactions WHERE YEAR(transaction_date)=$year GROUP BY month, transaction_type)bt RIGHT JOIN(SELECT * FROM income_expense_categories WHERE category_type NOT IN ('income'))iec ON bt.transaction_type = iec.id");

      return $data;
    }

    public function getIncomeReport($year)
    {
      $data = DB::select("SELECT bt.*,iec.name FROM(SELECT transaction_type,month(transaction_date) AS month,SUM(amount) as amount from transactions WHERE YEAR(transaction_date)=$year AND transaction_type_name IN ('deposit','cash-in-by-sale') GROUP BY month, transaction_type)bt LEFT JOIN(SELECT * FROM income_expense_categories WHERE category_type IN ('income'))iec ON bt.transaction_type = iec.id");

      return $data;
    }

    public function insertIncomeCategory()
    {
      $category = IncomeExpenseCategory::firstOrCreate(['name' => 'PURCHASE_PAYMENT']);
      $category->update(['name' => 'PURCHASE_PAYMENT', 'category_type' => 'income']);

      $category = IncomeExpenseCategory::firstOrCreate(['name' => 'DEPOSIT']);
      $category->update(['name' => 'DEPOSIT', 'category_type' => 'income']);
    }

    /**
     * [getIncomeStat description]
     * @param  [type] $from     [description]
     * @param  [type] $to       [description]
     * @param  [type] $currency [description]
     * @return [type]           [description]
     */
    public function getDepositStat($from, $to, $currency = null)
    {
      error_reporting(E_ALL - E_NOTICE);
      $amount = 0;
      $from = DbDateFormat($from);
      $to   = DbDateFormat($to);
      $incomeStat = [];
      $query = $this->select('amount', 'transaction_date', 'transaction_type')->whereIn('transaction_type', ['deposit', 'Cash-in']);
      $query->whereBetween('transaction_date', [$from, $to]);
      $data = $query->where('currency_id', $currency);
      $dataArray = $data->get()->toArray();

      foreach ($dataArray as $key => $value) {
        $currDate = date('M Y', strtotime($value['transaction_date']));
        $amount = (double) $value['amount'];
        if ($value['transaction_type'] == 'deposit' || $value['transaction_type'] == 'Cash-in') {
          $incomeStat['Deposit'][$currDate] += $amount;
        }
      }
      return $incomeStat;
    }


    public function getExpenseYears()
    {
      $year = array();
      $data = DB::select("SELECT DISTINCT(bt.year) FROM(SELECT transaction_type, year(transaction_date)as year from transactions)bt LEFT JOIN(SELECT * FROM income_expense_categories WHERE category_type NOT IN ('income'))iec ON bt.transaction_type = iec.id");

      if ( !empty($data)) {
        foreach ($data as $key => $value) {
          if (! empty($value->year)) {
            $year[$key] = $value->year;
          }
        }
      }
      return $year;
    }

    public function getIncomeYears()
    {
      $year = array();
      $data = DB::select("SELECT DISTINCT(bt.year) FROM(SELECT transaction_type,year(transaction_date)as year from transactions)bt LEFT JOIN(SELECT * FROM income_expense_categories WHERE category_type IN ('income'))iec ON bt.transaction_type = iec.id");

      if (!empty($data)) {
      foreach ($data as $key => $value) {
        if (!empty($value->year)) {
        $year[$key] = $value->year;
       }
      }}
      return $year;
    }

    public function getOpenInvoiceAmount()
    {
      $amount = 0;
      $invoicedAmount = DB::table('sales_orders')
            ->where('order_reference', '!=', NULL)
            ->sum('total');
      $paidAmount = DB::table('payment_history')->sum('amount');

      $amount = ($invoicedAmount-$paidAmount);

      return $amount;
    }

    public function lastThirtyDaysPaymentAmount(){
      $amount = 0;
      $today = date('Y-m-d H:i:s');
      $preDay = date('Y-m-d H:i:s', strtotime("-30 days"));

      $paidAmount = DB::select("SELECT SUM(amount) as amount FROM payment_history WHERE payment_date BETWEEN '$preDay' AND '$today'");
      if (!empty($paidAmount[0]->amount) ) {
        $amount = $paidAmount[0]->amount;
      }
      return $amount;
    }

    public function overDueAmount()
    {
      $amount = 0;
      $today =date('Y-m-d');
      $paidAmounts = DB::select("SELECT so.ord_date, DATE_ADD(so.ord_date, INTERVAL ipt.days_before_due DAY) as due_date, so.total, so.paid_amount FROM sales_orders as so
        LEFT JOIN invoice_payment_terms as ipt
          ON ipt.id = so.payment_term
        WHERE so.order_reference_id != 0");

      if (!empty($paidAmounts)) {
        foreach ($paidAmounts as $value) {
          if ($value->due_date<$today) {
          $due = ($value->total-$value->paid_amount);
          $amount += $due;
         }
        }
      }

      return $amount;
    }

    public function lastThirtyDaysExpenseAmount()
    {
      $amount = 0;
      $today = date('Y-m-d H:i:s');
      $preDay = date('Y-m-d H:i:s', strtotime("-30 days"));
      $expenseAmount = DB::select("SELECT SUM(amount) as amount FROM bank_transaction WHERE created_at BETWEEN '$preDay' AND '$today' AND trans_type='expense'");

      if (!empty($expenseAmount[0]->amount) ) {
        $amount = abs($expenseAmount[0]->amount);
      }

      return $amount;
    }

    public function expenseAmountByCategory()
    {
        $data = DB::select("SELECT bt.category_id, iec.name, SUM(ABS(bt.amount)) as amount FROM `bank_transaction` as bt
          LEFT JOIN income_expense_categories as iec
          ON iec.id = bt.category_id
          WHERE bt.trans_type = 'expense'
          GROUP BY bt.category_id
          ORDER BY amount DESC
          ");

        return $data;
    }

    public function getTotalIncome()
    {
      $data = DB::table('bank_transaction')
              ->where('trans_type','deposit')
              ->orWhere('trans_type','cash-in-by-sale')
              ->sum('amount');

      return $data;
    }

    public function getTotalExpense()
    {
      $data = DB::table('bank_transaction')
              ->where('trans_type','expense')
              ->sum('amount');
      return $data;
    }

    public function getsixMonthExpense()
    {
      $today = date('Y-m-d');
      $previousDate = previousDate();

      $data = DB::select("SELECT SUM(amount) as amount,month,year FROM(SELECT amount,trans_date,MONTH(trans_date) as month,YEAR(trans_date) as year  FROM bank_transaction
        WHERE trans_date BETWEEN '$previousDate' AND '$today' AND trans_type = 'expense')expense GROUP BY month,year");
        return $data;
    }
    public function getsixMonthIncome()
    {
      $today = date('Y-m-d');
      $previousDate = previousDate();

     $data = DB::select("SELECT SUM(amount) as amount,month,year FROM(SELECT amount,trans_date,MONTH(trans_date) as month,YEAR(trans_date) as year  FROM bank_transaction
        WHERE trans_date BETWEEN '$previousDate' AND '$today' AND trans_type IN ('deposit','cash-in-by-sale'))income GROUP BY month,year");
        return $data;
    }

    public function Prev_lastThirtyDaysIncomes()
    {
      $getLastOneMonthDates = getLastOneMonthDates();
      $final                = [];
      $data_map             = array();
      $today                = date('Y-m-d');
      $previousDate         = date("Y-m-d", strtotime ("-30 day",strtotime(date('d-m-Y') )));
      $data                 = DB::select("SELECT SUM(amount) as amount,trans_date,MONTH(trans_date) as month,DAY(trans_date) as day FROM bank_transaction WHERE trans_date BETWEEN '$previousDate' AND '$today' AND trans_type IN ('deposit','cash-in-by-sale') GROUP BY trans_date");

      if (!empty($data)) {
        foreach ($data as $key => $value) {
         $data_map[$value->day][$value->month] = abs($value->amount);
        }

        $dataArray = [];
        $i         = 0;
        foreach ($getLastOneMonthDates as $key => $value) {
          $date                   = explode('-', $value);
          $td                     = (int) $date[0];
          $tm                     = (int) $date[1];
          $dataArray[$i]['day']   =  $date[0];
          $dataArray[$i]['month'] =  $date[1];
          if (isset($data_map[$td][$tm])) {
            $dataArray[$i]['amount'] =  abs($data_map[$td][$tm]);
          }else{
            $dataArray[$i]['amount'] =  0;
         }
          $i++;
        }

        foreach($dataArray as $key=>$res){
          $final[$key] = abs($res['amount']);
        }

      }

      return $final;
    }

    // New Code Added by Aminul Islam starts here
    public function lastThirtyDaysIncomes()
    {
      $getLastOneMonthDates = getLastOneMonthDates();
      $final                = [];
      $data_map             = array();
      $today                = date('Y-m-d');
      $previousDate         = date("Y-m-d", strtotime ("-30 day",strtotime(date('d-m-Y') )));
      $data                 = DB::select("SELECT sod.*,so.ord_date,MONTH(so.ord_date) as month,DAY(so.ord_date) as day from sales_order_details as sod left join sales_orders as so on so.id=sod.sales_order_id WHERE
                              so.trans_type=202 AND sod.trans_type=202 AND so.ord_date BETWEEN '$previousDate' AND '$today'");

      $macDetails           = $this->getMacDetails();
      $priceDetails         = DB::select(DB::raw("SELECT sales_orders.ord_date,count(infos.sales_order_id) as total_order,SUM(infos.qty) as qty,infos.item_id,
      SUM(infos.purchase_price_excl_tax) as purchase_price_excl_tax,
      SUM(infos.purchase_price_incl_tax) as purchase_price_incl_tax,
      SUM(infos.sale_price_excl_tax) as sale_price_excl_tax,
      SUM(infos.sale_price_incl_tax) as sale_price_incl_tax

      FROM sales_orders
        LEFT JOIN(SELECT sale_purch_detail.sales_order_id,sale_purch_detail.item_id, count(sale_purch_detail.sales_order_id)as total_order,
        SUM(sale_purch_detail.quantity)as qty,
        SUM(sale_purch_detail.sale_price_excl_tax) as sale_price_excl_tax,
          SUM(sale_purch_detail.purchase_price_excl_tax)as purchase_price_excl_tax,
        SUM(sale_purch_detail.sale_price_incl_tax) as sale_price_incl_tax,
        SUM(sale_purch_detail.purchase_price_incl_tax)as purchase_price_incl_tax
        FROM(
          SELECT sd.*,
          (sd.quantity*pd.purchase_rate_incl_tax)as purchase_price_incl_tax,
          (sd.quantity*pd.purchase_rate_excl_tax)as purchase_price_excl_tax
           FROM(SELECT sod.sales_order_id,sod.item_id,sod.quantity,(sod.unit_price*sod.quantity-(sod.unit_price*sod.quantity*discount_percent)/100) as sale_price_excl_tax,((sod.unit_price*sod.quantity-(sod.unit_price*sod.quantity*discount_percent)/100)+((sod.unit_price*sod.quantity-(sod.unit_price*sod.quantity*discount_percent)/100)*itt.tax_rate/100)) as sale_price_incl_tax
          FROM sales_order_details as sod
          LEFT JOIN item_tax_types as itt
          ON itt.id = sod.tax_type_id

          WHERE sod.trans_type = 202)sd
          LEFT JOIN(SELECT pod.item_id,ROUND(SUM(pod.unit_price*pod.quantity_received)/SUM(pod.quantity_received),2) as purchase_rate_excl_tax,ROUND(SUM(pod.unit_price*pod.quantity_received+pod.unit_price*pod.quantity_received*itt.tax_rate/100)/SUM(pod.quantity_received),2) as purchase_rate_incl_tax FROM purch_order_details as pod LEFT JOIN item_tax_types as itt ON itt.id = pod.tax_type_id GROUP BY pod.item_id)pd
          ON sd.item_id = pd.item_id


        )sale_purch_detail
        GROUP BY sale_purch_detail.sales_order_id)infos
        ON infos.sales_order_id = sales_orders.id
        WHERE sales_orders.trans_type = 202
        GROUP BY sales_orders.ord_date
        ORDER BY sales_orders.ord_date DESC
        "));

     for($i=0;$i<count($priceDetails);$i++){

          for($j=0;$j<count($data);$j++){

              if ($data[$j]->ord_date==$priceDetails[$i]->ord_date) {
                  $data[$j]->price = $priceDetails[$i]->sale_price_excl_tax;
                  $data[$j]->cofds = $priceDetails[$i]->purchase_price_incl_tax;
              }
          }
      }

      for($k=0;$k<count($data);$k++){
          $data[$k]->income = $data[$k]->price;
          $data[$k]->cogs   = $data[$k]->cofds;
      }

      if (!empty($data)) {
        foreach ($data as $key => $value) {
         $data_map[$value->day][$value->month] = abs($value->income);

        }

        $dataArray = [];
        $i         = 0;
        foreach ($getLastOneMonthDates as $key => $value) {
          $date                   = explode('-', $value);
          $td                     = (int) $date[0];
          $tm                     = (int) $date[1];
          $dataArray[$i]['day']   =  $date[0];
          $dataArray[$i]['month'] =  $date[1];
          if (isset($data_map[$td][$tm])) {
            $dataArray[$i]['amount'] =  abs($data_map[$td][$tm]);
          } else {
            $dataArray[$i]['amount'] =  0;
         }
          $i++;
        }

        foreach ($dataArray as $key=>$res) {
          $final[$key] = abs(str_replace(',','',number_format($res['amount'],2)));
        }

      }
      return $final;
    }

    public function lastThirtyDaysTotalIncomes($a, $b)
    {
      $c = array_map(function () {
        return array_sum(func_get_args());
      }, $a, $b);

      return $c;
    }
    public function lastThirtyDaysExpenses()
    {
      $getLastOneMonthDates = getLastOneMonthDates();
      $final                = [];
      $data_map             = array();
      $today                = date('Y-m-d');
      $previousDate         = date("Y-m-d", strtotime ("-30 day",strtotime(date('d-m-Y') )));
      $data                 = DB::select("SELECT sod.*,so.ord_date,MONTH(so.ord_date) as month,DAY(so.ord_date) as day from sales_order_details as sod left join sales_orders as so on so.id=sod.sales_order_id WHERE
                              so.trans_type=202 AND sod.trans_type=202 AND so.ord_date BETWEEN '$previousDate' AND '$today'");
      $macDetails           = $this->getMacDetails();
      $priceDetails         = DB::select(DB::raw("SELECT sales_orders.ord_date,count(infos.sales_order_id) as total_order,SUM(infos.qty) as qty,infos.item_id,
      SUM(infos.purchase_price_excl_tax) as purchase_price_excl_tax,
      SUM(infos.purchase_price_incl_tax) as purchase_price_incl_tax,
      SUM(infos.sale_price_excl_tax) as sale_price_excl_tax,
      SUM(infos.sale_price_incl_tax) as sale_price_incl_tax

      FROM sales_orders
        LEFT JOIN(SELECT sale_purch_detail.sales_order_id,sale_purch_detail.item_id, count(sale_purch_detail.sales_order_id)as total_order,
        SUM(sale_purch_detail.quantity)as qty,
        SUM(sale_purch_detail.sale_price_excl_tax) as sale_price_excl_tax,
          SUM(sale_purch_detail.purchase_price_excl_tax)as purchase_price_excl_tax,
        SUM(sale_purch_detail.sale_price_incl_tax) as sale_price_incl_tax,
        SUM(sale_purch_detail.purchase_price_incl_tax)as purchase_price_incl_tax
        FROM(
          SELECT sd.*,
          (sd.quantity*pd.purchase_rate_incl_tax)as purchase_price_incl_tax,
          (sd.quantity*pd.purchase_rate_excl_tax)as purchase_price_excl_tax
           FROM(SELECT sod.sales_order_id,sod.item_id,sod.quantity,(sod.unit_price*sod.quantity-(sod.unit_price*sod.quantity*discount_percent)/100) as sale_price_excl_tax,((sod.unit_price*sod.quantity-(sod.unit_price*sod.quantity*discount_percent)/100)+((sod.unit_price*sod.quantity-(sod.unit_price*sod.quantity*discount_percent)/100)*itt.tax_rate/100)) as sale_price_incl_tax
          FROM sales_order_details as sod
          LEFT JOIN item_tax_types as itt
          ON itt.id = sod.tax_type_id

          WHERE sod.trans_type = 202)sd
          LEFT JOIN(SELECT pod.item_id,ROUND(SUM(pod.unit_price*pod.quantity_received)/SUM(pod.quantity_received),2) as purchase_rate_excl_tax,ROUND(SUM(pod.unit_price*pod.quantity_received+pod.unit_price*pod.quantity_received*itt.tax_rate/100)/SUM(pod.quantity_received),2) as purchase_rate_incl_tax FROM purch_order_details as pod LEFT JOIN item_tax_types as itt ON itt.id = pod.tax_type_id GROUP BY pod.item_id)pd
          ON sd.item_id = pd.item_id


        )sale_purch_detail
        GROUP BY sale_purch_detail.sales_order_id)infos
        ON infos.sales_order_id = sales_orders.id
        WHERE sales_orders.trans_type = 202
        GROUP BY sales_orders.ord_date
        ORDER BY sales_orders.ord_date DESC
        "));

     for ($i=0;$i<count($priceDetails);$i++) {

          for ($j=0;$j<count($data);$j++) {

              if ($data[$j]->ord_date==$priceDetails[$i]->ord_date) {
                  $data[$j]->price = $priceDetails[$i]->sale_price_excl_tax;
                  $data[$j]->cofds = $priceDetails[$i]->purchase_price_incl_tax;
              }
          }
      }
      if (!empty($data)) {
        foreach ($data as $key => $value) {
         $data_map[$value->day][$value->month] = abs($value->cofds);
        }
        $dataArray = [];
        $i         = 0;
        foreach ($getLastOneMonthDates as $key => $value) {
          $date                   = explode('-', $value);
          $td                     = (int) $date[0];
          $tm                     = (int) $date[1];
          $dataArray[$i]['day']   =  $date[0];
          $dataArray[$i]['month'] =  $date[1];
          if (isset($data_map[$td][$tm])) {
            $dataArray[$i]['amount'] =  abs($data_map[$td][$tm]);
          } else {
            $dataArray[$i]['amount'] =  0;
         }
          $i++;
        }
        foreach($dataArray as $key=>$res){
          $final[$key] = abs(str_replace(',','',number_format($res['amount'],2)));
        }

      }
      return $final;
    }
    // New Code Added by Aminul Islam ends here

    public function Prev_lastThirtyDaysExpenses()
    {
      $getLastOneMonthDates = getLastOneMonthDates();
      $final                = [];
      $data_map             = array();
      $today                = date('Y-m-d');
      $previousDate         = date("Y-m-d", strtotime ("-30 day",strtotime(date('d-m-Y') )));
      $data                 = DB::select("SELECT SUM(amount) as amount,trans_date,MONTH(trans_date) as month,DAY(trans_date) as day FROM bank_transaction WHERE trans_date BETWEEN '$previousDate' AND '$today' AND trans_type IN ('expense') GROUP BY trans_date");

      if (!empty($data)) {
        foreach ($data as $key => $value) {
         $data_map[$value->day][$value->month] = abs($value->amount);
        }

        $dataArray = [];
        $i = 0;
        foreach ($getLastOneMonthDates as $key => $value) {
          $date                   = explode('-', $value);
          $td                     = (int) $date[0];
          $tm                     = (int) $date[1];
          $dataArray[$i]['day']   =  $date[0];
          $dataArray[$i]['month'] =  $date[1];
          if (isset($data_map[$td][$tm])) {
            $dataArray[$i]['amount'] =  $data_map[$td][$tm];
          } else {
            $dataArray[$i]['amount'] =  0;
         }
          $i++;
        }


        foreach ($dataArray as $key=>$res) {
          $final[$key] = $res['amount'];
        }

      }
      return $final;
    }

    public function lastThirtyDaysTotalExpenses($a,$b)
    {
       $c = array_map(function () {
            return array_sum(func_get_args());
          }, $a, $b);

       return $c;
    }

    public function latestIncomeList()
    {
      $data = DB::table('bank_transaction')
            ->where('trans_type','deposit')
            ->orWhere('trans_type','cash-in-by-sale')
            ->orderBy('id','DESC')
            ->take(5)
            ->get();

      return $data;
    }

    public function latestIncomeExpenses()
    {
      $data = DB::table('bank_transaction')
            ->where('trans_type','expense')
            ->orderBy('id','DESC')
            ->take(5)
            ->get();
      return $data;
    }

  public function getTransactionByAccountId($from, $to, $account_id, $mode, $type)
  {
    $transactionList = Transaction::where('account_id', $account_id)->orderBy('transaction_date','ASC');
      if (!empty($from)) {
        $transactionList = $transactionList->whereDate('transaction_date', '>=', DbDateFormat($from));
      }
      if (!empty($to)) {
        $transactionList = $transactionList->whereDate('transaction_date', '<=', DbDateFormat($to));
      }
      if (!empty($mode) && $mode == 1) {
        $transactionList->where('amount', '>', 0);
      }
      if (!empty($mode) && $mode == 2) {
        $transactionList->where('amount', '<=', 0);
      }
      if (!empty($type)) {
        $transactionList->where('transaction_method', $type);
      }

      return $transactionList;
  }

  public function incomeVsExpense($year)
  {
    $data = [];
    $income_map = [];
    $expense_map = [];
    $incomeFinal = [];
    $expenseFinal = [];
    $finalArray = [];
    $monthList = DB::table('months')->pluck('name','id');
    // Income Start
    $incomeArray = DB::SELECT("SELECT month(transaction_date) AS month,SUM(amount) as amount from transactions WHERE transaction_type_name IN ('deposit','cash-in-by-sale') AND YEAR(transaction_date)='$year' GROUP BY month");

    foreach ($incomeArray as $key => $income) {
      $income_map[$income->month] = $income->amount;
    }
    $counter = 0;
    foreach ($monthList as $i => $month) {
      if (isset($income_map[$i])) {
        $incomeFinal[$counter]['amount'] = $income_map[$i];
        $incomeFinal[$counter]['month'] = $month;
      } else {
        $incomeFinal[$counter]['amount'] = 0;
        $incomeFinal[$counter]['month'] = $month;
      }
      $counter++;
    }


    // Expense Start
    $expenseArray = DB::SELECT("SELECT month(transaction_date) AS month,SUM(amount) as amount from transactions WHERE transaction_type_name IN ('expense') AND YEAR(transaction_date)='$year' GROUP BY month");

    foreach($expenseArray as $expense) {
      $expense_map[$expense->month] = $expense->amount;
    }
    $count = 0;
    foreach ($monthList as $index => $month) {
      if (isset($expense_map[$index])) {
        $expenseFinal[$count]['amount'] = abs($expense_map[$index]);
        $expenseFinal[$count]['month'] = $month;
      } else {
        $expenseFinal[$count]['amount'] = 0;
        $expenseFinal[$count]['month'] = $month;
      }
      $count++;
    }
    // Profit calcualtion
    for($row=0;$row<=11;$row++){
      $finalArray[$row]['month'] = $expenseFinal[$row]['month'];
      $finalArray[$row]['income'] = $incomeFinal[$row]['amount'];
      $finalArray[$row]['expense'] = $expenseFinal[$row]['amount'];
      $finalArray[$row]['profit'] = ($incomeFinal[$row]['amount'] - $expenseFinal[$row]['amount']);
    }
    return $finalArray;
  }

  public function getAllTransferById($from, $to, $account_no)
  {
    $data = DB::table('bank_transaction')
    ->leftJoin('bank_accounts','bank_accounts.id','=','bank_transaction.account_no')
    ->whereIn('bank_transaction.trans_type', ['cash-out-by-transfer','cash-in-by-transfer'])
    ->select('bank_transaction.*','bank_accounts.account_name','bank_accounts.account_no as acc_no');

    if ($from && $to) {
      $from = DbDateFormat($from);
      $to   = DbDateFormat($to);

      $data ->where('bank_transaction.trans_date', '>=', $from)
      ->where('bank_transaction.trans_date', '<=', $to);
    }
    if ($account_no) {
      $data->where('bank_transaction.account_no','=',$account_no);
    }
    return $data;
  }

  /**
   * [getCurrencyTransection description]
   * This method return data if a currencry have transaction
   * @param  [int] $currencyID is id of currency
   * @return [object]             its have currency name and id
   */
  public static function getCurrencyTransection($currencyID)
  {
    $data = DB::table('accounts')
              ->leftJoin('currencies', 'accounts.currency_id', '=', 'currencies.id')
              ->select('currencies.name', 'accounts.currency_id as id')
              ->get();
    return $data->where('id', '=', $currencyID)->first();
  }

  private function getMacDetails()
  {
    $data = DB::select('SELECT pod.item_id,(SUM(((pod.qty_invoiced*pod.unit_price) + (pod.qty_invoiced*pod.unit_price)*itt.tax_rate/100))/SUM(pod.qty_invoiced))as mac from purch_order_details as pod left join item_tax_types as itt on itt.id=pod.tax_type_id GROUP BY pod.item_id');
    return $data;
  }

  public function createInvoiceTransaction($data, $reference_id, $reference_type)
  {
    $bankTrans                              = new Transaction;
    $bankTrans->currency_id                 = $data['setCurrency'];
    $bankTrans->amount                      = validateNumbers($data['amount']);
    $bankTrans->transaction_type            = 'cash-in-by-sale';
    $bankTrans->account_id                  = (isset($data['account_no']) && !empty($data['account_no']))? $data['account_no'] : null;
    $bankTrans->transaction_date            = DbDateFormat($data['payment_date']);
    $bankTrans->user_id                     = Auth::user()->id;
    $bankTrans->transaction_reference_id    = $reference_id;
    $bankTrans->transaction_method          = 'INVOICE_PAYMENT';
    $bankTrans->description                 = $data['description'];
    $bankTrans->payment_method_id           = (isset($data['payment_type_id']) && !empty($data['payment_type_id'])) ? $data['payment_type_id'] : null;
    $bankTrans->save();
    return $bankTrans->id;
  }

  public function updateInvoiceBankTransaction($reference_id, $type)
  {
      $bankTrans = $this->where(['transaction_reference_id'=> $reference_id, 'transaction_method'=> $type])->first();
      $bankTrans->user_id = Auth::user()->id;
      $bankTrans->save();
      return $bankTrans;
  }
}
