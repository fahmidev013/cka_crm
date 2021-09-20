<?php

namespace App\Http\Controllers;

use App\Models\{
    Account,
    Category,
    Country,
    Currency,
    Customer,
    CustomerBranch,
    CustomerTransaction,
    Item,
    Location,
    Preference,
    SalePrice,
    SaleOrder,
    SaleOrderDetail,
    SaleTax,
    SaleType,
    StockCategory,
    StockMove,
    TaxType,
    Transaction,
    TransactionReference,
    User
};
use Illuminate\Http\Request;
use Auth;
use Cookie;
use DB;
use PDF;
use App\Rules\UniquePosPaymentReference;

class PointOfSaleController extends Controller
{
    public function __construct(GeneralLedgerController $glController)
    {
        $this->glController = $glController;
    }

    public function index()
    {
        $data = [];
        $location = Cookie::get('location');
        if (empty($location)) {
            return $this->getLocation();
        }
        $data['location'] = Location::getAll()->where('id', $location)->first();
        $data['items'] = Item::with(['salePrices:price,item_id', 'image:object_type,object_id,file_name', 'taxType:id,tax_rate', 'stockMoves' => function($query) use($data)
        {
            $query->where('location_id', $data['location']->id)->get();
        }])->where(['item_type' => 'product', 'is_active' => 1])->select(['id', 'name', 'is_stock_managed', 'description', 'tax_type_id'])->get();

        $data['categories']  = StockCategory::getAll()->where('is_active', 1);
        $data['customers']   = Customer::with(['currency:id,name'])->where(['is_active'=> 1])->select(['id', 'first_name', 'last_name', 'currency_id'])->get();
        $data['countries']   = Country::getAll();
        $data['sales_types'] = SaleType::getAll();
        $data['taxTypeList'] = TaxType::getAll();
        $data['currencies']  = Currency::getAll();
    	return view('admin.pointOfSale.index', $data);
    }

    public function addCustomer()
    {
        $data['type'] = isset($_GET['type']) ? $_GET['type'] : null;
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
    	$data['countries']   = Country::getAll();
    	$data['currencies']  = Currency::getAll();

    	return view('admin.pointOfSale.addCustomer', $data);
    }

    public function addNote()
    {
    	return view('admin.pointOfSale.addNote');
    }

    public function settings()
    {
        return view('admin.pointOfSale.settings');
    }

    public function addShipping()
    {
        $data['countries'] = Country::getAll();
        return view('admin.pointOfSale.addShipping', $data);
    }

    public function addDiscount()
    {
        return view('admin.pointOfSale.addDiscount');
    }

    public function orderOnHold()
    {
        return view('admin.pointOfSale.putOrderOnHold');
    }

    public function orderPayment(Request $request)
    {
        $invoice_count = SaleOrder::where('transaction_type', 'POSINVOICE')->count();
        if ($invoice_count > 0) {
            $invoiceReference = SaleOrder::where(['transaction_type' => 'POSINVOICE'])->select('reference')->orderBy('id', 'DESC')->first();
            $ref = explode("-", $invoiceReference->reference);
            $data['reference_no'] = 'POS-'. sprintf("%04d", $ref[1]+1);
        } else {
            $data['reference_no'] = 'POS-'. sprintf("%04d", 1);
        }
        return view('admin.pointOfSale.order-payment', $data);
    }

