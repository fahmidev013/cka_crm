<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;
use DB;
use Auth;
use Helpers;
use Session;
use App\Models\PurchaseOrder;
class SupplierOrderListDataTable extends DataTable
{
    public function ajax()
    {
        $supplierOrder = $this->query();
        return datatables()
        ->of($supplierOrder)
        ->addColumn('action', function ($supplierOrder) {

            $edit = Helpers::has_permission(Auth::user()->id, 'edit_purchase') ? '<a href="'. url('purchase/edit/'. $supplierOrder->id) .'" class="btn btn-xs btn-primary"><i class="feather icon-edit"></i></a>&nbsp;':'';
            
            if(Helpers::has_permission(Auth::user()->id, 'delete_purchase')){
                $delete= '<form method="post" action="'. url('purchase/delete/'. $supplierOrder->id) .'" id="delete-purchase-'. $supplierOrder->id .'" accept-charset="UTF-8" class="display_inline_block"> 
                '. csrf_field() .'
                <button title="'. __('Delete') .'" class="btn btn-xs btn-danger" type="button" data-id='. $supplierOrder->id .' data-label="Delete" data-toggle="modal" data-target="#confirmDelete" data-title="'. __('Delete purchase order') . '" data-message="'. __('Are you sure to delete this purchase order?') .'">
                <i class="feather icon-trash-2"></i> 
                </button>
                </form>';
            }
            return $edit.$delete;
        })
        ->addColumn('reference', function ($supplierOrder) {
            return '<a href="'. url('purchase/view-purchase-details/'. $supplierOrder->id ) .'">'. $supplierOrder->reference .'</a>';
        })

         ->addColumn('total', function ($supplierOrder) {
            return formatCurrencyAmount($supplierOrder->total);
        })

         ->addColumn('ord_date', function ($supplierOrder) {
            return formatDate($supplierOrder->order_date);
        })
        ->rawColumns(['action', 'reference', 'total', 'ord_date'])
        ->make(true);
    }

    public function query()
    {
        $id = $this->customer_id;
 
        $from = isset($_GET['from']) ? $_GET['from'] : null;
        $to   = isset($_GET['to']) ? $_GET['to'] : null;

        $supplierOrder = (new PurchaseOrder)->getAllById($id, $from, $to)->get();

        return $this->applyScopes($supplierOrder);
    }
    
    public function html()
    {
        return $this->builder()

        ->addColumn(['data' => 'id', 'name' => 'id', 'visible' => false])

        ->addColumn(['data' => 'reference', 'name' => 'reference', 'title' => __('Purchase no')])

        ->addColumn(['data' => 'total', 'name' => 'total', 'title' => __('Total')])

        ->addColumn(['data' => 'currency_name', 'name' => 'currency_name', 'title' => __('Currency')])

        ->addColumn(['data' => 'ord_date', 'name' => 'order_date', 'title' => __('Purchase date')])

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
