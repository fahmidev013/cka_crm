<?php

namespace App\Http\Controllers;

use App\DataTables\InventoryStockOnHandDataTable;
use App\DataTables\PurchaseReportDataTable;
use App\DataTables\SalesHistoryReportDataTable;
use App\DataTables\SalesReportDataTable;
use App\Http\Controllers\EmailController;
use App\Exports\salesHistoryReportExport;
use App\Exports\ItemOnHandReportExport;
use App\Exports\SaleReportExport;
use App\Exports\PurchaseReportExport;
use App\Http\Requests;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Item;
use App\Models\Location;
use App\Models\StockCategory;
use App\Models\Preference;
use App\Models\PurchaseOrder;
use App\Models\Report;
use App\Models\SaleOrder;
use App\Models\Lead;
use App\Models\LeadStatus;
use App\Models\LeadSource;
use DB;
use Excel;
use Illuminate\Http\Request;
use PDF;
use Session;

class ReportController extends Controller
{
    public function __construct(SaleOrder $sales, EmailController $email, Report $report, PurchaseOrder $purchase,  LeadStatus $leadStatus, LeadSource $leadSource)
    {
        $this->sale = $sales;
        $this->email = $email;
        $this->report = $report;
        $this->purchase = $purchase;
        $this->leadStatus = $leadStatus;
        $this->leadSource = $leadSource;
    }

    public function getStockOnHandDetail()
    {
        $data = [];
        $qtyOnHand = 0;
        $costValueQtyOnHand = 0;
        $retailValueOnHand = 0;
        $profitValueOnHand = 0;

        $data['type'] = isset($_GET['type']) ? $_GET['type'] : 'all';
        $data['location_id'] = isset($_GET['location']) ? $_GET['location'] : 'all';
        $data['itemList'] = $itemList = $this->report->getInventoryStockOnHand($data['type'], $data['location_id']);
        foreach ($itemList as $key => $item) {
            $qtyOnHand += $item->available_qty;

            if ($item->available_qty != 0) {
                $costValueQtyOnHand += $item->purchase_price * $item->available_qty;
                $retailValueOnHand += $item->retail_price * $item->available_qty;
            }
        }
        $profitValueOnHand = $retailValueOnHand - $costValueQtyOnHand;

        $data['menu']               = 'report';
        $data['sub_menu']           = 'report/inventory-stock-on-hand';
        $data['qtyOnHand']          = $qtyOnHand;
        $data['costValueQtyOnHand'] = abs($costValueQtyOnHand);
        $data['retailValueOnHand']  = abs($retailValueOnHand);
        $data['profitValueOnHand']  = abs($profitValueOnHand);
        $data['locationList']       = Location::getAll()->where('is_active', 1);
        $data['categoryList']       = StockCategory::getAll()->where('is_active', 1);

        return $data;
    }

    /**
    * Return inventory Stock On Hand
    */
    public function inventoryStockOnHand(InventoryStockOnHandDataTable $dataTable)
    {
        $data           = $this->getStockOnHandDetail();
        $data['page_title'] = __('Inventory Stock Report');
        $row_per_page   = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;
        $currency       = Currency::getDefault();

        return $dataTable->with('row_per_page', $row_per_page)->with('currency', $currency)->render('admin.report.inventory_stock_on_hand', $data);
    }

    /**
    * Return inventory Stock On Hand with pdf format
    */
    public function inventoryStockOnHandPdf()
    {
        $data = $this->getStockOnHandDetail();
        $data['location_name'] = 'All';
        $data['category_name'] = 'All';
        $type = isset($_GET['type']) ? $_GET['type'] : null;
        $location = isset($_GET['location']) ? $_GET['location'] : null;
        $data['locationSelected'] = Location::find($location);
        $data['categorySelected'] = StockCategory::find($type);
        foreach ($data['locationList'] as $location) {
            if ($data['location_id'] == $location->id) {
                $data['location_name'] = $location->name;
            }
        }

        foreach ($data['categoryList'] as $category) {
            if ($data['type'] == $category->id) {
                $data['category_name'] = $category->name;
            }
        }
        return printPDF($data, 'inventory_stock_on_hand_' . time() . '.pdf', 'admin.report.inventory_item_stock_on_hand_pdf', view('admin.report.inventory_item_stock_on_hand_pdf', $data), 'pdf', 'domPdf');

    }

