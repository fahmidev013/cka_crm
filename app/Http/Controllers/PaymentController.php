<?php

namespace App\Http\Controllers;

use App\DataTables\PurchasesPaymentListDataTable;
use App\DataTables\SalesPaymentDataTable;
use App\Exports\allPaymentExport;
use App\Exports\purchasePaymentExport;
use App\Http\Controllers\EmailController;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\CustomerTransaction;
use App\Models\ExchangeRate;
use App\Models\PaymentGateway;
use App\Models\PaymentMethod;
use App\Models\Location;
use App\Models\Preference;
use App\Models\Project;
use App\Models\PurchaseOrder;
use App\Models\Reference;
use App\Models\SaleOrder;
use App\Models\SupplierTransaction;
use App\Models\Supplier;
use App\Models\TransactionReference;
use App\Models\EmailTemplate;
use App\Models\File;
use App\Models\Activity;
use App\Rules\CheckValidEmail;
use Auth;
use DB;
use Excel;
use Illuminate\Http\Request;
use PDF;
use Session;
use Validator;
use App\Rules\UniqueCustomerPaymentReference;
use App\Rules\UniqueSupplierPaymentReference;

class PaymentController extends Controller
{

    public function __construct(Auth $auth, EmailController $email, GeneralLedgerController $glController, TransactionReference $transactionReference, Transaction $transaction, CustomerTransaction $customerTransaction)
    {
        $this->auth     = $auth::user();
        $this->email    = $email;
        $this->glController = $glController;
        $this->transactionReference = $transactionReference;
        $this->transaction = $transaction;
        $this->customerTransaction = $customerTransaction;
    }

    /**
     * Payment list
     */
    public function index(SalesPaymentDataTable $dataTable)
    {
        $data['menu']         = 'sales';
        $data['sub_menu']     = 'payment/list';
        $data['page_title'] = __('Payments');
        $data['customer']     = $customer = isset($_GET['customer']) ? $_GET['customer'] : null;
        $data['method']       = $method   = isset($_GET['method']) ? $_GET['method'] : null;
        $data['currency']     = $method   = isset($_GET['currency']) ? $_GET['currency'] : null;
        $data['status']       = $status   = isset($_GET['status']) ? $_GET['status'] : null;
        $data['customerList'] = DB::table('customers')->select('id', 'name')->where(['is_active' => 1])->get();
        $data['methodList']   = PaymentMethod::getAll();
        $data['currencyList'] = Currency::getAll();

        $data['from'] = isset($_GET['from']) ? $_GET['from'] : null;
        $data['to']   = isset($_GET['to']) ? $_GET['to'] : null;

        $row_per_page = Preference::getAll()->where('field', 'row_per_page')->first()->value;

        return $dataTable->with('row_per_page', $row_per_page)->render('admin.payment.list', $data);
    }

    public function customerPaymentPdf()
    {
        $data['customer']     = $customer     = isset($_GET['customer']) ? $_GET['customer'] : null;
        $data['method']       = $method       = isset($_GET['method']) ? $_GET['method'] : null;
        $data['from']         = $from         = isset($_GET['from']) ? $_GET['from'] : null;
        $data['to']           = $to           = isset($_GET['to']) ? $_GET['to'] : null;
        $data['currency'] =  $currency = isset($_GET['currency']) ? $_GET['currency'] : null;
        $data['status'] = $status = isset($_GET['status']) ? $_GET['status'] : null;

        $salesPayment = CustomerTransaction::orderBy('id','DESC');

        if (isset($customer) && !empty($customer)) {
            $data['customerData'] = Customer::find($customer);
        }
        if (! empty($from) && ! empty($to)) {
            $salesPayment->where('transaction_date', '>=', DbDateFormat($from));
            $salesPayment->where('transaction_date', '<=', DbDateFormat($to));
        }
        if (!empty($customer)) {
            $salesPayment->where('customer_id', '=', $customer);
        }
        if (!empty($method)) {
            $salesPayment->where('payment_method_id', '=', $method);
        }
        if (!empty($currency)) {
            $salesPayment->where('currency_id', '=', $currency);
        }
        if (!empty($status)) {
            $salesPayment->where('status', '=', $status);
        }

        $data['paymentList'] = $salesPayment->get();
        $data['date_range'] = (!empty($from) && !empty($to) ) ? $from . " " . __('To') . " " . $to : __('No Date Selected');
        $data['company_logo']   = Preference::getAll()->where('category','company')->where('field', 'company_logo')->first('value');
        return printPDF($data, 'customer_payment_list' . time() . '.pdf', 'admin.payment.customerPaymentPdf', view('admin.payment.customerPaymentPdf', $data), 'pdf', 'domPdf');
    }