    public function payment(Request $request)
    {
        $this->validate($request, [
            'reference.*'       => ['required', new UniquePosPaymentReference],
            'reference_no.*'    => 'required',
            'item_price.*'      => 'required',
            'item_name.*'       => 'required',
            'item_qty.*'        => 'required',
            'indivisiual_item_total_price.*' => 'required',
            'totalValue'        => 'required',
        ]);
        if (!empty($request->customer_id)) {
            if ($request->dflt_currency_id != $request->customerCurrency || $request->customer_id == null) {
                $result['status'] = 2;
                return json_encode($result);
                exit();
            }
        } else {
            $result['status'] = 2;
            return json_encode($result);
            exit();
        }
        $request->location_id = Cookie::get('location');
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $default_account = Account::where('is_default', 1)->first(['id']);
        $request->order_date = date("Y-m-d");
        if (!empty($request->customer_id)) {
            $customer_branch = CustomerBranch::where('customer_id', $request->customer_id)->first(['id']);
        }
        $request->customer_branch_id = isset($customer_branch->id) ? $customer_branch->id : null;
        $request->currency_id = $preference['dflt_currency_id'];
        $request->exchange_rate = 1;
        $request->has_tax = $request->has_description = $request->has_item_discount = 'on';
        $request->has_hsn = "";
        $request->has_other_discount = $request->other_discount_amount > 0 ? "on" : "";
        $request->has_shipping_charge = $request->shipping_charge > 0 ? "on" : "";
        $request->has_custom_charge = "";
        $request->pos_order_title = !empty($request->pos_order_title) ? $request->pos_order_title : $this->getHoldOrderTitle($request->customer_id);
        $request->pos_invoice_status = "clear";
        $request->payment_term_id = 1;
        $taxTable = TaxType::getAll();
        try {
            DB::beginTransaction();
            if ($request->order_id) {
                $oldOrder = SaleOrder::find($request->order_id);
                if (!empty($oldOrder->saleOrderDetails)) {
                    $oldOrderDetailsIds = $oldOrder->saleOrderDetails->pluck('id')->toArray();
                    $deleteTaxes = DB::table('sale_taxes')
                                    ->whereIn('sale_order_detail_id', $oldOrderDetailsIds)
                                    ->delete();
                    $deleteOrderDetails = DB::table('sale_order_details')
                                        ->whereIn('id', $oldOrderDetailsIds)
                                        ->delete();
                    if ($deleteOrderDetails > 0) {
                        $oldOrder->delete();
                    }
                }
            }
            $user_id = Auth::user()->id;
            $invoice_count = SaleOrder::where('transaction_type', 'POSINVOICE')->count();
            if ($invoice_count > 0) {
                $invoiceReference = SaleOrder::where(['transaction_type' => 'POSINVOICE'])->select('reference')->latest('id')->first();
                $ref = explode("-", $invoiceReference->reference);
                $reference = 'POS-'. sprintf("%04d", $ref[1]+1);
            } else {
                $reference = 'POS-'. sprintf("%04d", 1);
            }
            $searcharray = [];
            parse_str($request->item_data, $searcharray);
            if ($request->shippingDetail) {
                $shippingDetail = json_decode($request->shippingDetail);
            }
            if (!empty($searcharray['item_tax_type_id'])) {
                if (count($searcharray['item_tax_type_id']) > 0) {
                    foreach ($searcharray['item_tax_type_id'] as $key => $value) {
                        $searcharray['item_tax_type_id'][$key] = explode(",", $value[0]);
                    }
                }
            }
            if (isset($searcharray['custom_item_tax_type_id']) && count($searcharray['custom_item_tax_type_id']) > 0) {
                foreach ($searcharray['custom_item_tax_type_id'] as $key => $value) {
                    $searcharray['custom_item_tax_type_id'][$key] = explode(",", $value);
                }
            }
            $posInvoice = (new SaleOrder)->store($request, 'POSINVOICE', 'directPOS', $reference);
            if (!empty($posInvoice)) {
                if (!empty($searcharray['item_id'])) {
                    $invoiceDetails = (new SaleOrderDetail)->storeMass($request, $posInvoice->id, $searcharray['item_id'], $searcharray['item_desc'], $searcharray['item_name'], $searcharray['item_price'], 0, $searcharray['item_qty'], 0, $searcharray['item_discount'], $searcharray['item_discount_type'], null, 0, $searcharray['item_tax_type_id']);
                    foreach ($searcharray['item_id'] as $key => $value) {
                        $i = 0;
                        $invoiceTax = [];
                        foreach ($searcharray['item_tax_type_id'][$value] as $index => $tax) {
                            if (!empty($tax) && is_numeric($tax)) {
                                $selectedTax = $taxTable->where('id', $tax)->first();
                                $taxAmount = (new TaxType)->calculateTax($selectedTax->tax_rate, $request->tax_type, $request->discount_on, validateNumbers($invoiceDetails[$key]->unit_price) * validateNumbers($invoiceDetails[$key]->quantity), $invoiceDetails[$key]->discount, $invoiceDetails[$key]->discount_type, $request->other_discount_amount, $request->other_discount_type, $request->indivisual_discount_price);

                                $invoiceTax[$i]['sale_order_detail_id'] = $invoiceDetails[$key]->id;
                                $invoiceTax[$i]['tax_type_id'] = $tax;
                                $invoiceTax[$i]['tax_amount'] = $taxAmount;
                                $i++;
                            }
                        }
                        DB::table('sale_taxes')->insert($invoiceTax);
                        $stockMove = new StockMove();
                        $stockMove->item_id          = $value;
                        $stockMove->transaction_type_id = $posInvoice->id;
                        $stockMove->transaction_type = 'POSINVOICE';
                        $stockMove->location_id      = $request->location_id;
                        $stockMove->transaction_date = DbDateFormat($request->order_date);
                        $stockMove->user_id          = $user_id;
                        $stockMove->transaction_type_detail_id = $invoiceDetails[$key]->id;
                        $stockMove->reference        = 'store_out_' . $invoiceDetails[$key]->id;
                        $stockMove->quantity         = '-' . $invoiceDetails[$key]->quantity;
                        $stockMove->price            = $invoiceDetails[$key]->unit_price;
                        $stockMove->save();
                    }
                }
                if (!empty($searcharray['custom_row_no'])) {
                    $customOrderDetails = (new SaleOrderDetail)->storeCustomItems($request, $posInvoice->id, null, $searcharray['custom_item_desc'], $searcharray['custom_item_name'], $searcharray['custom_item_price'], 0, $searcharray['custom_item_qty'], 0, $searcharray['custom_item_discount'], $searcharray['custom_item_discount_type'], null, 0, $searcharray['custom_item_tax_type_id'], $searcharray['custom_row_no']);
                    foreach ($searcharray['custom_row_no'] as $key => $value) {
                        $i = 0;
                        $customSaleTax = [];
                        foreach ($searcharray['custom_item_tax_type_id'][$value] as $index => $tax) {
                            if (!empty($tax) && is_numeric($tax) && !empty($invoiceDetails)) {
                                $selectedTax = $taxTable->where('id', $tax)->first();
                                $taxAmount = (new TaxType)->calculateTax($selectedTax->tax_rate, $request->tax_type, $request->discount_on, validateNumbers($invoiceDetails[$key]->unit_price) * validateNumbers($invoiceDetails[$key]->quantity), $invoiceDetails[$key]->discount, $invoiceDetails[$key]->discount_type, $request->other_discount_amount, $request->other_discount_type, $request->indivisual_discount_price);

                                $customSaleTax[$i]['sale_order_detail_id'] = $customOrderDetails[$key]->id;
                                $customSaleTax[$i]['tax_type_id'] = $tax;
                                $customSaleTax[$i]['tax_amount'] = $taxAmount;
                                $i++;
                            }
                        }
                    }
                    DB::table('sale_taxes')->insert($customSaleTax);
                }
                $transaction_reference = (new TransactionReference)->createReference("POS_PAYMENT", $posInvoice->id);

                $customerTransaction = new CustomerTransaction();
                $customerTransaction->user_id = $user_id;
                // if POS customer is not availabe then get walking customer - id : 1
                $customerTransaction->customer_id = !empty($request->customer_id) ? $request->customer_id : 1;
                $customerTransaction->sale_order_id = $posInvoice->id;
                $customerTransaction->transaction_reference_id = $transaction_reference->id;
                $customerTransaction->currency_id = $preference['dflt_currency_id'];
                $customerTransaction->transaction_date = date("Y-m-d");
                $customerTransaction->amount = validateNumbers($request->totalValue);
                $customerTransaction->exchange_rate = 1;
                $customerTransaction->status = "Approved";
                $customerTransaction->save();

                $transaction = new Transaction();
                $transaction->amount = validateNumbers($request->totalValue);
                $transaction->transaction_type = $request->payment_type;
                $transaction->account_id = $default_account->id;
                $transaction->transaction_date = date("Y-m-d");
                $transaction->user_id = $user_id;
                $transaction->currency_id = $posInvoice->currency_id;
                $transaction->transaction_reference_id = $transaction_reference->id;
                $transaction->transaction_method = "POSINVOICE";
                $transaction->save();
            }
            $result['id'] = $posInvoice->id;
            $result['status'] = 1;
            DB::commit();
            $this->printReceipt($posInvoice->id);
        } catch (Exception $e) {
            $result['status'] = 0;
            DB::rollBack();
        }
        return json_encode($result);
        exit();
    }

