<?php
namespace App\DataTables;
use App\Models\Payment;
use App\Models\SupplierTransaction;
use Auth;
use DB;
use Helpers;
use Session;
use Yajra\DataTables\Services\DataTable;
class SupplierPaymentDataTable extends DataTable
{
    public function ajax()
    {
        $purchasesPayment = $this->query();
        return datatables()
        ->of($purchasesPayment)
        ->addColumn('action', function ($purchasesPayment) {
            if(Helpers::has_permission(Auth::user()->id, 'edit_purch_payment')){

                $edit='<a title="Edit" class="btn btn-xs btn-info" href="'.url('purchase_payment/edit/'.base64_encode($purchasesPayment->id)).'"><span class="feather icon-edit"></span></a>&nbsp;';
            }else{
                $edit ='';
            }
            if(Helpers::has_permission(Auth::user()->id, 'delete_purch_payment')){
                $delete ='  <form method="POST" action="'.url("purchase_payment/delete").'" id="delete-purchaseOrderPayment-'.$purchasesPayment->id.'" accept-charset="UTF-8" class="display_inline_block">
                '.csrf_field().'
                <input type="hidden" name="id" value="'.$purchasesPayment->id.'">
                <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id='.$purchasesPayment->id.' data-label="Delete" data-target="#confirmDelete" data-title="'. __('Delete payment') .'" data-message="'. __('Are you sure to delete this payment?') .'">
                <i class="feather icon-trash-2"></i> 
                </button>
                </form>';
            }else{
                $delete='';
            }
            return $edit.$delete;
        })
        ->addColumn('id', function ($purchasesPayment) {
            return '<a href="'.url('purchase_payment/view_receipt/'.$purchasesPayment->id).'">'.'00'.$purchasesPayment->id.'</a>';
        })
        ->addColumn('purch_order_reference', function ($purchasesPayment) {
            return !empty($purchasesPayment->purchaseOrder) ? '<a href="'. url('purchase/view-purchase-details/' . $purchasesPayment->purchaseOrder->id) .'">'. $purchasesPayment->purchaseOrder->reference.'</a>' : '';           
        })


       ->addColumn('pay_type', function ($purchasesPayment) {
            return isset($purchasesPayment->paymentMethod->name) && !empty($purchasesPayment->paymentMethod->name) ? $purchasesPayment->paymentMethod->name : '-';
        }) 

       ->addColumn('currency', function ($purchasesPayment) {
            return $purchasesPayment->currency['name'];
        })
        
        ->addColumn('payment_date', function ($purchasesPayment) {
            return formatDate($purchasesPayment->transaction_date);
        })



        ->addColumn('amount', function ($purchasesPayment) {
            return formatCurrencyAmount($purchasesPayment->amount);
        })
    
        ->rawcolumns(['id','purch_order_reference','action', 'pay_type', 'currency', 'payment_date', 'amount'])

        ->make(true);
    }

    public function query()
    { 
        $id = $this->supplier_id; 
        $from = isset($_GET['from']) ? $_GET['from'] : null ; 
        $to = isset($_GET['to']) ? $_GET['to'] : null ; 
        $purchasesPayment = SupplierTransaction::with('supplier','currency','paymentMethod')
                          ->where('supplier_id',$id)
                          ->select('supplier_transactions.*');
    
        if (!empty($from)) {
             $purchasesPayment->where('transaction_date', '>=', DbDateFormat($from));
        }
        if (!empty($to)) {
             $purchasesPayment->where('transaction_date', '<=', DbDateFormat($to));
        }
        $purchasesPayment = $purchasesPayment->get();

        return $this->applyScopes($purchasesPayment);
    }
    
    public function html()
    {
        return $this->builder()

        ->addColumn(['data' => 'id', 'name' => 'id', 'title' => __('Payment no')])

        ->addColumn(['data' => 'purch_order_reference', 'name' => 'purch_order_reference', 'title' => __('Purchase no')])


        ->addColumn(['data' => 'pay_type', 'title' => __('Payment method')])

        ->addColumn(['data' => 'amount', 'name' => 'amount', 'title' => __('Amount')])

        ->addColumn(['data' => 'currency', 'name' => 'currency', 'title' => __('Currency')])

        ->addColumn(['data' => 'payment_date', 'name' => 'transaction_date', 'title' => __('Payment date')])

        ->addColumn(['data'=> 'action', 'name' => 'action', 'title' => __('Action'), 'orderable' => false, 'searchable' => false])

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
        return 'customers_' . time();
    }
}
