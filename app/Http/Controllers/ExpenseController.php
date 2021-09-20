<?php

namespace App\Http\Controllers;

use App\DataTables\ExpensesDataTable;
use App\Exports\allExpenseExport;
use App\Http\Requests;
use App\Http\Start\Helpers;
use App\Models\Account;
use App\Models\ExchangeRate;
use App\Models\Expense;
use App\Models\Preference;
use App\Models\TransactionReference;
use App\Models\Transaction;
use App\Models\PaymentMethod;
use App\Models\Currency;
use App\Models\IncomeExpenseCategory;
use App\Models\File;
use Auth;
use DB;
use Excel;
use Illuminate\Http\Request;
use Image;
use Input;
use PDF;
use Session;
use Validator;
use App\Rules\UniqueExpenseReference;
use App\Rules\CheckValidFile;

class ExpenseController extends Controller
{
    public function __construct(Account $bank, Transaction $transaction, Expense $expense,GeneralLedgerController $glController)
    {
        $this->bank = $bank;
        $this->transaction = $transaction;
        $this->expense = $expense;
        $this->glController = $glController;
    }

    /**
     * Display a listing of the Bank Accounts
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ExpensesDataTable $dataTable)
    {
      $data = ['menu' => 'expense']; 
      $data['header'] =  'transaction';
      $data['page_title'] = __('Expenses');
      $data['bankAccounts'] = Account::where('is_deleted', '=', 0)->get();                     
      $data['categories'] = IncomeExpenseCategory::where('category_type', '=', 'expense')->get(['name', 'id']);
      $data['currencies'] = Currency::getAll();
      $data['paymentMethods'] = PaymentMethod::get(['name', 'id']);
      $data['currencies'] = Currency::getAll()->pluck('name', 'id')->toArray();
      $data['categoryId'] = isset($_GET['categoryName']) ? $_GET['categoryName'] : null;
      $data['methodId'] = isset($_GET['methodName']) ? $_GET['methodName'] : null;
      if (isset($_GET['from']) && isset($_GET['to'])) {
        $data['from'] = $_GET['from'];
        $data['to'] = $_GET['to'];
      }
      
      $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;
      return $dataTable->with('row_per_page', $row_per_page)->render('admin.expense.expense_list', $data);
    }


    /**
     * Show the form for creating a new Bank Account.
     *
     * @return \Illuminate\Http\Response
     */
    public function addExpense()
    {
        $data = ['menu' => 'expense', 'page_title' => __('Add Expense')];
        $data['header'] =  'transaction';
        $data['accounts'] =  DB::table('accounts')
                          ->leftJoin('currencies', 'accounts.currency_id', '=', 'currencies.id')
                          ->where('accounts.is_deleted', '=', 0)
                          ->select('accounts.*', 'currencies.name as currency')
                          ->get();
        $data['incomeCategories'] = DB::table('income_expense_categories')
                                    ->where('category_type', 'expense')
                                    ->orWhere('category_type', 'no')
                                    ->pluck('name', 'id');

        $data['paymentMethods'] = PaymentMethod::get(['name', 'id']);
        $data['currencies'] = Currency::getAll()->pluck('name', 'id')->toArray();
        $data['homeCurrency'] = Preference::getAll()
                                ->where('category', "company")
                                ->where('field', "dflt_currency_id")
                                ->pluck('value')->toArray();
    
        $reference = TransactionReference::where('reference_type', 'EXPENSE')->orderBy('id', 'DESC')->first();

        if (!empty($reference)) {
            $info = explode('/', $reference->code);
            $refNo = (int)$info[0];

            $data['reference'] = sprintf("%03d", $refNo + 1) . '/' . date('Y');
        } else {
            $data['reference'] = sprintf("%03d", 1) . '/' . date('Y');
        }
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['dflt_currency_id'] = $preference['dflt_currency_id'];
        return view('admin.expense.expense_add', $data);
    }

