<?php
namespace App\DataTables;
use App\Models\Payment;
use App\Models\SupplierTransaction;
use Auth;
use DB;
use Helpers;
use Session;
use Yajra\DataTables\Services\DataTable;

class PurchasesPaymentListDataTable extends DataTable
{
    public function ajax()
    {
        $purchasesPayment = $this->query();
        return datatables()
        ->of($purchasesPayment)
        ->addColumn('action', function ($purchasesPayment) {
            if(Helpers::has_permission(Auth::user()->id, 'edit_purch_payment')) {

                $edit = '<a title="Edit" class="btn btn-xs btn-info" href="'. url('purchase_payment/edit/' . base64_encode($purchasesPayment->st_id)).'"><span class="feather icon-edit"></span></a>&nbsp;';
            } else {
                $edit = '';
            }
            if(Helpers::has_permission(Auth::user()->id, 'delete_purch_payment')){
                $delete = '  <form method="POST" action="'. url("purchase_payment/delete") .'" accept-charset="UTF-8" class="display_inline" id="delete-item-'.$purchasesPayment->st_id.'">
                '. csrf_field() .'
                <input type="hidden" name="id" value="'. $purchasesPayment->st_id. '">
                <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id="'.$purchasesPayment->st_id.'" data-target="#theModal" data-label="Delete" data-title="' . __('Delete payment') . '" data-message="' . __('Are you sure to delete this payment?') . '">
                <i class="feather icon-trash-2"></i> 
                </button>
                </form>';
            } else {
                $delete = '';
            }
            return $edit . $delete;
        })
        ->addColumn('id', function ($purchasesPayment) {
            return '<a href="'.url('purchase_payment/view_receipt/'.$purchasesPayment->st_id).'">'. $purchasesPayment->st_id.'</a>';
        })
        ->addColumn('purch_order_reference', function ($purchasesPayment) {
            if (isset($purchasesPayment->purchaseOrder->id)) {
                return '<a href="'. url('purchase/view-purchase-details/' . $purchasesPayment->purchaseOrder->id) .'">'. $purchasesPayment->purchaseOrder->reference.'</a>';
            }
            return "-";
        })

        ->addColumn('supp_name', function ($purchasesPayment) {
            if(Helpers::has_permission(Auth::user()->id, 'edit_supplier') && isset($purchasesPayment->supplier->name)) {  
               return '<a href="' . url('edit-supplier/'. $purchasesPayment->supplier->id) .'">'. $purchasesPayment->supplier->name.'</a>';
           } else {
              return isset($purchasesPayment->supplier->name) ? $purchasesPayment->supplier->name : '';
           }
       })

       ->addColumn('pay_type', function ($purchasesPayment) {
            return isset($purchasesPayment->paymentMethod) ? $purchasesPayment->paymentMethod->name : 'N/A';
        }) 

       ->addColumn('currency', function ($purchasesPayment) {
            return $purchasesPayment->currency->name;
        })
        
        ->addColumn('payment_date', function ($purchasesPayment) {
            return formatDate($purchasesPayment->transaction_date);
        })

        ->addColumn('amount', function ($purchasesPayment) {
            return formatCurrencyAmount($purchasesPayment->amount);
        })
        
        ->rawcolumns(['id','purch_order_reference','supp_name','pay_type','currency','payment_date','action'])

        ->make(true);
    }

    public function query()
    { 
        $supplier = isset($_GET['supplier']) ? $_GET['supplier'] : null;
        $method   = isset($_GET['method']) ? $_GET['method'] : null;
        $currency = isset($_GET['currency']) ? $_GET['currency'] : null;
        $from     = isset($_GET['from']) ? $_GET['from'] : null;
        $to       = isset($_GET['to']) ? $_GET['to'] : null;

        $purchasesPayment = (new SupplierTransaction)->getAll($supplier, $method, $currency, $from, $to)->orderBy('transaction_date', 'desc');
        return $this->applyScopes($purchasesPayment);
    }
    
    public function html()
    {
        return $this->builder()

        ->addColumn(['data' => 'id', 'name' => 'supplier_transactions.id as st_id', 'title' => __('Payment no')])

        ->addColumn(['data' => 'purch_order_reference', 'name' => 'purchaseOrder.reference', 'title' => __('Purchase no')])

        ->addColumn(['data' => 'supp_name', 'name' => 'supplier.name', 'title' => __('Supplier name')])

        ->addColumn(['data' => 'pay_type', 'name' => 'paymentMethod.name', 'title' => __('Payment method')])

        ->addColumn(['data' => 'amount', 'name' => 'supplier_transactions.amount', 'title' => __('Amount')])

        ->addColumn(['data' => 'currency', 'name' => 'currency.name', 'title' => __('Currency')])

        ->addColumn(['data' => 'payment_date', 'name' => 'supplier_transactions.transaction_date', 'title' => __('Date'), 'orderable' => false])

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
