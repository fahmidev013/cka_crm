<?php

namespace App\Http\Controllers;

use App\DataTables\BankTrancastionDataTable;
use App\Exports\bankTransactionsExport;
use App\Models\Account;
use App\Models\Currency;
use App\Models\Preference;
use App\Models\Transaction;
use App\Models\Expense;
use App\Models\SaleOrder;
use App\Models\PaymentMethod;
use DB;
use Cache;
use Excel;
use Illuminate\Http\Request;
use PDF;

class TransactionController extends Controller
{
    public function __construct(Transaction $transaction, Expense $expense, SaleOrder $sales)
    {
        $this->transaction  = $transaction;
        $this->expense      = $expense;
        $this->sale         = $sales;
    }

    public function index(BankTrancastionDataTable $dataTable)
    {
        $data['menu']     = 'transaction';
        $data['sub_menu'] = 'transaction/list';
        $data['page_title'] = __('Transactions');
        $data['header']   = 'transaction';
        $data['paymentMethod'] = PaymentMethod::getAll()->pluck('name', 'id')->toArray();
        $data['type'] = Transaction::distinct()->get(['transaction_method']);
        $data['from']        = $from        = isset($_GET['from']) ?$_GET['from'] :null ;
        $data['to']          = $to          = isset($_GET['to']) ?$_GET['to'] :null;
        $data['method']  = $method  = isset($_GET['method']) ?$_GET['method']: null;
        $data['modeVal']  = isset($_GET['mode']) ?$_GET['mode']: null;
        $data['typeVal']  = isset($_GET['type']) ?$_GET['type']: null;
        $row_per_page = Preference::getAll()->where('field', 'row_per_page')->first()->value;
        $data['debit'] = $dataTable->query()->where('amount', '>', 0)->select(DB::raw('SUM(amount) as total, transactions.currency_id'))->groupBy('transactions.currency_id')->get();
        $data['credit'] = $dataTable->query()->where('amount', '<', 0)->select(DB::raw('SUM(amount) as total, transactions.currency_id'))->groupBy('transactions.currency_id')->get();
        $data['currencies'] = Currency::getAll()->pluck('symbol', 'id')->toArray();
        
        return $dataTable->with('row_per_page', $row_per_page)->render('admin.transaction.transaction_list', $data);
    }

    public function details($id)
    {
        $data['menu']     = 'transaction';
        $data['sub_menu'] = 'transaction/list';
        $data['page_title'] = __('View Transaction');
        $data['header']   = 'transaction';
        $data['fetchData'] = Transaction::find($id);

        if (empty($data['fetchData'])) {
            return redirect()->back()->with('fail', __('The data you are trying to access is not found.'));
        }
        
        $data['currency'] = Currency::where('id', $data['fetchData']->currency_id)->first();
        return view('admin.transaction.transaction_details', $data);
    }

