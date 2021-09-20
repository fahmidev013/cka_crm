<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use App\Rules\CheckValidEmail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\DataTables\{
    CustomerInvoiceListDataTable,
    CustomerListDataTable,
    CustomerOrderListDataTable,
    CustomerPaymentListDataTable,
    CustomerProjectListDataTable,
    CustomerTaskListDataTable,
    CustomerTicketListDataTable
};
use App\Exports\{
    CustomerExport,
    allCustomerExport,
    customerInvoiceExport,
    customerProjectExport,
    customerTaskExport,
    customerTicketExport,
    customerPaymentExport,
    customerQuotationExport,
    customerLedgerExport
};
use App\Models\{
    Country,
    Currency,
    CustomerBranch,
    Customer,
    CustomerTransaction,
    Department,
    EmailTemplate,
    PaymentMethod,
    Preference,
    Project,
    Tag,
    Task,
    TaskAssign,
    TaskStatus,
    Ticket,
    SaleType,
    SaleOrder,
    Supplier,
    User
};
use DB;
use Auth;
use Hash;
use Input;
use PDF;
use Exception;
use Session;
use Validator;

class CustomerController extends Controller
{
    public function __construct(SaleOrder $saleOrders, CustomerOrderListDataTable $customerOrderListDataTable, CustomerInvoiceListDataTable $customerInvoiceListDataTable, CustomerPaymentListDataTable $customerPaymentListDataTable, CustomerProjectListDataTable $customerProjectListDataTable, CustomerTaskListDataTable $customerTaskListDataTable, CustomerTicketListDataTable $customerTicketListDataTable, EmailController $email)
    {
        $action = request()->segment(2);
        if (in_array($action, ['edit', 'ledger', 'order', 'invoice', 'payment', 'notes', 'task', 'ticket', 'project', 'adminlogin'])) {
            $id = request()->segment(3);
            $customer = Customer::find($id);
            if (empty($customer)) {
                Session::flash('fail', __('Customer not found'));
                header('Location:' . url('customer/list'));
                exit;
            }
        }

        $this->saleOrder = $saleOrders;
        $this->customerOrderListDataTable = $customerOrderListDataTable;
        $this->customerInvoiceListDataTable = $customerInvoiceListDataTable;
        $this->customerPaymentListDataTable = $customerPaymentListDataTable;
        $this->customerProjectListDataTable = $customerProjectListDataTable;
        $this->customerTaskListDataTable = $customerTaskListDataTable;
        $this->customerTicketListDataTable = $customerTicketListDataTable;
        $this->email = $email;
    }

    /**
     * Display a listing of the Customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CustomerListDataTable $dataTable)
    {
        $data['menu'] = 'relationship';
        $data['sub_menu'] = 'customer';
        $data['page_title'] = __('Customers');
        $data['totalBranch'] = CustomerBranch::all()->count();
        $data['customerCount'] = Customer::all()->count();
        $data['customerActive'] = Customer::where('is_active', 1)->count();
        $data['customerInActive'] = Customer::where('is_active', 0)->count();

        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;

        return $dataTable->with('row_per_page', $row_per_page)->render('admin.customer.customer_list', $data);
    }

    /**
     * Show the form for creating a new Customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['menu'] = 'relationship';
        $data['sub_menu'] = 'customer';
        $data['page_title'] = __('Create Customer');
        $data['countries'] = Country::getAll()->pluck('name', 'id')->toArray();
        $data['currencies'] = Currency::getAll()->pluck('name', 'id')->toArray();

        return view('admin.customer.customer_add', $data);
    }

    /**
     * Validate email address while creating a new Customer.
     *
     * @return true or false
     */
    public function validateCustomerEmail(Request $request)
    {
        $query = DB::table('customers')->where('email', $request->email);
        if (isset($request->customerId) && !empty($request->customerId)) {
            $query->where('id', "!=", $request->customerId);
        }
        $result = $query->first();

        if (!empty($result)) {
            echo json_encode(__('This email is already existed.'));
        } else {
            echo "true";
        }
    }

