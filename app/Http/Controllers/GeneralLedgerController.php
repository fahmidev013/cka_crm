<?php

namespace App\Http\Controllers;

use App\DataTables\GlTransactionDataTable;
use App\Exports\glTransactionExport;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Transfer;
use App\Models\CustomerTransaction;
use App\Models\Deposit;
use App\Models\ExchangeRate;
use App\Models\Expense;
use App\Models\GeneralLedger;
use App\Models\IncomeExpenseCategory;
use App\Models\Preference;
use App\Models\Reference;
use App\Models\SupplierTransaction;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;

class GeneralLedgerController extends Controller
{
    public function __construct()
    {


    }

    public function createReference($reference_in, $type, $obejctId = null)
    {
        try {
            $reference['reference_type'] = $type;
            $reference['code'] = $reference_in;
            $reference['object_id'] = $obejctId;
            $insert_id = DB::table('transaction_references')->insertGetId($reference);
            return $insert_id;
        } catch (Exception $e) {
            return false;
        }

    }

    public function createBankDeposit($data, $reference_id, $reference_type)
    {
        $deposit                             = new Deposit;
        $deposit->account_id                 = $data['account_no'];
        $deposit->user_id                    = Auth::user()->id;
        $deposit->income_expense_category_id = $data['category_id'];
        $deposit->transaction_reference_id   = $reference_id;
        $deposit->payment_method_id          = $data['payment_method'];
        $deposit->transaction_date           = DbDateFormat($data['trans_date']);
        if (!is_null($data['description'])) {
            $deposit->description                = $data['description'];
        }
        $deposit->amount                     = validateNumbers($data['amount']);
        $deposit->save();
        return $deposit->id;
    }

    public function createDepositBankTransaction($data, $req = null, $reference_id, $reference_type)
    {
        $bankTrans                           = new Transaction;
        $bankTrans->currency_id              = $data['currency_id'];
        $bankTrans->amount                   = validateNumbers($data['amount']);
        $bankTrans->transaction_type         = 'deposit';
        $bankTrans->account_id               = $data['account_no'];
        $bankTrans->transaction_date         = DbDateFormat($data['trans_date']);
        $bankTrans->user_id                  = Auth::user()->id;
        $bankTrans->transaction_reference_id = $reference_id;
        $bankTrans->transaction_method       = $reference_type;
        if (!is_null($data['description'])) {
            $bankTrans->description                = $data['description'];
        }
        $bankTrans->payment_method_id        = $data['payment_method'];
        $bankTrans->save();

        return $bankTrans->id;
    }

    public function createDepositGeneralLedger($data, $reference_id, $reference_type)
    {
        $bank_account_info = DB::table('accounts')
                           ->where(['id' => $data['account_no']])
                           ->first();
         // Credit
         $glCredit = new GeneralLedger;
         $glCredit->reference_id     = $reference_id;
         $glCredit->reference_type   = $reference_type;
         $glCredit->user_id          = Auth::user()->id;
         $glCredit->gl_account_id    = $data['category_id'];
         $glCredit->currency_id      = $data['currency_id'];
         $glCredit->exchange_rate    = $data['exchange_rate'];
         $glCredit->amount           = '-'.$data['amount']*$data['exchange_rate'];
         $glCredit->comment          = '-';
         $glCredit->transaction_date =  DbDateFormat($data['trans_date']);
         $glCredit->save();

         // Debit
         $glDebit = new GeneralLedger;
         $glDebit->reference_id     = $reference_id;
         $glDebit->reference_type   = $reference_type;
         $glDebit->user_id          = Auth::user()->id;
         $glDebit->gl_account_id    = $bank_account_info->gl_account_id;
         $glDebit->currency_id      = $data['currency_id'];
         $glDebit->exchange_rate    = $data['exchange_rate'];
         $glDebit->amount           = $data['amount']*$data['exchange_rate'];
         $glDebit->comment          = $data['description'];
         $glDebit->transaction_date =  DbDateFormat($data['trans_date']);
         $glDebit->save();
    }


