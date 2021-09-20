<?php

namespace App\Models;
use App\Http\Start\Helpers;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Preference;

class PurchaseOrder extends Model
{
    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier', 'supplier_id');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency', 'currency_id');
    }

    public function purchaseReceiveType()
    {
        return $this->belongsTo('App\Models\PurchaseReceiveType', 'purchase_receive_type_id');
    }

    public function location()
    {
        return $this->belongsTo('App\Models\Location', 'location_id');
    }

    public function paymentTerm()
    {
        return $this->belongsTo('App\Models\PaymentTerm', 'payment_term_id');
    }

    public function purchaseOrderDetails()
    {
        return $this->hasMany('App\Models\PurchaseOrderDetail', 'purchase_order_id');
    }

    public function receivedOrders()
    {
        return $this->hasMany('App\Models\ReceivedOrder', 'purchase_order_id');
    }

    public function receivedOrderDetails()
    {
        return $this->hasMany('App\Models\ReceivedOrderDetail', 'purchase_order_id');
    }

    public function supplierTransactions()
    {
        return $this->hasMany('App\Models\SupplierTransaction', 'purchase_order_id');
    }

    public function getAll($supplier, $currency, $location, $status, $from, $to)
    {
        $data = $this->select('purchase_orders.id as po_id', 'purchase_orders.location_id', 'purchase_orders.currency_id', 'purchase_orders.supplier_id', 'purchase_orders.total', 'purchase_orders.order_date', 'purchase_orders.reference', 'purchase_orders.paid')->with(['supplier:id,name', 'location:id,name', 'currency:id,name']);

        if ( !empty($from) && !empty($to) ) {
          $from = DbDateFormat($from);
          $to   = DbDateFormat($to);
          $data ->where('order_date', '>=', $from)
          ->where('order_date', '<=', $to);
        }

        if ( !empty($supplier) ) {
            $data->where('supplier_id','=',$supplier);
        }

        if ( !empty($currency) ) {
            $data->where('currency_id', '=', $currency);
        }

        if ( !empty($location) ) {
            $data->where('location_id', '=', $location);
        }

        if ($status == 'paid') {
            $data->whereColumn('paid', '>=', 'total');
        }

        if ($status == 'partial') {
            $data->whereColumn('paid', '<', 'total')->where('paid', '>', 0);
        }

        if ($status == 'unpaid') {
            $data->where('paid', 0)->where('total', '>', 0);
        }

        if (Helpers::has_permission(Auth::user()->id, 'own_purchase') && !Helpers::has_permission(Auth::user()->id, 'manage_purchase')) {
            $id = Auth::user()->id;
            $data->where('user_id', $id);
        }
        return $data;
    }

    public function getAllById($id, $from = null, $to = null)
    {
        $data= $this->leftJoin('suppliers', 'purchase_orders.supplier_id', '=', 'suppliers.id')
                    ->leftJoin('locations', 'purchase_orders.location_id', '=', 'locations.id')
                    ->leftJoin('currencies','purchase_orders.currency_id','=','currencies.id')
                    ->select('purchase_orders.*', 'suppliers.name','locations.name as loc_name','currencies.name as currency_name', 'currencies.symbol as symbol')
                    ->where('purchase_orders.supplier_id',$id);

        if($from && $to){
            $from = DbDateFormat($from);
            $to   = DbDateFormat($to);

            $data ->whereDate('order_date','>=',$from)
                ->whereDate('order_date','<=', $to);
        }
        return $data;
    }

    public function getPurchaseInvoiceByID($id)
    {
        $data = DB::table('purchase_order_details')
                ->where(['purchase_order_details.purchase_order_id' => $id])
                ->leftJoin('purchase_orders', 'purchase_orders.id', '=', 'purchase_order_details.purchase_order_id')
                ->leftJoin('items', 'purchase_order_details.item_id', '=', 'items.id')
                ->leftJoin('purchase_taxes', 'purchase_taxes.purchase_order_detail_id', '=', 'purchase_order_details.id')
                ->leftjoin('tax_types','tax_types.id','=','purchase_taxes.tax_type_id')
                ->leftJoin('purchase_prices', 'purchase_order_details.item_id', '=', 'purchase_prices.item_id')
                ->select('purchase_order_details.*', 'tax_types.tax_rate', 'tax_types.id as tax_id', 'purchase_prices.id as purchase_price_id', 'items.is_stock_managed')
                ->get();
        return $data;
    }

    public function getSalseInvoicePdf($id)
    {
        return $this->where(['sales_orders.id'=>$id])

                    ->leftJoin('customers', 'sales_orders.customer_id', '=', 'customers.id')
                    ->leftJoin('purchase_order_details', 'sales_orders.id', '=', 'purchase_order_details.purchase_order_id')
                    ->select('sales_orders.*', 'customers.*')
                    ->first();
    }

    public function calculateTaxRows($id)
    {
        $purchaseOrder = $this->with('purchaseOrderDetails:id,purchase_order_id,unit_price,quantity_ordered')->find($id);
        $taxTypeName = $taxTypes = $array = [];
        $taxAmount = '';
        $preference = Preference::getAll()->where('category', 'preference')->whereIn('field', ['symbol_position', 'decimal_digits', 'thousand_separator'])->pluck('value', 'field')->toArray();
        foreach ($purchaseOrder->purchaseOrderDetails as $purchaseOrderDetails) {
            if ($purchaseOrderDetails->purchaseTaxes) {
                foreach ($purchaseOrderDetails->purchaseTaxes as $key => $value) {
                    if (!in_array($value->taxType->name, $taxTypeName))
                    {
                        $taxTypeName[] = $value->taxType->name;
                        $taxTypes[$value->taxType->name]['name']   = $value->taxType->name;
                        $taxTypes[$value->taxType->name]['rate']   = $value->taxType->tax_rate;
                        $taxTypes[$value->taxType->name]['amount'] = 0;
                    }
                    $array = explode('.', $value->tax_amount);
                    $taxAmount = substr($value->tax_amount, 0, (strlen($array[0]) + 1 + $preference['decimal_digits']));
                    $taxTypes[$value->taxType->name]['amount'] += $taxAmount;
                }
            }
        }
        return $taxTypes;
    }

    public function getAllPurchOrderByUserId($from, $to, $supplier, $location, $sid, $status)
    {

        $data = $this->leftJoin('suppliers', 'purchase_orders.supplier_id', '=', 'suppliers.id')
        ->leftJoin('currencies','purchase_orders.currency_id','=','currencies.id')
        ->leftJoin('locations', 'purchase_orders.location_id', '=', 'locations.id')
        ->select('purchase_orders.*', 'suppliers.name','locations.name as loc_name','currencies.name as currency_name', 'currencies.symbol as symbol')
        ->where('purchase_orders.user_id',$sid);

        if(!empty($from) && !empty($to)) {
            $from = DbDateFormat($from);
            $to   = DbDateFormat($to);

          $data->where('purchase_orders.order_date', '>=', $from)
                ->where('purchase_orders.order_date', '<=', $to);
        }
        if(!empty($supplier)) {
             $data->where('purchase_orders.supplier_id','=', $supplier);
        }
        if(!empty($location)) {
            $data->where('purchase_orders.location_id','=', $location);
        }
        if (!empty($status) && $status == 'paid') {
            $data->whereRaw('paid >= total');
        }

        if (!empty($status) && $status == 'partial') {
            $data->whereRaw('paid < total AND paid > 0');
        }

        if (!empty($status) && $status == 'unpaid') {
            $data->whereRaw('paid = 0 AND total > 0');
        }

        return $data;
    }

    /**
     * getPurchaseReport method
     * @param  [string] $from
     * @param  [string] $to
     * @return object
    */
    public function getPurchaseReport($type = null, $from = null, $to = null, $year = null, $month = null, $item = null, $supplier = null, $location = null, $currency)
    {
        $from = DbDateFormat($from);
        $to = DbDateFormat($to);
        $conditions = [];
        if (isset($location) && ! empty($location)) {
            $conditions['purchase_orders.location_id'] = $location;
        }
        if (!empty($currency) && $currency != 0) {
            $conditions['purchase_orders.currency_id'] = $currency;
        }
        if (isset($supplier) && ! empty($supplier)) {
            $conditions['purchase_orders.supplier_id'] = $_GET['supplier'];
        }
        $data = $this->with(['purchaseOrderDetails:id,item_id,item_name,unit_price,purchase_order_id,quantity_ordered,discount_amount'])->where($conditions);
        if (! isset($type) && empty($type)) {
           $type = '';
        }
        switch ($type) {
            case 'monthly':
                $data->orderBy('purchase_orders.order_date', 'desc');
                break;
            case 'yearly':
                if ($_GET['year'] == 'all') {
                    $data->orderBy('purchase_orders.order_date', 'desc');
                } else {
                    if ($_GET['month'] != 'all') {
                        $data->whereMonth('purchase_orders.order_date', '=' , $_GET['month'])->whereYear('purchase_orders.order_date', '=' , $_GET['year'])->orderBy('purchase_orders.order_date', 'desc');
                    } else {
                        $data->whereYear('purchase_orders.order_date', '=' , $_GET['year'])->orderBy('purchase_orders.order_date', 'desc');
                    }
                }
                break;
            case 'custom':
                $data->whereBetween('purchase_orders.order_date', [$from, $to]);
                break;
            default:
                $data->orderBy('purchase_orders.order_date');
                break;
        }

        $finalData  = [];
        $data       = $data->get();
        foreach ($data as $datakey => $order) {
            foreach ($order->purchaseOrderDetails as $key => $value) {
                $order->purchaseOrderDetails[$key]->totalTax =  $value->purchaseTaxes->sum('tax_amount');
            }
        }
        $data = $data->toArray();
        if (isset($_GET['product']) && ! empty($_GET['product']) && $_GET['product'] != 'all') {
            $item_id = (int) $_GET['product'];
            for ($i=0; $i<count($data); $i++) {
                for ($j=0; $j<count($data[$i]['purchase_order_details']); $j++){
                    if ($data[$i]['purchase_order_details'][$j]['item_id'] == $item_id) {
                        $finalData[] = $data[$i];
                    }
                }
            }
        }

        $data         = !empty($finalData) ? $finalData : $data;
        $totalCnt     = count($data);
        $filterArray  = array_values(array_unique(array_column($data, 'order_date')));
        $totalDateCnt = count($filterArray);
        $purchaseData = [];
        for ($i=0; $i < $totalCnt; $i++) {
                for ($j=0; $j < $totalDateCnt; $j++) {
                    if ($data[$i]['order_date'] == $filterArray[$j]) {
                        $purchaseData[$data[$i]['order_date']]['total'][] = $data[$i]['total'];
                        for($k=0;$k < count($data[$i]['purchase_order_details']); $k++) {
                            $purchaseData[$data[$i]['order_date']]['quantity_ordered'][] = $data[$i]['purchase_order_details'][$k]['quantity_ordered'];
                            $purchaseData[$data[$i]['order_date']]['totalTax'][] = $data[$i]['purchase_order_details'][$k]['totalTax'];

                            if ($data[$i]['has_item_discount'] == 1) {
                                $purchaseData[$data[$i]['order_date']]['itemDiscountAmount'][] = $data[$i]['purchase_order_details'][$k]['discount_amount'];
                            }

                            if ($data[$i]['has_other_discount'] == 1 && $data[$i]['other_discount_type'] != '%') {
                                if ($k == count($data[$i]['purchase_order_details']) - 2) {
                                    $purchaseData[$data[$i]['order_date']]['otherDiscountAmount'][] = $data[$i]['other_discount_amount'];
                                }
                            } else {
                                $purchaseData[$data[$i]['order_date']]['otherDiscountAmount'][] = ($data[$i]['purchase_order_details'][$k]['unit_price'] * $data[$i]['purchase_order_details'][$k]['quantity_ordered']) * ($data[$i]['other_discount_amount'] / 100);
                            }

                        }
                    }
                    $purchaseData[$data[$i]['order_date']]['currency_id'] = $data[$i]['currency_id'];
                }
        }

        $customData = [];
        foreach ($purchaseData as $key => $value) {
            $customData[$key]['totalAmount']            = !empty($value['total']) ? array_sum($value['total']) : 0;
            $customData[$key]['totalPurchaseTax']       = !empty($value['totalTax']) ? array_sum($value['totalTax']) : 0;
            $customData[$key]['itemDiscountAmount']     = !empty($value['itemDiscountAmount']) ? array_sum($value['itemDiscountAmount']) : 0;
            $customData[$key]['otherDiscountAmount']    = !empty($value['otherDiscountAmount']) ? array_sum($value['otherDiscountAmount']) : 0;
            $customData[$key]['totalInvoice']           = count($value['total']);
            $customData[$key]['totalQuantity']          = !empty($value['quantity_ordered']) ? array_sum($value['quantity_ordered']) : '';
            $customData[$key]['orderDate']              = $key;
            $customData[$key]['filterCurrency']         = $value['currency_id'];

        }

        $result = array();
        switch ($type) {
            case 'monthly':
                $result = $this->generateMonthData($customData);
                break;
            case 'yearly':
                if ($_GET['month'] == 'all') {
                    $result = $this->generateMonthData($customData);
                } else {
                    $result = $customData;
                }
                $result = $this->generateMonthData($customData);
                break;
            default:
                $result = $customData;
                break;
        }

        return $result;
    }

    /**
     * [generateMonthData description]
     * @param  [array] $customData [description]
     * @return [array]             [description]
     */
    public function generateMonthData($customData)
    {
        $monthData     = [];
        $result        = [];
        $totalAmount   = 0;
        $totalInvoice  = 0;
        $totalQuantity = 0;
        $totalTax      = 0;
        $itemDiscountAmount  = 0;
        $otherDiscountAmount = 0;
        foreach ($customData as $key => $value) {
            if (array_key_exists(date('M-Y', strtotime($key)), $result)) {
                $result[date('M-Y', strtotime($key))][] = $value;
            } else {
                $result[date('M-Y', strtotime($key))][] = $value;
            }
        }

        foreach ($result as $key => $value) {
           for ($i = 0; $i < count($value); $i++) {
                $totalAmount += $value[$i]['totalAmount'];
                $totalInvoice += $value[$i]['totalInvoice'];
                $totalQuantity += $value[$i]['totalQuantity'];
                $totalTax += $value[$i]['totalPurchaseTax'];
                $itemDiscountAmount += $value[$i]['itemDiscountAmount'];
                $otherDiscountAmount += $value[$i]['otherDiscountAmount'];
           }
           $monthData[$key]['totalAmount']          = $totalAmount;
           $monthData[$key]['totalInvoice']         = $totalInvoice;
           $monthData[$key]['totalQuantity']        = $totalQuantity;
           $monthData[$key]['totalPurchaseTax']     = $totalTax;
           $monthData[$key]['itemDiscountAmount']   = $itemDiscountAmount;
           $monthData[$key]['otherDiscountAmount']  = $otherDiscountAmount;
           $monthData[$key]['filterCurrency']       = $value[0]['filterCurrency'];
           $monthData[$key]['orderDate']            = $value[0]['orderDate'];
           $totalAmount   = 0;
           $totalInvoice  = 0;
           $totalQuantity = 0;
           $totalTax      = 0;
           $itemDiscountAmount  = 0;
           $otherDiscountAmount = 0;
        }
        return $monthData;
    }

    public function getPurchaseReportDateWise($date, $currency, $location, $supplier, $product)
    {
        if (isset($product)) {
            $detailIds = $this->with(['purchaseOrderDetails' => function ($query) use ($product) {
                $query->whereIn('item_id', [$product])->select('id');
            }])->pluck('id')->toArray();
            $data = $this->with(['supplier:id,name', 'purchaseOrderDetails:id,purchase_order_id,unit_price,quantity_ordered,discount_amount', 'purchaseOrderDetails.totalTax'])->whereIn('id', $detailIds);
        } else {
            $data = $this->with(['supplier:id,name', 'purchaseOrderDetails:id,purchase_order_id,unit_price,quantity_ordered,discount_amount', 'purchaseOrderDetails.totalTax']);
        }
        if (isset($date)) {
            $data->whereDate('order_date', $date);
        }
        if (isset($location)) {
            $data->where(['location_id' => $location]);
        }
        if (isset($supplier)) {
            $data->where(['supplier_id' => $supplier]);
        }
        $data->where('currency_id', $currency);
        $data = $data->get();
        foreach ($data as $key => $value) {
            foreach ($value->purchaseOrderDetails as $index => $detail) {
                $detail->total_tax = isset($detail->totalTax->total_tax) ? $detail->totalTax->total_tax : 0;
            }
        }
        return $data;
    }

    public function getPurchaseYears(){
        $data = DB::select("SELECT DISTINCT YEAR(order_date ) as year FROM purchase_orders ORDER BY order_date  DESC");
        return $data;
    }

    public function getPurchFilteringOrderById($from, $to, $id)
    {
        $from = ($from == "" || $from == null) ? DbDateFormat($from) : $from;
        $to = ($to == "" || $to == null) ? DbDateFormat(date('Y-m-d')) : $to;

        $data =  DB::table('purchase_orders')
            ->leftJoin('suppliers', 'purchase_orders.supplier_id', '=', 'suppliers.id')
            ->leftJoin('locations', 'purchase_orders.location_id', '=', 'locations.id')
            ->leftJoin('currencies','purchase_orders.currency_id','=','currencies.id')
            ->select('purchase_orders.*', 'suppliers.name','locations.name as loc_name','currencies.name as currency_name', 'currencies.symbol as symbol')
            ->where('purchase_orders.supplier_id', $id);
            if($from && $to){
              $data->whereDate('order_date','>=',DbDateFormat($from));
              $data->whereDate('order_date','<=',DbDateFormat($to));
            }
        $data = $data->orderBy('reference', 'desc')
            ->get();
        return $data;
    }

    /**
     * [getPurchaseReportFilterWise description]
     * @param  [int] $currency [description]
     * @param  [int] $location [description]
     * @param  [int] $supplier [description]
     * @param  [int] $product  [description]
     * @param  [int] $month    [description]
     * @param  [int] $year     [description]
     * @return [array]         [description]
     */
    public function getPurchaseReportFilterWise($currency, $location, $supplier, $product, $month, $year)
    {
        if (isset($product)) {
            $detailIds = $this->with(['purchaseOrderDetails' => function ($query) use ($product) {
                $query->whereIn('item_id', [$product])->select('id');
            }])->pluck('id')->toArray();
            $data = $this->with(['supplier:id,name', 'purchaseOrderDetails:id,purchase_order_id,quantity_ordered', 'purchaseOrderDetails.totalTax'])->whereIn('id', $detailIds);
        } else {
            $data = $this->with(['supplier:id,name', 'purchaseOrderDetails:id,purchase_order_id,quantity_ordered', 'purchaseOrderDetails.totalTax']);
        }
        if (isset($month)) {
            $data->whereMonth('order_date', $month);
        }
        if (isset($month)) {
            $data->whereYear('order_date', $year);
        }
        if (isset($location)) {
            $data->where(['location_id' => $location]);
        }
        if (isset($supplier)) {
            $data->where(['supplier_id' => $supplier]);
        }
        $data->where('currency_id', $currency);
        $data = $data->get();
        foreach ($data as $key => $value) {
            if (!empty($value->purchaseOrderDetails)) {
                foreach ($value->purchaseOrderDetails as $index => $detail) {
                    if (isset($detail->totalTax->total_tax)) {
                        $detail['total_tax'] = $detail->totalTax->total_tax;
                    }
                }
            }
        }
        return $data;
    }
}
