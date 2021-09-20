<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;
use DB;
use Auth;
use Helpers;
use Session;
use App\Models\SaleOrder;

class CustomerPanelQuotationListDataTable extends DataTable
{
    public function ajax()
    {
        $customerOrder   = $this->query();
        return datatables()
            ->of($customerOrder)
            ->addColumn('quotation', function ($customerOrder) {
                return '<a title="view" href="'.url('customer-panel/view-order-details/'.$customerOrder->id).'">'.$customerOrder->reference.'</a>';

            })
            ->addColumn('total_qty', function ($customerOrder) {
                return !empty($customerOrder->saleOrderDetails) ? formatCurrencyAmount($customerOrder->saleOrderDetails->sum('quantity')) : '' ;

            })
            ->addColumn('total_price', function ($customerOrder) {
                return formatCurrencyAmount($customerOrder->total, $this->currency->symbol);

            })
            ->addColumn('isInvoice', function ($customerOrder) {
                $checkInvoice = (new SaleOrder)->checkConversion($customerOrder->id)->first();
                if (!isset($checkInvoice->reference)) {
                    return '<label class="badge theme-bg2 text-white f-12 ml-50 quotation-convert">' . __('No') . '</label>';
                } else {
                    return '<label class="badge theme-bg text-white f-12 ml-50 quotation-convert">' . __('Yes') . '</label>';
                }

            })
            ->addColumn('ord_date', function ($customerOrder) {
                return formatDate($customerOrder->order_date);

            })

            ->rawcolumns(['quotation', 'total_qty', 'total_price', 'isInvoice', 'ord_date'])

            ->make(true);
    }

    public function query()
    {
        $from    = isset($_GET['from']) ? $_GET['from'] : null ;
        $to      = isset($_GET['to']) ? $_GET['to'] : null ;
        
        if (!empty($from) && !empty($to)) {
            $customerOrder = SaleOrder::with(['customer:id,name', 'currency:id,name', 'saleOrderDetails:id,sale_order_id,quantity'])
                        ->where([ 'transaction_type' => 'SALESORDER', 'customer_id' => Auth::guard('customer')->user()->id,'order_type'=>'Direct Order'])
                        ->where('order_date', '>=', DbDateFormat($from))
                        ->where('order_date', '<=' , DbDateFormat($to));
        } else {
            $customerOrder = SaleOrder::with(['customer:id,name', 'currency:id,name', 'saleOrderDetails:id,sale_order_id,quantity'])
                        ->where([ 'transaction_type' => 'SALESORDER', 'customer_id' => Auth::guard('customer')->user()->id,'order_type'=>'Direct Order']);
        }
        return $this->applyScopes($customerOrder);
        
    }
    
    public function html()
    {
        return $this->builder()
            ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'visible' => false])
            
            ->addColumn(['data' => 'quotation', 'name' => 'reference','title' => __('Quotation')])

            ->addColumn(['data' => 'total_qty', 'orderable' => false, 'searchable' => false, 'title' => __('Total Quantity')])

            ->addColumn(['data' => 'total_price', 'name' => 'total', 'title' => __('Total Amount')])

            ->addColumn(['data' => 'isInvoice', 'title' => __('Quotation Converted')])

            ->addColumn(['data' => 'ord_date', 'name' => 'order_date', 'title' => __('Order Date')])

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
        return 'customers_quotation' . time();
    }
}