    public function createExpense($data, $reference_id,$transInsertId, $type)
    {
        $expense                             = new Expense;
        $expense->transaction_id             = $transInsertId;
        $expense->user_id                    = Auth::user()->id;
        $expense->transaction_reference_id   = $reference_id;
        $expense->income_expense_category_id = (int) $data['category_id'];
        $expense->currency_id                = !empty($data['currency']) ? $data['currency'] : $data['currency_id'];
        $expense->payment_method_id          = !empty($data['payment_method_id']) ? $data['payment_method_id'] : null;
        $expense->amount                     = validateNumbers($data['amount']);
        if (!is_null($data['description'])) {
            $expense->note = $data['description'];
        }
        $expense->transaction_date           = DbDateFormat($data['trans_date']);
        $expense->save();

        return $expense->id;
    }


    public function createExpenseBankTransaction(Request $request, $data, $reference_id, $reference_type)
    {
        $bankTrans                           = new Transaction;
        $bankTrans->amount                   = '-'.validateNumbers($data['amount']);
        $bankTrans->transaction_type         = 'expense';
        $bankTrans->account_id               = !empty($data['account_no']) ? $data['account_no'] : null;
        $bankTrans->user_id                  = Auth::user()->id;
        $bankTrans->transaction_date         = DbDateFormat($data['trans_date']);
        $bankTrans->transaction_reference_id = $reference_id;
        $bankTrans->transaction_method       = $reference_type;
        if (!is_null($data['description'])) {
            $bankTrans->description = $data['description'];
        }
        $bankTrans->currency_id              = !empty($data['currency_id']) ? $data['currency_id'] : $data['currency'];
        $bankTrans->payment_method_id        = !empty($data['payment_method_id']) ? $data['payment_method_id'] : null;
        $bankTrans->save();
        return $bankTrans->id;
    }

    public function createExpenseGeneralLedger($data, $reference_id, $reference_type)
    {
        $bank_account_info = DB::table('bank_accounts')
                       ->where(['id' => $data['account_no']])
                       ->first();
        // Credit
        $glCredit = new GeneralLedger;
        $glCredit->reference_id     = $reference_id;
        $glCredit->reference_type   = $reference_type;
        $glCredit->user_id          = Auth::user()->id;
        $glCredit->gl_account_id    = $bank_account_info->gl_account_id;
        $glCredit->currency_id      = $data['currency_id'];
        $glCredit->exchange_rate    = $data['exchange_rate'];
        $glCredit->amount           = '-'.$data['amount'] * $data['exchange_rate'];
        $glCredit->comment          = $data['description'];
        $glCredit->transaction_date =  DbDateFormat($data['trans_date']);
        $glCredit->save();

        // Debit
        $glDebit = new GeneralLedger;
        $glDebit->reference_id     = $reference_id;
        $glDebit->reference_type   = $reference_type;
        $glDebit->user_id          = Auth::user()->id;
        $glDebit->gl_account_id    = $data['category_id'];
        $glDebit->currency_id      = $data['currency_id'];
        $glDebit->exchange_rate    = $data['exchange_rate'];
        $glDebit->amount           = $data['amount'] * $data['exchange_rate'];
        $glDebit->comment          = '-';
        $glDebit->transaction_date = DbDateFormat($data['trans_date']);
        $glDebit->save();
    }


    public function createBankTransfer($reference_id,$reference_type, $request)
    {
        $fromCurrency = $request['outgoing_currency_id'];
        $toCurrency   = $request['incoming_currency_id'];

        $bankTransfer                           = new Transfer;
        $bankTransfer->from_account_id          = $request['source'];
        $bankTransfer->to_account_id            = $request['destination'];
        $bankTransfer->user_id                  = Auth::user()->id;
        $bankTransfer->from_currency_id         = $request['outgoing_currency_id'];
        $bankTransfer->to_currency_id           = $request['incoming_currency_id'];
        $bankTransfer->transaction_date         = DbDateFormat($request['trans_date']);
        $bankTransfer->transaction_reference_id = $reference_id;
        $bankTransfer->description              = $request['description'];
        $bankTransfer->amount                   = validateNumbers($request['amount']);
        $bankTransfer->fee                      = validateNumbers($request['bank_charge']);
        $bankTransfer->exchange_rate            = !empty($request['exchange_rate']) ? validateNumbers($request['exchange_rate']) : null;
        $bankTransfer->incoming_amount          = !empty($request['incoming_amount']) ? validateNumbers($request['incoming_amount']) : validateNumbers($request['amount']);
        $bankTransfer->save();

        return $bankTransfer->id;
    }

