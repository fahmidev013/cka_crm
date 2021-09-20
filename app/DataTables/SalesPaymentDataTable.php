<?php
namespace App\DataTables;
use App\Models\CustomerTransaction;
use App\Models\Payment;
use App\Models\TransactionReference;
use Auth;
use DB;
use Helpers;
use Session;
use Yajra\DataTables\Services\DataTable;

class SalesPaymentDataTable extends DataTable
{
    public function ajax()
    {
        $salesPayment = $this->query();
        return datatables()
        ->of($salesPayment)
        ->addColumn('action', function ($salesPayment) {
            if (Helpers::has_permission(Auth::user()->id, 'edit_payment')) {

                $edit='<a title="Edit" class="btn btn-xs btn-info" href="'.url('payment/edit/'.base64_encode($salesPayment->id)).'"><span class="feather icon-edit"></span></a>&nbsp;';
            } else {
                $edit ='';
            }

            if (Helpers::has_permission(Auth::user()->id, 'delete_payment')) {
                $delete ='  <form method="POST" action="'.url("payment/delete").'" accept-charset="UTF-8" class="display_inline" id="delete-item-'.$salesPayment->id.'">
                '.csrf_field().'
                <input type="hidden" name="id" value="'.$salesPayment->id.'">
                <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id="'.$salesPayment->id.'" data-target="#theModal" data-label="Delete" data-title="'. __('Delete payment') . '" data-message="' . __('Are you sure to delete this?') . '">
                <i class="feather icon-trash-2"></i> 
                </button>
                </form>';
            } else {
                $delete='';
            }
            return $edit.$delete;
        })

        ->addColumn('name', function ($salesPayment) {
            if (Helpers::has_permission(Auth::user()->id, 'edit_customer')) {  
               return '<a href="'.url('customer/edit/'.$salesPayment->customer_id).'">'. $salesPayment->first_name.' '.$salesPayment->last_name .'</a>';
           } else {
              return $salesPayment->first_name.' '.$salesPayment->last_name;
           }
           
       })

        ->addcolumn('status',function($salesPayment){
            if ($salesPayment->status == 'Approved') {
                return '<span class="label label-success badge text-white f-12 task-priority color-4CAF50">'. __('Approved') .'</span>';
            } elseif ($salesPayment->status == 'Pending') {
                $top='<div class="btn-group">
                <button type="button" class="badge text-white f-12 dropdown-toggle task-priority color-04a9f5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               '. __('Pending').'&nbsp;<span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu scrollable-menu status_change task-priority-name">';

                $view='<li class="properties"><a class="status f-14 color_black" data-id="1" data-trans_id="'.htmlspecialchars(json_encode($salesPayment)).'">Approve</a>
                        </li>
                        <li class="properties"><a class="status f-14 cursor_pointer color_black" data-id="2" data-trans_id="'.$salesPayment->id.'">Decline</a></li>';

                $last='</ul></div>&nbsp';

                return $top.$view.$last;

            } elseif ($salesPayment->status == 'Declined') {
                return '<span class="label label-danger badge text-white f-12 task-priority color-f44236">'. __('Declined') .'</span>';
            }
        })

        ->addColumn('id', function ($salesPayment) {
            return '<a href="'.url('payment/view-receipt/'.$salesPayment->id).'">'.sprintf("%04d", $salesPayment->id).'</a>';
        })

        ->addColumn('invoice_reference', function ($salesPayment) {
            return '<a href="'.url('invoice/view-detail-invoice/' . $salesPayment->sale_order_id).'">'.$salesPayment->sale_order_reference.'</a>';
        })
        
        ->addColumn('pay_type', function ($salesPayment) {
            if ($salesPayment->transaction_type == "POSINVOICE") {
                return "<span class='text-capitalize'>" . $salesPayment->transactions_transaction_type . "</span>";
            }
            return isset($salesPayment->payment_type_name) ? $salesPayment->payment_type_name : 'N/A';
        })
     
       ->addColumn('currency', function ($salesPayment) {
            return $salesPayment->currency_name;
        })

        ->addColumn('payment_date', function ($salesPayment) {
            return formatDate($salesPayment->transaction_date);
        })

        ->addColumn('amount', function ($salesPayment) {
            return formatCurrencyAmount($salesPayment->amount);
        })
      
        ->rawColumns(['action','invoice_reference','name','order_amount', 'status', 'id', 'pay_type'])

        ->make(true);
    }