    public function customerPaymentCsv()
    {
        return Excel::download(new allPaymentExport(), 'customer_payment_list'.time().'.csv');
    }

    public function purchPaymentFiltering(PurchasesPaymentListDataTable $dataTable)
    {
        $data['menu']     = 'purchase';
        $data['sub_menu'] = 'purchase_payment/list';
        $data['page_title'] = __('Purchase Payments');

        $data['supplier'] = $supplier = isset($_GET['supplier']) ? $_GET['supplier'] : null;
        $data['method']   = $method   = isset($_GET['method']) ? $_GET['method'] : null;
        $data['currency'] = $method   = isset($_GET['currency']) ? $_GET['currency'] : null;


        $data['supplierList'] = DB::table('suppliers')->select('id', 'name')->get();
        $data['currencyList'] = Currency::getAll();
        $data['locationList'] = Location::getAll();
        $data['methodList']   = PaymentMethod::getAll();


        if (isset($_GET['from'])) {
            $data['from'] = $from = $_GET['from'];
        } else {
             $data['from'] = $from = null;
        }

        if (isset($_GET['to'])) {
            $data['to'] = $to = $_GET['to'];
        } else {
            $data['to'] = $to = null;
        }

        $row_per_page = Preference::getAll()->where('field', 'row_per_page')->first()->value;

        return $dataTable->with('row_per_page', $row_per_page)->render('admin.payment.filterPurchPaymentList', $data);
    }