    /**
     * Store a newly created Customer in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:30',
            'last_name' => 'required|max:30',
            'email' => ['nullable', 'unique:customers,email', new CheckValidEmail],
            'currency_id' => 'required',
        ]);
        if ($validator->fails()) {
            $data = [];
            if (isset($request->type) && !empty($request->type)) {
                $data['status'] = false;
                $data['errors'] = $validator->errors()->first();
                return $data;
            }
            return back()->withErrors($validator)->withInput();
        }
        try {
            \DB::beginTransaction();
            $password = Str::random(9);
            // create a new customer
            $timeZone = Preference::getAll()->where('category', 'preference')
                ->where('field', 'default_timezone')->first()->value;
            $newCustomer = new Customer();
            $newCustomer->first_name = stripBeforeSave($request->first_name);
            $newCustomer->last_name = stripBeforeSave($request->last_name);
            $newCustomer->name = stripBeforeSave($request->first_name . ' ' . $request->last_name);
            $newCustomer->email = validateEmail($request->email) ? $request->email : null;
            $newCustomer->phone = stripBeforeSave($request->phone);
            $newCustomer->tax_id = stripBeforeSave($request->tax_id);
            $newCustomer->currency_id = $request->currency_id;
            $newCustomer->timezone = $timeZone;
            $newCustomer->created_at = date('Y-m-d H:i:s');
            $newCustomer->is_verified = 1;
            $newCustomer->password = Hash::make($password);
            $newCustomer->save();
            $id = $newCustomer->id;

            if (isset($request->sendMail) && isset($request->email) && !empty($request->email) && validateEmail($request->email)) {
                // Retrive preference value and field name
                $prefer = Preference::getAll()->pluck('value', 'field')->toArray();
                // Retrive Set Password email template
                $data['emailInfo'] = EmailTemplate::where(['template_id' => 22, 'language_short_name' => $prefer['dflt_lang']])->first(['subject', 'body']);
                $subject = $data['emailInfo']['subject'];
                $message = $data['emailInfo']['body'];
                // Replacing template variable
                $subject = str_replace('{company_name}', $prefer['company_name'], $subject);
                $message = str_replace('{customer_name}', $request->first_name . ' ' . $request->last_name, $message);
                $message = str_replace('{company_name}', $prefer['company_name'], $message);
                $message = str_replace('{company_url}', url('/customer'), $message);
                $message = str_replace('{password}', $password, $message);
                $message = str_replace('{email}', $request->email, $message);

                // Send Mail to the customer
                $emailResponse = $this->email->sendEmail($request->email, $subject, $message, null, $prefer['company_name']);
                if ($emailResponse['status'] === false) {
                    \DB::rollBack();
                    return redirect()->back()->withInput()->withErrors(['fail' => $emailResponse['message']]);
                }
            }

            if (!empty($id)) {

                // create customer branch
                $newCustomerBranch = new CustomerBranch();
                $newCustomerBranch->customer_id = $id;
                $newCustomerBranch->name = stripBeforeSave($request->first_name . ' ' . $request->last_name);
                $newCustomerBranch->billing_street = stripBeforeSave($request->bill_street);
                $newCustomerBranch->billing_city = stripBeforeSave($request->bill_city);
                $newCustomerBranch->billing_state = stripBeforeSave($request->bill_state);
                $newCustomerBranch->billing_zip_code = stripBeforeSave($request->bill_zipCode);
                $newCustomerBranch->billing_country_id = stripBeforeSave($request->bill_country_id);
                $newCustomerBranch->shipping_street = stripBeforeSave($request->ship_street);
                $newCustomerBranch->shipping_city = stripBeforeSave($request->ship_city);
                $newCustomerBranch->shipping_state = stripBeforeSave($request->ship_state);
                $newCustomerBranch->shipping_zip_code = stripBeforeSave($request->ship_zipCode);
                $newCustomerBranch->shipping_country_id = $request->ship_country_id;
                $newCustomerBranch->save();
                \DB::commit();
                if (isset($request->type)) {
                    $currency = Currency::find($newCustomer->currency_id);
                    $data = [];
                    $data['status'] = true;
                    $data['message'] = __('Successfully Saved');
                    $data['customer'] = [
                        'id' => $id,
                        'name' => $newCustomer->name,
                        'currency_id' => $currency->id,
                        'currency_symbol' => $currency->symbol,
                        'currency_name' => $currency->name
                    ];
                    echo json_encode($data);
                    exit();
                } else {
                    \Session::flash('success', __('Successfully Saved'));
                    return redirect()->intended("customer/list");
                }

            }

        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified Customer.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['menu'] = 'relationship';
        $data['sub_menu'] = 'customer';
        $data['page_title'] = __('Customer Edit');
        $data['debtor_no'] = $id;
        $data['customerData'] = Customer::find($id);
        $data['cusBranchData'] = CustomerBranch::where('customer_id', $id)->first();
        $data['saleTypeData'] = SaleType::getAll();
        $data['countries'] = Country::getAll()->pluck('name', 'id')->toArray();
        $data['currencies'] = Currency::getAll()->pluck('name', 'id')->toArray();
        $data['status_tab'] = 'active';

        return view('admin.customer.customer_edit', $data);
    }

    public function changeStatus(Request $request)
    {
        $customer = Customer::find($request->id);
        $customer->is_active = $request->status;
        $customer->save();

        $data['customerActive'] = Customer::where('is_active', 1)->count();
        $data['customerInActive'] = Customer::where('is_active', 0)->count();
        $data['customerCount'] = intval($data['customerActive']) + intval($data['customerInActive']);
        $data['status'] = 'success';
        return $data;
    }

    public function salesOrder($id)
    {
        $data['menu'] = 'relationship';
        $data['sub_menu'] = 'customer';
        $data['page_title'] = __('Customer Quotations');
        $data['from'] = isset($_GET['from']) ? $_GET['from'] : null;
        $data['to'] = isset($_GET['to']) ? $_GET['to'] : null;

        $data['customerData'] = Customer::find($id);
        $data['currency'] = $currency = DB::table('currencies')
            ->select('currencies.*')
            ->leftjoin('customers', 'currencies.id', 'customers.currency_id')
            ->where('customers.id', $id)
            ->first();

        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;

        return $this->customerOrderListDataTable->with('row_per_page', $row_per_page)->with('currency', $currency)->with('customer_id', $id)->render('admin.customer.customer_sales_order', $data);
    }

    public function invoice($id)
    {
        $data['menu'] = 'relationship';
        $data['sub_menu'] = 'customer';
        $data['page_title'] = __('Customer Invoices');
        $data['from'] = $from = isset($_GET['from']) ? $_GET['from'] : null;
        $data['to'] = $to = isset($_GET['to']) ? $_GET['to'] : null;
        $data['payStatus'] = $allstatus = isset($_GET['pay_status_type']) ? $_GET['pay_status_type'] : null;
        $data['customerData'] = Customer::find($id);
        $invoiceSummery = $this->saleOrder->getMoneyStatus(['customer_id' => $id, 'from' => $from, 'to' => $to, 'status' => $allstatus]);
        $array = [];
        foreach ($invoiceSummery['amounts'] as $key => $value) {
            $array['totalInvoice'] = $value['totalInvoice'];
            $array['totalPaid'] = $value['totalPaid'];
            $array['totalDue'] = $value['totalInvoice'] - $value['totalPaid'];
        }
        foreach ($invoiceSummery['overDue'] as $key => $value) {
            $overDueTotalAmount = !empty($value['totalAmount']) ? $value['totalAmount'] : 0;
            $overDueTotalPaid = !empty($value['totalPaid']) ? $value['totalPaid'] : 0;;
            $array['overDue'] = $overDueTotalAmount - $overDueTotalPaid;
        }
        $data['invoiceSummery'] = $array;
        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;
        return $this->customerInvoiceListDataTable->with('row_per_page', $row_per_page)->with('customer_id', $id)->render('admin.customer.customer_invoice', $data);
    }

    public function invoiceCsv()
    {
        return Excel::download(new customerInvoiceExport(), 'customer_invoices_list' . time() . '.csv');
    }

    public function invoicePdf()
    {

        $to = isset($_GET['to']) ? $_GET['to'] : null;
        $from = isset($_GET['from']) ? $_GET['from'] : null;
        $data['customer'] = $customer = isset($_GET['customer']) ? $_GET['customer'] : null;
        $data['customers'] = Customer::where('id', $customer)->first();
        $status = isset($_GET['pay_status_type']) ? $_GET['pay_status_type'] : null;
        $data['salesList'] = $this->saleOrder->getAllInvoices($from, $to, $customer, null, null, $status, null, 'customerPanel')->orderBy('created_at', 'desc')->get();
        $data['company_logo'] = Preference::getAll()->where('category', 'company')->where('field', 'company_logo')->first('value');
        $data['date_range'] = !empty($from) && !empty($to) ? formatDate($from) . ' To ' . formatDate($to) : 'No Date Selected';
        return printPDF($data, 'customer_invoices_list_' . time() . '.pdf', 'admin.customer.customer_invoice_list_pdf', view('admin.customer.customer_invoice_list_pdf', $data), 'pdf', 'domPdf');
    }

    public function payment($id)
    {
        $data['menu'] = 'relationship';
        $data['sub_menu'] = 'customer';
        $data['page_title'] = __('Customer Payments');
        $data['customerData'] = Customer::find($id);
        $data['from'] = isset($_GET['from']) ? $_GET['from'] : null;
        $data['to'] = isset($_GET['to']) ? $_GET['to'] : null;
        $data['method'] = isset($_GET['method']) ? $_GET['method'] : null;
        $data['currency'] = isset($_GET['currency']) ? $_GET['currency'] : null;
        $data['methodList'] = PaymentMethod::getAll();
        $data['currencyList'] = Currency::getAll();

        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;

        return $this->customerPaymentListDataTable->with('row_per_page', $row_per_page)->with('customer_id', $id)->render('admin.customer.customer_payment', $data);
    }

    public function paymentCsv()
    {
        return Excel::download(new customerPaymentExport(), 'customer_payments_' . time() . '.csv');
    }

    public function paymentPdf()
    {
        $data['customer'] = $customer = isset($_GET['customerId']) ? $_GET['customerId'] : null;
        $data['customers'] = Customer::where('id', $customer)->first();
        $to = isset($_GET['to']) ? $_GET['to'] : null;
        $from = isset($_GET['from']) ? $_GET['from'] : null;
        $method = isset($_GET['method']) ? $_GET['method'] : null;
        $currency = isset($_GET['currency']) ? $_GET['currency'] : null;
        $customerPayment = CustomerTransaction::where('customer_id', $customer);
        if (!empty($from) && !empty($to)) {
            $customerPayment->whereDate('transaction_date', '>=', DbDateFormat($from));
            $customerPayment->whereDate('transaction_date', '<=', DbDateFormat($to));
        }
        if (!empty($method) && $method != 'all') {
            $customerPayment->where('payment_method_id', '=', $method);
        }
        if (!empty($currency) && $currency != 'all') {
            $customerPayment->where('customer_transactions.currency_id', '=', $currency);
        }
        $data['paymentList'] = $customerPayment->get();
        $data['date_range'] = ($from && $to) ? formatDate($from) . __('To') . formatDate($to) : __('No Date Selected');
        return printPDF($data, 'payment_report_' . time() . '.pdf', 'admin.customer.payment_report_pdf', view('admin.customer.payment_report_pdf', $data), 'pdf', 'domPdf');
    }

    /**
     * Update the specified Customer in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'first_name' => 'required|max:30',
            'last_name' => 'required|max:30',
            'email' => ['nullable', 'email', "unique:customers,email,$request->customer_id,id", new CheckValidEmail],
            'currency_id' => 'required',
        ]);

        try {
            \DB::beginTransaction();
            //update basic
            $customerToUpdate = Customer::find($id);
            $customerToUpdate->first_name = stripBeforeSave($request->first_name);
            $customerToUpdate->last_name = stripBeforeSave($request->last_name);
            $customerToUpdate->name = stripBeforeSave($request->first_name . ' ' . $request->last_name);
            $customerToUpdate->email = validateEmail($request->email) ? trim($request->email) : null;
            $customerToUpdate->phone = stripBeforeSave($request->phone);
            $customerToUpdate->tax_id = stripBeforeSave($request->tax_id);
            $customerToUpdate->is_active = $request->status;
            $customerToUpdate->is_verified = $request->is_activated;
            $customerToUpdate->customer_type = $request->is_walking == 1 ? 'walking' : NULL;
            $customerToUpdate->currency_id = $request->currency_id;
            $customerToUpdate->updated_at = date('Y-m-d H:i:s');
            $customerToUpdate->save();

            //update address details
            $customerBranch = CustomerBranch::where('customer_id', $id)->first();
            if (!empty($customerBranch->id)) {
                $branchToUpdate = CustomerBranch::find($customerBranch->id);
                $branchToUpdate->billing_street = stripBeforeSave($request->bill_street);
                $branchToUpdate->billing_city = stripBeforeSave($request->bill_city);
                $branchToUpdate->billing_state = stripBeforeSave($request->bill_state);
                $branchToUpdate->billing_zip_code = stripBeforeSave($request->bill_zipCode);
                $branchToUpdate->billing_country_id = $request->billing_country_id;

                $branchToUpdate->shipping_street = stripBeforeSave($request->ship_street);
                $branchToUpdate->shipping_city = stripBeforeSave($request->ship_city);
                $branchToUpdate->shipping_state = stripBeforeSave($request->ship_state);
                $branchToUpdate->shipping_zip_code = stripBeforeSave($request->ship_zipCode);
                $branchToUpdate->shipping_country_id = $request->shipping_country_id;
                $branchToUpdate->save();
            }

            \DB::commit();
            \Session::flash('success', __('Successfully updated'));
            return redirect()->intended('customer/list');
        } catch (Exception $e) {
            \DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|min:5|confirmed',
            'password_confirmation' => 'required|min:5',
        ]);

        $id = $request->customer_id;

        $password = \Hash::make(trim($request->password));

        \DB::beginTransaction();
        $customerPasswordToUpdate = Customer::find($id);
        $customerPasswordToUpdate->password = $password;
        $customerPasswordToUpdate->save();

        if (isset($request->sendmail) && filter_var($customerPasswordToUpdate->email, FILTER_VALIDATE_EMAIL)) {
            // Retrive preference value and field name
            $prefer = Preference::getAll()->pluck('value', 'field')->toArray();
            // Retrive Set Password email template
            $data['emailInfo'] = EmailTemplate::where(['template_id' => 18, 'language_short_name' => $prefer['dflt_lang']])->first(['subject', 'body']);
            $subject = (!empty($data['emailInfo']['subject'])) ? $data['emailInfo']['subject'] : "Set Password!";
            $message = $data['emailInfo']['body'];
            // Replacing template variable
            $message = str_replace('{user_name}', $customerPasswordToUpdate->first_name . ' ' . $customerPasswordToUpdate->last_name, $message);
            $message = str_replace('{user_id}', $customerPasswordToUpdate->email, $message);
            $message = str_replace('{user_pass}', $request->password, $message);
            $message = str_replace('{company_url}', url('/customer'), $message);
            $message = str_replace('{company_name}', $prefer['company_name'], $message);
            $message = str_replace('{company_email}', $prefer['company_email'], $message);
            $message = str_replace('{company_phone}', $prefer['company_phone'], $message);
            $message = str_replace('{company_street}', $prefer['company_street'], $message);
            $message = str_replace('{company_city}', $prefer['company_city'], $message);
            $message = str_replace('{company_state}', $prefer['company_state'], $message);
            // Send Mail to the customer
            $emailResponse = $this->email->sendEmail($customerPasswordToUpdate->email, $subject, $message, null, $prefer['company_name']);

            if ($emailResponse['status'] == false) {
                \DB::rollBack();
                return redirect()->back()->withInput()->withErrors(['fail' => $emailResponse['message']]);
            }
        }
        \Db::commit();
        \Session::flash('success', __('Successfully updated'));

        return redirect()->intended("customer/edit/" . $id);
    }

    /**
     * Remove the specified Customer from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (isset($id)) {
            $record = \DB::table('customers')->where('id', $id)->first();
            if ($record) {
                \DB::table('customers')->where('id', '=', $id)->delete();
                \Session::flash('success', __('Deleted Successfully.'));
            }
        }
        return redirect()->intended('customer');
    }

    public function downloadCsv($type)
    {
        if ($type == 'csv') {
            $customerdata = DB::table('customers')
                ->leftjoin('cust_branch', 'customers.id', '=', 'cust_branch.customer_id')
                ->select('customers.*', 'cust_branch.*')
                ->groupBy('cust_branch.customer_id')
                ->get();

            foreach ($customerdata as $key => $value) {
                $data[$key]['Customer'] = $value->name;
                $data[$key]['Email'] = $value->email;
                $data[$key]['Phone'] = $value->phone;
                $data[$key]['Tax Id'] = $value->tax_id;
                $data[$key]['Branch'] = $value->br_name;

                $data[$key]['Billing Street'] = $value->billing_street;
                $data[$key]['Billing City'] = $value->billing_city;
                $data[$key]['Billing State'] = $value->billing_state;
                $data[$key]['Billing Zipcode'] = trim($value->billing_zip_code);
                $data[$key]['Billing Country'] = trim($value->billing_country_id);

                $data[$key]['Shipping Street'] = $value->shipping_street;
                $data[$key]['Shipping City'] = $value->shipping_city;
                $data[$key]['Shipping State'] = $value->shipping_state;
                $data[$key]['Shipping Zipcode'] = trim($value->shipping_zip_code);
                $data[$key]['Shipping Country'] = ($value->shipping_country_id);
            }
        }

        if ($type == 'sample') {

            $data[0]['Customer'] = 'John Michel';
            $data[0]['Email'] = 'sample@gmail.com';
            $data[0]['Phone'] = '0123456789';
            $data[0]['Tax Id'] = '456789';

            $data[0]['Billing Street'] = '2430';
            $data[0]['Billing City'] = 'Washington';
            $data[0]['Billing State'] = 'Washington';
            $data[0]['Billing Zipcode'] = '1234';
            $data[0]['Billing Country'] = 'US';

            $data[0]['Shipping Street'] = '2430';
            $data[0]['Shipping City'] = 'Washington';
            $data[0]['Shipping State'] = 'Washington';
            $data[0]['Shipping Zipcode'] = '1234';
            $data[0]['Shipping Country'] = 'US';

            $type = 'csv';
        }

        return Excel::download(new CustomerExport, 'customer_sheet.csv');
    }

    public function import()
    {
        $data['menu'] = 'relationship';
        $data['sub_menu'] = 'customer';
        $data['page_title'] = __('Customer Import');

        return view('admin.customer.customer_import', $data);
    }

    public function importCustomerCsv(Request $request)
    {
        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');
            $validator = Validator::make(
                [
                    'file' => $file,
                    'extension' => strtolower($file->getClientOriginalExtension()),
                ],
                [
                    'file' => 'required',
                    'extension' => 'required|in:csv',
                ]
            );
            if ($validator->fails()) {
                return back()->withErrors(__("File type Invalid"));
            }

            $path = Input::file('csv_file')->getRealPath();
            $csv = [];

            if (is_uploaded_file($path)) {
                $csv = readCSVFile($path, true);
            }

            if (empty($csv)) {
                return back()->withErrors(__("Your CSV has no data to import"));
            }

            $requiredHeader = ["First Name", "Last Name", "Currency"];
            $header = array_keys($csv[0]);

            // Check if required headers are available or not
            if (!empty(array_diff($requiredHeader, $header))) {
                return back()->withErrors(__("Please Check CSV Header Name."));
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
            $systemCurrency = Preference::getAll()->where('category', 'company')->where('field', 'dflt_currency_id')->first('value')->value;
            $defaultCurrencyId = Currency::where('id', $systemCurrency)->first()->id;

            $email = DB::table('customers')->pluck('email')->toArray();
            $email = array_change_key_case($email, CASE_LOWER);
            $filteredEmails = array_filter($email);
            $errorMessages = [];

            foreach ($csv as $key => $value) {
                $errorFails = [];

                $value = array_map('trim', $value);

                // check if first name is null
                if (empty($value["First Name"])) {
                    $errorFails[] = __(':? is required.', ['?' => __('First Name')]);
                }

                // check if last name is null
                if (empty($value["Last Name"])) {
                    $errorFails[] = __(':? is required.', ['?' => __('Last Name')]);
                }

                // check if currency is null
                if (empty($value["Currency"])) {
                    $errorFails[] = __(':? is required.', ['?' => __('Currency')]);
                }

                // check if email is not used or a valid email
                if (!empty($value['Email']) && empty(validateEmail($value['Email']))) {
                    $errorFails[] = __('Enter a valid :?.', ['?' => __('email')]);
                } else if (!empty($value['Email']) && in_array($value['Email'], $filteredEmails)) {
                    $errorFails[] = __(':? is already taken.', ['?' => __('Email')]);
                }

                // check if the phone number is a valid phone number
                if (!empty($value['Phone']) && empty(validatePhoneNumber($value['Phone']))) {
                    $errorFails[] = __('Enter a valid :?.', ['?' => __('phone number')]);
                }

                if (empty($errorFails)) {
                    try {
                        DB::beginTransaction();
                        $newCustomer = new Customer;
                        $newCustomer->name = $value["First Name"] . ' ' . $value["Last Name"];
                        $newCustomer->first_name = !empty($value["First Name"]) ? $value["First Name"] : null;
                        $newCustomer->last_name = !empty($value["Last Name"]) ? $value["Last Name"] : null;
                        $newCustomer->email = !empty($value["Email"]) ? $value["Email"] : null;
                        $newCustomer->phone = !empty($value["Phone"]) ? $value["Phone"] : null;
                        $newCustomer->tax_id = !empty($value["Tax Id"]) ? $value["Tax Id"] : null;
                        $newCustomer->currency_id = array_key_exists(strtolower($value["Currency"]), $currenciesInfos) ? $currenciesInfos[strtolower($value["Currency"])] : $defaultCurrencyId;
                        $newCustomer->save();

                        $newCustomerBranch = new CustomerBranch;
                        $newCustomerBranch->name = $value["First Name"] . ' ' . $value["Last Name"];
                        $newCustomerBranch->customer_id = $newCustomer->id;
                        $newCustomerBranch->billing_street = !empty($value["Billing Street"]) ? $value["Billing Street"] : null;
                        $newCustomerBranch->billing_city = !empty($value["Billing City"]) ? $value["Billing City"] : null;
                        $newCustomerBranch->billing_state = !empty($value["Billing State"]) ? $value["Billing State"] : null;
                        $newCustomerBranch->billing_country_id = array_key_exists(strtolower($value["Billing Country"]), $countriesInfos) ? $countriesInfos[strtolower($value["Billing Country"])] : $defaultCountryId;
                        $newCustomerBranch->billing_zip_code = !empty($value["Billing Zipcode"]) ? $value["Billing Zipcode"] : null;
                        $newCustomerBranch->shipping_street = !empty($value["Shipping Street"]) ? $value["Shipping Street"] : null;
                        $newCustomerBranch->shipping_city = !empty($value["Shipping City"]) ? $value["Shipping City"] : null;
                        $newCustomerBranch->shipping_state = !empty($value["Shipping State"]) ? $value["Shipping State"] : null;
                        $newCustomerBranch->shipping_country_id = array_key_exists(strtolower($value["Shipping Country"]), $countriesInfos) ? $countriesInfos[strtolower($value["Shipping Country"])] : $defaultCountryId;
                        $newCustomerBranch->shipping_zip_code = !empty($value["Shipping Zipcode"]) ? $value["Shipping Zipcode"] : null;
                        $newCustomerBranch->save();

                        if (!empty($newCustomer->email)) {
                            array_push($filteredEmails, $newCustomer->email);
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
                \Session::flash('success', "Total Imported row: " . count($csv));
                return redirect()->intended('customer/list');
            } else {
                $data['menu'] = 'relationship';
                $data['sub_menu'] = 'customer';
                $data['page_title'] = __('Customer Import Issues');
                $data['totalRow'] = count($csv);

                return view('layouts.includes.csv_import_errors', $data)->with('errorMessages', $errorMessages);
            }
        } else {
            return back()->withErrors(['fail' => __("Please Upload a CSV File.")]);
        }
    }


    public function cusEmailData(Request $request)
    {
        if (isset($request->customer_id)) {
            $record = \DB::table('customers')->where('id', $request->customer_id)->first();
            if (!empty($record->email)) {
                $data['success'] = 1;
            } else {
                $data['success'] = 0;
                $data['message'] = __("Please Set Email First");
            }
            echo json_encode($data);
            exit();
        }
    }

    public function billingAddress(Request $request)
    {
        $data['status'] = 0;
        if ($request->type == '#supplier') {
            $data['supplier'] = Supplier::with('country')->where('id', $request->id)
                ->first(['country_id', 'city', 'zipcode', 'state']);
        }
        if ($request->type == '#customers') {
            $data['customer'] = CustomerBranch::with('billingCountry', 'shippingCountry')
                ->where('customer_id', $request->id)
                ->first(['billing_country_id', 'billing_city', 'billing_zip_code', 'billing_state']);
        }

        if (!empty($data['supplier'])) {
            $data['status'] = 1;
        }
        if (!empty($data['customer'])) {
            $data['status'] = 2;
        }
        return json_encode($data);
    }

    public function quotationFilterPdf()
    {
        $data['menu'] = 'relationship';
        $data['sub_menu'] = 'customer';
        $from = !empty($_GET['from']) ? $_GET['from'] : null;
        $to = !empty($_GET['to']) ? $_GET['to'] : null;
        $customerId = isset($_GET['debtor_no']) ? $_GET['debtor_no'] : null;
        $data['customerName'] = Customer::where('id', $customerId)->first();
        if (!empty($from) && !empty($to)) {
            $data['customerOrder'] = SaleOrder::with(['customer:id,name', 'currency:id,name,symbol', 'saleOrderDetails:id,sale_order_id,quantity'])
                ->where(['transaction_type' => 'SALESORDER', 'customer_id' => $customerId, 'order_type' => 'Direct Order'])
                ->where('order_date', '>=', DbDateFormat($from))
                ->where('order_date', '<=', DbDateFormat($to))
                ->orderBy('created_at', 'desc')->get();
        } else {
            $data['customerOrder'] = SaleOrder::with(['customer:id,name', 'currency:id,name,symbol', 'saleOrderDetails:id,sale_order_id,quantity'])
                ->where(['transaction_type' => 'SALESORDER', 'customer_id' => $customerId, 'order_type' => 'Direct Order'])
                ->orderBy('created_at', 'desc')->get();
        }
        $data['date_range'] = !empty($from) && !empty($to) ? formatDate(DbDateFormat($from)) . ' To ' . formatDate(DbDateFormat($to)) : 'No Date Selected';
        $data['company_logo'] = Preference::getAll()->where('category', 'company')->where('field', 'company_logo')->first('value');
        return printPDF($data, 'customer_quotation_list' . time() . '.pdf', 'admin.customer.customer_indivisual_quotation', view('admin.customer.customer_indivisual_quotation', $data), 'pdf', 'domPdf');
    }

    public function quotationFilterCsv()
    {
        return Excel::download(new customerQuotationExport(), 'customer_quotation_list' . time() . '.csv');
    }

    public function project($id)
    {
        $data['menu'] = 'relationship';
        $data['sub_menu'] = 'customer';
        $data['page_title'] = __('Customer Projects');
        $data['customerData'] = Customer::find($id);
        $data['status'] = DB::table('project_statuses')->select('id', 'name')->get();
        $data['from'] = $from = isset($_GET['from']) ? $_GET['from'] : null;
        $data['to'] = $to = isset($_GET['to']) ? $_GET['to'] : null;
        $data['allstatus'] = $allstatus = isset($_GET['status']) ? $_GET['status'] : '';
        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;

        return $this->customerProjectListDataTable->with('row_per_page', $row_per_page)->with('customer_id', $id)->render('admin.customer.customer_project', $data);
    }

    public function projectCsv()
    {
        return Excel::download(new customerProjectExport(), 'customer_projects_' . time() . '.csv');
    }

    public function projectPdf()
    {
        $data['customer'] = $customer = isset($_GET['customer']) ? $_GET['customer'] : null;
        $data['customers'] = Customer::where('id', $customer)->first();
        $from = isset($_GET['from']) ? ($_GET['from']) : null;
        $to = isset($_GET['to']) ? ($_GET['to']) : null;
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $query = Project::with(['customer', 'projectStatuses'])->orderBy('created_at', 'desc');
        if (!empty($status)) {
            $query->where('project_status_id', $status);
        } else {
            $query->where('project_status_id', '!=', 6);
        }
        if (!empty($from)) {
            $query->where('begin_date', '>=', DbDateFormat($from));
        }
        if (!empty($to)) {
            $query->where('begin_date', '<=', DbDateFormat($to));
        }
        if (!empty($customer)) {
            $query->where('customer_id', '=', $customer);
        }
        $data['projectList'] = $query->get();
        $data['company_logo'] = Preference::getAll()->where('category', 'company')->where('field', 'company_logo')->first('value');
        $data['date_range'] = !empty($from) && !empty($to) ? formatDate($from) . ' To ' . formatDate($to) : 'No Date Selected';
        return printPDF($data, 'projects_list_' . time() . '.pdf', 'admin.customer.customer_projects_pdf', view('admin.customer.customer_projects_pdf', $data), 'pdf', 'domPdf');
    }

    public function task($id)
    {
        $data['menu'] = 'relationship';
        $data['sub_menu'] = 'customer';
        $data['page_title'] = __('Customer Tasks');
        $user_id = Auth::user()->id;
        $data['customerData'] = Customer::find($id);
        $tags = Tag::all()->pluck('name')->toArray();
        $data['tags'] = json_encode($tags);
        $data['task_statuses_all'] = TaskStatus::getAll();
        $taskList = TaskStatus::getAll();
        $data['priorities'] = DB::table('priorities')->select('id', 'name')->get();
        $data['assignees'] = User::where('is_active', 1)->get();
        $data['from'] = isset($_GET['from']) && !empty($_GET['from']) ? $_GET['from'] : null;
        $data['to'] = isset($_GET['to']) && !empty($_GET['from']) ? $_GET['to'] : null;
        $data['allstatus'] = isset($_GET['status']) && !empty($_GET['status']) ? $_GET['status'] : null;
        $data['allassignee'] = $allassignee = isset($_GET['assignee']) ? $_GET['assignee'] : $user_id;
        $data['allpriority'] = isset($_GET['priority']) ? $_GET['priority'] : null;

        $task_summary = (new Task())->getTaskSummary(['task_status_id' => $data['allstatus'], 'related_to_id' => $id, 'related_to_type' => "2", 'from' => $data['from'], 'to' => $data['to'], 'user_id' => $user_id, 'allassignee' => $allassignee])->get();
        $summary = [];
        $stack = [];
        for ($i = 0; $i < count($taskList); $i++) {
            for ($j = 0; $j < count($task_summary); $j++) {
                if ($taskList[$i]->id == $task_summary[$j]->id) {
                    $summary[$i]['id'] = $task_summary[$j]->id;
                    $summary[$i]['name'] = $task_summary[$j]->name;
                    $summary[$i]['color'] = $task_summary[$j]->color;
                    $summary[$i]['total_status'] = $task_summary[$j]->total_status;
                    $stack[] = $task_summary[$j]->id;
                } else {
                    if (!in_array($taskList[$i]->id, $stack)) {
                        $summary[$i]['id'] = $taskList[$i]->id;
                        $summary[$i]['name'] = $taskList[$i]->name;
                        $summary[$i]['color'] = $taskList[$i]->color;
                        $summary[$i]['total_status'] = 0;
                    }
                }
            }
        }
        if (count($task_summary) === 0) {
            for ($i = 0; $i < count($taskList); $i++) {
                $summary[$i]['id'] = $taskList[$i]->id;
                $summary[$i]['name'] = $taskList[$i]->name;
                $summary[$i]['color'] = $taskList[$i]->color;
                $summary[$i]['total_status'] = 0;
            }
        }
        $data['summary'] = $summary;
        // Counting summary end

        // Individual counting
        if ($allassignee != Auth::user()->id) {
            for ($i = 0; $i < count($taskList); $i++) {
                $result = DB::table('tasks')
                    ->join('task_assigns', function ($join) use ($user_id) {
                        $join->on('task_assigns.task_id', 'tasks.id');
                        $join->where('task_assigns.user_id', $user_id);
                    })
                    ->join('task_statuses', function ($join) use ($i) {
                        $join->on('task_statuses.id', 'tasks.task_status_id');
                        $join->where('task_statuses.id', $i + 1);
                    });
                if (!empty($id)) {
                    $result->where(['related_to_id' => $id, 'related_to_type' => 2]);
                }

                $assign_to_me[$i] = $result->count();
            }
            $data['assign_to_me'] = $assign_to_me;
        }
        // Individual counting end

        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;

        return $this->customerTaskListDataTable->with('row_per_page', $row_per_page)->with('customer_id', $id)->render('admin.customer.customer_task', $data);
    }

    public function taskCsv()
    {
        return Excel::download(new customerTaskExport(), 'customer_tasks' . time() . '.csv');
    }

    public function taskPdf()
    {
        $to = isset($_GET['to']) ? $_GET['to'] : null;
        $from = isset($_GET['from']) ? $_GET['from'] : null;
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $assignee = isset($_GET['assignee']) ? $_GET['assignee'] : Auth::user()->id;
        $priority = isset($_GET['priority']) ? $_GET['priority'] : null;
        $data['customer'] = $customer = isset($_GET['customer']) ? $_GET['customer'] : null;
        $data['customers'] = Customer::where('id', $customer)->first();
        $data['taskList'] = (new Task())->getAllTaskForDT($from, $to, $status, null, $assignee, $priority, $customer, 2)->get();
        foreach ($data['taskList'] as $task) {
            $assigne = (new Task())->taskAssignsList($task->id)->pluck('user_name');
            $list = "";
            if (!empty($assigne)) {
                foreach ($assigne as $counter => $assign) {
                    $list .= $assign;
                    if ($counter < count($assigne) - 1) {
                        $list .= ", ";
                    }
                }
            }
            $task->assignee = $list;
        }
        if ($from && $to) {
            $data['date_range'] = formatDate($from) . __('To') . formatDate($to);
        } else {
            $data['date_range'] = __('No Date Selected');
        }
        return printPDF($data, 'tasks_list_' . time() . '.pdf', 'admin.customer.customer_tasks_pdf', view('admin.customer.customer_tasks_pdf', $data), 'pdf', 'domPdf');
    }

    public function ticket($id)
    {
        $data['menu'] = 'relationship';
        $data['sub_menu'] = 'customer';
        $data['page_title'] = __('Customer Tickets');
        $data['customerData'] = Customer::find($id);

        $data['status'] = DB::table('ticket_statuses')->select('id', 'name')->get();
        $data['projects'] = DB::table('projects')->where('project_status_id', '!=', 6)->select('id', 'name')->get();
        $data['departments'] = Department::get();

        $from = null;
        $to = null;
        if (isset($_GET['from']) && isset($_GET['to'])) {
            $data['from'] = $from = $_GET['from'];
            $data['to'] = $to = $_GET['to'];
        }
        $data['allstatus'] = $allstatus = isset($_GET['status']) && !empty($_GET['status']) ? $_GET['status'] : null;
        $data['allproject'] = $allproject = isset($_GET['project']) && !empty($_GET['project']) ? $_GET['project'] : null;
        $data['alldepartment'] = $alldepartment = isset($_GET['department_id']) && !empty($_GET['department_id']) ? $_GET['department_id'] : null;

        $data['summary'] = $summary = (new Ticket)->getTicketSummary($from, $to, $allstatus, $allproject, $alldepartment, $id);
        if ((isset($from) && !empty($from)) || (isset($to) && !empty($to)) || (isset($allproject) && !empty($allproject)) || (isset($alldepartment) && !empty($alldepartment))) {
            $data['exceptClickedStatus'] = $exceptClickedStatus = (new Ticket)->getExceptClickedStatus($allstatus, $id, $allproject, $alldepartment, $from, $to);
            if (!empty($data['exceptClickedStatus'])) {
                foreach ($summary as $key => $summ) {
                    foreach ($exceptClickedStatus as $key => $exceptClickedSts) {
                        if ($exceptClickedSts->name == $summ->name) {
                            $summ->total_status = $exceptClickedSts->total_status;
                        }
                    }
                }

            }
        } else {
            $data['filteredStatus'] = $filteredStatus = (new Ticket)->getFilteredStatus(['from' => $from, 'to' => $to, 'allstatus' => $allstatus, 'allproject' => $allproject, 'alldepartment' => $alldepartment, 'customerId' => $id]);
            if (!empty($data['filteredStatus'])) {
                foreach ($summary as $key => $summ) {
                    foreach ($filteredStatus as $key => $filtered) {
                        if ($filtered->name == $summ->name) {
                            $summ->total_status = $filtered->total_status;
                        }
                    }
                }
            }
        }
        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;

        return $this->customerTicketListDataTable->with('row_per_page', $row_per_page)->with('customer_id', $id)->render('admin.customer.customer_ticket', $data);
    }

    public function ticketCsv()
    {
        return Excel::download(new customerTicketExport(), 'customer_tickets' . time() . '.csv');
    }

    public function ticketPdf()
    {
        $to = isset($_GET['to']) ? $_GET['to'] : null;
        $from = isset($_GET['from']) ? $_GET['from'] : null;
        $data['customer'] = $customer = isset($_GET['customer']) ? $_GET['customer'] : null;
        $data['customers'] = Customer::where('id', $customer)->first();
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $project = isset($_GET['project']) ? $_GET['project'] : null;
        $departmentId = isset($_GET['department_id']) ? $_GET['department_id'] : null;
        $data['ticketList'] = (new Ticket())->getAllTicketDT($from, $to, $status, $project, $departmentId, $customer)->orderBy('id', 'desc')->get();
        $data['date_range'] = (!empty($from) && !empty($to)) ? formatDate($from) . ' To ' . formatDate($to) : 'No Date Selected';
        return printPDF($data, 'tickets_list_' . time() . '.pdf', 'admin.customer.customer_tickets_pdf', view('admin.customer.customer_tickets_pdf', $data), 'pdf', 'domPdf');
    }

    public function customerLedger($id)
    {
        $data['menu'] = 'relationship';
        $data['sub_menu'] = 'customer';
        $data['page_title'] = __('Customer Ledgers');
        $data['customerData'] = Customer::find($id);

        $from = isset($_GET['from']) && !empty($_GET['from']) ? $_GET['from'] : null;
        $to = isset($_GET['to']) && !empty($_GET['to']) ? $_GET['to'] : null;

        $sales = SaleOrder::where(['customer_id' => $id, 'transaction_type' => 'SALESINVOICE']);
        if ((isset($from) && isset($to)) && !empty($from) && !empty($to)) {
            $sales->where('order_date', '>=', DbDateFormat($from));
            $sales->where('order_date', '<=', DbDateFormat($to));
        }
        $sales = $sales->select('reference', 'order_date as transaction_date', 'total', 'id')
            ->orderBy('transaction_date')->get()->toArray();

        $payment = DB::table('customer_transactions')->join('sale_orders', 'sale_orders.id', '=', 'customer_transactions.sale_order_id')->where('customer_transactions.customer_id', $id);

        if ((isset($from) && isset($to)) && !empty($from) && !empty($to)) {
            $payment->where('transaction_date', '>=', DbDateFormat($from));
            $payment->where('transaction_date', '<=', DbDateFormat($to));
        }

        $payment = $payment->select('sale_orders.reference as reference', 'customer_transactions.transaction_date', 'customer_transactions.amount', 'sale_orders.id')->get();
        foreach ($payment as $key => $value) {
            $payment[$key] = (array)$payment[$key];
        }

        $payment = $payment->toArray();
        $merge = array_merge($sales, $payment);
        usort($merge, "custom_sort");
        $data['customer_ledger'] = $merge;

        $data['to'] = isset($_GET['to']) ? $_GET['to'] : null;
        $data['from'] = isset($_GET['from']) ? $_GET['from'] : null;

        return view('admin.customer.customer_ledger', $data);
    }

    /**
     * [getCustomerLedger description]
     * @param  [type] $from [description]
     * @param  [type] $to   [description]
     * @param  [type] $id   [description]
     * @return [type]       [description]
     */
    public static function getCustomerLedger($from, $to, $id)
    {
        $sales = SaleOrder::where(['customer_id' => $id, 'transaction_type' => 'SALESINVOICE']);
        if ($from && $to) {
            $sales->where('order_date', '>=', DbDateFormat($from));
            $sales->where('order_date', '<=', DbDateFormat($to));
        }
        $sales = $sales->select('reference', 'order_date as transaction_date', 'total', 'customer_id')
            ->orderBy('transaction_date')->get()->toArray();

        $payment = CustomerTransaction::where('customer_id', $id)
            ->with('saleOrder:id,reference')
            ->get(['sale_order_id', 'transaction_date', 'amount', 'customer_id'])->toArray();

        $merge = array_merge($sales, $payment);
        usort($merge, "custom_sort");
        $customer_ledger = $merge;
        return $customer_ledger;
    }

