<?php

namespace App\Http\Controllers;

use App\DataTables\BankDepositDataTable;
use App\Exports\bankAccountDepositsExport;
use App\Http\Requests;
use App\Http\Start\Helpers;
use App\Models\Account;
use App\Models\BankTransaction;
use App\Models\Deposit;
use App\Models\ExchangeRate;
use App\Models\Preference;
use App\Models\Reference;
use App\Models\TransactionReference;
use App\Models\Transaction;
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
use App\Rules\UniqueDepositReference;
use App\Rules\CheckValidFile;
class DepositController extends Controller
{
    public function __construct(Account $bank, TransactionReference $transaction, Deposit $deposit, GeneralLedgerController $glController)
    {
        $this->bank = $bank;
        $this->transaction = $transaction;
        $this->deposit = $deposit;
        $this->glController = $glController;
    }
    /**
     * Display a listing of the Bank Accounts
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BankDepositDataTable $dataTable)
    {
        $data['menu'] =  'transaction';
        $data['sub_menu'] = 'deposit/list';
        $data['page_title'] = __('Bank Account Deposits');
        $data['header'] =  'transaction';
        $data['bankAccounts'] = DB::table('accounts')
                               ->leftJoin('currencies','accounts.currency_id','=','currencies.id')
                               ->select('accounts.id','accounts.name','currencies.id as currency_id','currencies.name as currency_name')
                               ->where('is_deleted', '!=', 1)
                               ->get();
        $fromDate = DB::table('transactions')
                    ->leftJoin('accounts', 'accounts.id', '=', 'transactions.account_id' )
                    ->where('transaction_type','deposit')
                    ->orderBy('transactions.transaction_date','ASC')
                    ->first();

        $data['from'] = $from = isset($_GET['from']) ? $_GET['from'] : null;
        $data['to'] = $to = isset($_GET['to']) ? $_GET['to'] : null;
        $data['account_no'] = $account_no = isset($_GET['account_no']) ? $_GET['account_no'] : null;

        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;

        return $dataTable->with('row_per_page', $row_per_page)->render('admin.deposit.deposit_list', $data);
    }


    /**
     * Show the form for creating a new Bank Account.
     *
     * @return \Illuminate\Http\Response
     */
    public function addDeposit()
    {
        $data['menu'] =  'transaction';
        $data['sub_menu'] = 'deposit/list';
        $data['page_title'] = __('Create Bank Account Deposit');
        $data['header'] =  'transaction';
        $data['accounts'] =  DB::table('accounts')
                          ->leftJoin('currencies','accounts.currency_id','=','currencies.id')
                          ->where('accounts.is_deleted','=',0)
                          ->select('accounts.*','currencies.name as currency')
                          ->get();
        $data['incomeCategories'] = DB::table('income_expense_categories')
                                    ->where('category_type','income')
                                    ->pluck('name','id');
        $data['payment_methods'] = DB::table('payment_methods')->pluck('name','id');

        $reference = TransactionReference::where('reference_type', 'DEPOSIT')->orderBy('id','DESC')->first();

        if (!empty($reference)) {
            $info = explode('/', $reference->code);
            $refNo = (int)$info[0];

            $data['reference'] = sprintf("%03d", $refNo + 1) . '/' . date('Y');
        } else {
            $data['reference'] = sprintf("%03d", 1) . '/' . date('Y');
        }
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['dflt_currency_id'] = $preference['dflt_currency_id'];
        return view('admin.deposit.deposit_add', $data);
    }