    public function createPayment(Request $request)
    {
        $userId = Auth::user()->id;
        $paymentType = '';
        $checkExchangeRate = 0;
        $reference = $request->reference;
        $request['user_id']                = Auth::user()->id;
        $request['status']                 = 'Approved';
        if ($request->exchange_rate > 0 && $request->invoice_currency_id != $request->setCurrency) {
            $checkExchangeRate = $request->exchange_rate;
        } else if ($request->exchange_rate > 0 && $request->invoice_currency_id == $request->setCurrency) {
            $checkExchangeRate = 1;
        } else if ($request->exchange_rate <= 0 && $request->invoice_currency_id == $request->setCurrency) {
            $checkExchangeRate = 1;
        }

        $request['exchange_rate']  = $checkExchangeRate;

        switch ($request->payment_type) {
            case 'purchase':
                $paymentType = 'PURCHASE_PAYMENT';
                break;
            case 'invoice':
                $paymentType = 'INVOICE_PAYMENT';
                break;

            default:
                $paymentType = '';
                break;
        }
        if ($paymentType == 'INVOICE_PAYMENT') {
            $this->validate($request, [
                'reference'             => ['required', new UniqueCustomerPaymentReference],
                'amount'                => 'required',
                'payment_date'          => 'required',
                'description'           => 'required',
                'setCurrency'           => 'required',
                'attachment'            => 'mimes:jpg,jpeg,png,gif,docx,xls,xlsx,pdf|max:5000',
            ]);
            try {
                DB::beginTransaction();
                if (validateNumbers($request->amount) <= 0) {
                    $data['status'] = false;
                    $data['message'] = __("Paid amount can not be zero.");
                    return json_encode($data);
                    exit();
                } else if (validateNumbers($request->exchange_rate) <= 0) {
                    $data['status'] = false;
                    $data['message'] = __("Exchange rate can not be zero.");
                    return json_encode($data);
                    exit();
                }

                if (!empty($paymentType)) {
                    $reference_id = $this->transactionReference->createReference($paymentType, $request->invoice_no)->id;
                    if ($reference_id) {
                        $this->transaction->createInvoiceTransaction($request->all(), $reference_id, $paymentType);
                        $customer_transaction_id = $this->customerTransaction->createCustomerTransaction($request->all(), $reference_id, $paymentType);
                    }
                    $request->amount = isset($request->incoming_amount) && !empty($request->incoming_amount) && ($request->exchange_rate != -1) ? $request->incoming_amount : $request->amount;
                    // update paid amount
                    $old_paid_amount = SaleOrder::find($request->invoice_no);
                    $sum = ( (float) $old_paid_amount->paid + validateNumbers($request->amount));
                    $old_paid_amount->paid = $sum;
                    $old_paid_amount->save();

                    # region store files
                    if (isset($customer_transaction_id) && !empty($customer_transaction_id)) {
                        if (!empty($request->attachments)) {
                            $path = createDirectory("public/uploads/invoice_payment");
                            $fileIdList = (new File)->store($request->attachments, $path, 'Invoice Payment', $customer_transaction_id, ['isUploaded' => true, 'isOriginalNameRequired' => true]);
                        }
                    }
                    #end region
                    DB::commit();
                    $data['status'] = true;
                    $data['message'] = __("Payment successful");

                    Session::flash('success', __('Successfully Saved'));
                    return json_encode($data);
                }
            } catch (Exception $e) {
                DB::rollBack();
                $data['status'] = false;
                return json_encode($data);
            }
        } else if ($paymentType == 'PURCHASE_PAYMENT') {
            $this->validate($request, [
                'reference'             => ['required', new UniqueSupplierPaymentReference],
                'amount'                => 'required',
                'payment_date'          => 'required',
                'description'           => 'required',
                'setCurrency'           => 'required',
                'attachment'            => 'mimes:jpg,jpeg,png,gif,docx,xls,xlsx,pdf|max:5000',
            ]);
            $reference = $request->reference;
            $request['user_id']           = Auth::user()->id;
            $request['status']            = 'Approved';
            try {
                DB::beginTransaction();
                if (validateNumbers($request->amount) <= 0) {
                    $data['status'] = false;
                    $data['message'] = __("Paid amount can not be zero.");
                    return json_encode($data);
                    exit();
                } else if (validateNumbers($request->exchange_rate) <= 0) {
                    $data['status'] = false;
                    $data['message'] = __("Exchange rate can not be zero.");
                    return json_encode($data);
                    exit();
                }
                if ($request->setPaymentMethodName == "Bank") {
                    $account = Account::with(['transactions'])->find($request->account_no);
                    if (empty($account) || $account->is_deleted == 1) {
                        $data['status'] = false;
                        $data['message'] = __("Account not found");
                        return json_encode($data);
                        exit();
                    }
                    if ($account->transactions->sum('amount') < $request->amount) {
                        $data['status'] = false;
                        $data['message'] = __("Insufficient balance");
                        return json_encode($data);
                        exit();
                    }
                }

                $reference_id = $this->transactionReference->createReference($paymentType, $request->order_no);
                if (!empty($reference_id)) {
                    $this->glController->createPurchaseBankTransaction($reference_id, $paymentType, $request->all());
                    $supplier_transaction_id = $this->glController->createSupplierTransaction($reference_id, $paymentType, $request->all());
                }

                // update paid amount
                $old_paid_amount = PurchaseOrder::find($request->order_no);
                $sum = ((float) $old_paid_amount->paid + validateNumbers($request->incoming_amount));
                $old_paid_amount->paid = $sum;
                $old_paid_amount->save();

                # region store files
                if (isset($supplier_transaction_id) && !empty($supplier_transaction_id)) {
                    if (!empty($request->attachments)) {
                        $path = createDirectory("public/uploads/purchase_payment");
                        $fileIdList = (new File)->store($request->attachments, $path, 'Purchase Payment', $supplier_transaction_id, ['isUploaded' => true, 'isOriginalNameRequired' => true]);
                    }
                }
                #end region

                DB::commit();
                $data['status'] = true;
                $data['message'] = __("Payment successful");

                Session::flash('success', __('Successfully Saved'));
            } catch (\Exception $e) {
                DB::rollBack();
                $data['status'] = false;
            }
            return json_encode($data);
            exit();
        }
    }

    public function updatePaymentStatus(Request $request)
    {
        $transaction = CustomerTransaction::find($request->transaction_id);
        $transaction->status = $request->status;
        $transaction->save();
        return $transaction->status;
    }