    /**
     * Store a newly created expense in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validate($request, [
            'reference'   => ['required', new UniqueExpenseReference],
            'trans_date'  => 'required',
            'amount'      => 'required',
            'category_id' => 'required',
            'attachment'  => [new CheckValidFile(getFileExtensions(1))],
      ]);
      
      $reference = $request->reference;
       
      try {
        DB::beginTransaction();
        $reference_id  = $this->glController->createReference($reference, 'EXPENSE');
        $transInsertId = $this->glController->createExpenseBankTransaction($request, $request->all(), $reference_id, 'EXPENSE');
        $expense_id    = $this->glController->createExpense($request->all(), $reference_id, $transInsertId, (int)$request->category_id);
        // File Upload
        if (!empty($request->attachment)) {
          $path = createDirectory("public/uploads/expense");
           (new File)->store([$request->attachment], $path, 'EXPENSE', $expense_id, ['isUploaded' => false, 'isOriginalNameRequired' => true]);
        }
        // File Upload End

        DB::commit(); 
      } catch (\Exception $e) {
        DB::rollBack();
        return redirect('expense/add-expense')->withErrors($e->getMessage());
      } 

      Session::flash('success', __('Successfully Saved'));
      return redirect()->intended('expense/list');
    }

    /**
     * Show the form for editing the expense.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function editExpense($id)
    {
        $data = ['menu' => 'expense', 'page_title' => __('Edit Expense')];
        $data['header']   =  'transaction';
        $data['account'] = Account::where('is_deleted', '!=', 1)->get();
        $data['incomeCategories'] = DB::table('income_expense_categories')
                                    ->where('category_type', 'expense')
                                    ->pluck('name', 'id')->toArray();
        $data['paymentMethods'] = PaymentMethod::get(['name', 'id']);
        $data['currencies'] = Currency::getAll()->pluck('name', 'id')->toArray();
        $data['accounts'] =  DB::table('accounts')
                            ->leftJoin('currencies', 'accounts.currency_id', '=', 'currencies.id')
                            ->where('accounts.is_deleted', '=', 0)
                            ->select('accounts.*', 'currencies.name as currency', 'currencies.id as currency_id')
                            ->get();
        $data['expenseInfo'] = Expense::find($id);
        if (empty($data['expenseInfo'])) {
            return redirect()->back()->with('fail', __('The data you are trying to access is not found.'));
        }
        if (!empty($data['expenseInfo']->paymentMethod) && $data['expenseInfo']->paymentMethod->name == "Bank") {
          $data['balance'] = DB::table('transactions')
                    ->where(['id' => $data['expenseInfo']->transaction_id ])
                    ->sum('amount');
        }
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['files'] = (new File)->getFiles('EXPENSE', $id);
        $data['filePath'] = "public/uploads/expense";
        $data['dflt_currency_id'] = $preference['dflt_currency_id'];
        $data['homeCurrency'] = Preference::getAll()
                                ->where('category', "company")
                                ->where('field', "dflt_currency_id")
                                ->pluck('value')->toArray();
        return view('admin.expense.expense_edit', $data);
    }

    /**
     * Update the specified Expense in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateExpense(Request $request)
    {
      $this->validate($request, [
          'trans_date' => 'required',
          'amount'     =>'required',
          'attachment' => [new CheckValidFile(getFileExtensions(1))],
      ]);

       try {
        DB::beginTransaction();
        $transactionId = $this->glController->updateExpenseTransaction($request->all());
        $this->glController->updateExpense($transactionId, $request->all());
        // File Upload
        if (!empty($request->attachment)) {
          (new File)->deleteFiles('EXPENSE', $request->id, [], 'public/uploads/expense');
          if (!empty($request->attachment)) {
            $path = createDirectory("public/uploads/expense");
             (new File)->store([$request->attachment], $path, 'EXPENSE', $request->id, ['isUploaded' => false, 'isOriginalNameRequired' => true]);
          }
        }
        // File Upload End

        DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(__('Failed To Edit The Expense'));
        }  
       
        Session::flash('success', __('Successfully updated'));
        return redirect()->intended('expense/list');
    }

    public function destroy($id)
    {
        if (isset($id)) {
          try {
          DB::beginTransaction();
            $expense = Expense::find($id);
            $bank_trans = Transaction::find($expense->transaction_id);
            if (!empty($bank_trans->attachment)) {
                  $path = base_path('/uploads/attachment/'.$bank_trans->attachment);
                  @unlink($path);
            }
            $expense->delete();
            if (!empty ($bank_trans)) {
              $bank_trans->delete();
            }
            DB::commit();
            $data = ['type' => 'success', 'message'=> __('Deleted Successfully.')];
          } catch (\Exception $e) {
            DB::rollBack();
            $data = ['type' => 'fail', 'message'=> __('Failed to delete the expense.')];
          } 
          Session::flash($data['type'], $data['message']);
          return redirect()->intended('expense/list');    
        }
    }

    public function expenseListPdf()
    {
      $to         = $_GET['to'];
      $from       = $_GET['from'];
      $categoryId = $_GET['categoryName'];
      $methodId   = $_GET['methodName'];

      $expense = Expense::query();
      if ($from) {
        $expense->where('transaction_date', '>=', DbDateFormat($from));
      }
      if ($to) {
        $expense->where('transaction_date', '<=', DbDateFormat($to));
      }
      if ($categoryId && $categoryId != 'all') {
        $expense->where('expenses.income_expense_category_id', $categoryId);
        $data['categoryName'] = IncomeExpenseCategory::find($categoryId);
        $data['categoryName'] = $data['categoryName']->name;
      } else {
        $data['categoryName'] = __('All');
      }
      if ($methodId && $methodId != 'all') {
        $expense->where('expenses.payment_method_id', $methodId);
        $data['paymentMethod'] = PaymentMethod::find($methodId);
        $data['paymentMethod'] = $data['paymentMethod']->name;
      } else {
        $data['paymentMethod'] = __('All');
      }
      $data['expenseList'] = $expense->orderBy('expenses.id', 'desc')->get();
      if ($from && $to) {
		    $data['date_range'] =  formatDate($from) .' To '. formatDate($to);   
      } else {
        $data['date_range'] = __('No date selected');   
      } 
      $data['company_logo']   = Preference::getAll()->where('category','company')->where('field', 'company_logo')->first('value');
      $data['company_logo']   = isset($data['company_logo']['value']) && !empty($data['company_logo']['value']) ? $data['company_logo']['value'] : '';
      return printPDF($data, 'expense_list_' . time() . '.pdf', 'admin.expense.expense_list_pdf', view('admin.expense.expense_list_pdf', $data), 'pdf', 'domPdf');
    }

    public function expenseListCsv()
    {
      return Excel::download(new allExpenseExport(), 'expense_list_'. time() .'.csv');
    }
}
