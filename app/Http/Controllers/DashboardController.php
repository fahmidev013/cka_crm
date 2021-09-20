<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Start\Helpers;
use App\Http\Requests;
use DB;
use Session;
use Cache;
use App;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Report;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Preference;
use App\Models\Project;
use App\Models\ProjectStatus;
use App\Models\Task;
use App\Models\Lead;
use App\Models\Expense;
use App\Models\LeadStatus;
use App\Models\SaleOrder;
use App\Models\Ticket;
use MaddHatter\LaravelFullcalendar\Facades\Calendar;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\EmailTemplate;

class DashboardController extends Controller
{
    public function __construct(Currency $currency, Report $report, Transaction $transaction, Account $account,Project $project, Task $task, Ticket $ticket, ProjectStatus $projectStatus, SaleOrder $saleOrder, Lead $lead, LeadStatus $leadStatus, Expense $expense) {
        $this->currency      = $currency;
        $this->report        = $report;
        $this->transaction   = $transaction;
        $this->account       = $account;
        $this->project       = $project;
        $this->task          = $task;
        $this->ticket        = $ticket;
        $this->projectStatus = $projectStatus;
        $this->saleOrder     = $saleOrder;
        $this->lead          = $lead;
        $this->leadStatus    = $leadStatus;
        $this->expense       = $expense;
    }
    
	protected $data = [];

    /**
     * Display a listing of the Over All Information on Dashboard.
     *
     * @return Dashboard page view
     */
    public function index(Request $request)
    {
        $data['menu'] = 'dashboard';
        $data['page_title'] = __('Dashboard');
        $successQuotation = 0;

        if (!empty($_GET['from']) && !empty($_GET['to'])) {
            $from = DbDateFormat($_GET['from']);
            $to = DbDateFormat($_GET['to']);
            if ($from > $to) {
                return redirect()->back();
            }
        } else {
            $from = date('Y-m-d', strtotime("-1 year"));
            $to = date('Y-m-d');
        }

        $data['from'] = isset($_GET['from']) ? $_GET['from'] : null;
        $data['to']   = isset($_GET['to']) ? $_GET['to'] : null;

        $data['currencyList'] = Currency::getAll();
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $currency = isset($_GET['currency']) ? (int) $_GET['currency'] : $preference['dflt_currency_id'];
        $data['currency'] = $this->currency->find($currency);  
        $data['projectStat'] = $this->projectStatus->getProjectStat($from, $to);
        $data['taskStat'] = $this->task->getTaskStat($from, $to);
        $data['ticketStat'] = $this->ticket->getTicketStat($from, $to);  

        $salesOrder= (new SaleOrder)->getAllQuotation($from, $to, null, null, $currency)->get();
        for ($i=0; $i < count($salesOrder); $i++) { 
            if (isset($salesOrder[$i]->parent) && !empty($salesOrder[$i]->parent)) {
                $successQuotation++;
            }
        }
        $quotationStat['totalQuotation'] = count($salesOrder);
        $quotationStat['quotationInvoiced'] = $successQuotation;
        $data['quotationStat'] = $quotationStat;

        $expenseList = $this->expense->getGenerelExpenses($from, $to, $currency);
        $months = getMonths($from, $to);
        if (!empty($expenseList)) {
            foreach ($expenseList['expenses'] as $key => $value) {
                foreach ($months as $k => $month) {
                    $total[$key] += !empty($value[$month]) ? $value[$month] : 0;
                }
            }
        }

        $totalPurchaseExpense = 0;
        $purchaseExpenses = $this->expense->getPurchaseExpenses($from, $to, $currency)->get(['reference', 'order_date', 'currency_id', 'total']);
        foreach ($purchaseExpenses as $key => $value) {
            $totalPurchaseExpense += $value->total;
        }
        $total['Purchases'] = $totalPurchaseExpense; 
        $data['categoryTotalExpenses'] = $total;

        $totalIncome = [];
        $data['incomeList'] = $this->saleOrder->getAllIncomeStat($from, $to, $currency);
        $data['leasStat'] = [];
        $leads = $this->leadStatus->where('status', 'active');
        $leads->with(['leads' => function($query) use($from, $to) {
            $query->whereBetween('date_assigned', [$from, $to]);
        }]);
        $leasStat = $leads->get();
        foreach ($leasStat as $key => $value) {
            $data['leasStat'][$value->name] = ['count' => count($value->leads->toArray()), 'color' => $value->color];
        }
        $array = [];
        $invoiceSummery = $this->saleOrder->getMoneyStatus(['from' => $from, 'to' => $to, 'currency' => $currency]);
        foreach ($invoiceSummery['amounts'] as $key => $value) {
            $array['totalInvoice'] = $value['totalInvoice'];
            $array['totalPaid'] = $value['totalPaid'];
            $overDueTotalAmount = !empty($invoiceSummery['overDue'][$key]['totalAmount']) ? $invoiceSummery['overDue'][$key]['totalAmount'] : 0;
            $overDueTotalPaid = !empty($invoiceSummery['overDue'][$key]['totalPaid']) ? $invoiceSummery['overDue'][$key]['totalPaid'] : 0;
            $array['totalDue'] = $value['totalInvoice'] - $value['totalPaid'];
            $array['overDue'] = $overDueTotalAmount - $overDueTotalPaid;
        }
        $data['invoiceSummery'] = $array;
        
        $data['projects'] = $this->project->getLatestProject($from, $to);
        $data['tasks'] = $this->task->getLatestTask($from, $to);
        $data['tickets'] = $this->ticket->getLatestTicket($from, $to);
        $data['currencySymbol'] = Currency::find($currency)->symbol;
        // colors
        $data['colors'] = ['#DD4B39', '#FFA09A', '#00A65A', '#F39C12', '#00C0EF', '#3C8DBC', '#E5FFFF', '#BCBE36', '#A261FA', '#4483F0', '#F0E69C', '#0059b6'];
        return view('admin.dashboard', $data);
    }


    /**
     * Change Language function
     *
     * @return true or false
     */
    public function switchLanguage(Request $request)
    {
        if ($request->lang) {
            if (empty(Auth::user()->id) && isset(Auth::guard('customer')->user()->id) && $request->type == 'customer') {
                Cache::put('gb-customer-language-'. Auth::guard('customer')->user()->id, $request->lang, 5 * 365 * 86400);
                echo 1;
                exit;
            } elseif (isset(Auth::user()->id) && $request->type == 'user') {
                Cache::put('gb-user-language-'. Auth::user()->id, $request->lang, 5 * 365 * 86400);
                echo 1;
                exit;
            }
        }
        echo 0;
        exit();
    }
}
