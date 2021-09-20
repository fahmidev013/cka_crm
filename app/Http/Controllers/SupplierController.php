<?php

namespace App\Http\Controllers;

use App\DataTables\SupplierLedgerDataTable;
use App\DataTables\SupplierListDataTable;
use App\DataTables\SupplierOrderListDataTable;
use App\DataTables\SupplierPaymentDataTable;
use App\Exports\SupplierExport;
use App\Exports\supplierLedgerCsv;
use App\Exports\supplierPurchaseOrderExport;
use App\Exports\supplierPaymentExport;
use App\Exports\allSupplierExport;
use App\Http\Requests;
use App\Http\Start\Helpers;
use App\Models\Currency;
use App\Models\Payment;
use App\Models\Preference;
use App\Models\Country;
use App\Models\PurchaseOrder;
use App\Models\SupplierTransaction;
use App\Rules\CheckValidEmail;
use DB;
use Excel;
use Illuminate\Http\Request;
use Input;
use PDF;
use Exception;
use Session;
use Validator;
use App\Models\Supplier;
use App\Models\EmailTemplate;
use App\Http\Controllers\EmailController;

class SupplierController extends Controller
{
    public function __construct(SupplierOrderListDataTable $supplierOrderListDataTable, SupplierLedgerDataTable $supplierLedgerDataTable, SupplierPaymentDataTable $supplierPaymentDataTable, EmailController $email)
    {
        $this->supplierOrderListDataTable = $supplierOrderListDataTable;
        $this->supplierLedgerDataTable    = $supplierLedgerDataTable;
        $this->supplierPaymentDataTable   = $supplierPaymentDataTable;
        $this->email     = $email;
    }

