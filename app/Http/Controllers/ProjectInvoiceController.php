<?php

namespace App\Http\Controllers;

use App\DataTables\ProjectInvoiceDataTable;
use App\Exports\projectInvoicesExport;
use App\Http\Requests;
use App\Http\Start\Helpers;
use App\Models\Account;
use App\Models\Project;
use App\Models\BankTransaction;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\ExchangeRate;
use App\Models\Preference;
use App\Models\Reference;
use App\Models\SaleOrder;
use App\Models\Transaction;
use Auth;
use DB;
use Excel;
use Illuminate\Http\Request;
use Image;
use Input;
use PDF;
use Session;
use Validator;

class ProjectInvoiceController extends Controller
{
    public function __construct(Account $account, ProjectInvoiceDataTable $projectInvoiceDataTable,  SaleOrder $saleOrder)
    {
        $this->account                 = $account;
        $this->projectInvoiceDataTable = $projectInvoiceDataTable;
        $this->saleOrder               = $saleOrder;
    }
    /**
     * Display a listing of the Bank Accounts
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        if (isset($_GET['customer'])) {
            $data['menu'] = 'relationship';
            $data['sub_menu'] = 'customer';
        } else if (isset($_GET['users'])) {
            $data['menu'] = 'relationship';
            $data['sub_menu'] = 'users';
        } else {
            $data['menu']       = 'project';
            $data['sub_menu']   = '';
        }
        $data['header'] = 'project';
        $data['page_title'] = __('Invoices of Project');
        $data['navbar'] = 'invoice';
        $data['project'] = DB::table('projects')
                          ->leftJoin('project_statuses as ps','ps.id','=','projects.project_status_id')
                          ->select('projects.id','projects.name','customer_id','ps.name as status_name')
                          ->where('projects.id', $id)->first();
        if (isset($_GET['from'])) {
            $data['from'] = $_GET['from'];
        }
        if (isset($_GET['to'])) {
            $data['to'] = $_GET['to'];
        }
        $array = [];
        $data['amounts'] = $amounts = $this->saleOrder->getMoneyStatus(['project_id' => $id,'from' => !empty($data['from']) ? $data['from'] : '' , 'to' => !empty($data['to']) ? $data['to'] : '']);

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

        $row_per_page = Preference::getAll()->where('field', 'row_per_page')->first()->value;

        return $this->projectInvoiceDataTable->with('row_per_page', $row_per_page)->with('project_id', $id)->render('admin.project.invoice.list', $data);
    }

    public function project_invoice_csv()
    {
        return Excel::download(new projectInvoicesExport(), 'project_invoices_details'.time().'.csv');
    }

    public function project_invoice_pdf()
    {
        $data['from'] = $from = isset($_GET['from']) ? $_GET['from'] : null ;
        $data['to'] = $to = isset($_GET['to']) ? $_GET['to'] : null ;
        $data['project'] = $project = isset($_GET['project']) ? $_GET['project'] : null ;
        $data['projectData'] = Project::find($_GET['project']);
        $data['saleslist'] = (new SaleOrder)->getAllSaleOrderByProject($from, $to, $project)->orderBy('sale_orders.order_date','DESC')->get();
        $data['date_range'] = ($from && $to) ? formatDate($from) . ' To ' . formatDate($to) : 'No Date Selected';
        return printPDF($data, 'project_invoice_list_' . time() . '.pdf', 'admin.invoice.list-pdf-project_invoice', view('admin.invoice.list-pdf-project_invoice', $data), 'pdf', 'domPdf');
    }

}