    // Verification of customer bank payment
    /* If Payment Method Bank Settings Approval is Manual then the status will
       Pending and data will not be inserted into transactions table only inserted
       into customer_transactions table. When the status will be Approved then the
       data will be inserted into transactions table and update the sales_order
       table paid value.
    */
    public function paymentVerification(Request $request)
    {
        if ($request->status == 2) {
            $transaction = CustomerTransaction::find($request->customer_transaction_id);
            $confirm = $transaction->update(['status' => 'Declined']);

            $data['status']  = 0;
            if ($confirm) {
                $data['status']  = 1;
            }

            return $data;
        }
        if ($request->status == 1) {
            $transactionData = json_decode($request->customer_transaction_id);
            $toAccount = Account::where('id', $transactionData->account_id)->first();

            try {
                DB::beginTransaction();
                $bankTrans                              = new Transaction;
                $bankTrans->currency_id                 = $toAccount->currency_id;
                $bankTrans->amount                      = $transactionData->amount * $transactionData->exchange_rate;
                $bankTrans->transaction_type            = 'cash-in-by-sale';
                $bankTrans->account_id                  = $transactionData->account_id;
                $bankTrans->transaction_date            = $transactionData->transaction_date;
                $bankTrans->transaction_reference_id    = $transactionData->transaction_reference_id;
                $bankTrans->transaction_method          = 'INVOICE_PAYMENT';
                $bankTrans->payment_method_id           = 2;
                $bankTrans->save();
                // update paid amount
                $old_paid_amount = SaleOrder::find($transactionData->sale_order_id);
                $sum = ( (float) $old_paid_amount->paid + $transactionData->amount);
                $old_paid_amount->paid = $sum;
                $old_paid_amount->save();

                // Insert Activity
                if (isset($transactionData->project_id) && !empty($transactionData->project_id)) {
                    (new Activity)->store('Project', $transactionData->project_id, 'user', Auth::user()->id, __('A new payment of :? has been paid on :x', ['?' => '<strong>'. htmlentities(formatCurrencyAmount($transactionData->amount * $transactionData->exchange_rate, $transactionData->symbol)) .'</strong>', ':x' => $transactionData->sale_order_reference]));
                }

                $transaction = CustomerTransaction::find($transactionData->id);
                $confirm = $transaction->update(['status' => 'Approved']);
                DB::commit();

                $data['status']  = 0;
                if ($confirm) {
                    $data['status']  = 1;
                }

                return $data;
            } catch (Exception $e) {
                DB::rollBack();
            }
        }
    }

    /**
    * Purchase Payment Edit
    **/
    public function purchPaymentEdit($id)
    {
        if (isset($_GET['customer'])) {
            $data['menu'] = 'relationship';
            $data['sub_menu'] = 'customer';
        } else if (isset($_GET['supplier'])) {
            $data['menu'] = 'relationship';
            $data['sub_menu'] = 'supplier';
        } else if (isset($_GET['users'])) {
            $data['menu'] = 'relationship';
            $data['sub_menu'] = 'users';
        } else {
            $data['menu']     = 'purchase';
            $data['sub_menu'] = 'purchase_payment/list';
        }
        $data['page_title'] = __('Edit Payment');
        $id = base64_decode($id);
        $data['bank_accounts'] = Account::where('is_deleted', '!=', 1)->get();
        $data['payment_info']  = SupplierTransaction::with(['purchaseOrder:id,reference'])->find($id);
        if (empty($data['payment_info'])) {
            return redirect('purchase_payment/list')->with('fail', __('The data you are trying to access is not found.'));
        }
        $data['payment_method']= PaymentMethod::getAll();
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['dflt_currency_id'] = $preference['dflt_currency_id'];
        $data['date_format_type'] = $preference['date_format_type'];
        $data['files'] = null;

        $data['files'] = (new File)->getFiles('Purchase Payment', $id);
        if (!empty($data['files'])) {
            $data['filePath'] = "public/uploads/purchase_payment";
            foreach ($data['files'] as $key => $value) {
                $value->icon = getFileIcon($value->file_name);
                $value->extension = strtolower(pathinfo($value->file_name, PATHINFO_EXTENSION));
            }
        }

        return view('admin.payment.purch_pay_edit', $data);
    }

