<?php
namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use DB;
use Auth;
use Helpers;
use Session;
use App\Models\PurchaseOrder;
use App\Models\Preference;

class PurchaseReportDataTable  extends DataTable
{
    public function ajax()
    {
        $purchaseReport = $this->query();
        return datatables()
        ->of($purchaseReport)
        ->addColumn('order_date', function ($purchaseReport) {
            $location = isset($_GET['location']) ? $_GET['location'] : 'all';
            $supplier = isset($_GET['supplier']) ? $_GET['supplier'] : 'all';
            $product = isset($_GET['product']) ? $_GET['product'] : 'all';
            $searchType = isset($_GET['searchType']) ? $_GET['searchType'] : 'daily';
            if ($searchType == 'daily' || $searchType == 'custom') {
                return isset($purchaseReport['orderDate']) ? '<a href="' . url('report/purchase-report-datewise/' . strtotime($purchaseReport['orderDate'])) . '?' . 'currency=' . $purchaseReport['filterCurrency'] . '&location=' . $location . '&supplier=' . $supplier . '&product=' . $product . ' ">' . formatDate($purchaseReport['orderDate']) . ' </a>' : '';
            } else if ($searchType == 'monthly' || ($searchType == 'yearly' && $_GET['year'] == 'all') || ($searchType=='yearly' && $_GET['year'] !='all' && $_GET['month']=='all') || ($searchType=='yearly' && $_GET['year'] !='all' && $_GET['month']!='all')) {
                return isset($purchaseReport['orderDate']) ? date('F-Y',strtotime($purchaseReport['orderDate'])) : '';
            } else {
               return isset($purchaseReport['orderDate']) ? formatDate($purchaseReport['orderDate']) : '';
            }
        })

        ->addColumn('totalInvoice', function ($purchaseReport) {
            return $purchaseReport['totalInvoice'];
        })

        ->addColumn('totalQuantity', function ($purchaseReport) {
            return !empty($purchaseReport['totalQuantity']) ? formatCurrencyAmount($purchaseReport['totalQuantity']) : '';
        })

        ->addColumn('totalAmount', function ($purchaseReport) {
            return !empty($purchaseReport['totalAmount']) ? formatCurrencyAmount($purchaseReport['totalAmount']) : '';
        })

        ->addColumn('totalPurchaseTax', function ($purchaseReport) {
            return formatCurrencyAmount($purchaseReport['totalPurchaseTax']);
        })

        ->addColumn('totalPurchaseDiscount', function ($purchaseReport) {
            if ($purchaseReport['itemDiscountAmount'] + $purchaseReport['otherDiscountAmount'] > 0 ) {
                return formatCurrencyAmount($purchaseReport['itemDiscountAmount'] + $purchaseReport['otherDiscountAmount']);
            } else {
                return '-';
            }
        })

        ->rawcolumns(['order_date', 'totalInvoice', 'totalQuantity', 'totalAmount', 'totalPurchaseTax', 'totalPurchaseDiscount'])

        ->make(true);
    }

    public function query()
    {
        $data['location'] = isset($_GET['location']) && ($_GET['location'] != 'all') && ($_GET['location'] != 'null') ? $_GET['location'] : null;
        $data['supplier'] = isset($_GET['supplier']) && ($_GET['supplier'] != 'all') && ($_GET['supplier'] != 'null') ? $_GET['supplier'] : null;
        $data['item'] = isset($_GET['product']) && ($_GET['product'] != 'all') && ($_GET['product'] != 'null') ? $_GET['product'] : null;
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['currency'] = isset($_GET['currency']) && ($_GET['currency'] != 'all') && ($_GET['currency'] != 'null') && ($_GET['currency'] != '')? (int) $_GET['currency'] : $preference['dflt_currency_id'];
        $data['from'] = isset($_GET['from']) && ($_GET['from'] != '') && ($_GET['from'] != 'undefined') && ($_GET['from'] != 'null') ? $_GET['from'] : date("d-m-Y", strtotime("-1 months"));
        $data['to'] = isset($_GET['to']) && ($_GET['to'] != '') && ($_GET['to'] != 'undefined') && ($_GET['to'] != 'null') ? $_GET['to'] : date('d-m-Y');
        $data['year'] = isset($_GET['year']) && ($_GET['year'] != 'all') && ($_GET['year'] != 'null') ? $_GET['year'] : null;
        $data['month'] = isset($_GET['month']) && ($_GET['month'] != 'all') && ($_GET['month'] != 'null') ? $_GET['month'] : null;
        $data['searchType'] = $type = isset($_GET['searchType']) && ($_GET['searchType'] != 'all') && ($_GET['searchType'] != 'null') && ($_GET['searchType'] != '') ? $_GET['searchType'] : 'daily';


        $purchaseReport =(new PurchaseOrder)->getPurchaseReport($data['searchType'], $data['from'], $data['to'], $data['year'], $data['month'], $data['item'], $data['supplier'], $data['location'], $data['currency']);
        return $this->applyScopes($purchaseReport);
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

        ->addColumn(['data' => 'order_date', 'name' => 'order_date', 'title' => $message ])

        ->addColumn(['data' => 'totalInvoice', 'name' => 'totalInvoice', 'title' => __('No of invoice') ])

        ->addColumn(['data' => 'totalQuantity', 'name' => 'totalQuantity', 'title' => __('Purchase volume')])

        ->addColumn(['data' => 'totalAmount', 'name' => 'totalAmount', 'title' => __('Cost') . '  ' . '(' . $this->currency->symbol . ')'])

        ->addColumn(['data' => 'totalPurchaseTax', 'name' => 'totalPurchaseTax', 'title' => __('Tax') . '  ' . '(' . $this->currency->symbol . ')'])

        ->addColumn(['data' => 'totalPurchaseDiscount', 'name' => 'totalPurchaseDiscount', 'title' => __('Discount') . '  ' . '(' . $this->currency->symbol . ')'])

        ->parameters([
            'pageLength' => $this->row_per_page,
            'language' => [
                    'url' => url('/resources/lang/'.config('app.locale').'.json'),
                ],
            'order' => [0, 'asc']
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
