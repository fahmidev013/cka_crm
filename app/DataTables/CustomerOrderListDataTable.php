<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;
use DB;
use Auth;
use Helpers;
use Session;
use App\Models\SaleOrder;
class CustomerOrderListDataTable extends DataTable
{
    public function ajax()
    {
        $customerOrder = $this->query();
        return datatables()
        ->of($customerOrder)
        ->addColumn('action', function ($customerOrder) {


            $edit = Helpers::has_permission(Auth::user()->id, 'edit_quotation') ? '<a href="'.url('order/edit/'.$customerOrder->so_id).'" class="btn btn-xs btn-primary"><i class="feather icon-edit"></i></a>&nbsp;':'';

            if(Helpers::has_permission(Auth::user()->id, 'delete_quotation')){
                $delete= '<form method="post" action="'. url('order/delete/'. $customerOrder->so_id).'" id="delete-quotation-'. $customerOrder->so_id .'" accept-charset="UTF-8" class="display_inline_block">
                '.csrf_field().'
                <input type="hidden" name="sub_menu" value="customer">
                <input type="hidden" name="customer" value="'.$customerOrder->customer_id.'">
                <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id='. $customerOrder->so_id .' data-label="Delete" data-target="#confirmDelete" data-title="' . __('Delete order').'" data-message="'.__('Are you sure to delete this order?').'">
                <i class="feather icon-trash-2"></i>
                </button>
                </form>';
            }
            return $edit.$delete;
        })
        ->addColumn('quotation', function ($customerOrder) {
            return '<a title="view" href="'.url('order/view-order-details/'.$customerOrder->so_id).'">'.$customerOrder->reference.'</a>';

        })
        ->addColumn('total_qty', function ($customerOrder) {
            return !empty($customerOrder->saleOrderDetails) ? $customerOrder->saleOrderDetails->sum('quantity') : '' ;

        })
        ->addColumn('total_price', function ($customerOrder) {
            return formatCurrencyAmount($customerOrder->total, $this->currency->symbol);

        })

        ->addColumn('isInvoice', function ($customerOrder) {
            $checkInvoice = (new SaleOrder)->checkConversion($customerOrder->so_id)->first();
            if (!isset($checkInvoice->reference)) {
                return '<label class="badge theme-bg2 text-white f-12 customer-order">'. __('No') . '</label>';
            } else {
                return '<label class="badge theme-bg text-white f-12 customer-order">' . __('Yes') . '</label>';
            }

        })
        ->addColumn('ord_date', function ($customerOrder) {
            return formatDate($customerOrder->order_date);

        })
        ->rawcolumns(['quotation', 'total_qty', 'total_price', 'isInvoice', 'ord_date', 'view', 'action'])
        ->make(true);
    }

    public function query()
    {

        $from    = isset($_GET['from']) && !empty($_GET['from']) ? DbDateFormat($_GET['from']) : null ;
        $to      = isset($_GET['to']) && !empty($_GET['to']) ? DbDateFormat($_GET['to']) : null ;
        if (!empty($from) && !empty($to)) {
            $customerOrder = SaleOrder::select('sale_orders.id', 'sale_orders.id as so_id', 'sale_orders.customer_id', 'sale_orders.total', 'sale_orders.order_date', 'sale_orders.reference')->with(['customer:id,name', 'currency:id,name', 'saleOrderDetails:id,sale_order_id,quantity'])
                        ->where([ 'transaction_type' => 'SALESORDER', 'customer_id' => request()->segment(3),'order_type'=>'Direct Order'])
                        ->where('order_date', '>=', $from)
                        ->where('order_date', '<=' , $to);

        } else {
            $customerOrder = SaleOrder::select('sale_orders.id', 'sale_orders.id as so_id', 'sale_orders.customer_id', 'sale_orders.total', 'sale_orders.order_date', 'sale_orders.reference')->with(['customer:id,name', 'currency:id,name', 'saleOrderDetails:id,sale_order_id,quantity'])
                        ->where([ 'transaction_type' => 'SALESORDER', 'customer_id' => request()->segment(3),'order_type'=>'Direct Order']);
        }
        return $this->applyScopes($customerOrder);
    }

    public function html()
    {
        return $this->builder()
            ->addColumn(['data' => 'quotation', 'name' => 'sale_orders.reference', 'title' => __('Quotation')])

            ->addColumn(['data' => 'total_qty', 'title' => __('Total Quantity')])

            ->addColumn(['data' => 'total_price', 'name' => 'sale_orders.total', 'title' => __('Total Amount')])

            ->addColumn(['data' => 'isInvoice', 'title' => __('Quotation Converted')])

            ->addColumn(['data' => 'ord_date', 'name' => 'sale_orders.order_date', 'title' => __('Quotation Date')])

            ->addColumn(['data' => 'action', 'title' => __('Action'), 'orderable' => false, 'searchable' => false])

            ->parameters([
                'pageLength' => $this->row_per_page,
                'language' => [
                        'url' => url('/resources/lang/'.config('app.locale').'.json'),
                    ],
                'order' => [0, 'desc'],
                'buttons' => ['csv'],
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
