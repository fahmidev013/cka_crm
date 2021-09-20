<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;
use DB;
use Auth;
use Helpers;
use Session;
use App\Models\SaleOrder;
class UserInvoiceListDataTable extends DataTable
{
    public function ajax()
    {
        $userInvoice = $this->query();
        return datatables()
        ->of($userInvoice)
        ->addColumn('action', function ($userInvoice) {
            $edit = $delete = "";
            $edit = Helpers::has_permission(Auth::user()->id, 'edit_invoice') ? '<a href="'.url('invoice/edit/'.$userInvoice->so_id).'" class="btn btn-xs btn-primary"><i class="feather icon-edit"></i></a>&nbsp;' : '';
            if(Helpers::has_permission(Auth::user()->id, 'delete_invoice')) {
                $delete= '<form method="post" action="'.url('invoice/delete/'.$userInvoice->so_id).'" id="delete-invoice-'.$userInvoice->so_id.'" accept-charset="UTF-8" class="display_inline"> 
                    '.csrf_field().'
                    <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-id='.$userInvoice->so_id.' data-label="Delete" data-toggle="modal" data-target="#confirmDelete" data-title="' . __('Delete invoice') . '" data-message="' . __('Are you sure to delete this invoice?') . '">
                    <i class="feather icon-trash-2"></i> 
                    </button>
                    </form>';
            }
            return $edit.$delete;
        })
        ->addColumn('status', function ($userInvoice) {

            if ($userInvoice->paid == 0  && $userInvoice->total != 0) {
                return '<span class="badge text-white f-12 customer-invoice color-f44236">' . __('Unpaid') . '</span>';
            } else if ($userInvoice->paid > 0 && $userInvoice->total > $userInvoice->paid) {
                return '<span class="badge text-white f-12 customer-invoice color-04a9f5">' . __('Partially paid') . '</span>';
            } else if ($userInvoice->paid <= $userInvoice->paid || $userInvoice->paid == 0) {
                return '<span class="badge text-white f-12 customer-invoice color-4CAF50">' . __('Paid'). '</span>';
            }
        })

        ->addColumn('reference', function ($userInvoice) {
             return '<a href="'.url('invoice/view-detail-invoice/' . $userInvoice->so_id).'">'.$userInvoice->reference.'</a>';
        })

        ->addColumn('total', function ($userInvoice) {
            return formatCurrencyAmount($userInvoice->total) ;
        })


        ->addColumn('paid_amount', function ($userInvoice) {
            return formatCurrencyAmount($userInvoice->paid) ;
        })

        ->addColumn('ord_date', function ($userInvoice) {
            return formatDate($userInvoice->order_date);
        })

        ->addColumn('name', function ($userInvoice) {

            if (Helpers::has_permission(Auth::user()->id, 'edit_customer')) {
                if (isset($userInvoice->customer->name)) {
                    $name = '<a href="' . url('customer/edit/' . $userInvoice->customer_id) . '">' . $userInvoice->customer->name . '</a> (' . $userInvoice->currency->name . ')';
                    $project = isset($userInvoice->project->name) ? "<br><a href='". url('project/details/' . $userInvoice->project_id) ."'>" . $userInvoice->project->name . "</a>" : "";
                    return $name . $project;
                }
            } else if (isset($userInvoice->customer->name)) {
                return !empty($userInvoice->customer->name) ? $userInvoice->customer->name : "";
            } else if (isset($userInvoice->currency->name)) {
                return "<strong>". __('Walking customer') ."</strong>(" . !empty($userInvoice->currency->name) ? $userInvoice->currency->name : "". ")";
            }
        })


        ->addColumn('currency_name', function ($userInvoice) {
            return !empty($userInvoice->currency) ? $userInvoice->currency->name : '';
        })
    
        ->rawcolumns(['action','reference','status', 'total', 'paid_amount', 'ord_date', 'name'])

        ->make(true);
    }

    public function query()
    {
        $id       = $this->user_id;
        $from     =  isset($_GET['from']) ? $_GET['from'] : null ;
        $to       =  isset($_GET['to']) ? $_GET['to'] : null ;
        $customer =  isset($_GET['customer']) ? $_GET['customer'] : null ;
        $location =  isset($_GET['location']) ? $_GET['location'] : null ;
        $status   = isset($_GET['status']) ? $_GET['status'] : null;
        $userInvoice = (new SaleOrder)->getAllInvoices($from, $to, $customer, $location, null, $status, $id);
        return $this->applyScopes($userInvoice);
    }
    
    public function html()
    {
        return $this->builder()

        ->addColumn(['data' => 'reference', 'name' => 'sale_orders.reference', 'title' => __('Invoice')])

        ->addColumn(['data' => 'name', 'name' => 'customer.name', 'title' => __('Customer name')])

        ->addColumn(['data' => 'total', 'name' => 'sale_orders.total', 'title' => __('Total price')])

        ->addColumn(['data'=> 'paid_amount', 'name' => 'sale_orders.paid', 'title' => __('Paid amount')])

        ->addColumn(['data'=> 'currency_name', 'name' => 'currency.name', 'title' => __('Currency')])

        ->addColumn(['data' => 'status', 'name' => 'status', 'title' => __('Paid status'), 'orderable' => false])

        ->addColumn(['data' => 'ord_date', 'name' => 'sale_orders.order_date', 'title' => __('Invoice date')])   

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