    public function createBankTransferTransaction($reference_id, $reference_type, $request)
    {
        $fromCurrency = $request['outgoing_currency_id'];
        $toCurrency   = $request['incoming_currency_id'];
        // check if both currency is same
        if ($fromCurrency == $toCurrency) {
            $toAmount   = validateNumbers($request['amount']);
            $fromAmount = validateNumbers($request['amount']) + validateNumbers($request['bank_charge']);
        } else {
            // check if source currency is default currency
            $fromAmount = validateNumbers($request['amount']) + validateNumbers($request['bank_charge']);
            $toAmount   = validateNumbers($request['incoming_amount']);
        }


        // From Account
        $fromBankTrans                           = new Transaction;
        $fromBankTrans->currency_id              = $fromCurrency;
        $fromBankTrans->amount                   = '-'.$fromAmount;
        $fromBankTrans->transaction_type         = 'cash-out-by-transfer';
        $fromBankTrans->account_id               = $request['source'];
        $fromBankTrans->transaction_date         = DbDateFormat($request['trans_date']);
        $fromBankTrans->user_id                  = Auth::user()->id;
        $fromBankTrans->transaction_reference_id = $reference_id;
        $fromBankTrans->transaction_method       = $reference_type;
        $fromBankTrans->description              = $request['description'];
        $fromBankTrans->payment_method_id        = 2;
        $fromBankTrans->save();

        $toBankTrans                           = new Transaction;
        $toBankTrans->currency_id            = $toCurrency;
        $toBankTrans->amount                   = $toAmount;
        $toBankTrans->transaction_type         = 'cash-in-by-transfer';
        $toBankTrans->account_id               = $request['destination'];
        $toBankTrans->transaction_date         = DbDateFormat($request['trans_date']);
        $toBankTrans->user_id                  = Auth::user()->id;
        $toBankTrans->transaction_reference_id = $reference_id;
        $toBankTrans->transaction_method       = $reference_type;
        $toBankTrans->description              = $request['description'];
        $toBankTrans->payment_method_id        = 2;
        $toBankTrans->save();

    }

    public function updateBankDeposit($request)
    {
        $deposits                             = Deposit::find($request['id']);
        $deposits->account_id                 = $request['account_no'];
        $deposits->user_id                    = Auth::user()->id;
        $deposits->income_expense_category_id = $request['category_id'];
        $deposits->transaction_reference_id   = $request['reference_id'];
        $deposits->payment_method_id          = $request['payment_method'];
        $deposits->transaction_date           = DbDateFormat($request['transaction_date']);
        $deposits->description                = !empty($request['description']) ? $request['description'] : null;
        $deposits->amount                     = validateNumbers($request['amount']);
        $deposits->save();
    }

    public function updateDepositBankTransaction($request)
    {
        // Ubdate Bank Transaction
        Transaction::where([
            'transaction_reference_id' => $request['reference_id'],
            'transaction_type'         => 'deposit'
        ])->update([
            'amount'                     => validateNumbers($request['amount']),
            'payment_method_id'          =>  $request['payment_method'],
            'user_id'                    => Auth::user()->id,
            'description'                => !is_null($request['description']) ? $request['description'] : '',
            'transaction_date'           => DbDateFormat($request['transaction_date']),
        ]);
    }

    public function updateExpense($transactionId, $request)
    {
        $id                                  = $request['id'];
        $expense                             = Expense::find($id);
        $expense->transaction_id             = $transactionId;
        $expense->user_id                    = Auth::user()->id;
        $expense->transaction_reference_id   = $request['reference_id'];
        $expense->income_expense_category_id = $request['category_id'];
        $expense->currency_id                = !empty($request['currency']) ? $request['currency'] : $request['currency_id'];
        $expense->payment_method_id          = !empty($request['payment_method_id']) ?  $request['payment_method_id'] : null;
        $expense->amount                     = validateNumbers($request['amount']);
        $expense->note = !empty($request['description']) ? $request['description'] : null;
        $expense->transaction_date           = DbDateFormat($request['trans_date']);
        $expense->save();
    }