    public function expenseReport()
    {
        $data = [];
        $results = [];
        $total = [];
        $months = [];
        $newArray = [];
        $expenseList = [];
        $purchaseExpensesMonth = [];
        $purchaseExpenseList = [];

        $data['menu']     = 'report';
        $data['sub_menu'] = 'transaction/expense-report';
        $data['page_title'] = __('Expense Report');
        $data['header']   = 'report';
        $year             = date('Y');
        $data['from']     = $from = isset($_GET['from']) ? $_GET['from'] : null;
        $data['to']       = $to   = isset($_GET['to'])   ? $_GET['to']   : null;
        $data['currencyList'] = Currency::getAll();
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['currency'] = $currency = isset($_GET['currency']) ? (int) $_GET['currency'] : $preference['dflt_currency_id'];

        if ((isset($_GET['from']) && !empty($_GET['from'])) && (isset($_GET['to']) && !empty($_GET['to']))) {
            $fromDate = explode('-', $_GET['from']);
            $toDate = explode('-', $_GET['to']);
            $from = date('Y-m-d', strtotime('01 '. $fromDate[0] .' '. $fromDate[1]));

            $to = date('Y-m-d', strtotime('01 '. $toDate[0] .' '. $toDate[1]));
            $lastday = date('t',strtotime($to));
            $to = date('Y-m-d', strtotime($lastday.' '. $toDate[0] .' '. $toDate[1]));
            if ($from >= $to) {
                return redirect()->back();
            }
        } else {
            $from = date('Y-m-01', strtotime("-1 year"));
            $to = date('Y-m-d');
        }
        $months = getMonths($from, $to);

        $purchaseExpenses = $this->expense->getPurchaseExpenses($from, $to, $currency)->get(['reference', 'order_date', 'currency_id', 'total']);
        if (!empty($purchaseExpenses)) {
            foreach ($purchaseExpenses as $key => $value) {
                if (array_key_exists(date('M Y', strtotime($value->order_date)), $purchaseExpenseList)) {
                    $purchaseExpenseList[date('M Y', strtotime($value->order_date))] += $value->total;
                } else {
                    $purchaseExpenseList[date('M Y', strtotime($value->order_date))] = $value->total;
                }
            }
            
            foreach ($months as $k => $month) {
                $purchaseExpensesMonth[$month] = !empty($purchaseExpenseList[$month]) ? $purchaseExpenseList[$month] : 0;
            }
        }
        
        $expenseList = $this->expense->getGenerelExpenses($from, $to, $currency);
        if (!empty($expenseList)) {
            foreach ($expenseList['expenses'] as $key => $value) {
                foreach ($months as $k => $month) {
                    $results[$key][$month] = !empty($value[$month]) ? $value[$month] : 0;
                    $total[$key] += !empty($value[$month]) ? $value[$month] : 0;
                }
            }
        }

        
        $data['from']           = date('F-Y', strtotime($from));
        $data['to']             = date('F-Y', strtotime($to));
        $data['expenseList']    = $results;
        $data['purchaseList']   = $purchaseExpensesMonth;
        $data['categoryTotals'] = $total;
        $data['months']         = $months;
        // colors
        $data['colors'] = ['#DD4B39', '#FFA09A', '#00A65A', '#F39C12', '#00C0EF', '#3C8DBC', '#E5FFFF', '#BCBE36', '#A261FA', '#4483F0', '#F0E69C', '#0059b6'];
        return view('admin.TransactionReport.expense_report', $data);
    }

    public function incomeVsExpense()
    {
        $data = [];
        $total = [];
        $results = [];
        $months = [];
        $incomeArray = [];
        $expenseArray = [];
        $revenueArray = [];
        $totalIncomeArray = [];
        $totalExpenseArray = [];
        $purchaseExpenseList = [];

        $data['menu']     = 'report';
        $data['sub_menu'] = 'transaction/income-vs-expense';
        $data['page_title'] = __('Income vs Expense Report');
        $data['header']   = 'report';
        $data['from']     = $from = isset($_GET['from']) ? $_GET['from'] : null;
        $data['to']       = $to   = isset($_GET['to'])   ? $_GET['to']   : null;
        $data['currencyList'] = Currency::getAll();
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['currency'] = $currency = isset($_GET['currency']) ? (int) $_GET['currency'] : $preference['dflt_currency_id'];

        if ((isset($_GET['from']) && !empty($_GET['from'])) && (isset($_GET['to']) && !empty($_GET['to']))) {
            $fromDate = explode('-', $_GET['from']);
            $toDate = explode('-', $_GET['to']);
            $from = date('Y-m-d', strtotime('01 '. $fromDate[0] .' '. $fromDate[1]));

            $to = date('Y-m-d', strtotime('01 '. $toDate[0] .' '. $toDate[1]));
            $lastday = date('t',strtotime($to));
            $to = date('Y-m-d', strtotime($lastday.' '. $toDate[0] .' '. $toDate[1]));
            if ($from >= $to) {
                return redirect()->back();
            }
        } else {
            $from = date('Y-m-01', strtotime("-1 year"));
            $to = date('Y-m-d');
        }
        $months  = getMonths($from, $to);
        
        $incomeList = $this->sale->getAllIncome($from, $to, $currency);
        if (!empty($incomeList)) {
            foreach ($incomeList as $key => $value) {
                foreach ($months as $k => $month) {
                    $incomeArray[$key][$month] = !empty($value[$month]) ? $value[$month] : 0;
                }
            }
            
            foreach ($incomeArray as $key => $value) {
                foreach ($value as $month => $val) {
                    if (array_key_exists($month, $totalIncomeArray)) {
                        $totalIncomeArray[$month] += $val;
                    } else {
                        $totalIncomeArray[$month] = $val;
                    }
                }
            }
        }

        $purchaseExpenses = $this->expense->getPurchaseExpenses($from, $to, $currency)->get(['reference', 'order_date', 'currency_id', 'total']);
        if (!empty($purchaseExpenses)) {
            foreach ($purchaseExpenses as $key => $value) {
                if (array_key_exists(date('M Y', strtotime($value->order_date)), $purchaseExpenseList)) {
                    $purchaseExpenseList[date('M Y', strtotime($value->order_date))] += $value->total;
                } else {
                    $purchaseExpenseList[date('M Y', strtotime($value->order_date))] = $value->total;
                }
            }
        }

        $expenseList = $this->expense->getGenerelExpenses($from, $to, $currency);
        if (!empty($expenseList)) {
            foreach ($expenseList['expenses'] as $key => $value) {
                foreach ($months as $k => $month) {
                    $results[$key][$month] = !empty($value[$month]) ? $value[$month] : 0;
                }
            }
            foreach ($results as $key => $value) {
                foreach ($value as $month => $val) {
                    if (array_key_exists($month, $expenseArray)) {
                        $expenseArray[$month] += $val;
                    } else {
                        $expenseArray[$month] = $val;
                    }
                }
            }
        }
        
        foreach (array_keys($expenseArray + $purchaseExpenseList) as $key) {
            $totalExpenseArray[$key] = (isset($expenseArray[$key]) ? $expenseArray[$key] : 0) + (isset($purchaseExpenseList[$key]) ? $purchaseExpenseList[$key] : 0);
        }

        foreach ($months as $key => $month) {
            $revenueArray[$month] = $totalIncomeArray[$month] - $totalExpenseArray[$month];
        }
        $data['from']          = date('F-Y', strtotime($from));
        $data['to']            = date('F-Y', strtotime($to));
        $data['incomeArray']   = $totalIncomeArray;
        $data['expenseArray']  = $totalExpenseArray;
        $data['revenueArray']  = $revenueArray;
        $data['months']        = $months;
        $currency              = Currency::find($currency);
        $data['currency_sign'] = $currency->symbol;
        $data['incomeGraph']   = json_encode(!empty($data['incomeArray']) ? array_values($data['incomeArray']) : 0);
        $data['expenseGraph']  = json_encode(!empty($data['expenseArray']) ? array_values($data['expenseArray']) : 0);
        $data['revenueGraph']  = json_encode(!empty($data['revenueArray']) ? array_values($data['revenueArray']) : 0);
        $data['dateGraph']     = json_encode(!empty($data['months']) ? array_values($data['months']) : 0);

        return view('admin.TransactionReport.income_vs_expense', $data);
    }