    public function holdOrderPayment(Request $request)
    {
        $user_id = Auth::user()->id;
        $payment_method = 1;
        $location_id = Location::find(Cookie::get('location'))->id;
        $default_account = Account::where(['is_default' => 1, 'is_deleted' => 0])->first(['id']);
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        try {
            DB::beginTransaction();
            $order = SaleOrder::find($request->order_id);
            foreach ($order->saleOrderDetails as $key => $value) {
                if (!empty($value->item_id)) {
                    $stockMove = new StockMove();
                    $stockMove->item_id = $value->item_id;
                    $stockMove->transaction_type_id = $order->id;
                    $stockMove->transaction_type = "POSINVOICE";
                    $stockMove->location_id = $location_id;
                    $stockMove->transaction_date = date("Y-m-d");
                    $stockMove->user_id = $user_id;
                    $stockMove->transaction_type_detail_id = $value->id;
                    $stockMove->reference = 'store_out_' . $value->id;
                    $stockMove->quantity = $value->quantity;
                    $stockMove->save();
                }
            }
            $transactionReference = (new TransactionReference)->createReference("POS_PAYMENT", $order->id);
            $transaction = new Transaction();
            $transaction->currency_id = $order->currency_id;
            $transaction->amount = $request->order_amount;
            $transaction->transaction_type = $request->payment_type;
            $transaction->account_id = $default_account->id;
            $transaction->transaction_date = date("Y-m-d");
            $transaction->user_id = $user_id;
            $transaction->transaction_reference_id = $transactionReference->id;
            $transaction->transaction_method = "POSINVOICE";
            $transaction->save();
            if (!empty($order->customer_id)) {
                $customerTransaction = new CustomerTransaction();
                $customerTransaction->user_id = $user_id;
                $customerTransaction->customer_id = $order->customer_id;
                $customerTransaction->sale_order_id = $order->id;
                $customerTransaction->transaction_reference_id = $transactionReference->id;
                $customerTransaction->currency_id = $preference['dflt_currency_id'];
                $customerTransaction->transaction_date = date("Y-m-d");
                $customerTransaction->amount = $request->order_amount;
                $customerTransaction->exchange_rate = 1;
                $customerTransaction->status = "Approved";
                $customerTransaction->save();
            }
            $order->pos_invoice_status = 'clear';
            $order->paid = $request->order_amount;
            $order->amount_received = $request->amount_received;
            $order->save();
            $result['id'] = $order->id;
            $result['status'] = 1;
            DB::commit();
        } catch (Exception $e){
            $result['status'] = 0;
            DB::rollBack();
        }
        return json_encode($result);
        exit();
    }