    public function updateExpenseTransaction($request)
    {
         if (!empty($request['bank_transaction_id'])) {
            $transaction_id = $request['bank_transaction_id'];
            $bankTrans = Transaction::find($transaction_id);
            $bankTrans->amount                   = '-'.validateNumbers($request['amount']);
            $bankTrans->transaction_type         = 'expense';
            $bankTrans->account_id               = !empty($request['acc_no']) ?  $request['acc_no'] : ((!empty($request['account_number']) && $request['payment_method'] == 'Bank') ? $request['account_number'] : null);
            $bankTrans->transaction_date         = DbDateFormat($request['trans_date']);
            $bankTrans->user_id                  = Auth::user()->id;
            $bankTrans->transaction_reference_id = $request['reference_id'];
            $bankTrans->transaction_method       = "EXPENSE";
            if (!is_null($request['description'])) {
                $bankTrans->description = $request['description'];
            }
            $bankTrans->payment_method_id        = !empty($request['payment_method_id']) ?  $request['payment_method_id'] : null;
            $bankTrans->currency_id              = !empty($request['currency']) ? $request['currency'] : $request['currency_id'];
            $bankTrans->save();
            return $bankTrans->id;
        }

    }


    public function deleteTransferGeneralLedger($reference_id, $reference_type, $reference)
    {
        // Reverse Bank Transfer
        $gl = GeneralLedger::where(['reference_id'=> $reference_id, 'reference_type'=> $reference_type, 'is_reversed'=> 'no'])->get();
        if (count($gl) > 0) {
            foreach ($gl as $key => $value) {
                $glCredit = new GeneralLedger;
                $glCredit->reference_id     = $value->reference_id;
                $glCredit->reference_type   = $value->reference_type;
                $glCredit->user_id          = Auth::user()->id;
                $glCredit->gl_account_id    = $value->gl_account_id;
                $glCredit->currency_id      = $value->currency_id;
                $glCredit->exchange_rate    = $value->exchange_rate;
                $glCredit->amount           = -($value->amount);
                $glCredit->comment          = $reference.'(reversal)';
                $glCredit->transaction_date =  date("Y-m-d");
                $glCredit->is_reversed      =  'yes';
                $glCredit->save();
                // Status Update
                GeneralLedger::where('id',$value->id)->update(['is_reversed'=> 'yes']);
            }
        }
    }

    public function deleteCustomerTransactionGeneralLedger($reference_id, $reference_type, $reference)
    {
        // Reverse old Transfer
        $gl = GeneralLedger::where(['reference_id'=> $reference_id, 'reference_type'=> $reference_type, 'is_reversed'=> 'no'])->get();
        if (count($gl) > 0) {
            foreach ($gl as $key => $value) {
                $glCredit = new GeneralLedger;
                $glCredit->reference_id     = $value->reference_id;
                $glCredit->reference_type   = $value->reference_type;
                $glCredit->user_id          = Auth::user()->id;
                $glCredit->gl_account_id    = $value->gl_account_id;
                $glCredit->currency_id      = $value->currency_id;
                $glCredit->exchange_rate    = $value->exchange_rate;
                $glCredit->amount           = -($value->amount);
                $glCredit->comment          = $reference.'(reversal)';
                $glCredit->transaction_date =  date("Y-m-d");
                $glCredit->is_reversed      =  'yes';
                $glCredit->save();
                // Status Update
                GeneralLedger::where('id',$value->id)->update(['is_reversed'=> 'yes']);
            }
        }
        return true;
    }


    public function createPurchaseBankTransaction($reference, $reference_type, $request)
    {
        $amount = validateNumbers($request['amount']);
        $exchangeRate = validateNumbers($request['exchange_rate']);
        $accountBalance = $request['set_account_balance'];
        $bankTrans                              = new Transaction;
        $bankTrans->currency_id                 = $request['setCurrency'];
        $bankTrans->amount                      = '-'. abs($amount);
        $bankTrans->transaction_type            = 'cash-out-by-purchase';
        $bankTrans->account_id                  = (isset($request['account_no']) && !empty($request['account_no'])) ? $request['account_no'] : null;
        $bankTrans->transaction_date            = DbDateFormat($request['payment_date']);
        $bankTrans->user_id                     = Auth::user()->id;
        $bankTrans->transaction_reference_id    = $reference->id;
        $bankTrans->transaction_method          = 'PURCHASE_PAYMENT';
        $bankTrans->description                 = $request['description'];
        $bankTrans->payment_method_id           = (isset($request['payment_type_id']) && !empty($request['payment_type_id'])) ? $request['payment_type_id'] : null;
        $bankTrans->save();
        if ($bankTrans->id <= 0) {
            return false;
        }
        return $bankTrans->id;
    }


