<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;
use DB;
use Auth;
use Helpers;
use Session;
use App\Models\ReceivedOrder;
class PurchasesReceiveDataTable extends DataTable
{
    public function ajax()
    {
        $purchasesReceive = $this->query();
        return datatables()
        ->of($purchasesReceive)
        ->addColumn('action', function ($purchasesReceive) {

            $edit = Helpers::has_permission(Auth::user()->id, 'edit_purchase_receive') ? '<a href="javascript:void(0)" class="btn btn-xs btn-primary edit-btn" data-id="'.$purchasesReceive->ro_id.'" data-date="'.formatDate($purchasesReceive->receive_date).'" data-toggle="modal" data-target="#editModal" ><i class="feather icon-edit"></i></a>&nbsp;':'';
            
            if(Helpers::has_permission(Auth::user()->id, 'delete_purchase_receive')){
                $delete= '<form method="post" action="'.url('purchase/receive/delete/'.$purchasesReceive->ro_id).'" accept-charset="UTF-8" class="display_inline" id="delete-item-'.$purchasesReceive->ro_id.'"> 
                    '.csrf_field().'
                    <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id="'.$purchasesReceive->ro_id.'" data-target="#theModal" data-label="Delete" data-title="'. __('Delete purchase receive').'" data-message="'. __('Are you sure to delete this purchase receive?').'">
                    <i class="feather icon-trash-2"></i> 
                    </button>
                    </form>';
            }else{
                $delete='';
            }
            return $edit.$delete;
        })
        ->addColumn('id', function ($purchasesReceive) {
             return '<a href="'.url('purchase_receive/details/'.$purchasesReceive->ro_id).'">'.$purchasesReceive->ro_id.'</a>';
        })

        ->addColumn('reference', function ($purchasesReceive) {
             return '<a href="'.url('purchase/view-purchase-details/'.$purchasesReceive->purchase_order_id).'">'.$purchasesReceive->reference.'</a>';
        })

        ->addColumn('name', function ($purchasesReceive) {
            if(Helpers::has_permission(Auth::user()->id, 'edit_supplier')){  
             return '<a href="'.url('edit-supplier/'.$purchasesReceive->supplier_id).'">'. $purchasesReceive->supplier->name .'</a>';
            }
        })

        ->addColumn('total_receive', function ($purchasesReceive) {
            $qty = $purchasesReceive->receivedOrderDetails->sum('quantity');
            return formatCurrencyAmount($qty);
        })

        ->addColumn('order_receive_no', function ($purchasesReceive) {
            return $purchasesReceive->order_receive_no ? $purchasesReceive->order_receive_no : '-';
        })

        ->addColumn('receive_date', function ($purchasesReceive) {
            return formatDate($purchasesReceive->receive_date);
        })

        ->rawcolumns(['id','reference','name','action', 'order_receive_no'])

        ->make(true);
    }

    public function query()
    {
        $from     = isset($_GET['from']) ? $_GET['from'] : null;
        $to       = isset($_GET['to']) ? $_GET['to'] : null;
        $supplier = isset($_GET['supplier']) ? $_GET['supplier'] : null;
        $purchasesReceive = (new ReceivedOrder)->getAllPurchaseReceiveOrder($from, $to, $supplier);
        return $this->applyScopes($purchasesReceive);
    }
    
    public function html()
    {
        return $this->builder()

        ->addColumn(['data' => 'id', 'name' => 'received_orders.id as ro_id', 'title' => __('PR no').'#'])

        ->addColumn(['data' => 'reference', 'name' => 'received_orders.reference', 'title' => __('Purchase no')])

        ->addColumn(['data' => 'name', 'name' => 'supplier.name', 'title' => __('Supplier name')])

        ->addColumn(['data' => 'order_receive_no', 'name' => 'received_orders.order_receive_no', 'title' =>  __('Receipt no')])

        ->addColumn(['data'=> 'total_receive', 'name' => DB::raw('receivedOrderDetails.sum("quantity")'), 'title' => __('Quantity')])

        ->addColumn(['data' => 'receive_date', 'name' => 'received_orders.receive_date', 'title' => __('Date'), 'orderable' => false])

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
