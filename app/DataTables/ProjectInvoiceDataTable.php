<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;
use DB;
use Auth;
use Helpers;
use Session;
use App\Models\SaleOrder;
class ProjectInvoiceDataTable extends DataTable
{
    public function ajax()
    {
        $saleInvoice = $this->query();
        return  datatables()
        ->of($saleInvoice)
        ->addColumn('action', function ($saleInvoice) {
            $edit = Helpers::has_permission(Auth::user()->id, 'edit_invoice') ? '<a href="'. url('invoice/edit/'. $saleInvoice->id .'?type=project&project_id='. $this->project_id) .'" class="btn btn-xs btn-primary"><i class="feather icon-edit"></i></a>&nbsp;' : '';
            $delete = '';
            if (Helpers::has_permission(Auth::user()->id, 'delete_invoice')) {
                $delete = '<form method="get" action="'. url('invoice/delete-invoice/'. $saleInvoice->id .'?type=project') .'" class="display_inline"  id="delete-item-'. $saleInvoice->id .'">
                    '. csrf_field() .'
                    <button title="' . __('Delete') . '" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id="'. $saleInvoice->id .'" data-target="#theModal" data-label="Delete" data-title="' . __('Delete invoice') . '" data-message="' . __('Are you sure to delete this invoice?') . '">
                                    <i class="feather icon-trash-2"></i> </button>
                         
                    </form>';
            }
            
            return $edit . $delete;
        })
        ->addColumn('status', function ($saleInvoice) {
            if ($saleInvoice->paid == 0  && $saleInvoice->total != 0) {
                return '<span class="badge text-white f-12 customer-invoice color-f44236">' . __('Unpaid') . '</span>';
            } else if ($saleInvoice->paid > 0 && $saleInvoice->total > $saleInvoice->paid) {
                return '<span class="badge text-white f-12 customer-invoice color-04a9f5">' . __('Partially paid') . '</span>';
            } else if ($saleInvoice->paid <= $saleInvoice->paid || $saleInvoice->paid == 0) {
                return '<span class="badge text-white f-12 customer-invoice color-4CAF50">' . __('Paid'). '</span>';
            }
        })
        ->addColumn('id', function ($saleInvoice) {
             return '<a href="'. url('invoice/view-detail-invoice/' . $saleInvoice->id) .'">'. $saleInvoice->reference.'</a>';
        })
        ->addColumn('total', function ($saleInvoice) {
            return number_format($saleInvoice->total, 2, '.', ',');
        })
        ->addColumn('paid_amount', function ($saleInvoice) {
            return number_format($saleInvoice->paid, 2, '.', ',');
        })
        ->addColumn('ord_date', function ($saleInvoice) {
            return formatDate($saleInvoice->order_date);
        })
        ->addColumn('cus_name', function ($saleInvoice) {
            if(Helpers::has_permission(Auth::user()->id, 'edit_customer')) {
                return '<a href="'. url('customer/edit/'. $saleInvoice->customer_id) .'">'. $saleInvoice->first_name .' '. $saleInvoice->last_name .'</a>';
            }
        })
        ->rawColumns(['action', 'id', 'cus_name', 'status'])
        ->make(true);
    }

    public function query()
    {
        $project_id = $this->project_id;
        $from = isset($_GET['from']) ? $_GET['from'] : null;        
        $to = isset($_GET['to']) ? $_GET['to'] : null;
        
        $saleInvoice = (new SaleOrder)->getAllSaleOrderByProject($from, $to, $project_id);
        return $this->applyScopes($saleInvoice);
    }
    
    public function html()
    {
        return $this->builder()
        ->addColumn(['data' => 'id', 'name' => 'reference', 'title' => __('Invoice no')])
        ->addColumn(['data' => 'cus_name', 'name' => 'customers.name', 'title' => __('Customer name')])
        ->addColumn(['data' => 'total', 'name' => 'total', 'title' => __('Total price')])
        ->addColumn(['data'=> 'paid_amount', 'name' => 'paid', 'title' => __('Paid amount')])
        ->addColumn(['data'=> 'currency_name', 'name' => 'currencies.name', 'title' => __('Currency')])
        ->addColumn(['data' => 'status', 'name' => 'paid', 'title' => __('Status')])
        ->addColumn(['data' => 'ord_date', 'name' => 'order_date', 'title' => __('Invoice date')])
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
