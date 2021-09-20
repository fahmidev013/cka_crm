<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;

use DB;
use Auth;
use Helpers;
use Session;
use App\Models\SaleOrder; 

class CustomerPanelInvoiceListDataTable extends DataTable
{
    public function ajax()
    {
        $saleInvoice   = $this->query();
        return datatables()
            ->of($saleInvoice)
            ->addColumn('invoice', function ($saleInvoice) {
                return '<a href="'.url('customer-panel/view-detail-invoice/'.$saleInvoice->so_id).'">'.$saleInvoice->reference.'</a>';
            })
            ->addColumn('total_price', function ($saleInvoice) {
                return formatCurrencyAmount($saleInvoice->total);
            })
            ->addColumn('paid_amount', function ($saleInvoice) {
                return formatCurrencyAmount($saleInvoice->paid);                
            })
            ->addColumn('paid_status', function ($saleInvoice) {
                if ($saleInvoice->paid == 0  && $saleInvoice->total != 0) {
                    return '<span class="badge theme-bg-r text-white f-12">' . __('Unpaid') . '</span>';
                } else if ($saleInvoice->paid > 0 && $saleInvoice->total > $saleInvoice->paid) {
                    return '<span class="badge theme-bg2 text-white f-12">' . __('Partially Paid') . '</span>';
                } else if ($saleInvoice->paid <= $saleInvoice->paid || $saleInvoice->paid == 0) {
                    return '<span class="badge theme-bg text-white f-12">' . __('Paid'). '</span>';
                }

            })
            ->addColumn('invoice_date', function ($saleInvoice) {
                return formatDate($saleInvoice->order_date);

            })

            ->rawcolumns(['invoice', 'total_price', 'paid_amount', 'paid_status', 'invoice_date'])

            ->make(true);
    }

    public function query()
    {
        $id = Auth::guard('customer')->user()->id;
        $from = isset($_GET['from']) ? $_GET['from'] : null ;
        $to = isset($_GET['to']) ? $_GET['to'] : null ;
        $status = isset($_GET['pay_status_type']) ? $_GET['pay_status_type'] : null ;
        $flag = 'customerPanel';
        $saleInvoice = (new SaleOrder)->getAllInvoices($from, $to, $id, null, null, $status, null, $flag);

        return $this->applyScopes($saleInvoice);
        
    }
    
    public function html()
    {
        return $this->builder()
            ->addColumn(['data' => 'created_at', 'name' => 'sale_orders.created_at', 'visible' => false])
            
            ->addColumn(['data' => 'invoice', 'name' => 'sale_orders.reference', 'title' => __('Invoice No')])

            ->addColumn(['data' => 'total_price', 'name' => 'sale_orders.total', 'title' => __('Total Price')])

            ->addColumn(['data' => 'paid_amount', 'name' => 'sale_orders.paid', 'title' => __('Paid')])

            ->addColumn(['data' => 'paid_status', 'name' => 'paid', 'title' => __('Status') ])

            ->addColumn(['data' => 'invoice_date', 'name' => 'sale_orders.order_date', 'title' => __('Invoice Date')])

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
        return 'customers_invoice' . time();
    }
}
