<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;
use DB;
use Auth;
use Helpers;
use Session;
use App\Models\PurchaseOrder;
class PurchaseListDataTable extends DataTable
{
    public function ajax()
    {
        $purchaseList = $this->query();
        return datatables()
        ->of($purchaseList)
        ->addColumn('action', function ($purchaseList) {
            $edit = Helpers::has_permission(Auth::user()->id, 'edit_purchase') ? '<a href="' . url('purchase/edit/' . $purchaseList->po_id) . '" class="btn btn-xs btn-primary"><i class="feather icon-edit"></i></a>&nbsp;':'';
            
            if(Helpers::has_permission(Auth::user()->id, 'delete_purchase')){
                $delete= '<form method="post" action="' . url('purchase/delete/' . $purchaseList->po_id) . '" accept-charset="UTF-8" class="display_inline" id="delete-item-' . $purchaseList->po_id . '"> 
                    ' . csrf_field() . '
                    <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id="' . $purchaseList->po_id . '" data-target="#theModal" data-label="Delete" data-title="' . __('Delete purchase') . '" data-message="' . __('Are you sure to delete this purchase?') . '">
                    <i class="feather icon-trash-2"></i> 
                    </button>
                    </form>';
            }else{
                $delete='';
            }
            return $edit.$delete;
        })
        ->addColumn('status', function ($purchaseList) {
            if ($purchaseList->paid <= 0 && $purchaseList->total != 0) {
                return '<span class="badge text-white f-12 customer-invoice color-f44236">' . __('Unpaid') . '</span>';
            } else if ($purchaseList->paid > 0 && $purchaseList->total > $purchaseList->paid) {
                return '<span class="badge text-white f-12 customer-invoice color-04a9f5">' . __('Partially paid') . '</span>';
            } else if ($purchaseList->paid >= $purchaseList->total) {
                return '<span class="badge text-white f-12 customer-invoice color-4CAF50">' . __('Paid') . '</span>';
          }
        })

        ->addColumn('id', function ($purchaseList) {
             return '<a href="' . url('purchase/view-purchase-details/' . $purchaseList->po_id) .'">' . $purchaseList->reference . '</a>';
        })

        ->addColumn('name', function ($purchaseList) {
            if(Helpers::has_permission(Auth::user()->id, 'edit_supplier')) {  
             return '<a href="' . url('edit-supplier/' . $purchaseList->supplier->id) . '">' . isset($purchaseList->supplier->name) ? $purchaseList->supplier->name : '' . '</a>';
            }
        })

        ->addColumn('currency', function ($purchaseList) {
            return isset($purchaseList->currency->name) ? $purchaseList->currency->name : '' ;
        })

        ->addColumn('total', function ($purchaseList) {
            return formatCurrencyAmount($purchaseList->total) ;
        })

        ->addColumn('paid', function ($purchaseList) {
            return formatCurrencyAmount($purchaseList->paid) ;
        })

        ->addColumn('order_date', function ($purchaseList) {
            return formatDate($purchaseList->order_date);
        })

        ->rawcolumns(['id','name','action','status','total','paid','order_date'])

        ->make(true);
    }

    public function query()
    {
        $from =  isset($_GET['from']) ? $_GET['from'] : '';
        $to =  isset($_GET['to']) ? $_GET['to'] : '';
        $supplier_id = isset($_GET['supplier']) ? $_GET['supplier'] : '';
        $currency = isset($_GET['currency']) ? $_GET['currency'] : '';
        $location = isset($_GET['location']) ? $_GET['location'] : '';
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        $purchaseList = (new PurchaseOrder)->getAll($supplier_id, $currency, $location, $status, $from, $to);
        return $this->applyScopes($purchaseList);
    }
    
    public function html()
    {
        return $this->builder()

        ->addColumn(['data' => 'id', 'name' => 'reference', 'title' => __('Purchase no')])

        ->addColumn(['data' => 'name', 'name' => 'supplier.name', 'title' => __('Supplier name')])

        ->addColumn(['data' => 'total', 'name' => 'purchase_orders.total', 'title' => __('Total')])

        ->addColumn(['data'=> 'paid', 'name' => 'purchase_orders.paid', 'title' => __('Paid amount')])

        ->addColumn(['data'=> 'currency', 'name' => 'currency.name', 'title' => __('Currency')])
        
        ->addColumn(['data' => 'order_date', 'name' => 'purchase_orders.order_date', 'title' => __('Date'), 'searchable' => false])
        
        ->addColumn(['data' => 'status', 'name' => 'paid', 'title' => __('Status'), 'orderable' => false, 'searchable' => false])

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