    /**
     * Store a newly created deposit in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'account_no' => 'required',
            'reference'  => ['required', new UniqueDepositReference],
            'trans_date' => 'required',
            'amount'     => 'required',
            'attachment' => [new CheckValidFile(getFileExtensions(1))],
        ]);

        $reference = $request->reference;

       try {
          DB::beginTransaction();
          $reference_id  = $this->glController->createReference($reference, 'DEPOSIT');
          $deposit_id    = $this->glController->createBankDeposit($request->all(), $reference_id, 'DEPOSIT');
          $transInsertId = $this->glController->createDepositBankTransaction($request->all(), $request, $reference_id, 'DEPOSIT');

          // File Upload
          if (!empty($request->attachment) && !empty($deposit_id)) {
            $path = createDirectory("public/uploads/deposit");
             (new File)->store([$request->attachment], $path, 'DEPOSIT', $deposit_id, ['isUploaded' => false, 'isOriginalNameRequired' => true]);
          }
          // File Upload End

          DB::commit();
         } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(__('Failed To Add The Deposit'));
       }

     Session::flash('success', __('Successfully Saved'));
     return redirect()->intended('deposit/list');
    }

    /**
     * Show the form for editing the deposit.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editDeposit($id)
    {
        $data['menu'] =  'transaction';
        $data['sub_menu'] = 'deposit/list';
        $data['page_title'] = __('Edit Bank Account Deposit');
        $data['header'] =  'transaction';
        $data['accounts'] = Account::where('is_deleted','!=', 1)->get();
        $data['incomeCategories'] = DB::table('income_expense_categories')
                                    ->where('category_type','income')
                                    ->pluck('name','id');
        $data['payment_methods'] = DB::table('payment_methods')->pluck('name','id');
        $data['depositInfo'] = Deposit::find($id);

        if (empty($data['depositInfo'])) {
            return redirect()->back()->with('fail', __('The data you are trying to access is not found.'));
        }

        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['dflt_currency_id'] = $preference['dflt_currency_id'];
        $data['files'] = (new File)->getFiles('DEPOSIT', $id);
        $data['filePath'] = "public/uploads/deposit";
        return view('admin.deposit.deposit_edit', $data);
    }

    /**
     * Update the specified deposit in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateDeposit(Request $request)
    {
      $this->validate($request, [
          'transaction_date' => 'required',
          'amount'           => 'required',
          'category_id'      => 'required',
          'payment_method'   => 'required',
          'attachment'       => [new CheckValidFile(getFileExtensions(1))],
      ]);

      try {
          DB::beginTransaction();
            $this->glController->updateBankDeposit($request->all());
            $this->glController->updateDepositBankTransaction($request->all());

            // File Upload
            if (!empty($request->attachment) && !empty($request->id)) {
                $data = (new File)->deleteFiles('DEPOSIT', $request->id, [], 'public/uploads/deposit');
                $path = createDirectory("public/uploads/deposit");
                 (new File)->store([$request->attachment], $path, 'DEPOSIT', $request->id, ['isUploaded' => false, 'isOriginalNameRequired' => true]);
            }
            // File Upload End

            DB::commit();
            } catch (\Exception $e) {
              DB::rollBack();
              return redirect()->back()->withErrors(__('Failed To Edit The Deposit'));
          }

     Session::flash('success', __('Successfully updated'));
     return redirect()->intended('deposit/list');

    }

    public function destroy($id)
    {
      $conditions = [];
      if (!empty($id)) {
        try {
            DB::beginTransaction();
            $deposit      = Deposit::find($id);
            if (!empty($deposit)) {
              $reference_id = $deposit->transaction_reference_id;
              $conditions = [
                    'transaction_reference_id' => $reference_id,
              ];
            }
            if ($deposit->delete()) {
              (new File)->deleteFiles('DEPOSIT', $id, [], 'public/uploads/deposit');
              Transaction::where($conditions)->delete();
              TransactionReference::where('id', $reference_id)->delete();
            }

            DB::commit();
          } catch (\Exception $e) {
              DB::rollBack();
              return redirect()->back()->withErrors(__('Failed To Delete The Deposit'));
        }

       }
       Session::flash('success', __('Deleted Successfully.'));
       return redirect()->intended('deposit/list');
    }

    public function depositPdf()
    {
        $to               = isset($_GET['to']) ? $_GET['to'] : null ;
        $from             = isset($_GET['to']) ? $_GET['from'] : null ;
        $data['account_no'] = $account_no = isset($_GET['account_no']) ? $_GET['account_no'] : null ;
        if ($account_no !='all') {
            $data['account_name'] = Account::find($account_no);
        } else {
            $data['account_name'] = null;
        }
        $bankDeposit = Deposit::query();
                     if ($from) {
                       $bankDeposit->where('transaction_date', '>=', DbDateFormat($from));
                     }
                     if ($to) {
                       $bankDeposit->where('transaction_date', '<=', DbDateFormat($to));
                     }
                     if ($account_no && $account_no !='all') {
                       $bankDeposit->where('account_id','=',$account_no);
                     }
        $data['depositList'] = $bankDeposit->orderBy('transaction_date', 'desc')->get();
        if ($from && $to) {
         $data['date_range'] =  formatDate($from) . __('To') . formatDate($to);
        } else {
         $data['date_range'] = __('No date selected');
        }
        $data['company_logo']   = Preference::getAll()->where('category','company')->where('field', 'company_logo')->first('value');
        return printPDF($data, 'deposit_list_' . time() . '.pdf', 'admin.deposit.deposit_list_pdf', view('admin.deposit.deposit_list_pdf', $data), 'pdf', 'domPdf');
    }


    public function depositCsv()
    {
        return Excel::download(new bankAccountDepositsExport(), 'bank_account_depostie_details'.time().'.csv');
    }

    public function getBalance(Request $request)
    {
      $data['status'] = 0;
      $account_id   = $request->account_no;
      $date = DbDateFormat($request->date);
      if ( !empty($account_id)) {
        $balance = DB::table('transactions')
                ->where(['account_id' => $account_id])
                ->sum('amount');

        $data['balance'] = isset($balance) ? $balance : 0;
        $data['status'] = 1;
      }
      return $data;
    }

    public function deleteFile(Request $request)
    {
        // If file exeist then delete
        if (!empty($request->id)) {
            $data = (new File)->deleteFiles('DEPOSIT', $request->id, [], 'public/uploads/deposit');
            return $data;
        }
    }
}
