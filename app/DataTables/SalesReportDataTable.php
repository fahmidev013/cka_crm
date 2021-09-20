<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;
use DB;
use Auth;
use Helpers;
use Session;
use App\Models\Report;

class SalesReportDataTable  extends DataTable
{
    public function ajax()
    {
        $salesReport = $this->query();
        return datatables()
        ->of($salesReport)
        ->addColumn('order_date', function ($salesReport) {
            $searchType = isset($_GET['searchType']) ? $_GET['searchType'] : 'daily';
            $year = isset($_GET['year']) && $_GET['year'] != 'all' ? $_GET['year'] : null;
            $month = isset($_GET['month']) && $_GET['month'] != 'all' ? $_GET['month'] : null;
            $location = isset($_GET['location']) && $_GET['location'] != 'all' ? $_GET['location'] : null;
            $customer = isset($_GET['customer']) && $_GET['customer'] != 'all' ? $_GET['customer'] : null;
            $type = isset($_GET['type']) && $_GET['type'] != 'all' ? isset($_GET['type']) : null;
            $item = isset($_GET['product']) && $_GET['product'] != 'all' ? isset($_GET['product']) : null;
            $service = isset($_GET['service']) && $_GET['service'] != 'all' ? isset($_GET['service']) : null;
            $product = !empty($service) ? $service : $item;
            if (isset($type) && !empty($type) && !empty($product)) {
                if ($type == 'service') {
                    $product = $product . '-' . $type;
                } else {
                    $product = $product . '-' . $type;
                }
            }

            if ($searchType == 'monthly' || $searchType == 'yearly') {
                return '<a href="' . url('report/sale_report_filterwise/') . '?' . 'currency=' . $salesReport['filterCurrency'] . '&location=' . $location . '&customer=' . $customer . '&type=' . $type . '&product=' . $product . '&month=' . date('m',strtotime($salesReport['orderDate'])) . '&year=' . date('Y',strtotime($salesReport['orderDate'])) . ' ">' . date('F-Y',strtotime($salesReport['orderDate'])) . ' </a>';
            } else {
                return '<a href="' . url('report/sales-report-by-date/') . '?' . 'currency=' . $salesReport['filterCurrency'] . '&time=' . strtotime($salesReport['orderDate']) .'&location=' . $location . '&customer=' . $customer . '&type=' . $type . '&product=' . $product . ' ">' . formatDate($salesReport['orderDate']) . ' </a>';
            }
        })

        ->addColumn('totalProfitAmount', function ($salesReport) {
            $profitMargin = $salesReport['totalactualSalePrices'] - $salesReport['totalPurchase'];
            if ($salesReport['totalactualSalePrices'] != 0) {
                $profitMarginParcent = ($profitMargin / $salesReport['totalactualSalePrices']) * 100;
                return formatCurrencyAmount($profitMargin) . '<br>' . formatCurrencyAmount($profitMarginParcent) . '%';
            } else {
                return formatCurrencyAmount($profitMargin) . '<br>'. formatCurrencyAmount(0) . '%';
            }
        })

        ->addColumn('totalactualSalePrices', function ($salesReport) { 
            return formatCurrencyAmount($salesReport['totalactualSalePrices']);
        })

        ->addColumn('totalPurchase', function ($salesReport) { 
            return formatCurrencyAmount($salesReport['totalPurchase']);
        })

        ->addColumn('totalQuantity', function ($salesReport) { 
            return formatCurrencyAmount($salesReport['totalQuantity']);
        })

        ->addColumn('totalSaleTax', function ($salesReport) { 
            return formatCurrencyAmount($salesReport['totalSaleTax']);
        })

        ->addColumn('totalSaleDiscount', function ($salesReport) { 
            if ($salesReport['itemDiscountAmount'] + $salesReport['otherDiscountAmount'] > 0 ) {
                return formatCurrencyAmount($salesReport['itemDiscountAmount'] + $salesReport['otherDiscountAmount']);
            } else {
                return '-';
            } 
        })

        ->rawcolumns(['order_date', 'totalProfitAmount', 'totalactualSalePrices', 'totalPurchase', 'totalSaleTax', 'totalQuantity', 'totalSaleDiscount'])

        ->make(true);
    }

    public function query()
    { 
        $salesReport = $this->data['itemList'];

        return $this->applyScopes($salesReport);
    }
    
    public function html()
    {
        $type = isset($_GET['searchType']) && !empty($_GET['searchType']) ? $_GET['searchType'] : null;
        if($type== 'yearly' || $type== 'monthly') {
            $message = __('Month');
        } else{
            $message = __('Date');
        }
        return $this->builder()
        ->addColumn(['data' => 'orderDate', 'name' => 'orderDate', 'visible' => false])

        ->addColumn(['data' => 'order_date', 'name' => 'order_date', 'title' =>$message ])

        ->addColumn(['data' => 'totalInvoice', 'name' => 'totalInvoice', 'title' => __('No of invoice') ])

        ->addColumn(['data' => 'totalQuantity', 'name' => 'totalQuantity', 'title' => __('Volume')])

        ->addColumn(['data' => 'totalactualSalePrices', 'name' => 'totalactualSalePrices', 'title' => __('Sales value').' '.'('.$this->currency->symbol.')'])

        ->addColumn(['data' => 'totalPurchase', 'name' => 'totalPurchase', 'title' => __('Cost').' '.'('.$this->currency->symbol.')'])

        ->addColumn(['data' => 'totalSaleTax', 'name' => 'totalSaleTax', 'title' => __('Tax').' '.'('.$this->currency->symbol.')'])

        ->addColumn(['data' => 'totalSaleDiscount', 'name' => 'totalSaleDiscount', 'title' => __('Discount').' '.'('.$this->currency->symbol.')'])

        ->addColumn(['data' => 'totalProfitAmount', 'name' => 'totalProfitAmount', 'title' => __('Profit').' '.'('.$this->currency->symbol.')'])

        ->parameters([
            'pageLength' => $this->row_per_page,
            'language' => [
                    'url' => url('/resources/lang/'.config('app.locale').'.json'),
                ],
            'order' => [1, 'desc']
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