    /**
     * [ledegerFilterCsv description]
     * @param Request $request [description]
     * @return [type]           [description]
     */
    public function ledegerFilterCsv(Request $request)
    {
        return Excel::download(new CustomerLedgerExport(), 'customer_ledger_' . time() . '.csv');
    }

    /**
     * [ledegerFilterPdf description]
     * @param Request $request [description]
     * @return [type]           [description]
     */
    public function ledegerFilterPdf(Request $request)
    {
        $from = isset($request->from) ? formatDate(date("d-m-Y", strtotime($request->from))) : null;
        $to = isset($request->to) ? formatDate(date("d-m-Y", strtotime($request->to))) : null;
        $date_range = ($from && $to) ? formatDate($from) . ' To ' . formatDate($to) : 'No Date Selected';
        $customerData = Customer::find($request->id);
        $customer_ledger = $this->getCustomerLedger($from, $to, $request->id);
        $company_logo = Preference::getAll()->where('category', 'company')->where('field', 'company_logo')->first('value');

        $data = ['from' => $from, 'to' => $to, 'date_range' => $date_range, 'customerData' => $customerData, 'customer_ledger' => $customer_ledger, 'company_logo' => $company_logo];
        return printPDF($data, 'customer_ledger_' . time() . '.pdf', 'admin.customer.customer_ledger_pdf', view('admin.customer.customer_ledger_pdf', $data), 'pdf', 'domPdf');
    }