    /**
    *Purchase Payment Update
    **/
    public function purchPaymentUpdate(Request $request)
    {
        $this->validate($request, [
            'amount'           => 'required',
            'transaction_date' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $reference_id = $this->glController->updateSupplierTransaction($request->payment_id);
            $this->glController->updatePurchaseBankTransaction($reference_id, 'PURCHASE_PAYMENT');
            $purchaseOrderInfo = PurchaseOrder::find($request->order_no);
            $purchaseOrderInfo->save();

            #region store files
            if (isset($request->payment_id) && !empty($request->payment_id)) {
                if (!empty($request->attachments)) {
                    $path = createDirectory("public/uploads/purchase_payment");
                    $fileIdList = (new File)->store(array_values(array_filter($request->attachments)), $path, 'Purchase Payment', $request->payment_id, ['isUploaded' => true, 'isOriginalNameRequired' => true]);
                }
            }
            #end region
            DB::commit();
            Session::flash('success', __('Successfully updated'));
            if ($request->sub_menu == "supplier") {
                return redirect()->intended("supplier/payment/". $old_paid_amount->supplier_id);
            } else if ($request->sub_menu == "users") {
                return redirect()->intended("user/user-purchase-payment-list/". $old_paid_amount->$user_id);
            } else {
                return redirect()->intended('purchase_payment/list');
            }
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('fail', __('Update Failed.'));
            return redirect()->back();
        }
    }

    /**
     * Delete purchase payment by id
     **/
    public function purchPaymentdelete(Request $request)
    {
        $flag = 0;
        try {
            DB::beginTransaction();
            $supplier_transaction = SupplierTransaction::find($request->id);
            $transactionReference = TransactionReference::find($supplier_transaction->transaction_reference_id);
            // delete bank transaction
            $bt = Transaction::where(['transaction_reference_id'=> $supplier_transaction->transaction_reference_id, 'transaction_method'=> 'PURCHASE_PAYMENT'])->first();
            if (!empty($bt)) {
                $bt->delete();
            }

            // It will be added later; reverse general ledger

            // update purchase payment amount
            $old_paid_amount = PurchaseOrder::find($supplier_transaction->purchase_order_id);
            if (!empty($old_paid_amount)) {
                $sub = $old_paid_amount->paid - $supplier_transaction->amount;
                $old_paid_amount->paid = $sub;
                $old_paid_amount->save();
            }

            // delete supplier transaction
            $flag = $supplier_transaction->delete();
            $transactionReference->delete();
            DB::commit();

            // delete file region
            $fileIds = array_column(json_decode(json_encode(File::Where(['object_type' => 'Purchase Payment', 'object_id' => $request->id])->get(['id'])), true), 'id');
            if (!empty($fileIds) && $flag) {
                (new File)->deleteFiles('Purchase Payment', $request->id, ['ids' => $fileIds, 'isExceptId' => true], $path = 'public/uploads/purchase_payment');
            }
            # end region
            if ($request->menu == "purchase") {
                Session::flash('success', __('Deleted Successfully.'));
                return redirect()->intended('purchase_payment/list');
            } else {
                Session::flash('success', __('Deleted Successfully.'));
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('fail', __('Delete failed'));
            return redirect()->back();
        }

    }


    public function delete(Request $request)
    {
        $id = $request->id;
        try {
            DB::beginTransaction();
            $customer_transaction = CustomerTransaction::find($id);
            $transactionReference = TransactionReference::find($customer_transaction->transaction_reference_id);
            if ($transactionReference->reference_type == 'POS_PAYMENT') {
                $bt = Transaction::where(['transaction_reference_id' => $customer_transaction->transaction_reference_id, 'transaction_method'=> 'POSINVOICE'])->first();
            } else {
                $bt = Transaction::where(['transaction_reference_id' => $customer_transaction->transaction_reference_id, 'transaction_method'=> 'INVOICE_PAYMENT'])->first();
            }
            if ($bt) {
                $bt->delete();
            }

            // update purchase payment amount
            $old_paid_amount = SaleOrder::find($customer_transaction->sale_order_id);
            $sub             = $old_paid_amount->paid - $customer_transaction->amount;
            $old_paid_amount->paid = $sub;
            $old_paid_amount->save();
            $customer_transaction->delete();
            $transactionReference->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
        if (isset($request->sub_menu) && $request->sub_menu == "customer") {
            Session::flash('success', __('Successfully Saved'));
            return redirect()->back();
        } else {
            Session::flash('success', __('Successfully Saved'));
            return redirect()->intended('payment/list');
        }
    }

    /**
     * Display receipt of payment
     */

    public function viewReceipt($id)
    {
        if (isset($_GET['customer'])) {
            $data = ['menu' => 'relationship', 'sub_menu' => 'customer'];
        } else if (isset($_GET['users'])) {
            $data = ['menu' => 'relationship', 'sub_menu' => 'users'];
        } else {
            $data = ['menu' => 'sales', 'sub_menu' => 'payment/list'];
        }

        $data['page_title'] = __('View Payment');
        $data['invoiceType'] = '';
        $data['paymentInfo'] = CustomerTransaction::find($id);
        if (empty($data['paymentInfo'])) {
            \Session::flash('fail', __('The data you are trying to access is not found.'));
            return redirect()->back();
        }
        $lang = Preference::getAll()->where('field', 'dflt_lang')->first()->value;
        $data['emailInfo'] = EmailTemplate::getAll()
                            ->where('template_id', 1)
                            ->where('language_short_name', $lang)
                            ->first();
        $data['files'] = (new File)->getFiles('Invoice Payment', $id);

        if (!empty($data['files'])) {
            $data['filePath'] = "public/uploads/invoice_payment";
            foreach ($data['files'] as $key => $value) {
                $value->icon = getFileIcon($value->file_name);
                $value->extension = strtolower(pathinfo($value->file_name, PATHINFO_EXTENSION));
            }
        }
        return view('admin.payment.view', $data);
    }

    public function purchaseViewReceipt($id)
    {
        $data = ['files' => []];
        if (isset($_GET['customer'])) {
            $data['menu'] = 'relationship';
            $data['sub_menu'] = 'customer';
        } else if (isset($_GET['supplier'])) {
            $data['menu'] = 'relationship';
            $data['sub_menu'] = 'supplier';
        } else if (isset($_GET['users'])) {
            $data['menu'] = 'relationship';
            $data['sub_menu'] = 'users';
        } else {
            $data['menu']        = 'purchase';
            $data['sub_menu']    = 'purchase_payment/list';
        }
        $data['page_title'] = __('View Payment');
        $data['paymentInfo'] = SupplierTransaction::find($id);

        if (empty($data['paymentInfo'])) {
            Session::flash("fail", __('Payment data not found'));
            return redirect('purchase_payment/list');
        }

        $lang = Preference::getAll()->where('field', 'dflt_lang')->first()->value;
        $data['emailInfo'] = EmailTemplate::getAll()
                            ->where('template_id', 15)
                            ->where('language_short_name', $lang)
                            ->first();

        $data['files'] = (new File)->getFiles('Purchase Payment', $id);
        if (!empty($data['files'])) {
            $data['filePath'] = "public/uploads/purchase_payment";
            foreach ($data['files'] as $key => $value) {
                $value->icon = getFileIcon($value->file_name);
                $value->extension = strtolower(pathinfo($value->file_name, PATHINFO_EXTENSION));
            }
        }

        return view('admin.payment.purchViewReceipt', $data);

    }

    public function purchaseReceiptPdf($id)
    {
        $data['paymentInfo'] = SupplierTransaction::find($id);
        $data['companyInfo']['company'] = Preference::getAll()->where('category', 'company')->pluck('value', 'field')->toArray();
        return printPDF($data, 'purchase_payment_' . time() . '.pdf', 'admin.payment.purchasePaymentReceiptPdf', view('admin.payment.purchasePaymentReceiptPdf', $data), 'pdf', '');
    }

    /**
     * Create receipt of payment
     */

    public function createReceiptPdf($id)
    {

        $data['companyInfo']['company'] = Preference::getAll()->where('category', 'company')->pluck('value', 'field')->toArray();

        $data['paymentInfo'] = CustomerTransaction::find($id);
        $lang = Preference::getAll()->where('field', 'dflt_lang')->first()->value;
        if (strtolower($_GET['type']) == 'print') {
            return printPDF($data, 'payment_' . time() . '.pdf', 'admin.payment.paymentReceiptPdf', view('admin.payment.paymentReceiptPdf', $data), 'print', '');
        } else {
            return printPDF($data, 'payment_' . time() . '.pdf', 'admin.payment.paymentReceiptPdf', view('admin.payment.paymentReceiptPdf', $data), 'pdf', '');
        }
    }

    public function purchasePaymentPrint($id)
    {
        $data['paymentInfo'] = SupplierTransaction::find($id);
        $data['companyInfo']['company'] = Preference::getAll()->where('category', 'company')->pluck('value', 'field')->toArray();
        return printPDF($data, 'purchase_payment_' . time() . '.pdf', 'admin.payment.purchasePaymentReceiptPrint', view('admin.payment.purchasePaymentReceiptPrint', $data), 'print', '');
    }

    /**
     * Send email to customer for payment information
     */
    public function sendPaymentInformationByEmail(Request $request)
    {
        $this->validate($request, [
            'email' => ['required', new CheckValidEmail],
            'subject' => 'required',
            'message' => 'required'
        ]);
        $id = $request['id'];
        ini_set('max_execution_time', 0);
        $orderNo = $request['quotationRef'];
        $invoiceNo = $request['invoiceRef'];
        $invoiceName = 'payment_' . time() . '.pdf';
        $emailConfig = DB::table('email_configurations')->first();
        $companyName = Preference::getAll()->where('category','company')->where('field', 'company_name')->first()->value;


        if (isset($emailConfig->protocol) && $emailConfig->protocol == 'smtp') {
            return back()->withInput()->withErrors(['email' => "Verify your smtp settings of email"]);
        }
        if (isset($request['payment_pdf']) && $request['payment_pdf'] == 'on') {
            $this->paymentPdfEmail($id, $invoiceName);
            $emailResponse = $this->email->sendEmailWithAttachment($request['email'], $request['subject'], $request['message'], $invoiceName, $companyName);

            if ($emailResponse['status'] == false) {
                return redirect()->back()->withInput()->withErrors(['fail' => $emailResponse['message']]);
             }
        } else {
            $emailResponse = $this->email->sendEmail($request['email'], $request['subject'], $request['message'], null, $companyName);
            if ($emailResponse['status'] == false) {
                return redirect()->back()->withInput()->withErrors(['fail' => $emailResponse['message']]);
             }
        }

        Session::flash('success', __('Email has been sent successfully.'));
        return redirect()->intended('payment/view-receipt/' . $request['id']);
    }

    public function paymentPdfEmail($id, $invoiceName)
    {
        $data['paymentInfo'] = CustomerTransaction::find($id);
        $data['companyInfo']['company'] = Preference::getAll()->where('category', 'company')->pluck('value', 'field')->toArray();
        $pdf = PDF::loadView('admin.payment.paymentReceiptPdf', $data);
        $pdf->setPaper(array(0, 0, 750, 1060), 'portrait');
        return $pdf->save(public_path() . '/uploads/invoices/' . $invoiceName);
    }

    public function emailPurchPaymentInformation(Request $request)
    {
        $this->validate($request, [
            'email' => ['required', new CheckValidEmail],
            'subject' => 'required',
            'message' => 'required'
        ]);
        $id          = $request['id'];
        $invoiceName = 'payment' . time() . '.pdf';
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $emailConfig = DB::table('email_configurations')->first();
        if ($emailConfig->status == 0 && $emailConfig->protocol == 'smtp') {
            return back()->withInput()->withErrors(['email' => "Verify your smtp settings of email"]);
        }

        if (isset($request['payment_pdf']) && $request['payment_pdf'] == 'on') {
            $this->purchasePaymentPdfEmail($id, $invoiceName);
            $emailResponse = $this->email->sendEmailWithAttachment($request['email'], $request['subject'], $request['message'], $invoiceName, $preference['company_name']);

            if ($emailResponse['status'] == false) {
                return redirect()->back()->withInput()->withErrors(['fail' => $emailResponse['message']]);
             }
        } else {
            $emailResponse = $this->email->sendEmail($request['email'], $request['subject'], $request['message'], null, $preference['company_name']);

            if ($emailResponse['status'] == false) {
                return redirect()->back()->withInput()->withErrors(['fail' => $emailResponse['message']]);
             }
        }
        Session::flash('success', __('Email has been sent successfully.'));
        return redirect()->intended('purchase_payment/view_receipt/' . $request['id']);
    }

    public function purchasePaymentPdfEmail($id, $invoiceName)
    {
        $data['paymentInfo'] = SupplierTransaction::find($id);
        $data['companyInfo']['company'] = Preference::getAll()->where('category', 'company')->pluck('value', 'field')->toArray();
        $data['emailPdf'] = 'pdfEmail';
        $pdf = PDF::loadView('admin.payment.purchasePaymentReceiptPdf', $data);
        $pdf->setPaper(array(0, 0, 750, 1060), 'portrait');
        return $pdf->save(public_path() . '/uploads/invoices/' . $invoiceName);
    }

    /**
     * Pay all amount
     */
    public function payAllAmount($order_no)
    {
        $allInvoiced = DB::table('sales_orders')->where('order_reference_id', $order_no)->select('id as inv_no', 'order_reference', 'reference', 'customer_id', 'payment_id', 'total as invoiced_amount', 'paid_amount')->get();
        foreach ($allInvoiced as $key => $value)
        {
            $amount = ($value->invoiced_amount - $value->paid_amount);
            DB::table('sales_orders')->where('id', $value->inv_no)->update(['paid_amount' => $value->invoiced_amount]);
            if (abs($amount) >= 0) {
                $payment[$key]['invoice_reference'] = (string) $value->reference;
                $payment[$key]['order_reference']   = (string) $value->order_reference;
                $payment[$key]['payment_type_id']   = $value->payment_id;
                $payment[$key]['amount']            = $amount;
                $payment[$key]['payment_date']      = DbDateFormat(date('d-m-Y'));
                $payment[$key]['reference']         = 'by all pay';
                $payment[$key]['user_id']         = $this->auth->id;
                $payment[$key]['customer_id']       = $value->customer_id;
                $payments = DB::table('payment_history')->insertGetId($payment[$key]);
            }
        }
        Session::flash('success', __('Payments completed successfully'));
        return redirect()->intended('order/view-order-details/' . $order_no);
    }

    public function editPayment($id)
    {
        if (isset($_GET['customer'])) {
            $data['menu'] = 'relationship';
            $data['sub_menu'] = 'customer';
        } else if (isset($_GET['users'])) {
            $data['menu'] = 'relationship';
            $data['sub_menu'] = 'users';
        } else {
            $data['menu']     = 'sales';
            $data['sub_menu'] = 'payment/list';
        }
        $data['page_title'] = __('Edit Payment');
        $id = base64_decode($id);
        $data['bank_accounts'] = Account::where('is_deleted', '!=', 1)->get();
        $data['payment_info']  = CustomerTransaction::find($id);
        if (empty($data['payment_info'])) {
            return redirect('/payment/list')->with('fail', __('The data you are trying to access is not found.'));
        }
        $data['payment_method']= PaymentMethod::getAll();
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['dflt_currency_id'] = $preference['dflt_currency_id'];

        $data['files'] = (new File)->getFiles('Invoice Payment', $id);
        if (!empty($data['files'])) {
            $data['filePath'] = "public/uploads/invoice_payment";
            foreach ($data['files'] as $key => $value) {
                $value->icon = getFileIcon($value->file_name);
                $value->extension = strtolower(pathinfo($value->file_name, PATHINFO_EXTENSION));
            }
        }

        return view('admin.payment.edit-payment', $data);
    }

    public function updatePayment(Request $request)
    {
        $this->validate($request, [
            'amount'           => 'required',
            'transaction_date' => 'required',
        ]);
        try {
            DB::beginTransaction();
            # region store files
            if (isset($request->payment_id) && !empty($request->payment_id)) {
                if (!empty($request->attachments)) {
                    $path = "public/uploads/invoice_payment";
                    $fileIdList = (new File)->store(array_values(array_filter($request->attachments)), $path, 'Invoice Payment', $request->payment_id, ['isUploaded' => true, 'isOriginalNameRequired' => true]);
                }
            }
            # end region
            DB::commit();
            Session::flash('success', __('Payments completed successfully'));
            if ($request->sub_menu == 'customer') {
                return redirect()->intended("customer/payment/". $request->customer);
            } else if ($request->sub_menu == 'users') {
                return redirect()->intended("user/user-payment-list/". $user_id);
            } else {
                return redirect()->intended('payment/list');
            }
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('fail', __('Payment Failed'));
            return redirect()->intended('payment/list');
        }
    }

    public function  supplierPaymentPdf()
    {
        $supplier = isset($_GET['supplier']) ? $_GET['supplier'] : null;
        $method   = isset($_GET['method']) ? $_GET['method'] : null;
        $currency = isset($_GET['currency']) ? $_GET['currency'] : null;
        $from     = isset($_GET['from']) ? $_GET['from'] : null;
        $to       = isset($_GET['to']) ? $_GET['to'] : null;
        if (isset($supplier) && !empty($supplier)) {
            $data['supplierData'] = Supplier::find($supplier);
        }

        $purchasesPayment = (new SupplierTransaction)->getAll($supplier, $method, $currency, $from, $to);
        $data['paymentList']  = $purchasesPayment->get();

        if ($from && $to) {
           $data['date_range']   = $from . __('To') . $to;
        } else {
           $data['date_range']   = __('No Date Selected');
        }
        return printPDF($data, 'supplier_payment_' . time() . '.pdf', 'admin.payment.supplierPaymentPdf', view('admin.payment.supplierPaymentPdf', $data), 'pdf', 'domPdf');

    }

    public function supplierPaymentCsv()
    {
      return Excel::download(new purchasePaymentExport(), 'purchase_payment_details'. time() .'.csv');
    }

    public function getExchangeRate(Request $request)
    {
        return (new Currency)->getExchangeRate($request->toCurrency, $request->fromCurrency);
    }
}
