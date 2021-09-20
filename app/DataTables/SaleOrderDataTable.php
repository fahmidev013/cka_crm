<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;

use DB;
use Auth;
use Helpers;
use Session;
use App\Models\SaleOrder;
class SaleOrderDataTable extends DataTable
{
    public function ajax()
    {
        $salesOrder = $this->query();
        return datatables()
            ->of($salesOrder)
            ->addColumn('action', function ($salesOrder) {
                $edit = Helpers::has_permission(Auth::user()->id, 'edit_quotation') ? '<a title="' . __("Edit") . '" href="'. url('order/edit/'. $salesOrder->so_id) .'" class="btn btn-xs btn-primary"><i class="feather icon-edit"></i></a>&nbsp;' : '';
                $delete = '';
                if (Helpers::has_permission(Auth::user()->id, 'delete_quotation')) {
                    $delete = '<form method="post" action="'. url('order/delete/'. $salesOrder->so_id) .'" accept-charset="UTF-8" class="display_inline"   id="delete-item-'. $salesOrder->so_id .'"> 
                    '. csrf_field() .'
                    <button title="' . __("Delete") . '" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id="'. $salesOrder->so_id .'" data-target="#theModal" data-label = "Delete" data-title="'. __('Delete order') .'" data-message="'. __('Are you sure to delete this order?') .'">
                    <i class="feather icon-trash-2"></i> 
                    </button>
                    </form>';
                }
                return $edit . $delete;
            })

            ->addColumn('id', function ($salesOrder) {
                if (!empty($salesOrder->parent)) {
                    return '<a href="'. url('order/view-order-details/'. $salesOrder->so_id) .'"><span class="text-success">'. $salesOrder->reference .'</span></a>';
                } else {
                    return '<a href="'. url('order/view-order-details/'. $salesOrder->so_id) .'">'. $salesOrder->reference .'</a>';
                }
            })

            ->addColumn('customer_id', function ($salesOrder) {
                $name = isset($salesOrder->customer->name) ? $salesOrder->customer->name : '';
                return $salesOrder->customer_id == 1 ? $name : '<a href="'. url('customer/edit/'. $salesOrder->customer_id) .'">'. $name .'</a>';  
            })

            ->addColumn('total', function ($salesOrder) {
                return formatCurrencyAmount($salesOrder->total);
            })

            ->addColumn('qty', function ($salesOrder) {
                return !empty($salesOrder->saleOrderDetails) ? formatCurrencyAmount($salesOrder->saleOrderDetails->sum('quantity')) : '' ;
            })

            ->addColumn('order_date', function ($salesOrder) {
                return formatDate($salesOrder->order_date);
            })

            ->addColumn('location', function ($salesOrder) {
                return isset($salesOrder->location->name) ? $salesOrder->location->name : '';
            })

            ->addColumn('currency_name', function ($salesOrder) {
                return isset($salesOrder->currency->name) ? $salesOrder->currency->name : '';
            })
            
            ->rawColumns(['action', 'id', 'customer_id', 'total', 'order_date', 'qty', 'location'])
            ->make(true);
    }
 
    public function query()
    {
        $from     = isset($_GET['from']) ? $_GET['from'] : null;
        $to       = isset($_GET['to']) ? $_GET['to'] : null;
        $location = isset($_GET['location']) ? $_GET['location'] : null;
        $customer = isset($_GET['customer']) ? $_GET['customer'] : null;
        $currency = isset($_GET['currency']) ? $_GET['currency'] : null;
        $salesOrder = (new SaleOrder)->getAllQuotation($from, $to, $location, $customer, $currency);
        return $this->applyScopes($salesOrder);
    }
    
    public function html()
    {
        return $this->builder()
            ->addColumn(['data' => 'id', 'name' => 'sale_orders.reference', 'title' => __('Quotation no')])
            ->addColumn(['data' => 'customer_id', 'name' => 'customer.first_name', 'title' => __('Customer name')])
            ->addColumn(['data' => 'location', 'name' => 'location.name', 'title' => __('Location')])
            ->addColumn(['data' => 'qty', 'title' => __('Quantity'), 'orderable' => false, 'searchable' => false])
            ->addColumn(['data' => 'total', 'name' => 'sale_orders.total', 'title' => __('Total')])
            ->addColumn(['data' => 'currency_name', 'name' => 'currency.name', 'title' => __('Currency')])
            ->addColumn(['data' => 'order_date', 'name' => 'sale_orders.order_date', 'title' => __('Date')])
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