    public function adminLogin($id)
    {
        $customerExist = Customer::where(['id' => $id, 'is_active' => 0])->exists();
        if ($customerExist) {
            Session::flash('fail', __('Account has been deactivated.'));
            return redirect()->back();
        }
        Auth::guard('customer')->loginUsingId($id, true);
        return redirect()->intended('customer/dashboard');
    }

    public function customerListCsv()
    {
        return Excel::download(new allCustomerExport(), 'all_customer_details_' . time() . '.csv');
    }

    public function customerListPdf()
    {
        $url_components = parse_url(url()->previous());
        $url_components = !empty($url_components['query']) ? explode('=', $url_components['query']) : null;
        $data['customersList'] = Customer::select();
        if (!empty($url_components) && $url_components[1] == "active") {
            $data['customersList']->where('is_active', 1);
        }
        if (!empty($url_components) && $url_components[1] == "inactive") {
            $data['customersList']->where('is_active', 0);
        }
        $data['customersList'] = $data['customersList']->get();

        $data['company_logo'] = Preference::getAll()->where('category', 'company')->where('field', 'company_logo')->first('value');
        $data['company_logo'] = isset($data['company_logo']['value']) && !empty($data['company_logo']['value']) ? $data['company_logo']['value'] : '';
        return printPDF($data, 'customers_list_' . time() . '.pdf', 'admin.customer.customer_list_pdf', view('admin.customer.customer_list_pdf', $data), 'pdf', 'domPdf');
    }
}