    public function orderHold(Request $request) {
        // Create reference
        $this->validate($request, [
            'item_price.*'      => 'required',
            'item_name.*'       => 'required',
            'item_qty.*'        => 'required',
            'indivisiual_item_total_price.*' => 'required',
            'totalValue'        => 'required',
        ]);
        $request->location_id = Cookie::get('location');
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $request->order_date = date("Y-m-d");
        if (!empty($request->customer_id)) {
            $customer_branch = CustomerBranch::where('customer_id', $request->customer_id)->first(['id']);
        }
        $request->customer_branch_id = isset($customer_branch->id) ? $customer_branch->id : null;
        $request->currency_id = $preference['dflt_currency_id'];
        $request->exchange_rate = 1;
        $request->has_tax = $request->has_description = $request->has_item_discount = 'on';
        $request->has_hsn = "";
        $request->has_other_discount = $request->other_discount_amount > 0 ? "on" : "";
        $request->has_shipping_charge = $request->shipping_charge > 0 ? "on" : "";
        $request->has_custom_charge = "";
        $request->pos_order_title = !empty($request->pos_order_title) ? $request->pos_order_title : $this->getHoldOrderTitle($request->customer_id);
        $request->pos_invoice_status = "hold";
        $request->payment_term_id = 1;
        try{
            DB::beginTransaction();
            if ($request->order_id) {
                $oldOrder = SaleOrder::find($request->order_id);
                if (!empty($oldOrder->saleOrderDetails)) {
                    $oldOrderDetailsIds = $oldOrder->saleOrderDetails->pluck('id')->toArray();
                    $deleteTaxes = DB::table('sale_taxes')
                                    ->whereIn('sale_order_detail_id', $oldOrderDetailsIds)
                                    ->delete();
                    $deleteOrderDetails = DB::table('sale_order_details')
                                        ->whereIn('id', $oldOrderDetailsIds)
                                        ->delete();
                    if ($deleteOrderDetails > 0) {
                        $oldOrder->delete();
                    }
                }
            }
            $taxTable = TaxType::getAll();
            $user_id = Auth::user()->id;
            $amount = $request->net_payable;
            $account_no = isset($request->account_no) ? $request->account_no : 1;
            $payment_date = date('Y-m-d H:i:s');
            $dflt_account = Account::where('is_default', 1)->first();
            $invoice_count = SaleOrder::where('transaction_type', 'POSINVOICE')->count();
            if ($invoice_count > 0) {
                $invoiceReference = SaleOrder::where(['transaction_type' => 'POSINVOICE'])->select('reference')->latest('id')->first();
                $ref = explode("-", $invoiceReference->reference);
                $reference = 'POS-'.sprintf("%04d", $ref[1]+1);
            } else {
                $reference = 'POS-'.sprintf("%04d", 1);
            }
            $searcharray = [];
            parse_str($request->item_data, $searcharray);
            if ($request->shippingDetail) {
                $shippingDetail = json_decode($request->shippingDetail);
            }
            if (isset($searcharray['item_tax_type_id']) && !empty($searcharray['item_tax_type_id'])) {
                foreach ($searcharray['item_tax_type_id'] as $key => $value) {
                    $searcharray['item_tax_type_id'][$key] = explode(",", $value[0]);
                }
            }

            if (isset($searcharray['custom_item_tax_type_id']) && !empty($searcharray['custom_item_tax_type_id'])) {
                foreach ($searcharray['custom_item_tax_type_id'] as $key => $value) {
                    $searcharray['custom_item_tax_type_id'][$key] = explode(",", $value);
                }
            }

            $posInvoice = (new SaleOrder)->store($request, 'POSINVOICE', 'directPOS', $reference);
            if (!empty($posInvoice)) {
                if (!empty($searcharray['item_id'])) {
                    $invoiceDetails = (new SaleOrderDetail)->storeMass($request, $posInvoice->id, $searcharray['item_id'], $searcharray['item_desc'], $searcharray['item_name'], $searcharray['item_price'], 0, $searcharray['item_qty'], 0, $searcharray['item_discount'], $searcharray['item_discount_type'], null, 0, $searcharray['item_tax_type_id']);
                    $i = 0;
                    $invoiceTax = [];
                    foreach ($searcharray['item_id'] as $key => $value) {
                        foreach ($searcharray['item_tax_type_id'][$value] as $index => $tax) {
                            if (!empty($tax) && is_numeric($tax)) {
                                $selectedTax = $taxTable->where('id', $tax)->first();
                                $taxAmount = (new TaxType)->calculateTax($selectedTax->tax_rate, $request->tax_type, $request->discount_on, validateNumbers($invoiceDetails[$key]->unit_price) * validateNumbers($invoiceDetails[$key]->quantity), $invoiceDetails[$key]->discount, $invoiceDetails[$key]->discount_type, $request->other_discount_amount, $request->other_discount_type, $request->indivisual_discount_price);

                                $invoiceTax[$i]['sale_order_detail_id'] = $invoiceDetails[$key]->id;
                                $invoiceTax[$i]['tax_type_id'] = $tax;
                                $invoiceTax[$i]['tax_amount'] = $taxAmount;
                                $i++;
                            }
                        }
                    }
                    DB::table('sale_taxes')->insert($invoiceTax);
                }
                if (!empty($searcharray['custom_row_no'])) {
                    $customOrderDetails = (new SaleOrderDetail)->storeCustomItems($request, $posInvoice->id, null, $searcharray['custom_item_desc'], $searcharray['custom_item_name'], $searcharray['custom_item_price'], 0, $searcharray['custom_item_qty'], 0, $searcharray['custom_item_discount'], $searcharray['custom_item_discount_type'], null, 0, $searcharray['custom_item_tax_type_id'], $searcharray['custom_row_no']);
                    $i = 0;
                    $customSaleTax = [];
                    foreach ($searcharray['custom_row_no'] as $key => $value) {
                        foreach ($searcharray['custom_item_tax_type_id'][$value] as $index => $tax) {
                            if (!empty($tax) && is_numeric($tax)) {
                                $selectedTax = $taxTable->where('id', $tax)->first();
                                $taxAmount = (new TaxType)->calculateTax($selectedTax->tax_rate, $request->tax_type, $request->discount_on, validateNumbers($invoiceDetails[$key]->unit_price) * validateNumbers($invoiceDetails[$key]->quantity), $invoiceDetails[$key]->discount, $invoiceDetails[$key]->discount_type, $request->other_discount_amount, $request->other_discount_type, $request->indivisual_discount_price);

                                $customSaleTax[$i]['sale_order_detail_id'] = $customOrderDetails[$key]->id;
                                $customSaleTax[$i]['tax_type_id'] = $tax;
                                $customSaleTax[$i]['tax_amount'] = $taxAmount;
                                $i++;
                            }
                        }
                    }
                    DB::table('sale_taxes')->insert($customSaleTax);
                }

            }
            DB::commit();
            echo 1;
        } catch (Exception $e) {
            DB::rollBack();
            echo 0;
        }
        exit();
    }

