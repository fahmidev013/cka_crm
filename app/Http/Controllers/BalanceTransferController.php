<?php

namespace App\Http\Controllers;

use App\DataTables\BankTransferDataTable;
use App\Exports\bankAccountTransfersExport;
use App\Http\Requests;
use App\Http\Start\Helpers;
use App\Models\Account;
use App\Models\Transfer;
use App\Models\ExchangeRate;
use App\Models\TransactionReference;
use App\Models\Preference;
use App\Models\Transaction;
use App\Models\Currency;
use Auth;
use DB;
use Excel;
use Illuminate\Http\Request;
use Input;
use PDF;
use Session;
use Validator;
use App\Rules\UniqueTransferReference;

class BalanceTransferController extends Controller
{
    public function __construct(Account $bank, Transaction $transaction, GeneralLedgerController $glController)
    {
        $this->bank = $bank;
        $this->transaction = $transaction;
        $this->glController = $glController;
    }

    /**
     * Display a listing of the Bank Accounts
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BankTransferDataTable $dataTable)
    {
        $data['menu']     = 'transaction';
        $data['sub_menu'] = 'transfer/list';
        $data['page_title'] = __('Bank Account Transfers');
        $data['header']   = 'transaction';
        $data['bankAccounts'] = Account::select('id','name','currency_id')->where('is_deleted','=', 0)->get();                     
        
        if (isset($_GET['from'])) {
            $data['from'] = $from = $_GET['from'];
        } else {
            $data['from']  = $from = null;
        }
        
        if (isset($_GET['to'])) {
            $data['to'] = $to = $_GET['to'];
        } else {
            $data['to'] = $to = null;
        }

        if (isset($_GET['from_bank_id'])) {
            $data['from_bank_id'] = $from_bank_id = $_GET['from_bank_id'];
        } else {
           $data['from_bank_id'] = $from_bank_id = null;
        }

        if (isset($_GET['to_bank_id'])) {
            $data['to_bank_id'] = $to_bank_id = $_GET['to_bank_id'];
        } else {
           $data['to_bank_id'] = $to_bank_id = null;
        }

        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;

        return $dataTable->with('row_per_page', $row_per_page)->render('admin.transfer.transfer_list',$data);
    }

    /**
     * Show the form for creating a new Bank Account.
     *
     * @return \Illuminate\Http\Response
     */
    public function addTransfer()
    {
        $data['menu'] = 'transaction';
        $data['sub_menu'] = 'transfer/list';
        $data['page_title'] = __('Create Bank Account Transfer');
        $data['header'] = 'transaction';
        $data['accounts'] =  DB::table('accounts')
                          ->leftJoin('currencies','accounts.currency_id','=','currencies.id')
                          ->where('accounts.is_deleted','=', 0)
                          ->select('accounts.*','currencies.name as currency')
                          ->get();                  
        $data['incomeCategories'] = DB::table('income_expense_categories')
                                  ->where('category_type','income')
                                  ->pluck('name','id');
        $data['payment_methods'] = DB::table('payment_methods')->pluck('name','id');

        $reference = TransactionReference::where('reference_type', 'TRANSFER')->orderBy('id','DESC')->first();

        if (!empty($reference)) {
            $info = explode('/', $reference->code);
            $refNo = (int)$info[0];
            $data['reference'] = sprintf("%03d", $refNo + 1) . '/' . date('Y');
        } else {
            $data['reference'] = sprintf("%03d", 1) . '/' . date('Y');
        }
        $data['dflt_currency_id'] = Preference::getAll()->pluck('value', 'field')->toArray()['dflt_currency_id'];
        return view('admin.transfer.transfer_add', $data);
    }

