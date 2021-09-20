<?php
namespace App\DataTables;
use App\Models\CustomerTransaction;
use App\Models\Payment;
use Auth;
use DB;
use Helpers;
use Session;
use Yajra\DataTables\Services\DataTable;
class CustomerPaymentListDataTable extends DataTable
{
    public function ajax()
    {
        $customerPayment = $this->query();
        return datatables()
        ->of($customerPayment)
        ->addColumn('action', function ($customerPayment) {
           if(Helpers::has_permission(Auth::user()->id, 'edit_payment')){

                $edit='<a title="Edit" class="btn btn-xs btn-info" href="'.url('payment/edit/'.base64_encode($customerPayment->ct_id)).'"><span class="feather icon-edit"></span></a>&nbsp;';
            }else {
                $edit ='';
            }
            if(Helpers::has_permission(Auth::user()->id, 'delete_payment')){
                $delete = '<form method="post" action="'. url('payment/delete').'" id="delete-payment-'. $customerPayment->ct_id .'" accept-charset="UTF-8" class="display_inline_block"> '.csrf_field().'
                    <input type="hidden" name="sub_menu" value="customer">
                    <input type="hidden" name="id" value="'. $customerPayment->ct_id .'">
                    <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id='. $customerPayment->ct_id .' data-label="Delete" data-target="#confirmDelete" data-title="' . __('Payment Delete') . '" data-message="' . __('Are you sure to delete this payment?') . '">
                    <i class="feather icon-trash-2"></i>
                    </button>
                    </form>';
            } else {
                $delete='';
            }
            return $edit.$delete;
        })
        ->addColumn('no', function ($customerPayment) {
            return '<a href="'.url('payment/view-receipt/'.$customerPayment->ct_id).'">'.sprintf("%04d", $customerPayment->ct_id).'</a>';
        })
        ->addColumn('invoice_reference', function ($customerPayment) {   
            return '<a href="'. url('invoice/view-detail-invoice/' . $customerPayment->sale_order_id) .'">'. $customerPayment->saleOrder->reference .'</a>';
        })
         ->addColumn('payment_date', function ($customerPayment) {
            return formatDate($customerPayment->transaction_date);
        })

        ->addColumn('currency_name', function ($customerPayment) {
            return $customerPayment->currency->name;
        })
        ->addColumn('amount', function ($customerPayment) {
            return formatCurrencyAmount($customerPayment->amount);
        })

        ->addColumn('pay_type', function ($customerPayment) {
            return !empty($customerPayment->paymentMethod) ? $customerPayment->paymentMethod->name : '-';
        })

        ->addColumn('no', function ($customerPayment) {
            return $customerPayment->ct_id;
        })

        ->rawColumns(['action','invoice_reference','no', 'payment_date', 'currency_name', 'pay_type', 'no'])

        ->make(true);
    }

    public function query()
    {
        $id       = $this->customer_id; 
        $to       = isset($_GET['to']) ? $_GET['to'] : null ;
        $from     = isset($_GET['from']) ? $_GET['from'] : null ;
        $method   = isset($_GET['method']) ? $_GET['method'] : null ;
        $currency = isset($_GET['currency']) ? $_GET['currency'] : null ;
        $customerPayment = CustomerTransaction::select('customer_transactions.id as ct_id', 'customer_transactions.transaction_date', 'customer_transactions.amount', 'customer_transactions.sale_order_id', 'customer_transactions.currency_id', 'customer_transactions.payment_method_id')->with(['currency','saleOrder', 'paymentMethod'])
                         ->where(['customer_transactions.customer_id' => $id, 'customer_transactions.status' => 'Approved']);
        if(!empty($from) && !empty($to)) {
           $customerPayment->whereDate('transaction_date','>=', DbDateFormat($from));
           $customerPayment->whereDate('transaction_date','<=', DbDateFormat($to));
        }  
        if (isset($method) && !empty($method)) {
            $customerPayment->where('customer_transactions.payment_method_id', '=', $method);
        } 
        if(!empty($currency) && $currency != 'all') {
            $customerPayment->where('customer_transactions.currency_id', '=', $currency);
        }        
        if (Helpers::has_permission(Auth::user()->id, 'own_payment')) {
            $id = Auth::user()->id;
            $customerPayment->where('user_id', $id);
        } 
        return $this->applyScopes($customerPayment);
    }
    
    public function html()
    {
        return $this->builder()

        ->addColumn(['data' => 'no', 'name' => 'customer_transactions.id as ct_id', 'title' => 'No', 'orderable' => true])

        ->addColumn(['data' => 'invoice_reference', 'name' => 'saleOrder.reference', 'title' => __('Invoice No'), 'orderable' => true])

        ->addColumn(['data' => 'pay_type', 'name' => 'pay_type', 'title' => __('Payment Method')])

        ->addColumn(['data' => 'amount', 'name' => 'customer_transactions.amount', 'title' => __('Amount') ])

         ->addColumn(['data' => 'currency_name', 'name' => 'currency.name', 'title' => __('Currency'), 'orderable' => false ])

        ->addColumn(['data' => 'payment_date', 'name' => 'transaction_date', 'title' => __('Payment Date')])

        ->addColumn(['data'=> 'action', 'name' => 'action', 'title' => __('Action'), 'orderable' => false, 'searchable' => false])

        ->parameters([
            'pageLength' => $this->row_per_page,
            'language' => [
                    'url' => url('/resources/lang/'.config('app.locale').'.json'),
                ],
            'order' => [1, 'desc']
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