    public function transactionListCsv()
    {
        return Excel::download(new bankTransactionsExport(), 'bank_transactionr_details'.time().'.csv');
    }

    public function transactionListPdf()
    {
        $method = isset($_GET['method']) ? $_GET['method'] : null ;
        $from   = isset($_GET['from']) ? $_GET['from'] : null ;
        $to     = isset($_GET['to']) ? $_GET['to'] : null ;
        $mode   = isset($_GET['mode']) ? $_GET['mode'] : null ;
        $type   = isset($_GET['type']) ? $_GET['type'] : null ;
        if ($mode == 1) {
            $data['modeSelected'] = __('Cash in (Debit)');
        } else if ($mode == 2) {
            $data['modeSelected'] = __('Cash out (Credit)');
        } else {
            $data['modeSelected'] = null;
        }
        $transaction = $this->transaction->getAllTransactionCollection($from, $to, $method, $mode, $type);
        $data['method'] = !empty($method) ? PaymentMethod::where('id', $method)->select('name')->first() : null ;
        $data['transactionList']  = $transaction->orderBy('reference', 'desc')->get();
        $data['company_logo']   = Preference::getAll()->where('category','company')->where('field', 'company_logo')->first('value');
        $data['date_range'] = !empty($from) && !empty($to) ?  formatDate($from) . ' To ' . formatDate($to) : 'No date selected' ;
        return printPDF($data, 'transaction_list_pdf' . time() . '.pdf', 'admin.transaction.transaction_list_pdf', view('admin.transaction.transaction_list_pdf', $data), 'pdf', 'domPdf');
    }

    public function selectCategory(Request $request)
    {
        $type              = $request->type;
        $data['status_no'] = 0;
        $category_id       = '';
        $category          = DB::table('income_expense_categories')
            ->select('id', 'name')
            ->where('type', '=', $type)
            ->orderBy('id', 'ASC')
            ->get();
        if (!empty($category))
        {
            $data['status_no'] = 1;
            $category_id .= "<option value='all'>". __('All'). "</option>";
            foreach ($category as $key => $result)
            {
                $category_id .= "<option value='" . $result->id . "'>" . "$result->name" . "</option>";
            }
            $data['category_id'] = $category_id;
        }
        return json_encode($data);
    }

}