    /**
    * Return inventory Stock On Hand with csv format
    */
    public function inventoryStockOnHandCsv()
    {
        return Excel::download(new ItemOnHandReportExport(), 'inventory_stock_on_hand_report'. time() .'.csv');
    }

    /**
     * [generateSaleData description]
     * @return [type] [description]
     */
    public function generateSaleData()
    {
        $data = [];
        $data['menu']       = 'report';
        $data['sub_menu']   = 'report/sales-report';
        $data['from']       = isset($_GET['from']) && ($_GET['from'] != '') && ($_GET['from'] != 'undefined') ? $_GET['from'] : null;
        $data['to']         = isset($_GET['to']) && ($_GET['to'] != '') && ($_GET['to'] != 'undefined') ? $_GET['to'] : null;
        $data['year']       = isset($_GET['year']) && ($_GET['year'] != 'all') && ($_GET['year'] != 'null') ? $_GET['year'] : null;
        $data['month']      = isset($_GET['month']) && ($_GET['month'] != 'all') && ($_GET['month'] != 'null') ? $_GET['month'] : null;
        $data['type']       = isset($_GET['type']) && ($_GET['type'] != 'all') && ($_GET['type'] != 'null') ? $_GET['type'] : null;
        $data['location']   = isset($_GET['location']) && ($_GET['location'] != 'all') && ($_GET['location'] != 'null') ? $_GET['location'] : null;
        $data['customer']   = isset($_GET['customer']) && ($_GET['customer'] != '') && ($_GET['customer'] != 'null')  ? $_GET['customer'] : null;
        $data['searchType'] = isset($_GET['searchType']) ? $_GET['searchType'] : 'daily';
        $data['currencyList'] = Currency::getAll();
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['currency'] = isset($_GET['currency']) && ($_GET['currency'] != 'null') ? (int) $_GET['currency'] : (int) $preference['dflt_currency_id'];

        $data['customerList'] = Customer::select('id', DB::raw("CONCAT(customers.first_name,' ',customers.last_name)  AS name"))->where(['is_active' => 1])->get();

        $data['locationList'] = Location::getAll()->where('is_active', '=', 1);

        $data['productList']  = Item::where(['is_active' => 1, 'item_type' => 'product'])->select('id', 'stock_id', 'name')->get();
        $data['serviceList']  = Item::where(['is_active' => 1, 'item_type' => 'service'])->select('id', 'stock_id', 'name')->get();
        $data['yearList'] = $this->sale->getSaleYears();

        $sales = SaleOrder::whereIn('transaction_type', ['SALESINVOICE', 'POSINVOICE'])->get();
        $data['no_of_invoice'] = $sales->count();
        $data['itemList'] = $this->sale->getSalesReport($data['searchType'], $data['from'], $data['to'], $data['year'], $data['month'], null, $data['customer'], $data['location'], $data['currency']);

        $data['from'] = !empty($from) && strtotime($from) ? DbDateFormat($from) : DbDateFormat(date("d-m-Y", strtotime("-1 months")));
        $data['to'] = !empty($to) && strtotime($to) ? DbDateFormat($to) : DbDateFormat(date('d-m-Y'));

        return $data;
    }