    public function getHoldItems()
    {
        $data['orders'] = SaleOrder::where(['transaction_type' => 'POSINVOICE', 'invoice_type' => 'quantity', 'pos_invoice_status' => 'hold'])
                                ->latest('order_date')
                                ->limit(10)
                                ->get(['id', 'pos_order_title']);
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['currency'] = Currency::find($preference['dflt_currency_id']);
        return view('admin.pointOfSale.ordersOnHold', $data);
    }

    public function getLocation()
    {
        $data['menu'] = 'pos';
        $data['page_title'] = __('Point of Sale');
        $data['dflt_location'] = Cookie::get("location");
        $data['locations'] = Location::getAll()->where('is_active', 1);
        return view('admin.pointOfSale.get-location', $data);
    }

    public function setLocation(Request $request)
    {
        Cookie::queue("location", $request->location, 86400);
        return redirect('pos');
    }

    public function getHoldOrderDetails(Request $request)
    {
        $data = [];
        $data['order'] = SaleOrder::with('saleOrderDetails')->find($request->orderId);
        $taxAmount = 0;
        $totalItemPrice = 0;
        foreach ($data['order']->saleOrderDetails as $key => $value) {
            $taxes = SaleTax::with(['taxType:id,tax_rate'])->where('sale_order_detail_id', $value->id)->get();
            $totalItemPrice += $value->quantity * $value->unit_price;
            $tax_ids = [];
            $tax_rates = [];
            foreach ($taxes as $val) {
                $tax_ids[] = $val->taxType->id;
                $tax_rates[] = $val->taxType->tax_rate;
                $taxAmount = $val->tax_amount;
            }
            $value->tax_ids = $tax_ids;
            $value->tax_rates = $tax_rates;
            $value->total_tax_amount = round($value->saleTaxes->sum('tax_amount'), 8);
            $value->item = $value->item;
            if ($value->item['is_stock_managed'] == 1) {
                $value->item->stock = $value->item->stockMoves->sum('quantity');
            }
        }
        $discountAmount = 0;
        if ($data['order']->other_discount_type == "$") {
            $discountAmount = $data['order']->other_discount_amount;
        } else {
            $discountAmount = $totalItemPrice * $data['order']->other_discount_amount / 100;
        }
        $data['order']->total_tax_amount = round($data['order']->saleOrderDetails->sum('total_tax_amount'), 8);
        $data['order']->total_discount_amount = round($data['order']->saleOrderDetails->sum('discount_amount') + $discountAmount, 8);
        $data['user'] = User::where('id', $data['order']->user_id)->first(['full_name'])->full_name;
        if ($data['order']->customer_id != 0) {
            $data['customer'] = $data['order']->customer;
        }
        $data['status']       = $data['order']->count() > 0 ? 1 : 0;
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['currency'] = Currency::find($preference['dflt_currency_id']);
        return $data;
    }

