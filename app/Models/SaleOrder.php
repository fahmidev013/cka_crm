<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Start\Helpers;
use App\Models\SaleOrderDetail;
use DB;
use Auth;

class SaleOrder extends Model
{
    // Relations
    public function saleOrderDetails()
    {
    	return $this->hasMany('App\Models\SaleOrderDetail', 'sale_order_id');
    }

    public function customerTransactions()
    {
       return $this->hasMany(CustomerTransaction::class, 'sale_order_id');
    }

    public function project()
    {
        return $this->belongsTo('App\Models\Project', 'project_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    public function customerBranch()
    {
        return $this->belongsTo('App\Models\CustomerBranch', 'customer_branch_id');
    }

    public function user()
    {
    	return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function location()
    {
    	return $this->belongsTo('App\Models\Location', 'location_id');
    }

    public function currency()
    {
    	return $this->belongsTo('App\Models\Currency', 'currency_id');
    }

    public function paymentTerm()
    {
    	return $this->belongsTo('App\Models\PaymentTerm', 'payment_term_id');
    }

    public function stockMoves()
    {
        return $this->hasMany('App\Models\StockMove', 'transaction_type_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\SaleOrder', 'id', 'order_reference_id');
    }

    public function checkConversion($id)
    {
    	return $this->where('order_reference_id', $id)->select('reference', 'order_reference_id');
    }

    public function store($request, $transaction_type, $order_type, $reference, $order_reference_id = 0)
    {
        $paymentTerm = PaymentTerm::find($request->payment_term_id);
        $due_date = date("Y-m-d", strtotime(DbDateFormat($request->order_date).", +$paymentTerm->days_before_due days"));
        $saleOrder                      = new SaleOrder();
        $saleOrder->transaction_type    = $transaction_type;
        $saleOrder->invoice_type        = $request->invoice_type;
        $saleOrder->order_type          = $order_type;
        $saleOrder->project_id          = isset($request->project_id) ? $request->project_id : null;
        $saleOrder->customer_id         = $request->customer_id;
        $saleOrder->customer_branch_id  = $request->customer_branch_id;
        $saleOrder->user_id             = Auth::user()->id;
        $saleOrder->tax_type            = $request->tax_type;
        $saleOrder->reference           = $reference;
        $saleOrder->order_reference_id  = $order_reference_id;
        $saleOrder->comment             = stripBeforeSave($request->comment);
        $saleOrder->has_comment         = $request->has_comment == 'on' ? 1 : 0;
        $saleOrder->order_date          = DbDateFormat($request->order_date);
        $saleOrder->due_date            = $due_date;
        $saleOrder->location_id         = $request->location_id;
        $saleOrder->discount_on         = $request->discount_on;
        $saleOrder->currency_id         = $request->currency_id;
        $saleOrder->exchange_rate       = !empty($request->exchange_rate) ? validateNumbers($request->exchange_rate) : 1;
        $saleOrder->has_tax             = $request->has_tax == 'on' ? 1 : 0;
        $saleOrder->has_description     = $request->has_description == 'on' ? 1 : 0 ;
        $saleOrder->has_item_discount   = $request->has_item_discount == 'on' ? 1 : 0 ;
        $saleOrder->has_hsn             = $request->has_hsn == 'on' ? 1 : 0 ;
        $saleOrder->has_other_discount  = $request->has_other_discount == 'on' ? 1 : 0 ;
        $saleOrder->has_shipping_charge = $request->has_shipping_charge == 'on' ? 1 : 0 ;
        $saleOrder->has_custom_charge   = $request->has_custom_charge == 'on' ? 1 : 0 ;
        $saleOrder->other_discount_type = $request->has_other_discount == 'on' ? $request->other_discount_type : '%';
        $saleOrder->other_discount_amount = $request->has_other_discount == 'on' ? validateNumbers($request->other_discount_amount) : 0;
        $saleOrder->total               = validateNumbers($request->totalValue);
        $saleOrder->amount_received     = isset($request->amount_received) ? validateNumbers($request->amount_received) : null;
        $saleOrder->shipping_charge     = $request->has_shipping_charge == 'on' ? validateNumbers($request->shipping_charge) : 0;
        $saleOrder->custom_charge_title =  $request->has_custom_charge == 'on' ? $request->custom_charge_title : "";
        $saleOrder->custom_charge_amount= $request->has_custom_charge == 'on' ? validateNumbers($request->custom_charge_amount) : 0;
        $saleOrder->paid                = $request->paid ? validateNumbers($request->paid) : 0;
        $saleOrder->payment_term_id     = $request->payment_term_id;
        if (isset($request->pos_invoice_status)) {
            $saleOrder->pos_invoice_status  =  $request->pos_invoice_status;
        }
        if (isset($request->pos_order_title)) {
            $saleOrder->pos_order_title  =  $request->pos_order_title;
        }
        if (isset($request->pos_shipping)) {
            $saleOrder->pos_shipping  =  $request->pos_shipping;
        }
        $saleOrder->created_at          = date('Y-m-d H:i:s');
        $saleOrder->save();
        return $saleOrder;
    }

    public function updateOrder($request, $id)
    {
        $paymentTerm = PaymentTerm::find($request->payment_term_id);
        $due_date = date("Y-m-d", strtotime(DbDateFormat($request->order_date).", +$paymentTerm->days_before_due days"));
        $saleOrder                      = $this->find($id);
        $saleOrder->project_id          = isset($request->project_id) ? $request->project_id : null;
        $saleOrder->user_id             = Auth::user()->id;
        $saleOrder->tax_type            = $request->tax_type;
        $saleOrder->comment             = stripBeforeSave($request->comment);
        $saleOrder->has_comment         = $request->has_comment == 'on' ? 1 : 0 ;
        $saleOrder->order_date          = DbDateFormat($request->order_date);
        $saleOrder->due_date            = $due_date;
        $saleOrder->location_id         = $request->location_id;
        $saleOrder->discount_on         = $request->discount_on;
        $saleOrder->currency_id         = $request->currency_id;
        $saleOrder->exchange_rate       = !empty($request->exchange_rate) ? validateNumbers($request->exchange_rate) : 1;
        $saleOrder->has_tax             = $request->has_tax == 'on' ? 1 : 0;
        $saleOrder->has_description     = $request->has_description == 'on' ? 1 : 0 ;
        $saleOrder->has_item_discount   = $request->has_item_discount == 'on' ? 1 : 0 ;
        $saleOrder->has_hsn             = $request->has_hsn == 'on' ? 1 : 0 ;
        $saleOrder->has_other_discount  = $request->has_other_discount == 'on' ? 1 : 0 ;
        $saleOrder->has_shipping_charge = $request->has_shipping_charge == 'on' ? 1 : 0 ;
        $saleOrder->has_custom_charge   = $request->has_custom_charge == 'on' ? 1 : 0 ;
        $saleOrder->other_discount_type = $request->has_other_discount == 'on' ? $request->other_discount_type : '%';
        $saleOrder->other_discount_amount = $request->has_other_discount == 'on' ? validateNumbers($request->other_discount_amount) : 0;
        $saleOrder->total               = validateNumbers($request->totalValue);
        $saleOrder->shipping_charge     = $request->has_shipping_charge == 'on' ? validateNumbers($request->shipping_charge) : 0;
        $saleOrder->custom_charge_title =  $request->has_custom_charge == 'on' ? $request->custom_charge_title : "";
        $saleOrder->custom_charge_amount= $request->has_custom_charge == 'on' ? validateNumbers($request->custom_charge_amount) : 0;
        $saleOrder->payment_term_id     = $request->payment_term_id;
        $saleOrder->updated_at          = date('Y-m-d H:i:s');
        $saleOrder->save();
        return $saleOrder;
    }

    /* Start quotation */

    public function getQuotationStat($from = null, $to = null, $currency)
    {
		$arr = $results1 = $results2 = $quotationStat = $totalQuotation = $totalInvoice = [];

        if (!empty($from) && !empty($to)) {
          $from = DbDateFormat($from);
          $to = DbDateFormat($to);
          $results1 = DB::select(DB::raw("SELECT COUNT('sale_orders.id') as totalQuotation FROM sale_orders WHERE `sale_orders`.`order_date` BETWEEN '" . $from . "' AND '" . $to . "' AND `sale_orders`.`currency_id` = '" . $currency . "' "));
            $results2 = DB::select(DB::raw("SELECT COUNT('sale_orders.id') as totalInvoice FROM sale_orders WHERE `sale_orders`.`order_date` BETWEEN '" . $from . "' AND '" . $to . "' AND `sale_orders`.`order_reference_id` != 0 AND `sale_orders`.`currency_id` = '" . $currency . "' "));
        } else {
            $results1 = DB::select(DB::raw("SELECT COUNT('sale_orders.id') as totalQuotation FROM sale_orders WHERE `sale_orders`.`currency_id` = '" . $currency . "' "));
            $results2 = DB::select(DB::raw("SELECT COUNT('sale_orders.id') as totalInvoice FROM sale_orders WHERE `sale_orders`.`order_reference_id` != 0 AND `sale_orders`.`currency_id` = '" . $currency . "' "));
        }
		$totalQuotation = array_column($results1, 'totalQuotation');
		$totalInvoice = array_column($results2, 'totalInvoice');
		$arr['totalQuotation'] = $totalQuotation[0];
		$arr['totalInvoice'] = $totalInvoice[0];
		$arr['notInvoices'] = $totalQuotation[0] - $totalInvoice[0];

		return $arr;
    }

    public function getAllQuotation($from, $to, $location, $customer, $currency, $user_id = null)
    {
        $saleOrder = SaleOrder::select('sale_orders.id', 'sale_orders.id as so_id', 'sale_orders.location_id', 'sale_orders.currency_id', 'sale_orders.customer_id', 'sale_orders.total', 'sale_orders.order_date', 'sale_orders.reference')->with(['customer:id,first_name,last_name,name', 'currency:id,name,symbol', 'saleOrderDetails:id,sale_order_id,quantity', 'location:id,name', 'parent:order_reference_id,id'])->where([ 'transaction_type' => 'SALESORDER', 'order_type'=>'Direct Order']);
        if (!empty($from) && !empty($to)) {
            $from = DbDateFormat($from);
            $to = DbDateFormat($to);
            $saleOrder->where('order_date', '>=', $from)
                        ->where('order_date', '<=', $to);
        }
        if (!empty($location)) {
            $saleOrder->where('location_id', '=', $location);
        }
        if (!empty($customer)) {
            $saleOrder->where('customer_id', '=', $customer);
        }
        if (!empty($currency)) {
            $saleOrder->where('currency_id', '=', $currency);
        }
        if (Helpers::has_permission(Auth::user()->id, 'own_quotation') && !Helpers::has_permission(Auth::user()->id, 'manage_quotation')) {
            $saleOrder->where('user_id', '=', Auth::user()->id);
        }
        if (!empty($user_id)) {
            $saleOrder->where('user_id', $user_id);
        }
        return $saleOrder;
    }

    /* End quotation */

    /* Start invoice */

    public function getMoneyStatus($options = [])
    {
        $conditions = [];
        if (isset($options['customer_id']) && !empty($options['customer_id'])) {
            $conditions['customer_id'] = $options['customer_id'];
        }

        if (isset($options['location']) && !empty($options['location'])) {
            $conditions['location_id'] = $options['location'];
        }

        if (isset($options['currency']) && !empty($options['currency'])) {
            $conditions['currency_id'] = $options['currency'];
        }

        if (isset($options['user_id'])) {
            $conditions['user_id'] = $options['user_id'];
            if (isset($_GET['customer']) && !empty($_GET['customer'])) {
                $conditions['customer_id'] = $_GET['customer'];
            }
        }

        if (isset($options['transaction_type']) && !empty($options['transaction_type'])) {
            $conditions['transaction_type'] = $options['transaction_type'];
        }

        if (isset($options['project_id']) && !empty($options['project_id'])) {
            $conditions['project_id'] = $options['project_id'];
        }

        if (\Request::segment(1) == 'invoice') {
            if (isset($_GET['customer']) && !empty($_GET['customer'])) {
                $conditions['customer_id'] = $_GET['customer'];
            }
        }
        $amounts = $this->where($conditions)->selectRaw('currency_id, sum(total) as totalInvoice, sum(paid) as totalPaid');

        $overDues = $this->where($conditions)
                            ->leftJoin('payment_terms', 'payment_terms.id', '=', 'sale_orders.payment_term_id')
                            ->where('payment_terms.days_before_due', '>', 0)
                            ->whereRaw('paid < total AND due_date < CURDATE() AND total > 0')
                            ->selectRaw('currency_id, sum(total) as totalAmount, sum(paid) as totalPaid');
        if (isset($options['from']) && ! empty($options['from'])) {
            $amounts->whereDate('order_date', '>=', DbDateFormat($options['from']));
            $overDues->whereDate('order_date', '>=', DbDateFormat($options['from']));
        }
        if (isset($options['to']) && ! empty($options['to'])) {
            $amounts->whereDate('order_date', '<=', DbDateFormat($options['to']));
            $overDues->whereDate('order_date', '<=', DbDateFormat($options['to']));
        }


        if (!empty($options['status']) && $options['status'] == 'paid') {
            $amounts->whereColumn('paid', '>=', 'total');
            $overDues->whereColumn('paid', '>=', 'total');
        } else if (!empty($options['status']) && $options['status'] == 'partial') {
            $amounts->whereColumn('paid', '<', 'total')->where('paid', '>', 0);
            $overDues->whereColumn('paid', '<', 'total')->where('paid', '>', 0);
        } else if (!empty($options['status']) && $options['status'] == 'unpaid') {
            $amounts->where('paid', 0)->where('total', '>', 0);
            $overDues->where('paid', 0)->where('total', '>', 0);
        }
        $data['amounts'] = $amounts->wherein('transaction_type', ['SALESINVOICE', 'POSINVOICE'])->where('pos_invoice_status', 'clear')->groupBy('currency_id')->get(['currency_id', 'totalInvoice', 'totalPaid']);
        $data['overDue'] = $overDues->wherein('transaction_type', ['SALESINVOICE', 'POSINVOICE'])->where('pos_invoice_status', 'clear')->groupBy('currency_id')->get(['currency_id', 'totalAmount', 'totalPaid']);
        return $data;
    }

    public function getAllInvoices($from, $to, $customer, $location, $currency, $status, $user_id = null, $flag = null)
    {
        $data = $this->select('sale_orders.id as so_id', 'sale_orders.customer_id', 'sale_orders.location_id', 'sale_orders.total', 'sale_orders.paid', 'sale_orders.reference', 'sale_orders.order_date', 'sale_orders.project_id', 'sale_orders.currency_id', 'sale_orders.created_at', 'sale_orders.transaction_type')->with(['customer' => function ($query)
        {
            $query->select('id', 'name', 'currency_id');
        }, 'location:id,name', 'currency:id,name,symbol'])
                ->whereIn('transaction_type', ['SALESINVOICE', 'POSINVOICE'])
                ->where('pos_invoice_status', 'clear');
        if(!empty($from) && !empty($to)) {
            $from = DbDateFormat($from);
            $to   = DbDateFormat($to);
            $data->where('order_date','>=',$from)
                ->where('order_date','<=',$to);
        }
        if (!empty($customer) && $customer != 'all') {
            $data->where(['customer_id' => $customer]);
        }
        if (!empty($location) && $location != 'all') {
            $data->where(['location_id' => $location]);
        }
        if (!empty($currency)) {
            $data->where(['currency_id' => $currency]);
        }
        if (!empty($status) && $status == 'paid') {
            $data->whereColumn('paid', '>=', 'total');
        } else if (!empty($status) && $status == 'partial') {
            $data->whereColumn('paid', '<', 'total')->where('paid', '>', 0);
        } else if (!empty($status) && $status == 'unpaid') {
            $data->where('paid', 0)->where('total', '>', 0);
        }
        if (!empty($user_id)) {
            $data->where('user_id', $user_id);
        }
        if (empty($flag)) {
            if (Helpers::has_permission(Auth::user()->id, 'own_invoice') && !Helpers::has_permission(Auth::user()->id, 'manage_invoice')) {
                $data->where('user_id', '=', Auth::user()->id);
            }
        }
        return $data;
    }
    /* End invoice */

    public function calculateTaxRows($id)
    {
        $saleOrder = $this->with('saleOrderDetails:id,sale_order_id,unit_price,quantity')->find($id);
        $taxTypeName = $taxTypes = $array = [];
        $taxAmount = '';
        foreach ($saleOrder->saleOrderDetails as $saleOrderDetails) {
            if ($saleOrderDetails->saleTaxes) {
                foreach ($saleOrderDetails->saleTaxes as $key => $value) {
                    if (!in_array($value->taxType->name, $taxTypeName))
                    {
                        $taxTypeName[] = $value->taxType->name;
                        $taxTypes[$value->taxType->name]['name']   = $value->taxType->name;
                        $taxTypes[$value->taxType->name]['rate']   = $value->taxType->tax_rate;
                        $taxTypes[$value->taxType->name]['amount'] = 0;
                    }
                    $preference = Preference::getAll()->where('category', 'preference')->whereIn('field', ['symbol_position', 'decimal_digits', 'thousand_separator'])->pluck('value', 'field')->toArray();
                    $array = explode('.', $value->tax_amount);
                    $taxAmount = substr($value->tax_amount, 0, (strlen($array[0]) + 1 + $preference['decimal_digits']));
                    $taxTypes[$value->taxType->name]['amount'] += $taxAmount;
                }
            }
        }
        return $taxTypes;
    }

    public function getAllSalseOrderFilteringByCustomer($from, $to, $id)
    {
        if ((!empty($from)) && (!empty($to))) {
            $from = DbDateFormat($from);
            $to = DbDateFormat($to);
            $data = DB::table('sale_orders')
                ->select('sale_orders.*', 'sale_order_details.quantity as quantity', DB::raw('(SELECT SUM(sale_order_details.quantity) FROM sale_order_details where sale_order_details.sale_order_id = sale_orders.id ) as total_quantity'))
                ->leftJoin('sale_order_details', 'sale_orders.id', '=', 'sale_order_details.sale_order_id')
                ->leftJoin('currencies','sale_orders.currency_id','=','currencies.id')
                ->where('sale_orders.customer_id', $id)
                ->where('sale_orders.transaction_type', '=', 'SALESORDER')
                ->where('sale_orders.invoice_type', '=', 'Direct Order')
                ->whereDate('sale_orders.ord_date', '>=', $from)
                ->whereDate('sale_orders.ord_date', '<=', $to)
                ->orderBy('sale_orders.reference', 'desc')
                ->addSelect('currencies.name as currency_name')
                ->groupBy('id');
        } else {
            $data = DB::table('sale_orders')
                ->select('sale_orders.*', 'sale_order_details.quantity as quantity', DB::raw('(SELECT SUM(sale_order_details.quantity) FROM sale_order_details where sale_order_details.sale_order_id = sale_orders.id ) as total_quantity'))
                ->leftJoin('sale_order_details', 'sale_orders.id', '=', 'sale_order_details.sale_order_id')
                ->leftJoin('currencies','sale_orders.currency_id','=','currencies.id')
                ->where('sale_orders.customer_id', $id)
                ->where('sale_orders.transaction_type', '=', 'SALESORDER')
                ->where('sale_orders.invoice_type', '=', 'Direct Order')
                ->orderBy('sale_orders.reference', 'desc')
                ->addSelect('currencies.name as currency_name')
                ->groupBy('id');
        }

        return $data;
    }

    public function getAllSalseOrder($from, $to, $customer, $location)
    {
      $data = $this->leftJoin('customers', 'sale_orders.customer_id', '=', 'customers.id')
        ->leftJoin('currencies', 'sale_orders.currency_id','=', 'currencies.id')
        ->where('sale_orders.transaction_type','=', 'SALESINVOICE')
        ->select('sale_orders.*', 'customers.first_name', 'customers.last_name', 'currencies.name as currency_name');
        if(!empty($from) && !empty($to)) {
          $from = DbDateFormat($from);
          $to   = DbDateFormat($to);

          $data->where('sale_orders.ord_date','>=', $from)
          ->where('sale_orders.ord_date','<=', $to);
        }
        if(!empty($customer) && $customer!='all') {
            $data->where('sale_orders.customer_id','=', $customer);
        }
        if(!empty($location) && $location!='all') {
            $data->where('sale_orders.location_id','=', $location);
        }
        if (Helpers::has_permission(Auth::user()->id, 'own_invoice')) {
          $id = Auth::user()->id;
          $data->where('sale_orders.user_id','=',$id);
        }

        return $data;
    }

    public function getAllSaleOrderByProject($from, $to, $project)
    {
        $data = $this->leftJoin('customers', 'sale_orders.customer_id', '=', 'customers.id')
                ->leftJoin('currencies','sale_orders.currency_id','=', 'currencies.id')
                ->where('sale_orders.transaction_type','=', 'SALESINVOICE')
                ->select('sale_orders.*', 'customers.first_name', 'customers.last_name', 'currencies.name as currency_name');
        if(!empty($from) && !empty($to)) {
          $from = DbDateFormat($from);
          $to   = DbDateFormat($to);
          $data->where('sale_orders.order_date','>=', $from)
               ->where('sale_orders.order_date','<=', $to);
        }
        if($project) {
            $data->where('sale_orders.project_id','=', $project);
        }
        return $data;
    }

    public function getQuotationId($id)
    {
        return $this->where('id', $id)->get(['order_reference_id','reference']);
    }

    public function getSaleYears()
    {
        $data = DB::select("SELECT DISTINCT YEAR(order_date ) as year FROM sale_orders ORDER BY order_date  DESC");
        return $data;
    }

    public function getSalesReport ($type, $from = null, $to = null, $year = null, $month = null, $item = null, $customer = null, $location = null, $currency = null)
    {
        $from = !empty($from) && strtotime($from) ? DbDateFormat($from) : DbDateFormat(date("d-m-Y", strtotime("-1 months")));
        $to = !empty($to) && strtotime($to) ? DbDateFormat($to) : DbDateFormat(date('d-m-Y'));
        $data = $this->whereIn('transaction_type', ['SALESINVOICE', 'POSINVOICE']);
        if (!empty($item)) {
            $data->with(['saleOrderDetails' => function($query) use($item) {
                    $query->where('item_id', $item)->select('sale_order_id', 'id', 'item_id', 'item_name', 'unit_price', 'quantity', 'discount_amount');
                }]);
        } else {
            $data->with(['saleOrderDetails:sale_order_id,id,item_id,item_name,unit_price,quantity,discount_amount']);
        }

        if (!empty($customer)) {
            $data->where(['customer_id' => $customer]);
        }
        if (!empty($location)) {
            $data->where(['location_id' => $location]);
        }
        if (!empty($currency)) {
            $data->where(['currency_id' => $currency]);
        }

        switch ($type) {
            case 'monthly':
                $data->orderBy('sale_orders.order_date', 'desc');
                break;
            case 'yearly':
                if (empty($year)) {
                    $data->orderBy('sale_orders.order_date', 'desc');
                } else {
                    if (!empty($month)) {
                        $data->whereMonth('sale_orders.order_date', '=' , $month)->whereYear('sale_orders.order_date', '=' , $year)->orderBy('sale_orders.order_date', 'desc');
                    } else {
                        $data->whereYear('sale_orders.order_date', '=' , $year)->orderBy('sale_orders.order_date', 'desc');
                    }
                }
                break;
            case 'custom':
                $data->whereBetween('sale_orders.order_date', [$from, $to]);
                break;
            default:
                $data->orderBy('sale_orders.order_date');
                break;
        }

        $data = $data->get();
        foreach ($data as $datakey => $order) {
            foreach ($order->saleOrderDetails as $key => $value) {
                if (isset($value->item->purchasePrices) && !empty($value->item->purchasePrices)) {
                    $order->saleOrderDetails[$key]->purchasePrice = $value->item->purchasePrices->price;
                    $exchangeRate = $order->exchange_rate;
                } else {
                    $order->saleOrderDetails[$key]->purchasePrice = 0;
                    $order->saleOrderDetails[$key]->totalTax = 0;
                    $exchangeRate = 1;
                }
                $order->saleOrderDetails[$key]->totalTax =  $value->saleTaxes->sum('tax_amount');
            }
        }

        $saleData     = [];
        $data         = $data->toArray();
        $totalCnt     = count($data);
        $filterArray  = array_values(array_unique(array_column($data, 'order_date')));
        $totalDateCnt = count($filterArray);
        $otherDiscountAmount = 0;
        $itemDiscountAmount = 0;
        $customChargeAmount = 0;
        $shippingCharge = 0;
        $posShipping = 0;

        for ($i = 0; $i < $totalCnt; $i++) {
            for ($j = 0; $j < $totalDateCnt; $j++) {
                if ($data[$i]['order_date'] == $filterArray[$j]) {
                    $saleData[$data[$i]['order_date']]['total'][] = $data[$i]['total'];
                    for($k =0 ; $k < count($data[$i]['sale_order_details']); $k++) {
                        $itemDiscountAmount = 0;
                        $saleData[$data[$i]['order_date']]['quantity'][] = $data[$i]['sale_order_details'][$k]['quantity'];
                        $saleData[$data[$i]['order_date']]['totalPurchasePrice'][] = $data[$i]['sale_order_details'][$k]['purchasePrice'] * $data[$i]['sale_order_details'][$k]['quantity'] * $exchangeRate;
                        $saleData[$data[$i]['order_date']]['totalTax'][] = $data[$i]['sale_order_details'][$k]['totalTax'];

                        if ($data[$i]['has_item_discount'] == 1) {
                            $saleData[$data[$i]['order_date']]['itemDiscountAmount'][] = $data[$i]['sale_order_details'][$k]['discount_amount'];
                        }

                        if ($data[$i]['has_other_discount'] == 1 && $data[$i]['other_discount_type'] != '%') {
                            if ($k == count($data[$i]['sale_order_details']) - 2) {
                                $saleData[$data[$i]['order_date']]['otherDiscountAmount'][] = $data[$i]['other_discount_amount'];
                            }
                        } else {
                            $saleData[$data[$i]['order_date']]['otherDiscountAmount'][] = ($data[$i]['sale_order_details'][$k]['unit_price'] * $data[$i]['sale_order_details'][$k]['quantity']) * ($data[$i]['other_discount_amount'] / 100);
                        }
                    }
                    $saleData[$data[$i]['order_date']]['actualSalePrices'][] = $data[$i]['total'];
                }
                $saleData[$data[$i]['order_date']]['currency_id'] = $data[$i]['currency_id'];
            }
        }

        $customData = [];
        foreach ($saleData as $key => $value) {
            $customData[$key]['totalactualSalePrices']  = !empty($value['actualSalePrices']) ? array_sum($value['actualSalePrices']) : 0;
            $customData[$key]['totalAmount']            = !empty($value['total']) ? array_sum($value['total']) : 0;
            $customData[$key]['itemDiscountAmount']     = !empty($value['itemDiscountAmount']) ? array_sum($value['itemDiscountAmount']) : 0;
            $customData[$key]['otherDiscountAmount']    = !empty($value['otherDiscountAmount']) ? array_sum($value['otherDiscountAmount']) : 0;
            $customData[$key]['totalInvoice']           = !empty($value['total']) ? count($value['total']) : 0;
            $customData[$key]['totalQuantity']          = !empty($value['quantity']) ? array_sum($value['quantity']) : 0;
            $customData[$key]['totalPurchase']          = !empty($value['totalPurchasePrice']) ? array_sum($value['totalPurchasePrice']) : 0;
            $customData[$key]['totalSaleTax']           = !empty($value['totalTax']) ? array_sum($value['totalTax']) : 0;
            $customData[$key]['orderDate']              = $key;
            $customData[$key]['totalProfitAmount']      = $customData[$key]['totalAmount'] - $customData[$key]['totalPurchase'];
            $customData[$key]['filterCurrency']         = $value['currency_id'];
        }

        $result = array();
        switch ($type) {
            case 'monthly':
                $result = $this->generateMonthData($customData);
                break;
            case 'yearly':
                if (empty($year)) {
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
     * @param  [type] $customData [description]
     * @return [type]             [description]
     */
    public function generateMonthData($customData)
    {
        $monthData = $result = [];
        $totalAmount = $totalInvoice = $totalQuantity = $totalPurchase = $totalTax = $totalProfit = 0;
        $totalactualSalePrices = $itemDiscountAmount = $otherDiscountAmount = 0;
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
                $totalactualSalePrices += $value[$i]['totalactualSalePrices'];
                $totalInvoice += $value[$i]['totalInvoice'];
                $totalQuantity += $value[$i]['totalQuantity'];
                $totalPurchase += $value[$i]['totalPurchase'];
                $totalTax += $value[$i]['totalSaleTax'];
                $totalProfit += $value[$i]['totalProfitAmount'];
                $itemDiscountAmount += $value[$i]['itemDiscountAmount'];
                $otherDiscountAmount += $value[$i]['otherDiscountAmount'];
           }
           $monthData[$key]['totalAmount']              = $totalAmount;
           $monthData[$key]['totalactualSalePrices']    = $totalactualSalePrices;
           $monthData[$key]['totalInvoice']             = $totalInvoice;
           $monthData[$key]['totalQuantity']            = $totalQuantity;
           $monthData[$key]['totalPurchase']            = $totalPurchase;
           $monthData[$key]['totalSaleTax']             = $totalTax;
           $monthData[$key]['totalProfitAmount']        = $totalProfit;
           $monthData[$key]['itemDiscountAmount']       = $itemDiscountAmount;
           $monthData[$key]['otherDiscountAmount']      = $otherDiscountAmount;
           $monthData[$key]['filterCurrency']           = $value[0]['filterCurrency'];
           $monthData[$key]['orderDate']                = $value[0]['orderDate'];

           $totalTax               = 0;
           $totalAmount            = 0;
           $totalProfit            = 0;
           $totalInvoice           = 0;
           $totalQuantity          = 0;
           $totalPurchase          = 0;
           $itemDiscountAmount     = 0;
           $otherDiscountAmount    = 0;
           $totalactualSalePrices  = 0;
        }
        return $monthData;
    }

    public function getAllIncome($from, $to, $currency = null)
    {
        $saleInvoice = [];
        $posInvoice = [];
        $incomeStat = [];
        $res = [];
        $saleIncomeStat = [];
        $data = $this->whereIn('transaction_type', ['SALESINVOICE', 'POSINVOICE']);
        $from = DbDateFormat($from);
        $to   = DbDateFormat($to);
        $data->whereBetween('order_date', [$from, $to]);
        $data->where('currency_id', $currency);
        $result = $data->get();
        for ($i=0; $i < count($result); $i++) {
           if ($result[$i]->transaction_type == 'SALESINVOICE') {
                if (array_key_exists(date('M Y', strtotime($result[$i]->order_date)), $saleInvoice) ) {
                    $saleInvoice[date('M Y', strtotime($result[$i]->order_date))] += $result[$i]->total;
                } else {
                    $saleInvoice[date('M Y', strtotime($result[$i]->order_date))] = $result[$i]->total;
                }
           } else if ($result[$i]->transaction_type == 'POSINVOICE') {
                if (array_key_exists(date('M Y', strtotime($result[$i]->order_date)), $posInvoice) ) {
                    $posInvoice[date('M Y', strtotime($result[$i]->order_date))] += $result[$i]->total;
                } else {
                    $posInvoice[date('M Y', strtotime($result[$i]->order_date))] = $result[$i]->total;
                }
           }
        }

        $saleIncomeStat['Sale Invoices'] = $saleInvoice;
        $saleIncomeStat['POS Invoices'] = $posInvoice;
        $depositStat = (new Deposit)->getGenerelIncome($from, $to, $currency, 'detail');

        $saleIncomeStat = is_array($saleIncomeStat) ? $saleIncomeStat : [];
        $depositStat = is_array($depositStat) ? $depositStat : [];
        $incomeStat = array_merge($saleIncomeStat, $depositStat);

        return $incomeStat;
    }

    public function getSalesReportByDate($date, $location = null, $customer = null, $product = null, $currency = null, $month = null, $year = null)
    {
        $url = url()->current();
        $chkDaily = 'sales-report-by-date';
        $chkYearMonth = 'sale_report_filterwise';
        $data = $this->whereIn('transaction_type', ['SALESINVOICE', 'POSINVOICE']);
        if (!empty($product)) {
            $data->with(['saleOrderDetails' => function($query) use($product) {
                    $query->where('item_id', $product)->select('sale_order_id', 'id', 'item_id', 'item_name', 'unit_price', 'quantity', 'discount_amount');
                }]);
        } else {
            $data->with(['saleOrderDetails:sale_order_id,id,item_id,item_name,unit_price,quantity,discount_amount']);
        }

        if (strpos($url, $chkDaily) == true) {
            if (!empty($date)) {
                $data->whereDate('order_date', $date);
            }
        }
        if (strpos($url, $chkYearMonth) == true) {
            if (!empty($year)) {
                $data->whereYear('order_date', $year);
            }
            if (!empty($month)) {
                $data->whereMonth('order_date', $month);
            }
        }
        if (!empty($location)) {
            $data->where(['location_id' => $location]);
        }
        if (!empty($customer)) {
            $data->where(['customer_id' => $customer]);
        }
        $data->where('currency_id', $currency);
        $data = $data->get();
        return $data;
    }

    public function getAllIncomeStat($from = null, $to = null, $currency = null)
    {
        $saleInvoiceTotal = $posTotal = 0;
        $res = $saleIncomeStat = [];
        $data = $this->whereIn('transaction_type', ['SALESINVOICE', 'POSINVOICE']);
        $data->whereBetween('order_date', [$from, $to]);
        $data->where('currency_id', $currency);
        $result = $data->get();
        for ($i=0; $i < count($result); $i++) {
           if ($result[$i]->transaction_type == 'SALESINVOICE') {
                $saleInvoiceTotal += $result[$i]->total;
           } else if ($result[$i]->transaction_type == 'POSINVOICE') {
                $posTotal += $result[$i]->total;
           }
        }

        $saleIncomeStat['Sale Invoices'] = $saleInvoiceTotal;
        $saleIncomeStat['POS Invoices'] = $posTotal;
        $depositStat = (new Deposit)->getGenerelIncome($from, $to, $currency, 'summery');
        $res['saleIncomeStat'] = $saleIncomeStat;
        $res['depositStat'] = $depositStat;

        return $res;
    }

}
