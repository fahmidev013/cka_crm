<?php

namespace App\Http\Controllers;

use App\DataTables\BankListDataTable;
use App\Exports\bankStatementExport;
use App\Exports\AllBankStatementExport;
use App\Http\Requests;
use App\Http\Start\Helpers;
use App\Models\Account;
use App\Models\Currency;
use App\Models\IncomeExpenseCategory;
use App\Models\AccountType;
use App\Models\Preference;
use App\Models\Transaction;
use App\Models\TransactionReference;
use App\Models\Country;
use Auth;
use DB;
use Excel;
use Illuminate\Http\Request;
use Input;
use PDF;
use Session;
use Validator;

class BankController extends Controller
{

    public function __construct(Account$bank, Transaction $transaction, GeneralLedgerController $glController)
    {
        $this->bank = $bank;
        $this->transaction = $transaction;
        $this->glController = $glController;
    }

    /**
     * Display a list of the Accounts
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BankListDataTable $dataTable)
    {
        $data = [];
        $data['menu'] = 'transaction';
        $data['sub_menu'] = 'bank/list';
        $data['page_title'] = __('Bank Accounts');
        $data['header'] = 'bank';
        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;
        return $dataTable->with('row_per_page', $row_per_page)->render('admin.bank.account_list', $data);
    }

    /**
     * Show the form for creating a new AccountAccount.
     *
     * @return \Illuminate\Http\Response
     */
    public function addAccount()
    {
        $data = [];
        $data['menu'] = 'transaction';
        $data['sub_menu'] = 'bank/list';
        $data['page_title'] = __('Create Bank Account');
        $data['header'] = 'bank';
        $data['currencies']  = Currency::getAll();

        $data['accountTypes'] = AccountType::get();
        return view('admin.bank.account_add', $data);
    }

    /**
     * Store a newly created AccountAccount in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAccount(Request $request)
    {
        $userId = Auth::user()->id;
        $this->validate($request, [
            'account_name'       => 'required',
            'account_type_id'    => 'required',
            'account_no'         => 'required',
            'bank_name'          => 'required',
            'opening_balance'    => 'required',
            'currency_id'        => 'required'
        ]);

        $data = [];
        $data['account_type_id']  = stripBeforeSave($request->account_type_id);
        $data['name']             = stripBeforeSave($request->account_name);
        $data['account_number']   = stripBeforeSave($request->account_no);
        $data['currency_id']      = stripBeforeSave($request->currency_id);
        $data['bank_name']        = stripBeforeSave($request->bank_name);
        $data['branch_name']      = stripBeforeSave($request->branch_name);
        $data['branch_city']      = stripBeforeSave($request->branch_city);
        $data['swift_code']       = stripBeforeSave($request->swift_code);
        $data['bank_address']     = stripBeforeSave($request->bank_address);

        try {
            DB::beginTransaction();
            if ($request->default_account == 1) {
                $updateDefault = Account::where('is_default', 1)->update(['is_default' => 0]);
            }
            $data['is_default'] = $request->default_account;
            $id = DB::table('accounts')->insertGetId($data);
            $reference = TransactionReference::where('reference_type', 'OPENING_BALANCE')->orderBy('id', 'DESC')->first();
            if (!empty($reference)) {
                $info = explode('/', $reference->code);
                $refNo = (int)$info[0];
                $reference = sprintf("%03d", $refNo + 1) . '/' . date('Y');
            } else {
                $reference = sprintf("%03d", 1) . '/' . date('Y');
            }
            $reference_id  = $this->glController->createReference($reference, 'OPENING_BALANCE');

            if (!empty($id)) {
                $trans['amount'] = validateNumbers($request->opening_balance);
                $trans['transaction_type'] = 'Cash-in';
                $trans['transaction_date'] = date('Y-m-d');
                $trans['currency_id'] = $request->currency_id;
                $trans['account_id'] = $id;
                $trans['user_id'] = $userId;
                $trans['transaction_reference_id'] = $reference_id;
                $trans['transaction_method'] = 'OPENING_BALANCE';

                $trans['payment_method_id'] = 1;
                $trans['description'] = 'opening balance';
                $trans['created_at'] = date("Y-m-d H:i:s");

                DB::table('transactions')->insert($trans);
           }
           DB::commit();
        } catch (\Excenption $e) {
            DB::rollBack();
            return redirect('expense/add-expense')->withErrors(__('Failed To Add the Bank Account'));
        }

        Session::flash('success', __('Successfully Saved'));
        return redirect()->intended('bank/list');
    }

    /**
     * Show the form for editing the Account.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editAccount(Request $request, $tab, $account_id)
    {
        $data = [];
        $data['menu'] = 'transaction';
        $data['sub_menu'] = 'bank/list';
        $data['page_title'] = __('Edit Bank Account');
        $data['header'] = 'bank';
        $data['accountTypes'] = DB::table('account_types')->get();
        $data['accountInfo'] = Account::where('id', $account_id)->first();

        if (empty($data['accountInfo'])) {
            return redirect()->back()->with('fail', __('The data you are trying to access is not found.'));
        }

        $data['presentCurrency']  = Currency::find($data['accountInfo']->currency_id);
        $data['currencies']  = Currency::getAll();
        $data['tab'] = $tab;
        $data['startDate'] = $startDate = isset($request->from) ? $request->from : null /*date("Y-m-d", strtotime ("-30 day", strtotime(date('d-m-Y') )))*/;
        $data['endDate'] = $endDate = isset($request->to) ? $request->to : null/*date('Y-m-d')*/;
        $data['type'] = Transaction::distinct()->get(['transaction_method']);
        $data['modeVal']  = $mode = isset($request->mode) ? $request->mode : null;
        $data['typeVal']  = $type = isset($request->type) ? $request->type : null;