    public function posItemSearch(Request $request)
    {
        $location = Cookie::get('location');
        if (empty($location)) {
            return $this->getLocation();
        }
        $data['location'] = Location::find($location);
        $query = Item::with(['salePrices', 'image:object_type,object_id,file_name', 'stockMoves' => function($query) use($data)
        {
            $query->where('location_id', $data['location']->id)->get();
        }])->where(['item_type' => 'product', 'is_active' => 1]);

        $query = !empty($request->activeCategoryId) ? $query->where(['stock_category_id' => $request->activeCategoryId]) : $query;

        if (isset($request->searchType) && $request->searchType == 'category') {
            if (!empty($request->data)) {
                $query->where('stock_category_id', $request->data);
            }
        } else {
            $query->where('name', 'LIKE', '%' . $request->data . '%');
        }
        $items = $query->select(['id', 'stock_id', 'name', 'item_unit_id', 'tax_type_id', 'is_stock_managed', 'description'])->get();
        $tax_type_id = [];
        $sale_prices  = [];
        $tax_rates    = [];
        foreach ($items as $key => $value) {
            if (isset($value->image->file_name) && !empty($value->image->file_name) && file_exists(public_path('/uploads/items/' . $value->image->file_name))) {
                $items[$key]['file_name'] = "/public/uploads/items/" . $value->image->file_name;
            } else {
                $items[$key]['file_name'] = '/public/dist/img/default-image.png';
            }

            $value->available = $value->stockMoves->sum('quantity');
            $tax_type_id[$key] = $value->tax_type_id;
            $sale_prices[$key] = isset($value->salePrices->price) && !empty($value->salePrices->price) ? $value->salePrices->price : 0;
            $tax_rates[$key]   = !empty($value->tax_type_id) ? $value->taxType->tax_rate : NULL;
        }

        $data['items']       = json_encode($items);
        $data['tax_type_id'] = json_encode($tax_type_id);
        $data['sale_prices'] = json_encode($sale_prices);
        $data['tax_rates']   = json_encode($tax_rates);
        $data['status_no']   = 1;
        return $data;
    }