    /**
    * Sales history report
    */
    public function salesReport(SalesReportDataTable $dataTable)
    {
        $data = [];
        $list = [];
        $type = [];
        $tax = [];
        $sale = [];
        $purchase = [];
        $profit = [];
        $date['date'] = [];
        $currency = [];
        $qty = 0;
        $cost = 0;
        $order = 0;
        $actualSale = 0;
        $totalSaleTax = 0;
        $totalPurchase = 0;
        $totalProfitAmount = 0;

        $data = $this->generateSaleData();
        $data['page_title'] = __('Sales Report');
        $type = $data['searchType'];
        $list = $data['itemList'];

        foreach ($list as $key => $value) {
            $cost               += $value['totalAmount'];
            $actualSale         += $value['totalactualSalePrices'];
            $order              += $value['totalInvoice'];
            $qty                += $value['totalQuantity'];
            $totalPurchase      += $value['totalPurchase'];
            $totalSaleTax       += $value['totalSaleTax'];
            $totalProfitAmount  += $value['totalProfitAmount'];
            $orderDate           = $value['orderDate'];
            if ($type == 'daily' || $type == 'custom') {
                $date['date'][$key] = date('d-m-Y', strtotime($key));
            } else if ($type == 'monthly') {
                $date['date'][$key] = date('F-Y', strtotime($key));
            } else if ($type == 'yearly' && $data['year'] == 'all') {
                $date['date'][$key] = date('Y', strtotime($key));
            } else if ($type == 'yearly' && $data['year'] != 'all' && $data['month'] == 'all') {
                $date['date'][$key] = date('F-Y', strtotime($key));
            } else if ($type == 'yearly' && $data['year'] != 'all' && $data['month'] != 'all') {
                $date['date'][$key] = date('d-m-Y', strtotime($key));
            }
            $tax['tax'][$key]           = $value['totalSaleTax'];
            $sale['sale'][$key]         = $value['totalactualSalePrices'];
            $purchase['purchase'][$key] = $value['totalPurchase'];
            $profit['profit'][$key]     = $value['totalProfitAmount'];
        }
        $data['totalSale']          = $cost;
        $data['totalActualSale']    = $actualSale;
        $data['totalPurchase']      = $totalPurchase;
        $data['totalProfitAmount']  = $totalProfitAmount;
        $data['totalQuantity']      = $qty;
        $data['totalSaleTax']       = $totalSaleTax;
        $data['tax'] = json_encode(!empty($tax['tax']) ? array_values($tax['tax']) : 0);
        $data['sale'] = json_encode(!empty($sale['sale']) ? array_values($sale['sale']) : 0);
        $data['date']   =  json_encode(!empty($date['date']) ? array_values($date['date']) : 0);
        $data['purchase'] = json_encode(!empty($purchase['purchase']) ? array_values($purchase['purchase']) : 0);
        $data['profit'] = json_encode(!empty($profit['profit']) ? array_values($profit['profit']) : 0);
        $row_per_page = Preference::getAll()->where('field', 'row_per_page')->first()->value;
        $currency = Currency::find($data['currency']);
        $data['currency_sign'] = $currency->symbol;

        return $dataTable->with('row_per_page', $row_per_page)->with('currency', $currency)->with('data', $data)->render('admin.report.sales_report', $data);
    }

    /**
    * Sales report on csv
    */

    public function salesReportCsv()
    {
        return Excel::download(new SaleReportExport(), 'sales_report_'. time() .'.csv');
    }

    /**
    * Sales report on pdf
    */

    public function salesReportPdf()
    {
        $data = [];
        $data = $this->generateSaleData();
        $list = $data['itemList'];
        $type = $data['searchType'];
        $currency = Currency::find($data['currency']);
        $data['currencyShortName'] = $currency->name;
        $data['date_range'] =  formatDate($data['from']) .' To '. formatDate($data['to']);
        if (isset($data['customer']) && !empty($data['customer'])) {
            $data['customerDetails'] = Customer::find($data['customer'])->name;
        } else {
            $data['customerDetails'] = null;
        }
        if (isset($data['location']) && !empty($data['location'])) {
            $data['locationDetails'] = Location::find($data['location'])->name;
        } else {
            $data['locationDetails'] = null;
        }

        return printPDF($data, 'sales_report_' . time() . '.pdf', 'admin.report.sales_report_pdf', view('admin.report.sales_report_pdf', $data), 'pdf', 'domPdf');
    }

    /**
    * Sales report by date
    */