    /**
     * Store a newly created transfer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $validator = Validator::make($request->all(),[
            'source'                 => 'required',
            'destination'            => 'required',
            'trans_date'             => 'required',
            'reference'              => ['required', new UniqueTransferReference],
            'amount'                 => 'required',
        ]);

        if (array_key_exists("incoming_amount", $request->all())) {
          if (! isset($request->incoming_amount)) {
              $validator->after(function ($validator) use ($request) {
                $validator->errors()->add('incoming_amount', __('Incoming amount is required.'));
              }); 
          }
        }

        if ($validator->fails()) {
          return redirect('transfer/create')
                      ->withErrors($validator)
                      ->withInput();
        }      
        $reference = $request->reference;
        try {
            DB::beginTransaction(); 
            $reference_id  = $this->glController->createReference($reference, 'TRANSFER');
            $transfer_id   = $this->glController->createBankTransfer($reference_id, 'TRANSFER', $request->all());
            $transInsertId = $this->glController->createBankTransferTransaction($reference_id, 'TRANSFER', $request->all());

            DB::commit();
            Session::flash('success', __('Successfully Saved'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(__('Failed To Add The Transfer'));
        }                
        Session::flash('success', __('Successfully Saved'));
        return redirect()->intended('transfer/list');
    }

    public function details($id) {
        $data['menu'] = 'transaction';
        $data['sub_menu'] = 'transfer/list';
        $data['page_title'] = __('View Bank Account Transfer');
        $data['header'] = 'transaction';
        $data['fetchData'] = Transfer::find($id);

        if (empty($data['fetchData'])) {
            return redirect()->back()->with('fail', __('The data you are trying to access is not found.'));
        }
        
        return view('admin.transfer.transfer_details', $data);
    }

    public function destroy($id)
    {
      if (!empty($id)) {
        try {
          DB::beginTransaction();
          $transfer = Transfer::find($id);
          if (!empty($transfer)) {
            $fromAcc = $transfer->from_account_id;
            $toAcc = $transfer->to_account_id;
            $reference = $transfer->transaction_reference_id;
          }
          if ($transfer->delete()) {
            Transaction::where([
              'account_id' => $fromAcc, 
              'transaction_reference_id' => $reference, 
              'transaction_method' => 'TRANSFER'])->delete();
            Transaction::where([
              'account_id' => $toAcc, 
              'transaction_reference_id' => $reference, 
              'transaction_method' => 'TRANSFER'])->delete();
            TransactionReference::where('id', $reference)->delete();
          }
          DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(__('Failed To Delete The Transfer'));
        }  

        Session::flash('success', __('Deleted Successfully.'));
        return redirect()->intended('transfer/list');
            
      }
    }

    public function checkBalance(Request $request)
    {
        $account_no = $request->account_no;
        $balance = DB::table('transactions')->where('account_id',$account_no)->sum('amount');
        return $balance;

    }

     public function selectDestination(Request $request)
     {
        $source = $request->source;
        $data = [ 'status_no' => 0 ];
        $destination = '';
        $accounts = DB::table('accounts')
                  ->leftJoin('currencies','accounts.currency_id','=','currencies.id')
                  ->select('accounts.id','accounts.name','accounts.currency_id','currencies.name as currency')
                  ->where('accounts.id','!=',$source)
                  ->where('accounts.is_deleted','0')
                  ->orderBy('accounts.name','ASC')
                  ->get();
        if (!empty($accounts)) {
            $data['status_no'] = 1;
            $destination .= "<option value=''>" . __('Choose an Account') . "</option>";
            foreach ($accounts as $key => $result) {
            $destination .= "<option value='".$result->id."' currency-id='".$result->currency_id."' currency-code='".$result->currency."'>"."$result->name".'('.$result->currency.')'."</option>";  
        }
        $data['destination'] = $destination; 
       }
        return json_encode($data);
    }

    public function accTransferPdf()
    {
      $from_bank_id = isset($_GET['from_bank_id']) ? $_GET['from_bank_id'] : null ;
      $to_bank_id   = isset($_GET['to_bank_id']) ? $_GET['to_bank_id'] : null ;
      $from         = isset($_GET['from']) ? $_GET['from'] : null ;
      $to           = isset($_GET['to']) ? $_GET['to'] : null ;

      $data['fromAcc'] = !empty($from_bank_id) ? Account::find($from_bank_id) : null ;
      $data['toAcc'] = !empty($to_bank_id) ? Account::find($to_bank_id) : null ;
      $bankTransfer = Transfer::with(['fromBank', 'toBank', 'currency', 'transactionReference'])->select('transfers.*');
      if (!empty($from)) {
        $bankTransfer->where('transaction_date', '>=', DbDateFormat($from));
      }
      if (!empty($to)) {
        $bankTransfer->where('transaction_date', '<=', DbDateFormat($to));
      }
      if (!empty($from_bank_id)) {
        $bankTransfer->where('from_account_id', '=', $from_bank_id);
      }
      if (!empty($to_bank_id)) {
        $bankTransfer->where('to_account_id', '=', $to_bank_id);
      }

      $data['transferList'] = $bankTransfer->orderBy('transaction_date', 'desc')->get();
      $data['company_logo']   = Preference::getAll()->where('category','company')->where('field', 'company_logo')->first('value');
      $data['date_range'] =  !empty($from) && !empty($from) ? formatDate($from) . __('To') . formatDate($to) : __('No date selected'); 
      return printPDF($data, 'transfer_list_' . time() . '.pdf', 'admin.transfer.transfer_list_pdf', view('admin.transfer.transfer_list_pdf', $data), 'pdf', 'domPdf');
    }

    public function bankAccTransferCsv()
    {
      return Excel::download(new bankAccountTransfersExport(), 'bank_account_transfer_details'.time().'.csv');
    }


    public function accountCheck(Request $request)
    {
      $data = ['status' => 0 ];
      if (!empty($request->source)) {
        $data['toAccount'] = Account::with('currency:id,name')->where('id', '!=', $request->source)
                            ->select('id','name','currency_id')->where('is_deleted','=', 0)->get();
        $data['status'] = 1;   
      } 
      if (!empty($request->to)) {
        $data['fromAccount'] = Account::with('currency:id,name')->where('id', '!=', $request->to)
                            ->select('id','name','currency_id')->where('is_deleted','=', 0)->get();
        $data['status'] = 2;   
      }
      return $data;
    }

    public function getExchangeRate(Request $request)
    {
      return (new Currency)->getExchangeRate($request->toCurrency, $request->fromCurrency);
    }
}