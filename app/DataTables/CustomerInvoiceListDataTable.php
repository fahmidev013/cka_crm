<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;
use DB;
use Auth;
use Helpers;
use Session;
use App\Models\SaleOrder;
class CustomerInvoiceListDataTable extends DataTable
{
    public function ajax()
    {
        $customerInvoice = $this->query();
        return datatables()
        ->of($customerInvoice)
        ->addColumn('action', function ($customerInvoice) {


            $edit = Helpers::has_permission(Auth::user()->id, 'edit_invoice') ? '<a href="'. url('invoice/edit/'. $customerInvoice->so_id) .'" class="btn btn-xs btn-primary"><i class="feather icon-edit"></i></a>&nbsp;':'';
            
            if(Helpers::has_permission(Auth::user()->id, 'delete_invoice')){
                $delete = '<form method="post" action="'. url('invoice/delete/'. $customerInvoice->so_id) .'" accept-charset="UTF-8" class="display_inline_block" id="delete-invoice-'. $customerInvoice->so_id .'">
                '. csrf_field() .'

                <input type="hidden" name="menu" value="relationship">
                <input type="hidden" name="sub_menu" value="customer">
                <input type="hidden" name="customer" value="'. $customerInvoice->customer_id .'">
                <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-id='. $customerInvoice->so_id .' data-label="Delete" data-toggle="modal" data-target="#confirmDelete" data-title="' . __('Delete invoice').'" data-message="' . __('Are you sure to delete this invoice?').'">
                <i class="feather icon-trash-2"></i>
                </button>
                </form>';
            }
            else{
                $delete='';
 
            }
            return $edit.$delete;
        })

        ->addColumn('status', function ($customerInvoice) {
            if ($customerInvoice->paid == 0  && $customerInvoice->total != 0) {
                return '<span class="badge text-white f-12 customer-invoice color-f44236">' . __('Unpaid') . '</span>';
            } else if ($customerInvoice->paid > 0 && $customerInvoice->total > $customerInvoice->paid) {
                return '<span class="badge text-white f-12 customer-invoice color-04a9f5">' . __('Partially Paid') . '</span>';
            } else if ($customerInvoice->paid <= $customerInvoice->paid || $customerInvoice->paid == 0) {
                return '<span class="badge text-white f-12 customer-invoice color-4CAF50">' . __('Paid'). '</span>';
            }
        })

        ->addColumn('reference', function ($customerInvoice) {
             return '<a href="'. url('invoice/view-detail-invoice/' . $customerInvoice->so_id) .'">'. $customerInvoice->reference .'</a>';
        })

        ->addColumn('total', function ($customerInvoice) {
            return formatCurrencyAmount($customerInvoice->total, $customerInvoice->currency->symbol) ;
        })


        ->addColumn('paid_amount', function ($customerInvoice) {
            return formatCurrencyAmount($customerInvoice->paid, $customerInvoice->currency->symbol) ;
        })

        ->addColumn('order_date', function ($customerInvoice) {
            return formatDate($customerInvoice->order_date);
        })
               

        ->rawColumns(['action', 'reference', 'status', 'total', 'paid_amount', 'order_date'])

        ->make(true);
    }

    public function query()
    {
        $id = $this->customer_id;
        $from = isset($_GET['from']) ? $_GET['from'] : null ;
        $to = isset($_GET['to']) ? $_GET['to'] : null ;
        $status = isset($_GET['pay_status_type']) ? $_GET['pay_status_type'] : null ;
        $customerInvoice = (new SaleOrder)->getAllInvoices($from, $to, $id, null, null, $status, null);
        
        return $this->applyScopes($customerInvoice);
    }
    
    public function html()
    {
        return $this->builder()

        ->addColumn(['data' => 'reference', 'name' => 'sale_orders.reference', 'title' => __('Invoice No')])

        ->addColumn(['data' => 'total', 'name' => 'sale_orders.total', 'title' => __('Total Price')])

        ->addColumn(['data' => 'paid_amount', 'name' => 'sale_orders.paid', 'title' => __('Paid')])

        ->addColumn(['data'=> 'status', 'title' => __('Status'), 'orderable' => false, 'searchable' => false])

        ->addColumn(['data' => 'order_date', 'name' => 'sale_orders.order_date', 'title' => __('Date')])

        ->addColumn(['data'=> 'action', 'name' => 'action', 'title' => __('Action'), 'orderable' => false, 'searchable' => false])

        ->parameters([
            'pageLength' => $this->row_per_page,
            'language' => [
                    'url' => url('/resources/lang/'.config('app.locale').'.json'),
                ],
            'order' => [1, 'asc']
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