    public function salesReportByDate(Request $request)
    {
        $data['menu']     = 'report';
        $data['sub_menu'] = 'report/sales-report';
        $type             = isset($_GET['type']) && ($_GET['type'] != 'all') ? $_GET['type'] : null;
        $location         = isset($_GET['location']) && ($_GET['location'] != 'all') ? $_GET['location'] : null;
        $customer         = isset($_GET['customer']) && ($_GET['customer'] != 'all') ? $_GET['customer'] : null;
        $item             = isset($_GET['product']) && ($_GET['product'] != 'all') ? $_GET['product'] : null;
        $service          = isset($_GET['service']) && ($_GET['service'] != 'all') ? $_GET['service'] : null;
        $month            = isset($_GET['month']) && !empty($_GET['month']) ? $_GET['month'] : null;
        $year             = isset($_GET['year']) && !empty($_GET['year']) ? $_GET['year'] : null;
        $product          = !empty($service) ? $service : $item;
        $url = url()->current();
        $chkYearMonth = 'sale_report_filterwise';
        if (strpos($url, $chkYearMonth) == true) {
            $date             = null;
            $data['reportOn'] = date('F', $month) . '-' . $year;
        } else {
            $time             = isset($_GET['time']) && !empty($_GET['time']) ? $_GET['time'] : null;
            $data['date']     = date('d-m-Y', $time);
            $date             = $data['reportDate'] = DbDateFormat($data['date']);
        }
        $preference       = Preference::getAll()->pluck('value', 'field')->toArray();
        $currency         = isset($_GET['currency']) ? (int) $_GET['currency'] : (int) $preference['dflt_currency_id'];
        $data['saleData'] = $this->sale->getSalesReportByDate($date, $location, $customer, $product, $currency, $month, $year);
        $data['currency'] = Currency::where('id', $currency)->first();
        return view('admin.report.sales_report_by_date', $data);

    }

    public function getIncomeStat()
    {
        $data             = [];
        $total            = [];
        $results          = [];
        $months           = [];
        $incomeList       = [];

        $data['menu']     = 'report';
        $data['sub_menu'] = 'transaction/income-report';
        $data['page_title'] = __('Income Report');
        $data['header']   = 'report';
        $data['from']     = $from = isset($_GET['from']) ? $_GET['from'] : null;
        $data['to']       = $to   = isset($_GET['to'])   ? $_GET['to']   : null;
        $data['currencyList'] = Currency::getAll();
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['currency'] = $currency = isset($_GET['currency']) ? (int) $_GET['currency'] : $preference['dflt_currency_id'];

        if ((isset($_GET['from']) && !empty($_GET['from'])) && (isset($_GET['to']) && !empty($_GET['to']))) {
            $fromDate = explode('-', $_GET['from']);
            $toDate = explode('-', $_GET['to']);
            $from = date('Y-m-d', strtotime('01 '. $fromDate[0] .' '. $fromDate[1]));

            $to = date('Y-m-d', strtotime('01 '. $toDate[0] .' '. $toDate[1]));
            $lastday = date('t', strtotime($to));
            $to = date('Y-m-d', strtotime($lastday.' '. $toDate[0] .' '. $toDate[1]));
            if ($from >= $to) {
                return redirect()->back();
            }
        } else {
            $from = date('Y-m-01', strtotime("-1 year"));
            $to = date('Y-m-d');
        }

        $months  = getMonths($from, $to);
        $incomeList = $this->sale->getAllIncome($from, $to, $currency);
        if (!empty($incomeList)) {
            foreach ($incomeList as $key => $value) {
                foreach ($months as $k => $month) {
                    $results[$key][$month] = !empty($value[$month]) ? $value[$month] : 0;
                    $total[$key] += !empty($value[$month]) ? $value[$month] : 0;
                }
            }
        }

        $data['from']           = date('F-Y', strtotime($from));
        $data['to']             = date('F-Y', strtotime($to));
        $data['incomeList']     = $results;
        $data['categoryTotals'] = $total;
        $data['months']         = $months;
        $data['currencySymbol'] = Currency::find($currency)->symbol;
        $data['colors'] = ['#17a2b8', '#28a745', '#00A65A', '#F39C12', '#00C0EF', '#3C8DBC', '#E5FFFF', '#BCBE36', '#A261FA', '#4483F0', '#F0E69C', '#0059b6'];

        return view('admin.TransactionReport.income_report', $data);
    }

