<?php
namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Models\CustomerTransaction;
use App\Models\Payment;
use Auth;
use DB;
use Helpers;
use Session;

class CustomerPanelPaymentListDataTable extends DataTable
{
    public function ajax()
    {
        $salesPayment = $this->query();
        return datatables()
        ->of($salesPayment)

        ->addcolumn('status',function($salesPayment){
            if ($salesPayment->status == 'Approved') {
                return '<span class="badge theme-bg text-white f-12">' . __('Approved') . '</span>';
            } elseif ($salesPayment->status == 'Pending') {
                return '<span class="badge theme-bg2 text-white f-12">Pending</span>';
            } elseif($salesPayment->status == 'Declined') {
                return '<span class="badge theme-bg-r text-white f-12">' . __('Declined') . '</span>';
            }
        })

        ->addColumn('id', function ($salesPayment) {
            return '<a href="'.url('customer-panel/view-receipt/'.$salesPayment->id).'">'.sprintf("%04d", $salesPayment->id).'</a>';
        })

        ->addColumn('invoice_reference', function ($salesPayment) {
            $reference = isset($salesPayment->saleOrder->reference) && !empty($salesPayment->saleOrder->reference) ? $salesPayment->saleOrder->reference : '';  
            return '<a href="' . url('customer-panel/view-detail-invoice') . '/' . $salesPayment->sale_order_id . '">' . $reference . '</a>';    
        })
        
        ->addColumn('pay_type', function ($salesPayment) {
            return isset($salesPayment->paymentMethod) ? $salesPayment->paymentMethod->name : '';
        })

        ->addColumn('payment_date', function ($salesPayment) {
            return formatDate($salesPayment->transaction_date);
        })

        ->addColumn('amount', function ($salesPayment) {
            return formatCurrencyAmount($salesPayment->amount) ;
        })

        ->addColumn('currency', function ($salesPayment) {
            return isset($salesPayment->currency->name) ? $salesPayment->currency->name : '';
        })
      
        ->rawColumns(['invoice_reference','status', 'id', 'pay_type', 'amount', 'payment_date', 'currency'])

        ->make(true);
    }

    public function query()
    {
        $from     = isset($_GET['from']) ? $_GET['from'] :  null;
        $to       = isset($_GET['to']) ? $_GET['to'] :  null;
        $method   = isset($_GET['method']) ? $_GET['method'] :  null;
        $status   = isset($_GET['status']) ? $_GET['status'] :  null;

        $salesPayment = CustomerTransaction::with(['currency','saleOrder', 'paymentMethod'])->where(['customer_transactions.customer_id' => Auth::guard('customer')->user()->id])->select();
        
       if (! empty($from) && ! empty($to)) {
            $salesPayment->where('customer_transactions.transaction_date', '>=', DbDateFormat($from));
            $salesPayment->where('customer_transactions.transaction_date', '<=', DbDateFormat($to));
        }
        if (!empty($method)) {
            $salesPayment->where('customer_transactions.payment_method_id', '=', $method);
        }
        if (!empty($status)) {
            $salesPayment->where('customer_transactions.status', '=', $status);
        }
        
        return $this->applyScopes($salesPayment);
        
    }
    
    public function html()
    {
        return $this->builder()
        
        ->addColumn(['data' => 'id', 'name' => 'customer_transactions.id', 'title' => 'No'])

        ->addColumn(['data' => 'invoice_reference', 'name' => 'customer_transactions.sale_order_id', 'title' => __('Invoice No')])

        ->addColumn(['data' => 'pay_type', 'name' => 'customer_transactions.payment_method_id', 'title' => __('Payment')])

        ->addColumn(['data' => 'amount', 'name' => 'customer_transactions.amount', 'title' => __('Amount')])

        ->addColumn(['data' => 'currency', 'name' => 'customer_transactions.currency_id', 'title' => __('Currency')])

        ->addColumn(['data' => 'status', 'name' => 'customer_transactions.status', 'title' => __('Status')])

        ->addColumn(['data' => 'payment_date', 'name' => 'payment_date', 'title' => __('Payment Date')])

        ->parameters([
            'pageLength' => $this->row_per_page,
            'language' => [
                    'url' => url('/resources/lang/'.config('app.locale').'.json'),
                ],
            'order' => [0, 'desc']
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
        return 'customers_payment' . time();
    }
}