    /**
     * Display a listing of the Customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SupplierListDataTable $dataTable)
    {
        $data['menu']             = 'relationship';
        $data['sub_menu']         = 'supplier';
        $data['page_title'] = __('Suppliers');

        $data['supplierCount']    = DB::table('suppliers')->count();
        $data['supplierActive']   = DB::table('suppliers')->where('is_active', 1)->count();
        $data['supplierInActive'] = DB::table('suppliers')->where('is_active', 0)->count();

        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;

        return $dataTable->with('row_per_page', $row_per_page)->render('admin.supplier.supplier_list', $data);
    }

    public function changeStatus(Request $request)
    {
        $customer = Supplier::find($request->id);
        $customer->is_active = $request->status;
        $customer->save();

        $data['supplierCount'] = Supplier::all()->count();
        $data['supplierActive'] = Supplier::where('is_active', 1)->count();
        $data['supplierInActive'] = Supplier::where('is_active', 0)->count();
        $data['status'] = 'success';
        return $data;
    }

    public function orderList($id)
    {
        $data['menu'] = 'relationship';
        $data['sub_menu'] = 'supplier';
        $data['page_title'] = __('Supplier Purchase Orders');
        $data['tab'] = 'supplier_orders';
        $data['supplierid'] = $id;
        $data['supplierData'] = DB::table('suppliers')->where('id', $id)->first();
        if (empty($data['supplierData'])) {
            \Session::flash('fail', __('Supplier not found'));
            return redirect('supplier');
        }

        $data['from'] = isset($_GET['from']) ? $_GET['from'] : null;
        $data['to'] = isset($_GET['to']) ? $_GET['to'] : null;

        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;
        return $this->supplierOrderListDataTable->with('row_per_page', $row_per_page)->with('customer_id', $id)->render('admin.supplier.supplier_order', $data);
    }


    /**
     * Show the form for creating a new Customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $data['menu'] = 'relationship';
      $data['sub_menu'] = 'supplier';
      $data['page_title'] = __('Create Supplier');
      $data['countries'] = Country::getAll()->pluck('name', 'id')->toArray();
      $data['currencies']  = Currency::getAll()->pluck('name', 'id')->toArray();

      return view('admin.supplier.supplier_add', $data);
    }

    /**
     * Store a newly created Customer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'supp_name' => 'required|max:30',
            'email' => ['nullable','unique:suppliers,email', new CheckValidEmail],
            'currency_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data['name'] = $request->supp_name;
            $data['email'] = validateEmail($request->email) ? trim($request->email) : null;
            $data['currency_id'] = $request->currency_id;
            $data['street'] = $request->street;
            $data['contact'] = $request->contact;
            $data['tax_id'] = $request->tax_id;
            $data['city'] = $request->city;
            $data['state'] = $request->state;
            $data['zipcode'] = $request->zipcode;
            $data['is_active'] = $request->status;
            $data['country_id'] = $request->country;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['is_active'] = 1;
            if (isset($request->status)) {
                $data['is_active'] = $request->status;
            }
            $id = DB::table('suppliers')->insertGetId($data);

            if (isset($request->sendMail) && isset($request->email) && !empty($request->email) && validateEmail($request->email)) {
                // Retrive preference value and field name
                $prefer = Preference::getAll()->pluck('value', 'field')->toArray();
                // Retrive Set Password email template
                $data['emailInfo'] = EmailTemplate::where(['template_id' => 23, 'language_short_name' => $prefer['dflt_lang']])->first(['subject', 'body']);
                $subject =  $data['emailInfo']['subject'];
                $message =  $data['emailInfo']['body'];
                // Replacing template variable
                $subject = str_replace('{company_name}', $prefer['company_name'], $subject);
                $subject = str_replace('{supplier_name}', $request->supp_name, $subject);
                $message = str_replace('{supplier_name}', $request->supp_name, $message);
                $message = str_replace('{company_name}', $prefer['company_name'], $message);
                // Send Mail to the customer
                $emailResponse = $this->email->sendEmail($request->email, $subject, $message, null, $prefer['company_name']);
                if ($emailResponse['status'] == false) {
                     DB::rollBack();
                    return redirect()->back()->withInput()->withErrors(['fail' => $emailResponse['message']]);
                 }
            }
            DB::commit();
            if (!empty($id)) {
                if (isset($request->type) && $request->type == md5('add-supplier')) {
                    $currency = Currency::find($request->currency_id);
                    $data = [];
                    $data['status'] = true;
                    $data['message'] = __('Successfully Saved');
                    $data['supplier'] = [
                                            'id' => $id,
                                            'name' => $request->supp_name,
                                            'currency_id' => $currency->id,
                                            'currency_symbol' => $currency->symbol,
                                            'currency_name' => $currency->name
                                        ];
                    echo json_encode($data);
                    exit();
                }
                \Session::flash('success', __('Successfully Saved'));
                if ($request->type=='purchase') {
                    return redirect()->intended('purchase/add');
                } else {
                    return redirect()->intended('supplier');
                }
            }

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['email' => __("Invalid Request")]);
        }
    }

    /**
     * Show the form for editing the specified Customer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['menu'] = 'relationship';
        $data['sub_menu'] = 'supplier';
        $data['page_title'] = __('Supplier Edit');
        $data['tab'] = 'supplier_edit';
        $data['currencies']  = Currency::getAll();
        $data['supplierData'] = DB::table('suppliers')->where('id', $id)->first();
        if (empty($data['supplierData'])) {
            \Session::flash('fail', __('Supplier not found'));
            return redirect('supplier');
        }
        $data['countries'] = DB::table('countries')->get();
        return view('admin.supplier.supplier_edit', $data);
    }

    /**
     * Update the specified Customer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'supp_name' => 'required|max:30',
            'email' => "nullable|email|unique:suppliers,email,$request->supplier_id,id",
            'currency_id'=> 'required'
        ]);

        try {
            \DB::beginTransaction();
            $data['name'] = $request->supp_name;
            $data['email'] = validateEmail($request->email) ? trim($request->email) : null;
            $data['currency_id'] = $request->currency_id;
            $data['street'] = $request->street;
            $data['contact'] = $request->contact;
            $data['tax_id'] = $request->tax_id;
            $data['city'] = $request->city;
            $data['state'] = $request->state;
            $data['zipcode'] = $request->zipcode;
            $data['is_active'] = $request->status;
            $data['country_id'] = $request->country;

            $data['updated_at'] = date('Y-m-d H:i:s');
            \DB::table('suppliers')->where('id', $id)->update($data);
            \DB::commit();

            \Session::flash('success', __('Successfully updated'));
            return redirect()->intended('supplier');
        } catch (Exception $e) {
            \DB::rollBack();
            return back()->withInput()->withErrors(['email' => __("Invalid Request")]);
        }
    }

    /**
     * Remove the specified Customer from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (isset($id)) {
            $record = \DB::table('suppliers')->where('id', $id)->first();
            if ($record) {

                \DB::table('suppliers')->where('id', '=', $id)->delete();

                \Session::flash('success', __('Deleted Successfully.'));
                return redirect()->intended('supplier');
            }
        }
    }

    public function downloadCsv($type)
    {
        return Excel::download(new SupplierExport($type), 'supplier_sheet'. time() .'.csv');
    }

    public function import()
    {
        $data['menu'] = 'relationship';
        $data['sub_menu'] = 'supplier';
        $data['page_title'] = __('Supplier Import');

        return view('admin.supplier.supplier_import', $data);
    }

    public function importSupplierCsv(Request $request)
    {
        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');
            $validator = Validator::make(
                [
                    'file'      => $file,
                    'extension' => strtolower($file->getClientOriginalExtension()),
                ],
                [
                    'file'      => 'required',
                    'extension' => 'required|in:csv',
                ]
            );
            if ($validator->fails()) {
                return back()->withErrors(['email' => __("File type Invalid")]);
            }

            $path = Input::file('csv_file')->getRealPath();
            $csv = [];

            if (is_uploaded_file($path)) {
                $csv = readCSVFile($path, true);
            }

            if (empty($csv)) {
                return back()->withErrors(__("Your CSV has no data to import"));
            }

            $requiredHeader = ["Name", "Currency"];
            $header = array_keys($csv[0]);

            // Check if required headers are available or not
            if (!empty(array_diff($requiredHeader, $header))) {
                return back()->withErrors( __("Please Check CSV Header Name."));
            }

            // get all countries
            $countriesInfos = Country::getAll()->pluck('id', 'name')->toArray();
            $countriesInfos = array_change_key_case($countriesInfos, CASE_LOWER);

            // get default country
            $systemCountry = Preference::getAll()->where('category', 'company')->where('field', 'company_country')->first('value')->value;
            $defaultCountryId = Country::where('id', $systemCountry)->first()->id;

            // get all currencies
            $currenciesInfos = Currency::getAll()->pluck('id', 'name')->toArray();
            $currenciesInfos = array_change_key_case($currenciesInfos, CASE_LOWER);

            // get default currency
            $systemCurrency = Preference::getAll()->where('category','company')->where('field', 'dflt_currency_id')->first('value')->value;
            $defaultCurrencyId = Currency::where('id', $systemCurrency)->first()->id;

            // get all supplier data
            $emails = Supplier::pluck('email')->toArray();
            $email = array_change_key_case($emails, CASE_LOWER);
            $filteredEmails = array_filter($emails);
            $errorMessages = [];
            foreach ($csv as $key => $value) {
                $value = array_map('trim', $value);
                $errorFails = [];
                $data = [];

                // check if the name is empty
                if (empty($value['Name'])) {
                    $errorFails[] = __(':? is required.', ['?' => __('Name')]);
                }

                // check if the email is not used and a valid email
                if (!empty($value['Email']) && empty(validateEmail($value['Email']))) {
                    $errorFails[] = __('Enter a valid :?.', ['?' => __('email')]);
                } else if (!empty($value['Email']) && in_array($value['Email'],$filteredEmails)) {
                    $errorFails[] = __(':? is already taken.', ['?' => __('Email')]);
                }

                // check if the phone number is a valid phone number
                if (!empty($value['Phone']) && empty(validatePhoneNumber($value['Phone']))) {
                    $errorFails[] = __('Enter a valid :?.', ['?' => __('phone number')]);
                }

                // check if the currency is empty
                if (empty($value["Currency"])) {
                    $errorFails[] = __(':? is required.', ['?' => __('Currency')]);
                }

                // check if the status is available or not
                if (!empty($value["Status"]) && !(strtolower($value["Status"]) == 'active' || strtolower($value["Status"]) == 'inactive')) {
                    $errorFails[] = __('Status can be either :? or :x', ['?' => __('Active'), 'x' => __('Inactive')]);
                } else if (strtolower($value["Status"]) == 'active') {
                    $data['status'] = 1;
                } else if (strtolower($value["Status"]) == 'inactive') {
                    $data['status'] = 0;
                } else if (empty($value["Status"])) {
                    $errorFails[] = __(':? is required.', ['?' => __('Status')]);
                }

                if (empty($errorFails)) {
                    try {
                        DB::beginTransaction();

                        $newSupplier = new Supplier;
                        $newSupplier->name = $value['Name'];
                        $newSupplier->email = $value['Email'];
                        $newSupplier->contact = !empty($value['contact']) ? $value['contact'] : null;
                        $newSupplier->tax_id = !empty($value['tax_id']) ? $value['tax_id'] : null;
                        $newSupplier->street = !empty($value['street']) ? $value['street'] : null;
                        $newSupplier->is_active = $data['status'];
                        $newSupplier->city = !empty($value['city']) ? $value['city'] : null;
                        $newSupplier->state = $value['State'];
                        $newSupplier->zipcode = $value['Zipcode'];
                        $newSupplier->country_id = array_key_exists(strtolower($value["Country"]), $countriesInfos) ? $countriesInfos[strtolower($value["Country"])] : $defaultCountryId;
                        $newSupplier->currency_id = array_key_exists(strtolower($value["Currency"]), $currenciesInfos) ? $currenciesInfos[strtolower($value["Currency"])] : $defaultCurrencyId;

                        $newSupplier->save();

                        if (!empty($newSupplier->email)) {
                            array_push($filteredEmails, $newSupplier->email);
                        }

                        DB::commit();

                    } catch (\Exception $e) {
                        DB::rollBack();
                        $errorFails[] = $e->getMessage();
                    }
                }

                // set the error messages
                if (!empty($errorFails)) {
                    $errorMessages[$key] = ['fails' => $errorFails, 'data' => $value];
                }
            }

            // redirect with success message if no error found.
            if (empty($errorMessages)) {
                \Session::flash('success', "Total Imported row: ". count($csv));
                return redirect()->intended('supplier');
            } else {
                $data['menu'] = 'relationship';
                $data['sub_menu'] = 'supplier';
                $data['page_title'] = __('Supplier Import Issues');
                $data['tab'] = 'supplier_payment';
                $data['totalRow'] = count($csv);

                return view('layouts.includes.csv_import_errors', $data)->with('errorMessages', $errorMessages);
            }
        } else {
            return back()->withErrors(['fail' => __("Please Upload a CSV File.")]);
        }
    }

    public function supplierListCsv()
    {
      return Excel::download(new allSupplierExport(), 'all_supplier_details'. time() .'.csv');
    }

    public function supplierListPdf()
    {
        $url_components = parse_url(url()->previous());
        $url_components = !empty($url_components['query']) ? explode('=', $url_components['query']) : null;
        $data['company_logo']   = Preference::getAll()
                                            ->where('category','company')
                                            ->where('field', 'company_logo')
                                            ->first('value');
        $data['suppliersList'] = Supplier::select();

        if (!empty($url_components) && $url_components[1] == "active") {
            $data['suppliersList']->where('is_active', 1);
        }
        if (!empty($url_components) && $url_components[1] == "inactive") {
            $data['suppliersList']->where('is_active', 0);
        }
        $data['suppliersList'] = $data['suppliersList']->get();
        return printPDF($data, 'suppliers_list_' . time() . '.pdf', 'admin.supplier.supplier_list_pdf', view('admin.supplier.supplier_list_pdf', $data), 'pdf', 'domPdf');

    }

    public function billingAddress(Request $request)
    {
        $data['street'] = Supplier::find($request->id);
        $data['status'] = !empty($data['street']) ? 1 : 0;
        return json_encode($data);
    }

    public function supplierPurchaseCsv()
    {
        return Excel::download(new supplierPurchaseOrderExport(), 'supplier_payment_details'. time() .'.csv');
    }

    public function supplierPurchasePdf()
    {
        $from = $_GET['from'];
        $to   = $_GET['to'];
        $supplier_id =  $_GET['supplier_id'];
        $data['supplier'] = $_GET['supplier'];
        $data['date_range'] = !empty($from) && !empty($to) ? " : ". $from ." to ". $to : 'No Date Selected' ;
        $data['purchaseFilter'] = (new PurchaseOrder)->getPurchFilteringOrderById($from, $to, $supplier_id);
        return printPDF($data, 'supplier_individual_purchase' . time() . '.pdf', 'admin.supplier.supplier_individual_purchase', view('admin.supplier.supplier_individual_purchase', $data), 'pdf', 'domPdf');
    }

    public function paymentList($id)
    {
        $data['menu'] = 'relationship';
        $data['sub_menu'] = 'supplier';
        $data['page_title'] = __('Supplier Payments');
        $data['tab'] = 'supplier_payment';
        $data['supplierid'] = $id;
        $data['supplierData'] = DB::table('suppliers')->where('id', $id)->first();
        if (empty($data['supplierData'])) {
            \Session::flash('fail', __('Supplier not found'));
            return redirect('supplier');
        }
        $data['from'] = isset($_GET['from']) ? $_GET['from'] : null;
        $data['to'] = isset($_GET['to']) ? $_GET['to'] : null;
        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;
        return $this->supplierPaymentDataTable->with('row_per_page', $row_per_page)->with('supplier_id', $id)->render('admin.supplier.supplier_payment_list', $data);
      }

      public function supplierPaymentByIdPdf()
      {
        $from = isset($_GET['from']) ? $_GET['from'] : null;
        $to   = isset($_GET['to']) ? $_GET['to'  ] : null;
        $sid  = isset($_GET['supplier_id']) ? $_GET['supplier_id'] : null;
        $data['supplierData'] = \DB::table('suppliers')->where('id', $sid)->first();

        $purchasesPayment = SupplierTransaction::orderBy('id','DESC')
                         ->where('supplier_id',$sid);
        if (!empty($from)) {
            $purchasesPayment->where('transaction_date', '>=', DbDateFormat($from));
        }
        if (!empty($to)) {
            $purchasesPayment->where('transaction_date', '<=', DbDateFormat($to));
        }
        $data['paymentList']  = $purchasesPayment->get();
        $data['date_range'] = (!empty($from) && !empty($to)) ? $from . ' to ' . $to : 'No Date Selected' ;
        return printPDF($data, 'supplier_individual_payment' . time() . '.pdf', 'admin.supplier.supplier_individual_payment', view('admin.supplier.supplier_individual_payment', $data), 'pdf', 'domPdf');
      }

      public function supplierPaymentByIdCsv()
      {
        return Excel::download(new supplierPaymentExport(), 'supplier_individual_payment'. time() .'.csv');
      }


      public function supplierLedger($id = null)
      {
        $data['menu'] = 'relationship';
        $data['sub_menu'] = 'supplier';
        $data['page_title'] = __('Supplier Ledgers');
        $data['tab'] = 'supplier_ledger';
        $data['supplierid'] = $id;
        $from = null;
        $to = null;
        if (isset($_GET['from']) && isset($_GET['to'])) {
            $data['from'] = $from = $_GET['from'];
            $data['to'] = $to = $_GET['to'];
        }
        $data['supplierData'] = \DB::table('suppliers')
                              ->leftJoin('currencies','suppliers.currency_id','=','currencies.id')
                              ->select('suppliers.*','currencies.name as currency_name', 'currencies.symbol as symbol')
                              ->where('suppliers.id', $id)->first();
        if (empty($id) || empty($data['supplierData'])) {
            \Session::flash('fail', __('Supplier not found'));
            return redirect('supplier');
        }
        $po = PurchaseOrder::with('currency')->where('supplier_id', $id);
        $payment = SupplierTransaction::with(['purchaseOrder:id,reference', 'currency'])->where('supplier_id', $id);
        if (!empty($from) && !empty($to)) {
            $po->where('order_date', '>=', DbDateFormat($from))
            ->where('order_date', '<=', DbDateFormat($to));
            $payment->where('transaction_date', '>=', DbDateFormat($from))
            ->where('transaction_date', '<=', DbDateFormat($to));
        }
        $po = $po->select('id', 'reference', 'order_date as transaction_date', 'total', 'currency_id')
            ->get()
            ->toArray();
        $payment = $payment->select('purchase_order_id', 'transaction_date', 'amount', 'currency_id')
            ->get()
            ->toArray();
        $merge   = array_merge($po, $payment);
        usort($merge, "custom_sort");
        $data['supplierLedger'] = $merge;

        return view('admin.supplier.supplier_ledger', $data);
      }

      public function ledgerSupplierPdf()
      {
        $id = isset($_GET['supplier_id']) ? $_GET['supplier_id'] : null;
        if (empty($id)) {
            \Session::flash('fail', __('Supplier not found'));
            return redirect('supplier');
        }
        $data['supplierData'] = \DB::table('suppliers')
        ->leftJoin('currencies', 'suppliers.currency_id', '=', 'currencies.id')
        ->select('suppliers.*', 'currencies.name as currency_name')
        ->where('suppliers.id', $id)->first();

        if (empty($data['supplierData'])) {
            \Session::flash('fail', __('Supplier not found'));
            return redirect('supplier');
        }
        $data['supplier'] = $data['supplierData']->name;

        $from = null;
        $to = null;
        if (isset($_GET['from']) && isset($_GET['to'])) {
            $from = $_GET['from'];
            $to = $_GET['to'];
        }

        $data['date_range']   = !empty($from) && !empty($to) ? $from . ' to ' . $to : 'No Date Selected';

        $po = PurchaseOrder::with('currency')->where('supplier_id', $id);
        $payment = SupplierTransaction::with(['purchaseOrder:id,reference', 'currency'])->where('supplier_id', $id);

        if (!empty($from) && !empty($to)) {
            $po->where('order_date', '>=', DbDateFormat($from))
            ->where('order_date', '<=', DbDateFormat($to));
            $payment->where('transaction_date', '>=', DbDateFormat($from))
            ->where('transaction_date', '<=', DbDateFormat($to));
        }
        $po = $po->select('reference', 'order_date as transaction_date', 'total', 'currency_id')
            ->get()
            ->toArray();
        $payment = $payment->select('purchase_order_id', 'transaction_date', 'amount', 'currency_id')
            ->get()
            ->toArray();

        $merge   = array_merge($po, $payment);

        $data['supplierLedger'] = $merge;

        return printPDF($data, 'supplier_individual_ledger' . time() . '.pdf', 'admin.supplier.supplier_individual_ledger_pdf', view('admin.supplier.supplier_individual_ledger_pdf', $data), 'pdf', 'domPdf');
      }


      public function ledgerSupplierCsv()
      {
        return Excel::download(new supplierLedgerCsv(), 'supplier_ledger_details'. time() .'.csv');
      }

      /**
     * Validate email address while creating a new Supplier.
     *
     * @return true or false
     */
     public function validateSupplierEmail(Request $request)
     {
        if (!isset($request->email)) {
            return "true";
        }
        $query = DB::table('suppliers')
                ->where('email', $request->email);
        if (isset($request->supplierId) && !empty(isset($request->supplierId))) {
            $query->where('id', "!=", $request->supplierId);
        }
        $result = $query->first();
        if (!empty($result)) {
            echo json_encode(__('This email is already existed.'));
            exit;
        }
        return "true";
     }
}
