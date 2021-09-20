<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeBase;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use App\Models\Country;
use App\Models\Customer;
use App\Models\CustomerBranch;
use App\Models\Item;
use App\Models\ItemTaxType;
use App\Models\Location;
use App\Models\CustomerTransaction;
use App\Models\PaymentMethod;
use App\Models\Preference;
use App\Models\SaleOrder;
use App\Models\SaleType;
use App\Models\Ticket;
use App\Models\SaleTax;
use App\Models\TaxType;
use App\Models\File;
use App\Models\Currency;
use App\Models\Account;
use App\Models\TransactionReference;
use App\Models\EmailTemplate;
use App\Models\Project;
use DB;
use PDF;
use Carbon;
use App\DataTables\CustomerPanelProjectListDataTable;
use App\DataTables\CustomerPanelTaskListDataTable;
use App\DataTables\CustomerPanelTimeSheetListDataTable;
use App\DataTables\CustomerPanelMileStoneListDataTable;
use App\DataTables\CustomerPanelQuotationListDataTable;
use App\DataTables\CustomerPanelInvoiceListDataTable;
use App\DataTables\CustomerPanelPaymentListDataTable;
use App\DataTables\MilestoneTaskDataTable;
use App\Models\Task;
use Session;
use Cache;


class CustomerPanelController extends Controller
{
	public function __construct(SaleOrder $saleOrder, CustomerPanelProjectListDataTable $customerPanelProjectListDataTable, CustomerPanelTaskListDataTable $customerPanelTaskListDataTable, Task $task, CustomerPanelTimeSheetListDataTable $timesheetDataTable, CustomerPanelMileStoneListDataTable $milestoneDataTable, MilestoneTaskDataTable $mileStoneTaskDataTable, CustomerPanelQuotationListDataTable $customerPanelQuotationListDataTable, CustomerPanelPaymentListDataTable $customerPanelPaymentListDataTable, EmailController $email)
    {
       $this->middleware('customer');
       $this->saleOrder = $saleOrder;
       $this->task = $task;
       $this->customerPanelProjectListDataTable = $customerPanelProjectListDataTable;
       $this->customerPanelTaskListDataTable = $customerPanelTaskListDataTable;
       $this->timesheetDataTable = $timesheetDataTable;
       $this->milestoneDataTable = $milestoneDataTable;
       $this->mileStoneTaskDataTable = $mileStoneTaskDataTable;
       $this->customerPanelQuotationListDataTable = $customerPanelQuotationListDataTable;
       $this->customerPanelPaymentListDataTable = $customerPanelPaymentListDataTable;
       $this->email = $email;
    }

    public function index()
    {
        $data['menu'] = 'home';
        $data['page_title'] = __('Customer Dashboard');
        $uid = Auth::guard('customer')->user()->id;

        $data['totalOrder'] =   SaleOrder::where(['transaction_type' => "SALESORDER",
                                                    'customer_id' => $uid
                                                ])->count();

        $totalInvoice =   SaleOrder::where(['transaction_type' => "SALESINVOICE",
                                                    'customer_id' => $uid
                                                ])->count();
        $totalPos =   SaleOrder::where(['transaction_type' => "POSINVOICE",
                                                    'order_type' => 'directPOS',
                                                    'customer_id' => $uid
                                                ])->count();
        $data['totalInvoice'] = $totalInvoice + $totalPos;
        $data['totalSupport'] = Ticket::where(['customer_id' => $uid])->count();

        $data['totalBranch'] = CustomerBranch:: where('customer_id', $uid)->count();
        $data['uid'] = $uid;
        $data['totalKnowledge'] = KnowledgeBase::getAll()->where('status','publish')->count();
        return view('admin.customer.customer_panel', $data);
    }