    public function query()
    {
        $customer = isset($_GET['customer']) ? $_GET['customer'] : null;
        $method = isset($_GET['method']) ? $_GET['method'] : null;
        $currency = isset($_GET['currency']) ? $_GET['currency'] : null;
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $from = isset($_GET['from']) ? $_GET['from'] : null;
        $to = isset($_GET['to']) ? $_GET['to'] : null;
        
        $salesPayment = DB::table('customer_transactions')
                        ->leftJoin('sale_orders', 'customer_transactions.sale_order_id', 'sale_orders.id')
                        ->leftJoin('customers', 'customer_transactions.customer_id', 'customers.id')
                        ->leftJoin('payment_methods', 'customer_transactions.payment_method_id', 'payment_methods.id')
                        ->leftJoin('currencies', 'customer_transactions.currency_id', 'currencies.id')
                        ->leftJoin('transactions', 'transactions.transaction_reference_id', 'customer_transactions.transaction_reference_id')
                        ->whereIn('sale_orders.transaction_type', ['SALESINVOICE', 'POSINVOICE'])
                        ->select('customer_transactions.*','sale_orders.order_reference_id', 'sale_orders.id as sale_order_id', 'sale_orders.reference as sale_order_reference', 'sale_orders.transaction_type', 'customers.first_name', 'customers.last_name', 'payment_methods.name as payment_type_name', 'currencies.name as currency_name', 'transactions.transaction_type as transactions_transaction_type', 'sale_orders.project_id', 'currencies.symbol' );
        
        if (!empty($from)) {
            $salesPayment->where('customer_transactions.transaction_date', '>=', DbDateFormat($from));  
        }
        if (!empty($to)) {
            $salesPayment->where('customer_transactions.transaction_date', '<=', DbDateFormat($to));
        }
        if (!empty($customer)) {
            $salesPayment->where('customer_transactions.customer_id', '=', $customer);
        }
        if (!empty($method)) {
            $salesPayment->where('customer_transactions.payment_method_id', '=', $method);
        }
        if (!empty($currency)) {
            $salesPayment->where('customer_transactions.currency_id', '=', $currency);
        }
        if (!empty($status)) {
            $salesPayment->where('customer_transactions.status', '=', $status);
        }
        if (Helpers::has_permission(Auth::user()->id, 'own_payment') && !Helpers::has_permission(Auth::user()->id, 'manage_payment')) {
            $salesPayment->where('customer_transactions.user_id', Auth::user()->id);
        }
        return $this->applyScopes($salesPayment);
    }
    
    public function html()
    {
        return $this->builder()

        ->addColumn(['data' => 'id', 'name' => 'customer_transactions.id', 'title' => 'No'])

        ->addColumn(['data' => 'invoice_reference', 'name' => 'sale_orders.reference', 'title' => __('Invoice No')])

        ->addColumn(['data' => 'name', 'name' => 'customers.first_name', 'title' => __('Customer Name')])

        ->addColumn(['data' => 'pay_type', 'name' => 'payment_methods.name', 'title' => __('Payment')])

        ->addColumn(['data' => 'amount', 'name' => 'customer_transactions.amount', 'title' => __('Amount')])

        ->addColumn(['data' => 'currency', 'name' => 'currencies.name', 'title' => __('Currency')])

        ->addColumn(['data' => 'status', 'name' => 'customer_transactions.status', 'title' => __('Status') ])

        ->addColumn(['data' => 'payment_date', 'name' => 'customer_transactions.transaction_date', 'title' => __('Payment Date') , 'orderable' => false])

        ->addColumn(['data'=> 'action', 'name' => 'action', 'title' => __('Action') , 'orderable' => false, 'searchable' => false])

        ->parameters([
            'pageLength' => $this->row_per_page,
            'language' => [
                    'url' => url('/resources/lang/'.config('app.locale').'.json'),
                ],
            'order' => [0, 'DESC']
        ]);
    }

    protected function getColumns()
    {
        return [
            'id',
            'created_at',
            'updated_at',
        ];
    }

    protected function filename()
    {
        return 'customers_' . time();
    }
}