    /**
    * Sales report by date on csv
    */
    public function salesReportByDateCsv($date)
    {
        $itemList =  $this->report->getSalesReportByDate(date('Y-m-d', $date));
        $currency = Currency::getDefault();
        foreach ($itemList as $key => $item) {
            $profit = ($item->sale_price_excl_tax - $item->purchase_price_incl_tax);
            if ($item->purchase_price_incl_tax <= 0) {
                $profit_margin = 100;
            } else {
                $profit_margin = ($profit * 100) / $item->purchase_price_incl_tax;
            }

            $data[$key]['Order No'] = $item->reference;
            $data[$key]['Date'] = formatDate($item->ord_date);
            $data[$key]['Customer'] = $item->name;
            $data[$key]['Qty'] = $item->quantity;
            $data[$key]['Sales Value('. $currency->symbol .')'] = $item->sale_price_excl_tax;
            $data[$key]['Cost Value('. $currency->symbol .')'] = $item->purchase_price_incl_tax;
            $data[$key]['Tax('. $currency->symbol .')'] = $item->sale_price_incl_tax-$item->sale_price_excl_tax;
            $data[$key]['Profit('. $currency->symbol .')'] = $profit;
            $data[$key]['Profit Margin(%)'] = number_format(($profit_margin), 2, '.', ',');
        }

        return Excel::create('sales_report_by_date_'. time() .'', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
        })->download();

    }


    public function generatePurchaseData()
    {
        $data = [];
        $data['menu'] = 'report';
        $data['sub_menu'] = 'purchase-report';
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

        $data['yearList'] = $this->purchase->getPurchaseYears();
        $data['supplierList'] = DB::table('suppliers')->select('id', 'name')->where(['is_active'=>1])->get();
        $data['locationList'] = DB::table('locations')->select('id', 'name')->get();
        $data['currencyList'] = Currency::getAll();
        $data['itemList'] = DB::table('items')->where(['is_active' => 1])->select('id', 'name')->get();

        if ($data['searchType'] == 'custom' || $data['searchType'] == 'daily') {
            $data['list'] = $this->purchase->getPurchaseReport($data['searchType'], $data['from'], $data['to'], $data['year'], $data['month'], $data['item'], $data['supplier'], $data['location'], $data['currency']);
        } else if ($data['searchType'] == 'yearly') {
            if ($data['year'] == null) {
                $data['list'] = $this->purchase->getPurchaseReport($data['searchType'], $data['from'], $data['to'], $data['year'], $data['month'], $data['item'], $data['supplier'], $data['location'], $data['currency']);
                $data['year'] = 'all';
                $data['searchType'] = 'yearly';
                $data['month'] = NULL;
            } else if ($data['year'] !='all' AND $data['month'] == null) {
                $data['list'] = $this->purchase->getPurchaseReport($data['searchType'], $data['from'], $data['to'], $data['year'], $data['month'], $data['item'], $data['supplier'], $data['location'], $data['currency']);
                $data['searchType'] = 'yearly';

            } else if ($data['year'] != null AND $data['month'] != null) {
                $data['list'] = $this->purchase->getPurchaseReport($data['searchType'], $data['from'], $data['to'], $data['year'], $data['month'], $data['item'], $data['supplier'], $data['location'], $data['currency']);
                $data['searchType'] = 'yearly';
            }
        } else if ($data['searchType'] != 'yearly' && $data['searchType'] !='custom') {
            $data['list'] = $this->purchase->getPurchaseReport($data['searchType'], $data['from'], $data['to'], $data['year'], $data['month'], $data['item'], $data['supplier'], $data['location'], $data['currency']);
            $data['month'] = NULL;
        }

        return $data;
    }

    public function purchaseReport(PurchaseReportDataTable $dataTable)
    {
        $data = [];
        $graph = [];
        $qty = 0;
        $cost = 0;
        $order = 0;
        $data = $this->generatePurchaseData();
        $data['page_title'] = __('Purchase Report');
        $currency = $data['currency'];
        $type = $data['searchType'];
        $list = $data['list'];
        foreach ($list as $key => $value) {
            $qty        = $value['totalQuantity'];
            $cost       = $value['totalAmount'];
            $order      = $value['totalInvoice'];
            $orderDate  = $value['orderDate'];
            if ($type == 'monthly' || $type == 'yearly') {
                $graph[$key]['date'] = date('F-Y', strtotime($key));
            } else {
                $graph[$key]['date'] = date('d-m-Y', strtotime($key));
            }
            $graph[$key]['cost'] = $value['totalAmount'];
        }
        $data['filterCurrency'] = Currency::where('id', (int) $currency)->first();
        $data['cost'] = $cost;
        $data['qty'] = $qty;
        $data['order'] = $order;
        $data['graphData'] = $graph;
        $row_per_page = Preference::getAll()->where('field', 'row_per_page')->first()->value;
        return $dataTable->with('row_per_page', $row_per_page)->with('currency', $data['filterCurrency'])->render('admin.report.purchase_report', $data);
    }

    /**
    * Purchase report pdf
    */
    public function purchaseReportPdf()
    {
        $data = [];
        $data = $this->generatePurchaseData();
        $list = $data['list'];
        $currency = $data['currency'];
        $data['type'] = $data['searchType'];
        $currency = Currency::find($data['currency']);
        $data['currencyShortName'] = $currency->name;
        $data['date_range'] =  formatDate($data['from']) .' To '. formatDate($data['to']);
        if (isset($data['supplier']) && !empty($data['supplier'])) {
            $data['supplierDetails'] = Supplier::find($data['supplier'])->name;
        } else {
            $data['supplierDetails'] = null;
        }
        if (isset($data['location']) && !empty($data['location'])) {
            $data['locationDetails'] = Location::find($data['location'])->name;
        } else {
            $data['locationDetails'] = null;
        }
        if (isset($data['item']) && !empty($data['item'])) {
            $data['itemDetails'] = Item::find($data['item'])->name;
        } else {
            $data['itemDetails'] = null;
        }
        return printPDF($data, 'purchase_report_' . time() . '.pdf', 'admin.report.purchase_report_pdf', view('admin.report.purchase_report_pdf', $data), 'pdf', 'domPdf');
    }

    public function purchaseReportDateWise($time, Request $request)
    {
        $data['menu']     = 'report';
        $data['sub_menu'] = 'purchase-report';
        $data['date']     = $time;
        $date = $data['reportDate'] = DbDateFormat(date('d-m-Y', $time));
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $currency   = isset($_GET['currency']) && !empty($_GET['currency']) && $_GET['currency'] != 'all' ? $_GET['currency'] : $preference['dflt_currency_id'];
        $location   = isset($_GET['location']) && !empty($_GET['location']) && $_GET['location'] != 'all' ? $_GET['location'] : null;
        $supplier   = isset($_GET['supplier']) && !empty($_GET['supplier']) && $_GET['supplier'] != 'all' ? $_GET['supplier'] : null;
        $product    = isset($_GET['product']) && !empty($_GET['product']) && $_GET['product'] != 'all' ? $_GET['product'] : null;
        $data['purchData'] = $this->purchase->getPurchaseReportDateWise($date, $currency, $location, $supplier, $product);
        $data['filterCurrency'] = Currency::where('id', $currency)->first();
        $row_per_page = Preference::getAll()->where('field', 'row_per_page')->first()->value;
        return view('admin.report.purchase_report_datewise', $data);
    }

    /**
    * Purchase report pdf
    */
    public function purchaseReportCsv()
    {
        return Excel::download(new PurchaseReportExport(), 'purchase_report_'. time() .'.csv');
    }

    public function purchReportByDateCsv()
    {
       if (isset($_GET['reportDate'])) {
           $date = $_GET['reportDate'];
       }

       $date = DbDateFormat(date('d-m-Y', $date));
       $purchData = $this->purchase->getPurchaseReportDateWise($date);
       if (count($purchData) > 0) {
            $datas = [];
            foreach ($purchData as $key => $value) {
                $datas[$key]['Purchase No']     = $value->reference;
                $datas[$key]['Supplier Name']   = $value->supp_name;
                $datas[$key]['Quantity']        = $value->total_quantity;
                $datas[$key]['Total']           = number_format($value->total, 2, '.', ',');
                $datas[$key]['Tax']             = number_format(($value->total - $value->total_without_tax), 2, '.', ',');
            }
        } else {
            $datas = [0 => [
                   'Purchase No' => '',
                   'Supplier Name' => '',
                   'Quantity' => '',
                   'Total' => '',
                   'Tax' => ''
               ]
           ];
        }

        return Excel::create('purchase_list_filter_'. time() .'', function($excel) use ($datas) {
            $excel->sheet('mySheet', function($sheet) use ($datas)
            {
                $sheet->fromArray($datas);
            });
        })->download();
    }


    public function getCustomerByCurrency(Request $request)
    {
        $currency_id = $request->currency;
        $customers   = Customer::select('id', DB::raw("CONCAT(customers.first_name,' ',customers.last_name)  AS name"))->where(['is_active' => 1, 'currency_id' => $currency_id])->get();

           $customer = "<option value=''>" . __('All') . "</option>";
       foreach ($customers as $key => $value) {
           $customer .= "<option value='" . $value->id . "' >" . $value->name . "</option>";
       }

       $data['customers'] = $customer;
       $data['status'] = 1;
       return $data;
    }

    public function leadsReport()
    {
        $data = [];
        $months = [];
        $results = [];
        $leadList = [];
        $allLeadStatus = [];
        $leadBySource = [];
        $data['menu']     = 'report';
        $data['sub_menu'] = 'leads-report';
        $data['page_title'] = __('Leads Report');
        $data['header']   = 'report';
        $from = isset($_GET['from']) ? $_GET['from'] : null;
        $to   = isset($_GET['to'])   ? $_GET['to']   : null;

        if ((isset($from) && !empty($from)) && (isset($to) && !empty($to))) {
            $fromDate = explode('-', $_GET['from']);
            $toDate = explode('-', $_GET['to']);
            $from = date('Y-m-d', strtotime('01 '. $fromDate[0] .' '. $fromDate[1]));

            $to = date('Y-m-d', strtotime('01 '. $toDate[0] .' '. $toDate[1]));
            $lastday = date('t',strtotime($to));
            $to = date('Y-m-d', strtotime($lastday.' '. $toDate[0] .' '. $toDate[1]));
            if ($from >= $to) {
                return redirect()->back();
            }
        } else {
            $from = date('Y-m-01', strtotime("-1 year"));
            $to = date('Y-m-t');
        }

        $months     = getMonths($from, $to);
        $leadList   = (new Lead())->generateMonthlyLeadData($from, $to);
        $allLeadStatus = $this->leadStatus->where('status', 'active')->get(['name'])->pluck('name')->toArray();
        if (!empty($allLeadStatus)) {
            foreach ($allLeadStatus as $key => $value) {
                if (! array_key_exists($value, $leadList)) {
                    $leadList[$value] = 0;
                }
            }
        }
        if (!empty($leadList)) {
            foreach ($leadList as $key => $value) {
                foreach ($months as $k => $month) {
                    $results[$key][$month] = !empty($value[$month]) ? $value[$month] : 0;
                }
            }
        }
        $leadSource = $this->leadSource->where('status', 'active');
        $leadSource->with(['leads' => function($query) use($from, $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }]);

        $leadSourceStat = $leadSource->get();
        foreach ($leadSourceStat as $key => $value) {
            $data['totalLeadBySource'][$value->name] = ['count' => count($value->leads->toArray())];
            $leadBySource[$value->name] = count($value->leads->toArray());
        }

        $leadStatus = $this->leadStatus->where('status', 'active');
        $leadStatus->with(['leads' => function($query) use($from, $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }]);
        $leadStat = $leadStatus->get();
        foreach ($leadStat as $key => $value) {
            $data['totalLeadByStatus'][$value->name] = ['count' => count($value->leads->toArray()), 'color' => $value->color];
        }

        $data['monthlyLeads'] = $results;
        $data['from'] = $from;
        $data['to']   = $to;
        $data['months'] = $months;
        $data['colors'] = ['#DD4B39', '#4483F0', '#FFA09A', '#00A65A', '#F39C12', '#00C0EF', '#3C8DBC', '#E5FFFF', '#BCBE36', '#A261FA', '#F0E69C', '#0059b6'];
        return view('admin.report.leads_report', $data);
    }
}