    public function profile()
    {
        $data['menu'] = 'home';
        $id = Auth::guard('customer')->user()->id;
        $data['userData'] = DB::table('customers')->where('id', $id)->first();

        return view('admin.customerPanel.editProfile', $data);
    }

    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'first_name'            => 'required',
            'last_name'             => 'required'
        ]);

        $id = Auth::guard('customer')->user()->id;

        $data['first_name'] = $request->first_name;
        $data['last_name']  = $request->last_name;
        $data['email']      = $request->email;
        $data['phone']      = $request->phone;
        $data['timezone']   = $request->timezone;
        $data['updated_at'] = date('Y-m-d H:i:s');

        Cache::forget('gb-dflt_timezone_customer');

        DB::table('customers')->where('id', $id)->update($data);
        Session::flash('success', __('Successfully updated'));
        return redirect()->back();
    }

    public function updateCustomerPassword(Request $request)
    {
        $this->validate($request, [
            'password'              => 'min:5|confirmed',
            'password_confirmation' => 'min:5'
        ]);

        $id = Auth::guard('customer')->user()->id;
        $email = Auth::guard('customer')->user()->email;

        if ($request->password) {
            $data['password'] = \Hash::make($request->password);
        }

        \DB::beginTransaction();

        DB::table('customers')->where('id', $id)->update($data);
        // Retrive preference value and field name
        $prefer = Preference::getAll()->pluck('value', 'field')->toArray();
        // Retrive Set Password email template
        $data['emailInfo'] = EmailTemplate::where(['template_id' => 18, 'language_short_name' => $prefer['dflt_lang']])->first(['subject', 'body']);

        $subject =  (!empty($data['emailInfo']['subject'])) ? $data['emailInfo']['subject'] : "Set Password!";
        $message =  $data['emailInfo']['body'];
        // Replacing template variable
        $message = str_replace('{company_name}', $prefer['company_name'], $message);
        $message = str_replace('{company_email}', $prefer['company_email'], $message);
        $message = str_replace('{company_phone}', $prefer['company_phone'], $message);
        $message = str_replace('{company_street}', $prefer['company_street'], $message);
        $message = str_replace('{company_city}', $prefer['company_city'], $message);
        $message = str_replace('{company_state}', $prefer['company_state'], $message);

        // Send Mail to the customer
        if (!empty($email)) {
            $emailResponse = $this->email->sendEmail($email, $subject, $message);

            if ($emailResponse['status'] == false) {
                 \DB::rollBack();
                return redirect()->back()->withInput()->withErrors(['fail' => $emailResponse['message']]);
             }
        }

        \Db::commit();
        Session::flash('success', __('Successfully updated'));
        return redirect()->back();
    }


    public function salesOrder(CustomerPanelQuotationListDataTable $dataTable)
    {
        $id = Auth::guard('customer')->user()->id;
        $data['menu'] = 'order';
        $data['page_title'] = __('Customer Quotations');
        $data['from'] = $from = isset($_GET['from']) ? $_GET['from'] : null ;
        $data['to'] = $to = isset($_GET['to']) ? $_GET['to'] : null ;

        $data['settings'] = Preference::getAll();

        $prefer = $data['settings']->pluck('value', 'field')->toArray();

        $data['currency'] = $currency =  DB::table('currencies')
                                            ->select('currencies.symbol')
                                            ->leftjoin('customers', 'currencies.id', 'customers.currency_id')
                                            ->where('customers.id', $id)
                                            ->first();

        $data['customerData']   = Customer::find($id);

        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;
        return $dataTable->with('row_per_page', $row_per_page)->with('currency', $currency)->render('admin.customerPanel.customer_sales_order', $data);
    }



    public function invoice(CustomerPanelInvoiceListDataTable $dataTable)
    {
        $id = Auth::guard('customer')->user()->id;
        $data['menu'] = 'invoice';
        $data['page_title'] = __('Customer Invoices');
        $data['from'] = $from = isset($_GET['from']) ? $_GET['from'] : null;
        $data['to'] = $to = isset($_GET['to']) ? $_GET['to'] : null;
        $data['payStatus'] = $allstatus = isset($_GET['pay_status_type']) ? $_GET['pay_status_type'] : null;
        $data['settings'] = Preference::getAll();
        $data['currency'] = $currency =  DB::table('currencies')
                                            ->select('currencies.symbol')
                                            ->leftjoin('customers', 'currencies.id', 'customers.currency_id')
                                            ->where('customers.id', $id)
                                            ->first();
        $data['customerData'] = $customerData = Customer::find($id);
        $data['amounts'] = $amounts = $this->saleOrder->getMoneyStatus(['customer_id' => $id, 'from' => $from, 'to' => $to, 'status' => $allstatus]);

        $allCurrency = [];
        $overdueCurrency = [];
        foreach ($amounts['amounts'] as $amount) {
            if (isset($amount->currency->symbol) && !empty($amount->currency->symbol)) {
                $allCurrency[] =  $amount->currency->symbol;
            }
        }
        foreach ($amounts['overDue'] as $amount) {
            if (isset($amount->currency->symbol) && !empty($amount->currency->symbol)) {
                $allCurrency[] =  $amount->currency->symbol;
            }
        }
        $data['allCurrency'] = array_diff($allCurrency, $overdueCurrency);

        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;
        return $dataTable->with('row_per_page', $row_per_page)->with('currency', $currency)->with('customerInfo', $customerData)->render('admin.customerPanel.customer_invoice', $data);
    }

    public function payment(CustomerPanelPaymentListDataTable $dataTable)
    {
        $id = Auth::guard('customer')->user()->id;
        $data['menu'] = 'payment';
        $data['page_title'] = __('Customer Payments');
        $data['from']         = $from         = isset($_GET['from']) ? $_GET['from'] : null;
        $data['to']           = $to           = isset($_GET['to']) ? $_GET['to'] : null;
        $data['method']   = $method   = isset($_GET['method']) ? $_GET['method'] : null;
        $data['status'] = $status   = isset($_GET['status']) ? $_GET['status'] : null;
        $data['paymentMethod'] = PaymentMethod::getAll()->pluck('name', 'id')->toArray();

        $data['settings'] = Preference::getAll();

        $data['currency'] = $currency = DB::table('currencies')->select('currencies.symbol')
                                            ->leftjoin('customers', 'customers.currency_id', '=', 'currencies.id')
                                            ->where('customers.id', $id)
                                            ->first();
        $data['customerData'] = $customerData = Customer::find($id);

        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;
        return $dataTable->with('row_per_page', $row_per_page)->with('currency', $currency)->with('customerInfo', $customerData)->render('admin.customerPanel.customer_payment', $data);
    }

    public function viewOrderDetails($orderNo)
    {
        $data['menu'] = 'order';
        $data['page_title'] = __('View Customer Quotation');
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['dflt_currency_id'] = $preference['dflt_currency_id'];

        $data['saleOrderData'] = SaleOrder::with([
                                                'saleOrderDetails',
                                                'location:id,name',
                                                'customer',
                                                'CustomerBranch',
                                                'currency',
                                                'paymentTerm',
                                                ])->find($orderNo);
        foreach ($data['saleOrderData']->saleOrderDetails as $key => $value) {
            if ($data['saleOrderData']->has_tax == 1 && $value->quantity > 0) {
                $value->taxList = (new SaleTax)->getSaleTaxesInPercentage($value->id);
            }
        }
        $data['item_tax_types']   = TaxType::getAll();
        $data['taxes']            = (new SaleOrder)->calculateTaxRows($orderNo);
        $fileOrderType = $data['saleOrderData']->order_type == 'Direct Order' ? 'Direct Order' : 'Direct Order';
        $data['files'] = (new File)->getFiles($fileOrderType, $orderNo);

        if (!empty($data['files'])) {
            $data['filePath'] = "public/uploads/invoice_order";
            foreach ($data['files'] as $key => $value) {
                $value->icon = getFileIcon($value->file_name);
                $value->extension = strtolower(pathinfo($value->file_name, PATHINFO_EXTENSION));
            }
        }
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['dflt_currency_id'] = $preference['dflt_currency_id'];
        $lang = $preference['dflt_lang'];

        $checkInvoiced = SaleOrder::where('order_reference_id', $orderNo)->first();
        if ($checkInvoiced) {
            $data['invoiced_date'] = $checkInvoiced->created_at;
            $data['invoiced_status'] = 'yes';
            $data['ref_invoice']     = $checkInvoiced->reference;
            $data['order_reference_id'] = $checkInvoiced->order_reference_id;
            $data['order_no']           = $checkInvoiced->id;
        } else {
            $data['invoiced_status'] = 'no';
        }

        return view('admin.customerPanel.viewOrderDetails', $data);
    }

    /**
    * Preview of Invoice details
    * @params order_no, invoice_no
    **/
    public function viewInvoiceDetails($invoiceNo)
    {
        $data['menu'] = 'invoice';
        $data['page_title'] = __('View Customer Invoice');
        $data['invoice_no']       = $invoiceNo;
        $preference               = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['dflt_currency_id'] = $preference['dflt_currency_id'];
        $data['saleInvoiceData']  = SaleOrder::with([
                                                'location:id,name',
                                                'paymentTerm:id,days_before_due',
                                                'currency:id,name,symbol',
                                                'saleOrderDetails',
                                                'customer:id,first_name,last_name,email,phone',
                                                'customerBranch:id,name,billing_street,billing_city,billing_state,billing_zip_code,billing_country_id'
                                            ])->find($invoiceNo);
        $data['saleOrderData']    = $data['saleInvoiceData'] != "POSINVOICE" ? SaleOrder::with(['location:id,name'])->find($data['saleInvoiceData']->order_reference_id) : null;
        if (empty($data['saleInvoiceData'])) {
            Session::flash('fail', __('Invoice not available'));
            return redirect()->intended('invoice/list');
        }
        foreach ($data['saleInvoiceData']->saleOrderDetails as $key => $value) {
            if ($data['saleInvoiceData']->has_tax == 1 && $value->quantity > 0) {
                $value->taxList = (new SaleTax)->getSaleTaxesInPercentage($value->id);
            }
        }
        $data['taxes']            = (new SaleOrder)->calculateTaxRows($invoiceNo);
        $data['paymentMethods']   = PaymentMethod::getAll();
        $data['paymentsList']     = CustomerTransaction::where('sale_order_id', $invoiceNo)->latest('id')->get();
        $data['currencies']       = Currency::getAll()->pluck('name', 'id')->toArray();

        $data['item_tax_types']   = TaxType::getAll();

        if ($data['saleInvoiceData']->pos_shipping) {
            $data['saleInvoiceData']->shipping_address = json_decode($data['saleInvoiceData']->pos_shipping);
            if ($data['saleInvoiceData']->shipping_address->ship_country_id) {
                $data['saleInvoiceData']->shipping_address->ship_country = Country::where('code', $data['saleInvoiceData']->shipping_address->ship_country_id);
            } else {
                $data['saleInvoiceData']->shipping_address->ship_country = "";
            }
        }
        $reference = TransactionReference::where('reference_type', 'INVOICE_PAYMENT')->latest('id')->first();
        if (!empty($reference)) {
            $info  = explode('/', $reference->code);
            $refNo = (int)$info[0];
            $data['reference'] = sprintf("%03d", $refNo + 1) . '/' . date('Y');
        } else {
            $data['reference'] = sprintf("%03d", 1) . '/' . date('Y');
        }

        $data['files'] = (new File)->getFiles('Direct Invoice', $invoiceNo);
        if (!empty($data['files'])) {
            $data['filePath'] = "public/uploads/invoice_order";
            foreach ($data['files'] as $key => $value) {
                $value->icon = getFileIcon($value->file_name);
                $value->extension = strtolower(pathinfo($value->file_name, PATHINFO_EXTENSION));
            }
        }

        $data['publishableKey'] = PaymentMethod::getAll()->where('id', 3)->first()->consumer_key;
        $data['paymentmentMethod'] = PaymentMethod::getAll()->pluck('is_active', 'name')->toArray();
        $accountId = PaymentMethod::getAll()->where('id', 2)->first()->client_id;
        $data['accountInfo'] = Account::where('id', $accountId)->first();

        return view('admin.customerPanel.viewInvoiceDetails', $data);
    }


    /**
    * Display receipt of payment
    */
    public function viewReceipt($id)
    {
        $data['menu'] = 'payment';
        $data['page_title'] = __('Customer Payment Receipt');

        $data['paymentInfo'] = CustomerTransaction::find($id);
        $lang = Preference::getAll()->where('field', 'dflt_lang')->first()->value;
        $data['files'] = (new File)->getFiles('Invoice Payment', $id);

        if (!empty($data['files'])) {
            $data['filePath'] = "public/uploads/invoice_payment";
            foreach ($data['files'] as $key => $value) {
                $value->icon = getFileIcon($value->file_name);
                $value->extension = strtolower(pathinfo($value->file_name, PATHINFO_EXTENSION));
            }
        }

        return view('admin.customerPanel.viewReceipt', $data);
    }

    public function projectList()
    {
        $id = Auth::guard('customer')->user()->id;
        $data['menu'] = 'project';
        $data['page_title'] = __('Customer Projects');
        $data['customer_id'] = $id;
        $data['customerData']   = Customer::find($id);
        $data['status'] = DB::table('project_statuses')->select('id','name')->get();
        $data['from']         = $from = isset($_GET['from']) ? $_GET['from'] : null;
        $data['to']           = $to = isset($_GET['to']) ? $_GET['to'] : null;
        $data['allstatus']    = $allstatus = isset($_GET['status']) ? $_GET['status'] : '';
        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;
        return $this->customerPanelProjectListDataTable->with('row_per_page', $row_per_page)->with('customer_id', $id)->render('admin.customerPanel.project.list',$data);
    }

    public function projectDetails($project_id)
    {
        $customer_id = Auth::guard('customer')->user()->id;
        $data['menu'] = 'project';
        $data['page_title'] = __('View Customer Project');
        $data['header'] = 'project';
        $data['navbar'] = 'details';
        $data['project'] = Project::with(['customer:id,name', "projectStatuses:id,name"])
                                    ->where(['id' => $project_id, 'customer_id' => $customer_id])->first();
        if (empty($data['project'])) {
            Session::flash('fail', __('The data you are trying to access is not found'));
            return redirect()->back();
        }
        $data['totalTask'] = DB::table('tasks')
          ->where('tasks.related_to_type', 1)
          ->where('tasks.related_to_id', $project_id)
          ->count();
        $data['completedTask'] = DB::table('tasks')
          ->where('tasks.related_to_type', 1)
          ->where('related_to_id', $project_id)->where('task_status_id', 4)
          ->count();

      if ($data['project']->due_date == null) {
        $datediff = time() - strtotime($data['project']->begin_date);
        $data['dayCount'] = abs(intval(round($datediff / (60 * 60 * 24))));
        $data['dayTitle'] = __('Days passed');
      } else {
        $datediff = time() - strtotime($data['project']->due_date);
        $data['dayCount'] = abs(intval(round($datediff / (60 * 60 * 24))));
        $data['dayTitle'] = __('Days left');
      }


        $data['checkTicket'] = Ticket::where(['project_id' => $project_id, 'customer_id' => $customer_id])->first();
        $data['projectMembers'] = DB::table('project_members')
            ->where('project_members.project_id', $project_id)
            ->leftJoin('users','users.id','=','project_members.user_id')
            ->get();
        $data['users']=DB::table('users')->get();
        $data['tags'] = DB::table('tag_assigns')
                      ->leftJoin('tags','tags.id','=','tag_assigns.tag_id')
                      ->select(DB::raw("(GROUP_CONCAT(tags.name SEPARATOR ',')) as `all_tags`"))
                      ->groupBy('tag_assigns.reference_id')
                      ->where(['tag_assigns.reference_id'=> $project_id, 'tag_type'=> 'project'])
                      ->first();
        $newArry = [];
        foreach ($data['projectMembers'] as $key => $value) {
            $icon = (new File)->getFiles('USER', $value->user_id);
            if (count($icon) != 0) {
                $value->imageIcon = $icon[0]->file_name;
            }
        }
        $data['oldMembers'] = json_encode($newArry);

        $data['total_logged_time'] = (new Project)->projectLoggedTime($project_id);

        $data['amounts'] = $amounts = $this->saleOrder->getMoneyStatus(['customer_id' => $customer_id, 'project_id' => $project_id]);

        $allCurrency = [];
        $overdueCurrency = [];
        foreach ($amounts['amounts'] as $amount) {
            if (isset($amount->currency->symbol) && !empty($amount->currency->symbol)) {
              $allCurrency[] =  $amount->currency->symbol;
            }
        }
        foreach ($amounts['overDue'] as $amount) {
            if (isset($amount->currency->symbol) && !empty($amount->currency->symbol)) {
              $overdueCurrency[] =  $amount->currency->symbol;
            }
        }
        $data['allCurrency'] = array_diff($allCurrency, $overdueCurrency);

        $data['files'] = (new File)->getFiles('PROJECT', $project_id);

        if (!empty($data['files'])) {
            $data['filePath'] = "public/uploads/project_files";
            foreach ($data['files'] as $key => $value) {
                $value->icon = getFileIcon($value->file_name);
                $value->extension = strtolower(pathinfo($value->file_name, PATHINFO_EXTENSION));
            }
        }

      return view('admin.customerPanel.project.detail', $data);
    }

    public function projectTaskList($project_id,$customer_id)
    {
        $data['menu'] = 'project';
        $data['header'] = 'project';
        $data['navbar'] = 'task';
        $data['customer_id']=$customer_id;
        $data['project'] = $this->getProject($project_id);
        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;
        return $this->customerPanelTaskListDataTable->with('row_per_page', $row_per_page)->with('project_id',$project_id)->render('admin.customerPanel.project.tasklist',$data);
    }

    public function projectTaskDetails($project_id,$task_id)
    {
        $data['menu']   = 'project';
        $data['page_title'] = __('Customer Project\'s Tasks');
        $data['header'] = 'project';
        $data['navbar'] = 'task';
        $data['task_comment']     = $this->task->getAllCommentCustomerPanel($task_id);
        $data['task_logged_time'] = $this->task->getTaskLoggedTimeById($task_id);

        $data['project'] = $this->getProject($project_id);
        $data['task']         = $this->task->getTaskDetailsById($task_id);
        $data['task_assign']  = $this->task->taskAssignsList($task_id);
        $data['files']        = $this->task->getCustomerTaskFiles($task_id);
        $data['file_extns']   = ['gif','png','jpg','jpeg'];
        return view('admin.customerPanel.project.task.details',$data);
    }

    private function getCustomerInfo($orderNo)
    {
        return DB::table('sale_orders')
                ->where('sale_orders.id', $orderNo)
                ->leftjoin('customers', 'customers.id', '=', 'sale_orders.customer_id')
                ->leftjoin('cust_branch', 'cust_branch.id', '=', 'sale_orders.branch_id')
                ->leftjoin('countries', 'countries.code', '=', 'cust_branch.shipping_country_id')
                ->select('customers.id', 'customers.name', 'customers.phone', 'customers.email', 'cust_branch.br_name', 'cust_branch.br_address', 'cust_branch.billing_street', 'cust_branch.billing_city', 'cust_branch.billing_state', 'cust_branch.billing_zip_code', 'cust_branch.billing_country_id', 'cust_branch.shipping_street', 'cust_branch.shipping_city', 'cust_branch.shipping_state', 'cust_branch.shipping_zip_code', 'cust_branch.shipping_country_id', 'countries.country')
                ->first();
    }

    private function getProject($project_id)
    {
        return Project::with(['customer:id,name', "projectStatuses:id,name"])->where('id', $project_id)->first();
    }

    private function getSalesData($orderNo)
    {
        return DB::table('sale_orders')
                ->where('sale_orders.id', $orderNo)
                ->leftJoin('locations','locations.id','=','sale_orders.location_id')
                ->select("sale_orders.*","locations.name")
                ->first();
    }

    private function getPaymentInfo($id)
    {
        return DB::table('customer_transactions')
            ->leftjoin('payment_terms','payment_terms.id','=','customer_transactions.payment_type_id')
            ->leftjoin('sale_orders','sale_orders.reference','=','customer_transactions.invoice_reference')
            ->leftjoin('customers','customers.id','=','customer_transactions.customer_id')
            ->leftjoin('cust_branch','cust_branch.id','=','sale_orders.branch_id')
            ->leftjoin('countries','countries.code','=','cust_branch.billing_country_id')
            ->where('customer_transactions.id',$id)
            ->select('customer_transactions.*','payment_terms.name as payment_method','cust_branch.br_name as branch_name','cust_branch.br_address','cust_branch.billing_street','cust_branch.billing_city','cust_branch.billing_state','cust_branch.billing_zip_code','cust_branch.billing_country_id','sale_orders.ord_date as invoice_date','sale_orders.total as invoice_amount','sale_orders.order_reference_id','countries.country','customers.email','customers.phone','customers.name')
            ->first();
    }
}
