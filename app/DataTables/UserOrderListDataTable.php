<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;
use DB;
use Auth;
use Helpers;
use Session;
use App\Models\Orders;
use App\Models\PurchaseOrder;
class UserOrderListDataTable extends DataTable
{
    public function ajax()
    {
        $userOrder = $this->query();
        return datatables()
        ->of($userOrder)
        ->addColumn('action', function ($userOrder) {


            $edit = Helpers::has_permission(Auth::user()->id, 'edit_purchase') ? '<a href="'.url('purchase/edit/'.$userOrder->id).'" class="btn btn-xs btn-primary"><i class="feather icon-edit"></i></a>&nbsp;':'';
            
            if(Helpers::has_permission(Auth::user()->id, 'delete_purchase')){
                $delete= '<form method="post" action="'.url('purchase/delete/'.$userOrder->id).'" id="delete-purchaseOrder-'.$userOrder->id.'" accept-charset="UTF-8" class="display_inline"> 
                    '.csrf_field().'
                    <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-id='.$userOrder->id.' data-label="Delete" data-toggle="modal" data-target="#confirmDelete" data-title="' . __('Delete Purchase Order') . '" data-message="' . __('Are you sure to delete this purchase order?') . '">
                    <i class="feather icon-trash-2"></i> 
                    </button>
                    </form>';
            }
            return $edit.$delete;
        })
        ->addColumn('reference', function ($userOrder) {
            return '<a href="'.url('purchase/view-purchase-details/'.$userOrder->id).'">'.$userOrder->reference.'</a>';
        })

        ->addColumn('supp_name', function ($userOrder) {
            return '<a href="'.url('edit-supplier/'.$userOrder->supplier_id).'">'.$userOrder->name.'</a>';
        })

         ->addColumn('total', function ($userOrder) {
            return formatCurrencyAmount($userOrder->total) ;
        })

         ->addColumn('ord_date', function ($userOrder) {
            return formatDate($userOrder->order_date);
        })

        ->addColumn('status', function ($userOrder) {
            if ($userOrder->paid <= 0 && $userOrder->total != 0) {
                return '<span class="badge f-12 customer-invoice color-f44236">' . __('Unpaid') . '</span>';
            } else if ($userOrder->paid > 0 && $userOrder->total > $userOrder->paid) {
                return '<span class="badge f-12 customer-invoice color-04a9f5">' . __('Partially paid') . '</span>';
            } else if ($userOrder->total <= $userOrder->paid) {
                return '<span class="badge f-12 customer-invoice color-04a9f5">' . __('Paid') . '</span>';
          }
        })

        ->addColumn('paid', function ($userOrder) {
            return formatCurrencyAmount($userOrder->paid) ;
        })

         ->rawcolumns(['reference','supp_name','action','total','ord_date', 'status', 'paid'])

        ->make(true);
    }

    public function query()
    {
        $id = $this->user_id;

        $from = isset($_GET['from']) ? $_GET['from'] : null ;
        $to = isset($_GET['to']) ? $_GET['to'] : null ;
        $supplier = isset($_GET['supplier']) ? $_GET['supplier'] : null ;
        $location = isset($_GET['location']) ? $_GET['location'] : null ;
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $userOrder= (new PurchaseOrder)->getAllPurchOrderByUserId($from, $to, $supplier, $location, $id, $status)->get();

        return $this->applyScopes($userOrder);
    }
    
    public function html()
    {
        return $this->builder()

        ->addColumn(['data' => 'reference', 'name' => 'reference', 'title' => __('Purchase no')])

        ->addColumn(['data' => 'supp_name', 'name' => 'supp_name', 'title' => __('Supplier name')])

        ->addColumn(['data' => 'total', 'name' => 'total', 'title' => __('Total')])

        ->addColumn(['data'=> 'paid', 'name' => 'paid', 'title' => __('Paid amount')])

        ->addColumn(['data' => 'currency_name', 'name' => 'currency_name', 'title' => __('Currency')])

        ->addColumn(['data' => 'status', 'name' => 'paid', 'title' => __('Paid status'), 'orderable' => false, 'searchable' => false])

        ->addColumn(['data' => 'ord_date', 'name' => 'ord_date', 'title' => __('Purchase date')])

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
