<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;
use DB;
use Auth;
use Helpers;
use Session;
use App\Models\SaleOrder;
use App\Models\Purchase;

class UserQuotationListDataTable extends DataTable
{
    public function ajax()
    {
        $userQuotation = $this->query();
        return datatables()
        ->of($userQuotation)
        ->addColumn('action', function ($userQuotation) {


            $edit = Helpers::has_permission(Auth::user()->id, 'edit_quotation') ? '<a href="'.url('order/edit/'.$userQuotation->so_id).'" class="btn btn-xs btn-primary"><i class="feather icon-edit"></i></a>&nbsp;':'';
            
            if(Helpers::has_permission(Auth::user()->id, 'delete_quotation')){
                $delete= '<form method="post" action="'.url('order/delete/'.$userQuotation->so_id).'" id="delete-quotation-'.$userQuotation->so_id.'" accept-charset="UTF-8" class="display_inline"> 
                    '.csrf_field().'
                    <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-id='.$userQuotation->so_id.' data-label="Delete" data-toggle="modal" data-target="#confirmDelete" data-title="' . __('Delete quotation') . '" data-message="' . __('Are you sure to delete this quotation?') . '">
                    <i class="feather icon-trash-2"></i> 
                    </button>
                    </form>';
            }
            return $edit.$delete;
        })
        ->addColumn('reference', function ($userQuotation) {
            return '<a href="'.url('order/view-order-details/'.$userQuotation->so_id).'">'.$userQuotation->reference.'</a>';
        })

        ->addColumn('name', function ($userQuotation) {
            return '<a href="'.url('customer/edit/'.$userQuotation->customer_id).'">'.$userQuotation->customer->first_name.' '. $userQuotation->customer->last_name. '</a>';
        })

        ->addColumn('order_amount', function ($userQuotation) {
            return formatCurrencyAmount($userQuotation->total) ;
        })

        ->addColumn('ordered_quantity', function ($userQuotation) {
            return !empty($userQuotation->saleOrderDetails) ? formatCurrencyAmount($userQuotation->saleOrderDetails->sum('quantity')) : '' ;
        })

        ->addColumn('order_date', function ($userQuotation) {
            return formatDate($userQuotation->order_date);
        })

        ->addColumn('location', function ($userQuotation) {
            return isset($userQuotation->location->name) && !empty($userQuotation->location->name) ? $userQuotation->location->name : '';
        })

        ->addColumn('currency_name', function ($userQuotation) {
            return isset($userQuotation->currency->name) ? $userQuotation->currency->name : '';
            })
        ->rawcolumns(['action','reference','name','order_amount', 'order_date', 'ordered_quantity'])

        ->make(true);
    }

    public function query()
    {
        $id = $this->user_id;
        $from     = isset($_GET['from']) ? ($_GET['from']) : null;
        $to       = isset($_GET['to']) ? ($_GET['to']) : null;
        $location = isset($_GET['location']) ? $_GET['location'] : null;
        $customer = isset($_GET['customer']) ? $_GET['customer'] : null;
        $userQuotation = (new SaleOrder)->getAllQuotation($from, $to, $location, $customer, null, $id);

        return $this->applyScopes($userQuotation);
    }
    
    public function html()
    {
        return $this->builder()

        ->addColumn(['data' => 'reference', 'name' => 'sale_orders.reference', 'title' => __('Quotation')])

        ->addColumn(['data' => 'name', 'name' => 'customer.first_name', 'title' => __('Customer name')])

        ->addColumn(['data' => 'location', 'name' => 'location.name', 'title' => __('Location')])

        ->addColumn(['data' => 'ordered_quantity', 'title' => __('Quantity'), 'orderable' => false, 'searchable' => false])

        ->addColumn(['data' => 'order_amount', 'name' => 'sale_orders.total', 'title' => __('Total')])

         ->addColumn(['data' => 'currency_name', 'name' => 'currency.name', 'title' => __('Currency')])

        ->addColumn(['data' => 'order_date', 'name' => 'sale_orders.order_date', 'title' => __('Date')])

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
