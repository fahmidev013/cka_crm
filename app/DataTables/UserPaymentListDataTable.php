<?php
namespace App\DataTables;
use App\Models\CustomerTransaction;
use App\Models\Payment;
use Auth;
use DB;
use Helpers;
use Session;
use Yajra\DataTables\Services\DataTable;

class UserPaymentListDataTable extends DataTable
{
    public function ajax()
    {
        $salesPayment = $this->query();
        return datatables()
        ->of($salesPayment)
        ->addColumn('action', function ($salesPayment) {
            if(Helpers::has_permission(Auth::user()->id, 'edit_payment')){

                $edit='<a title="Edit" class="btn btn-xs btn-info" href="'.url('payment/edit/'.base64_encode($salesPayment->id)).'"><span class="feather icon-edit"></span></a>&nbsp;';
            }else{
                $edit ='';
            }

            if(Helpers::has_permission(Auth::user()->id, 'delete_payment')){
                $delete ='  <form method="POST" action="'.url("payment/delete").'" accept-charset="UTF-8" id="delete-invoicePayments-'.$salesPayment->id.'" class="display_inline">
                '.csrf_field().'
                <input type="hidden" name="id" value="'.$salesPayment->id.'">
                <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id='.$salesPayment->id.' data-label="Delete" data-target="#confirmDelete" data-title="' . __('Delete payment') . '" data-message="' . __('Are you sure to delete this payment?') . '">
                <i class="feather icon-trash-2"></i> 
                </button>
                </form>';
            }else{
                $delete='';
            }
            return $edit.$delete;
        })        

        ->addColumn('name', function ($salesPayment) {
            if(Helpers::has_permission(Auth::user()->id, 'edit_customer')){  
               return '<a href="'.url('customer/edit/'.$salesPayment->customer_id).'">'.$salesPayment->customer->name.'</a>';
           }else{
              return $salesPayment->customer->name;
           }
       })

        ->addColumn('id', function ($salesPayment) {
            return '<a href="'.url('payment/view-receipt/'.$salesPayment->id).'">'.sprintf("%04d", $salesPayment->id).'</a>';
        })
        ->addColumn('invoice_reference', function ($salesPayment) {
            return '<a href="'.url('invoice/view-detail-invoice/' . $salesPayment->sale_order_id).'">'.$salesPayment->saleOrder->reference.'</a>';            
        })
        
        ->addColumn('pay_type', function ($salesPayment) {
            return !empty($salesPayment->paymentMethod) ? $salesPayment->paymentMethod->name : '-';
        })
     

       ->addColumn('currency', function ($salesPayment) {
            return $salesPayment->currency->name;
        })


        ->addColumn('payment_date', function ($salesPayment) {
            return formatDate($salesPayment->transaction_date);
        })     

        ->addColumn('amount', function ($salesPayment) {
            return !empty($salesPayment->saleOrder) ? formatCurrencyAmount($salesPayment->saleOrder->paid) : '';
        })
      
        ->rawcolumns(['id','action','invoice_reference','name','pay_type','currency','payment_date','amount'])

        ->make(true);
    }

    public function query()
    {
        $id       = $this->user_id;
        $customer = isset($_GET['customer']) ? $_GET['customer'] : null ;
        $from     = isset($_GET['from']) ? $_GET['from'] : null ;
        $to       = isset($_GET['to']) ? $_GET['to'] : null ;
        $method   = isset($_GET['method']) ? $_GET['method'] : null;
        $currency = isset($_GET['currency']) ? $_GET['currency'] : null;
        $salesPaymentData = CustomerTransaction::with('paymentMethod','currency','customer','saleOrder')->where('user_id',$id)
                      ->select('customer_transactions.*');
                      if (!empty($from)) {
                        $salesPaymentData->where('transaction_date', '>=', DbDateFormat($from));  
                      }
                      if (!empty($to)) {
                         $salesPaymentData->where('transaction_date', '<=', DbDateFormat($to));
                      }
                      if (!empty($customer) && $customer !='all') {
                          $salesPaymentData->where('customer_id', '=', $customer);
                      }
                      if (!empty($method) && $method != 'all') {
                          $salesPaymentData->where('payment_method_id', '=', $method);
                      }
                      if(!empty($currency) && $currency != 'all') {
                        $salesPaymentData->where('customer_transactions.currency_id', '=', $currency);
                      } 
        $salesPayment = $salesPaymentData->get();      
        
        return $this->applyScopes($salesPayment);
    }
    
    public function html()
    {
        return $this->builder()

        ->addColumn(['data' => 'id', 'name' => 'id', 'title' => __('Payment no')])

        ->addColumn(['data' => 'invoice_reference', 'name' => 'invoice_reference', 'title' => __('Invoice no')])

        ->addColumn(['data' => 'name', 'name' => 'name', 'title' => __('Customer name')])

        ->addColumn(['data' => 'pay_type', 'name' => 'pay_type', 'title' => __('Payment method')])

        ->addColumn(['data' => 'amount', 'name' => 'amount', 'title' => __('Amount')])

        ->addColumn(['data' => 'currency', 'name' => 'currency', 'title' => __('Currency')])

        ->addColumn(['data' => 'payment_date', 'name' => 'payment_date', 'title' => __('Payment date')])

        ->addColumn(['data'=> 'action', 'name' => 'action', 'title' => __('Action'), 'orderable' => false, 'searchable' => false])

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