        $data['transactionList'] = $this->transaction->getTransactionByAccountId($startDate, $endDate, $account_id, $mode, $type)
                                    ->get();
        $amount = Transaction::select(DB::raw("SUM(amount) as opening_balance"))
              ->whereDate('transaction_date', '<', DbDateFormat($startDate))
              ->where('account_id', $account_id);
        if (!empty($mode) && $mode == 1) {
            $amount->where('amount', '>', 0);
        }
        if (!empty($mode) && $mode == 2) {
            $amount->where('amount', '<=', 0);
        }
        if (!empty($type)) {
            $amount->where('transaction_method', $type);
        }
        $openingBalance = $amount->first()->opening_balance;
        $data['amount'] = !empty($openingBalance) ? $openingBalance : 0;
        $data['account_id'] = $account_id;
        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;

       return view('admin.bank.account_edit', $data);
    }

    /**
     * Update the specified Customer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAccount(Request $request)
    {
        $this->validate($request, [
            'account_name' => 'required',
            'account_type_id' => 'required',
            'account_no' =>'required',
            'bank_name'=>'required',
        ]);

        $data = [];
        $id                       = stripBeforeSave($request->id);
        $data['name']             = stripBeforeSave($request->account_name);
        $data['account_type_id']  = stripBeforeSave($request->account_type_id);
        $data['account_number']   = stripBeforeSave($request->account_no);
        $data['bank_name']        = stripBeforeSave($request->bank_name);
        $data['branch_name']      = stripBeforeSave($request->branch_name);
        $data['branch_city']      = stripBeforeSave($request->branch_city);
        $data['swift_code']       = stripBeforeSave($request->swift_code);
        $data['bank_address']     = stripBeforeSave($request->bank_address);

        if ($request->default_account == 1) {
            $updateDefault = Account::where('is_default', 1)->update(['is_default' => 0]);
        }

        $data['is_default'] = $request->default_account;

        DB::table('accounts')->where('id', $id)->update($data);

        Session::flash('success', __('Successfully updated'));
        return redirect()->intended('bank/list');
    }

    public function destroy($id)
    {
        if (!empty($id)) {
            $data = ['type' => 'fail', 'message'=> __('Can not be deleted. This account has transaction records.')];
            try {
                DB::beginTransaction();
                $record = Transaction::where('account_id', $id)->where('transaction_method', '!=', 'OPENING_BALANCE')->count();
                if ($record == 0) {
                    DB::table('accounts')->where('id', $id)->update(['is_deleted' => 1]);
                    $data = ['type' => 'success', 'message'=> __('Deleted Successfully.')];
                }
                DB::commit();
                \Session::flash($data['type'], $data['message']);
                return redirect()->intended('bank/list');
            } catch (\Exception $e) {
              DB::rollBack();
              return redirect()->back()->withErrors(__('Failed To Delete The Account'));
            }
        }
    }

    public function allBankStatementPdf()
    {
        $data['banks'] = Account::with([
                           'accountType' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'currency' => function ($query) {
                                $query->select('id', 'name');
                            }
                          ])->where('is_deleted','!=', 1)->select('accounts.*')->orderBy('id', 'desc')->get();
        $data['company_logo']   = Preference::getAll()->where('category','company')->where('field', 'company_logo')->first('value');
        return printPDF($data, 'all_bank_statement' . time() . '.pdf', 'admin.bank.all_bank_statement_pdf', view('admin.bank.all_bank_statement_pdf', $data), 'pdf', 'domPdf');
    }

    public function allBankStatementCsv()
    {
        return Excel::download(new AllBankStatementExport(), 'all_bank_statement'. time() .'.csv');
    }

    public function statementPdf()
    {
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['from'] = $from = isset($_GET['from']) ? $_GET['from'] : null;

        $data['to'] = $to = isset($_GET['to']) ? $_GET['to'] : null;
        $data['account_no'] = $account_no = isset($_GET['account_no']) ? $_GET['account_no'] : NULL;
        $mode = isset($_GET['mode']) ?$_GET['mode']: null;
        $type = isset($_GET['type']) ?$_GET['type']: null;

        $data['transactionList'] = $this->transaction->getTransactionByAccountId($from, $to, $account_no, $mode, $type)->orderBy('transaction_date', 'desc')->get();
        $amount = Transaction::select(DB::raw("SUM(amount) as amount"))
              ->whereDate('transaction_date', '<', DbDateFormat($from))
              ->where('account_id', $account_no)->first()->amount;
        $data['amount'] = !empty($amount) ? $amount : 0;

        $data['bankAccounts'] = Account::find($account_no);
        $data['presentCurrency']  = Currency::find($data['bankAccounts']->currency_id);
        $data['date_range'] = (!empty($from) && !empty($to)) ?  formatDate($from) .' To '. formatDate($to) : 'No Date Selected';
        $data['company_logo']   = Preference::getAll()->where('category','company')->where('field', 'company_logo')->first('value');
        return printPDF($data, 'bank_statement' . time() . '.pdf', 'admin.bank.account_statement_pdf', view('admin.bank.account_statement_pdf', $data), 'pdf', 'domPdf');
    }

    public function statementCsv()
    {
        return Excel::download(new bankStatementExport(), 'bank_statement' . time() .'.csv');
    }

    /**
     * [showAccountType description]
     * @return [type] [description]
     */
    public function showAccountType() {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'finance';
        $data['page_title'] = __('Bank Account Type');
        $data['list_menu'] = 'account_type';
        $data['typeNames'] = AccountType::all();

        return view('admin.bankAccountType.account_type_list', $data);
    }

    /**
     * [saveAccountType description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function saveAccountType(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:2',
        ]);
        $validateName = $this->validAccountTypeName(trim($request->name));
        if (empty($validateName)) {
            $accTypeObject = new AccountType();
            $accTypeObject->name = $request->name;
            $accTypeObject->save();

            \Session::flash('success', __('Successfully Saved'));
            return redirect()->intended('bank/account-type');
        } else {
            return back()->withErrors(__('This Account Type is already taken.'));
        }
    }

    /**
     * [editAccountType description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function editAccountType(Request $request)
    {
        $id = $request->id;
        $result = [];
        if (!empty($id)) {
            $taxData = AccountType::find($id);
            $result['name'] = $taxData->name;
            $result['id'] = $taxData->id;
        }
        echo json_encode($result);
    }

    /**
     * [updateAccountType description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateAccountType(Request $request)
    {
        $this->validate($request, [
            'name' => "required|unique:account_types,name,$request->type_id",
            'type_id' => 'required',
        ]);

        $accTypeObject = AccountType::find($request->type_id);
        $accTypeObject->name = $request->name;
        $accTypeObject->save();

        \Session::flash('success', __('Successfully updated'));
        return redirect()->intended('bank/account-type');
    }

    /**
     * destroyAccountType method
     * @param  Request $request
     * @return redirect AccountAccount Type List page view
     */
    public function destroyAccountType(Request $request)
    {
        $data = [
                    'type'    => 'fail',
                    'message' => __('Something went wrong, please try again.')
                ];
        $record            = AccountType::find($request->acc_type_id);
        if (!empty($record)) {
            $bank_account_type = Account::where('account_type_id', $request->acc_type_id)->exists();
            if ($bank_account_type == 'true') {
                $data = [
                    'type'    => 'fail',
                    'message' => __('Can not be deleted. This account type has records!')
                ];
            } else {
                if ($record->delete()) {
                    $data = [
                        'type'    => 'success',
                        'message' => __('Deleted Successfully.')
                    ];
                }
            }
        }
        \Session::flash($data['type'], $data['message']);
        return redirect()->intended('bank/account-type');
    }

    /**
     * [validAccountTypeName description]
     * @param  string $name [description]
     * @return [type]       [description]
     */
    public function validAccountTypeName($name = "")
    {
        $typeName = trim(isset($_GET['name']) ? $_GET['name'] : $name);

        if (isset($_GET['type_id']) && !empty($_GET['type_id'])) {
            $result = AccountType::where('name', '=', $typeName)->where('id', '!=', $_GET['type_id'])->first();
        } else {
            $result = AccountType::where(['name' => $typeName])->first();
        }

        if (!empty($name)) {
            return $result;
        }

        if (!empty($result)) {
            echo json_encode(__('Account Type is already taken.'));
        } else {
            echo "true";
        }
    }

    public function bankAccountValidation(Request $request)
    {
        $data = ['status' => 0];
        $resultAccountNo = 0;
        if (empty($request->id)) {
            if (!empty($request->account_no) && !empty($request->account_name)) {
                $resultAccountNo = Account::where(["account_number" => $request->account_no, "name" => $request->account_name])->count();
            }
            if (!empty($resultAccountNo)) {
                $data['status'] = 1;
            }
        } else {
            if (!empty($request->account_no) && !empty($request->account_name) && !empty($request->id)) {
                $resultAccountNo = Account::where('id', '!=', $request->id)->where(["account_number" => $request->account_no, "name" => $request->account_name])->count();
            }
            if (!empty($resultAccountNo)) {
                $data['status'] = 1;
            }
        }
        return $data;
    }
}