    public function updatePurchaseBankTransaction($reference_id, $type)
    {
        $bankTrans = Transaction::where(['transaction_reference_id'=> $reference_id, 'transaction_method'=> $type])->first();
        if (!empty($bankTrans)) {
            $bankTrans->user_id                  = Auth::user()->id;
            $bankTrans->save();
        }

    }


    public function createSupplierTransaction($reference, $reference_type, $request)
    {
        $supplier_transaction                               = new SupplierTransaction;
        $supplier_transaction->user_id                      = Auth::user()->id;
        $supplier_transaction->transaction_reference_id     = $reference->id;
        $supplier_transaction->currency_id                  = $request['invoice_currency_id'];
        $supplier_transaction->supplier_id                  = $request['supplier_id'];
        $supplier_transaction->purchase_order_id            = $request['order_no'];
        $supplier_transaction->payment_method_id            = isset($request['payment_type_id']) && !empty($request['payment_type_id']) ? $request['payment_type_id'] : null;
        $supplier_transaction->transaction_date             = DbDateFormat($request['payment_date']);
        $supplier_transaction->amount                       = validateNumbers($request['incoming_amount']);
        $supplier_transaction->exchange_rate                = validateNumbers($request['exchange_rate']);
        $supplier_transaction->save();

        return $supplier_transaction->id;
    }


    public function updateSupplierTransaction($payment_id)
    {
        $supplier_transaction = SupplierTransaction::find($payment_id);
        $supplier_transaction->user_id           = Auth::user()->id;
        $supplier_transaction->save();
        return $supplier_transaction->transaction_reference_id;
    }


    public function deleteSupplierTransactionGeneralLedger($reference_id, $reference_type, $reference)
    {
        // Reverse old Transfer
        $gl = GeneralLedger::where(['reference_id'=> $reference_id, 'reference_type'=> $reference_type, 'is_reversed'=> 'no'])->get();
        if (count($gl) > 0) {
            foreach ($gl as $key => $value) {
                $glCredit = new GeneralLedger;
                $glCredit->reference_id     = $value->reference_id;
                $glCredit->reference_type   = $value->reference_type;
                $glCredit->user_id          = Auth::user()->id;
                $glCredit->gl_account_id    = $value->gl_account_id;
                $glCredit->currency_id      = $value->currency_id;
                $glCredit->exchange_rate    = $value->exchange_rate;
                $glCredit->amount           = -($value->amount);
                $glCredit->comment          = $reference.'(reversal)';
                $glCredit->transaction_date =  date("Y-m-d");
                $glCredit->is_reversed      =  'yes';
                $glCredit->save();
                // Status Update
                GeneralLedger::where('id',$value->id)->update(['is_reversed'=> 'yes']);
            }
        }
        return true;
    }

    public function createPOSBankTransaction($amount, $account_no, $date, $reference_id, $reference_type, $reference, $description, $payment_method)
    {
        $bankTrans = new BankTransaction;
        $bankTrans->amount       = $amount;
        $bankTrans->trans_type   = 'cash-in-by-sale';
        $bankTrans->account_no   = $account_no;
        $bankTrans->trans_date   = $date;
        $bankTrans->user_id      = Auth::user()->id;
        $bankTrans->reference_id = $reference_id;
        $bankTrans->type         = $reference_type;
        $bankTrans->reference    = $reference;
        $bankTrans->description  = $description;
        $bankTrans->payment_method = $payment_method;
        $bankTrans->save();
        return $bankTrans->id;
    }



    public function getTodayExchangeRate(Request $request)
    {

        $exchange_rate = ExchangeRate::where('currency_id', $request->currency_id)->orderBy('date', 'desc')->first();
        if ($Exchange_rate) {
            $data['exchange_rate'] = $exchange_rate->rate;
        }else{
            $data['exchange_rate'] = 1;
        }
        return $data;
    }

}