    public function destroyHoldItem(Request $request)
    {
        $order = SaleOrder::find($request->id);
        if ($order) {
            if (count($order->saleOrderDetails) == 0) {
                return DB::table('sale_orders')->where('id', $order->id)->delete();
            }
            $deletedDetails = DB::table('sale_order_details')->where('sale_order_id', $order->id)->delete();
            if ($deletedDetails > 0) {
                return DB::table('sale_orders')->where('id', $order->id)->delete();
            }
        }
        return 0;
    }

    public function holdItemSearch(Request $request)
    {
        $query = SaleOrder::where(['transaction_type' => 'POSINVOICE', 'order_type' => 'directPOS', 'pos_invoice_status' => 'hold']);
        if (!empty($request->keyword)) {
            $query = $query->where('pos_order_title', 'like', '%'. $request->keyword .'%');
        }
        $data['orders'] = $query->latest('order_date')->limit(10)->get(['id', 'pos_order_title']);
        return json_encode($data['orders']);
    }

    public function customerBranch(Request $request)
    {
        $customer = Customer::with([
            'CustomerBranch' => function($query){
                  $query->select('customer_id', 'shipping_street', 'shipping_city', 'shipping_state', 'shipping_zip_code','shipping_country_id');
            }
        ])->where('id', $request->id)
        ->first(['id', 'name', 'email']);
        return json_encode($customer);
    }

    public function printReceipt($id)
    {
        $data['saleInvoiceData']  = SaleOrder::with([
                                                'location:id,name',
                                                'currency:id,name,symbol',
                                                'saleOrderDetails',
                                                'customer:id,first_name,last_name,email,phone'
                                            ])->where('transaction_type', 'POSINVOICE')->find($id);
        if (empty($data['saleInvoiceData'])) {
            return redirect()->back();
        }
        $data['totalTaxAmount'] = 0;
        foreach ((new SaleOrder)->calculateTaxRows($id) as $key => $value) {
            $data['totalTaxAmount'] += $value['amount'];
        }
        $pdf = PDF::loadView('admin.pointOfSale.receipt-pdf', $data);
        $pdf->setPaper(array(0, 0, 750, 1060), 'portrait');
        return $pdf->stream('invoice_' . time() . '.pdf', array("Attachment" => 0));
    }

    public function getHoldOrderTitle($customerID)
    {
        $customerName = Customer::where('id', $customerID)->first(['name']);
        $time = timeZoneformatDate(date('Y-m-d')) . '.' . timeZonegetTime(date("h:i"));
        if (!$customerName) {
            return 'Unknown' . '-' . date('Y.m.d.H.m', strtotime($time));
        }
        return $customerName->name . '-' . date('Y.m.d.H.m', strtotime($time));
    }
}
