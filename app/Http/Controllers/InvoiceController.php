<?php

namespace App\Http\Controllers;

use App\Models\{
    Account,
    Country,
    Currency,
    Customer,
    CustomerBranch,
    CustomerTransaction,
    Deposit,
    EmailTemplate,
    ExchangeRate,
    File,
    InvoicePaymentTerm,
    Item,
    Location,
    PaymentMethod,
    PaymentTerm,
    Preference,
    Project,
    SaleOrder,
    SaleOrderDetail,
    SalePrice,
    SaleTax,
    SaleType,
    StockMove,
    TaxType,
    Transaction,
    TransactionReference,
    UrlShortner,
    Activity
};
use Illuminate\Http\Request;
use App\DataTables\SaleInvoiceDataTable;
use App\Exports\allInvoiceExport;
use App\Http\Controllers\EmailController;
use App\Rules\CheckValidEmail;
use App\Http\Start\Helpers;
use Excel;
use Auth;
use DB;
use PDF;
use Session;

class InvoiceController extends Controller
{

    public function __construct(SaleOrder $saleOrder, EmailController $email, Deposit $deposit)
    {
        $this->saleOrder = $saleOrder;
        $this->email     = $email;
        $this->deposit   = $deposit;
    }

    /**
     * Display a listing of the resource.
     * @param [SalesInvoiceDataTable] $dataTable
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SaleInvoiceDataTable $dataTable)
    {
        $data = [];
        $data['menu']         = 'sales';
        $data['sub_menu']     = 'sales/direct-invoice';
        $data['page_title'] = __('Invoices');
        $data['location']     = isset($_GET['location']) ? $_GET['location'] : NULL;
        $data['customer']     = isset($_GET['customer']) ? $_GET['customer'] : NULL;
        $data['currency']     = isset($_GET['currency']) ? $_GET['currency'] : NULL;
        $data['project']      = isset($_GET['project']) ? $_GET['project'] : NULL;
        $data['status']       = isset($_GET['status']) ? $_GET['status'] : NULL;
        $data['from']         = isset($_GET['from']) ? $_GET['from'] : NULL;
        $data['to']           = isset($_GET['to']) ? $_GET['to'] : NULL;
        $data['transactionType']  = isset($_GET['transactionType']) ? $_GET['transactionType'] : NULL;
        $data['customerList'] = Customer::where('is_active', 1)->get(['id', 'name']);
        $data['locationList'] = Location::getAll();
        $data['currencyList'] = Currency::getAll();
        $data['projectList']  = Project::getAll(['id', 'name']);
        $data['amounts'] = $amounts = $invoiceSummery = $this->saleOrder->getMoneyStatus(['customer_id' => $data['customer'], 'location' => $data['location'], 'currency' => $data['currency'], 'from' => $data['from'], 'to' => $data['to'], 'transaction_type' => $data['transactionType'], 'status' => $data['status']]);
        $allCurrency = [];
        $overdueCurrency = [];
        foreach ($amounts['amounts'] as $amount) {
            if (isset($amount->currency->symbol) && !empty($amount->currency->symbol)) {
                $allCurrency[] =  $amount->currency->symbol;
            }
        }
        foreach ($amounts['overDue'] as $amount) {
            if (isset($amount->currency->symbol) && !empty($amount->currency->symbol)) {
                $overdueCurrency[] =  $amount->currency->symbol;
            }
        }
        $data['allCurrency'] = array_diff($allCurrency, $overdueCurrency);

        $row_per_page = Preference::getAll()->where('field', 'row_per_page')->first()->value;
        return $dataTable->with('row_per_page', $row_per_page)->render('admin.invoice.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $data['menu'] = 'sales';
        if (isset($_GET['type']) && $_GET['type'] == 'project') {
            if (!(Project::where('id', $_GET['project_id'])->exists())) {
                abort(404);
            }
            if (!empty($_GET['customer_id'])) {
                if (!(Customer::where('id', $_GET['customer_id'])->exists()) || (!(Project::where('id', $_GET['project_id'])->exists()) && !(Customer::where('id', $_GET['customer_id'])->exists()))) {
                    abort(404);
                }
            }
            $data['menu'] = 'project';
            $data['project_id'] = $_GET['project_id'];
            $data['customer_id'] = $_GET['customer_id'];
            $data['projects'] = Project::all();
        }
        $data['sub_menu'] = 'sales/direct-invoice';
        $data['page_title'] = __('Create Invoice');
        $data['url'] = 'invoice/list';

        $data['customerData'] = Customer::with(['currency:id,name,symbol'])->where(['is_active' => 1])->get();

        $data['countries']    = Country::getAll();
        $data['currencies']   = Currency::getAll();
        $data['paymentTerms'] = PaymentTerm::getAll();
        $data['locations']    = Location::getAll()->where('is_active', 1);
        $data['salesType']    = SaleType::select('sale_type', 'id')->get();

        $invoice_count        = SaleOrder::where('transaction_type', 'SALESINVOICE')->count();

        if ($invoice_count > 0) {
            $invoiceReference = SaleOrder::where('transaction_type', 'SALESINVOICE')
                                        ->latest('id')
                                        ->first(['reference']);
            $ref = explode("-", $invoiceReference->reference);
            $data['invoice_count'] = (int)$ref[1];
        } else {
            $data['invoice_count'] = 0;
        }

        $taxTypeList   = TaxType::getAll();
        $data['taxes'] = json_encode($taxTypeList);
        $taxOptions    = '';
        $selectStart   = "<select name='item_tax[]' class='inputTax form-control bootstrap-select selectpicker' multiple>";
        $selectEnd = "</select>";

        $selectStartCustom = "<select class='inputTax form-control bootstrap-select selectpicker' multiple name='custom_item_tax[1][]'>";
        $selectEndCustom   = "</select>";
        $taxHiddenField    = "";

        foreach ($taxTypeList as $key => $value) {
            $taxHiddenField.="<input type='hidden' class='itemTaxAmount itemTaxAmount-".$value->id."'>";
            $taxOptions .= "<option title='" . $value->tax_rate . "%' value='" . $value->id . "' taxrate='" . $value->tax_rate . "'>" . $value->name . '(' . $value->tax_rate . ')' . "</option>";
        }
        $data['tax_type']         = $selectStart . $taxOptions . $selectEnd;
        $data['custom_tax_type']  = $selectStartCustom . $taxOptions . $selectEndCustom . $taxHiddenField;
        $data['projects'] = Project::where('customer_id', '!=', 0)->get();
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['exchange_rate_decimal_digits'] = $preference['exchange_rate_decimal_digits'];

        return view('admin.invoice.add', $data);
    }


    /**
     * [store description]
     * @param  Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userId = Auth::user()->id;
        $this->validate($request, [
            'reference' => 'required|unique:sale_orders',
            'location_id' => 'required',
            'order_date' => 'required',
            'customer_id' => 'required',
            'currency_id' => 'required',
            'custom_item_name.*' => 'sometimes|required',
            'item_name.*' => 'sometimes|required',
            'exchange_rate' => 'required',
        ]);
        $flag = "";
        if ($request->menu != 'sales') {
            $flag = $request->sub_menu;
            if ($request->menu == 'project') {
                $flag = "type=project&project_id=".$request->project_id;
            }
        }
        $url = DB::transaction(function () use ($request, $userId, $flag) {
            /* Assign the values */
            $customerId = $request->customer_id;
            $customer_branch = CustomerBranch::where('customer_id', $customerId)->first(['id']);
            $request->customer_branch_id = $customer_branch->id;

            /* input field variables for inventory items */
            $item_id         = $request->item_id;
            $sorting_no      = $request->sorting_no;
            $item_name       = $request->item_name;
            $item_qty        = $request->item_qty;
            $item_price      = $request->item_price;
            $item_hsn        = $request->item_hsn;
            $item_discount   = $request->item_discount;
            $item_tax        = $request->item_tax;
            $item_description = $request->item_description;
            $item_discount_type = $request->item_discount_type;

            /* input field variables for Custom items */
            $row_no             = $request->row_no;
            $custom_sorting_no  = $request->custom_sorting_no;
            $custom_item_name   = $request->custom_item_name;
            $custom_item_qty    = $request->custom_item_qty;
            $custom_item_price  = $request->custom_item_price;
            $custom_item_hsn    = $request->custom_item_hsn;
            $custom_item_tax    = $request->custom_item_tax;
            $custom_item_discount = $request->custom_item_discount;
            $custom_item_description = $request->custom_item_description;
            $custom_item_discount_type = $request->custom_item_discount_type;
            $isError = false;
            if (!empty($item_qty)) {
                foreach ($item_qty as $key => $qty) {
                    if (validateNumbers($qty) <= 0) {
                        Session::flash('danger', __('Item quantity can not be zero.'));
                        $isError = true;
                        break;
                    }
                }
            }
            if (!empty($custom_item_qty)) {
                foreach ($custom_item_qty as $key => $qty) {
                    if (validateNumbers($qty) <= 0) {
                        Session::flash('danger', __('Item quantity can not be zero.'));
                        $isError = true;
                        break;
                    }
                }
            }
            if ($isError) {
                return 'invoice/add';
            }
            $taxTable = TaxType::getAll();

            # region salesOrder create
            // create salesOrder start
            $saleInvoice = (new SaleOrder)->store($request, 'SALESINVOICE', 'Direct Invoice', $request->reference);
            // Insert Activity
            if (isset($request->project_id)){
                (new Activity)->store('Project', $request->project_id, 'user', Auth::user()->id, __('A new invoice has been created'));
            }
            # endregion
            # region inventory item add
            if (!empty($item_id)) {
                $isAvailable = true;
                foreach ($item_id as $key => $value) {
                    $itemDetails = Item::find($value);
                    if (isset($itemDetails->is_stock_managed)) {
                        if (!empty($itemDetails->is_stock_managed) && $itemDetails->is_stock_managed == 1) {
                            $available = StockMove::where(['item_id' => $value, 'location_id' => $request->location_id])->get()->sum('quantity');
                            if ($available < $item_qty[$key]) {
                                $isAvailable = false;
                                break;
                            }
                        }
                    }
                }
                if (!$isAvailable) {
                    Session::flash('fail', __('Item not available in stock.'));
                    return "invoice/list" . $flag;
                }
                $saleInvoiceDetails = (new SaleOrderDetail)->storeMass($request, $saleInvoice->id, $item_id, $item_description, $item_name, $item_price, 0, $item_qty, 0, $item_discount, $item_discount_type, $item_hsn, $sorting_no, $request->item_tax);
                foreach ($item_id as $key => $item) {
                    if ($item_qty[$key] > 0) {
                        if ($request->has_tax == 'on' && isset($item_tax[$item_id[$key]])) {
                            $i = 0;
                            $invoiceTax = [];
                            if (isset($item_tax[$item][$key])) {
                                foreach ($item_tax[$item][$key] as $tax) {
                                    $selectedTax = $taxTable->where('id', $tax)->first();
                                    if ($selectedTax) {
                                        $taxAmount = (new TaxType)->calculateTax($selectedTax->tax_rate, $request->tax_type, $request->discount_on, validateNumbers($item_price[$key]) * validateNumbers($item_qty[$key]), $item_discount[$key], $item_discount_type[$key], $request->other_discount_amount, $request->other_discount_type, $request->indivisual_discount_price);
                                        $invoiceTax[$i]['sale_order_detail_id'] = $saleInvoiceDetails[$key]->id;
                                        $invoiceTax[$i]['tax_type_id'] = $tax;
                                        $invoiceTax[$i]['tax_amount'] = $taxAmount;
                                        $i++;
                                    }
                                }
                            }
                            $result = DB::table('sale_taxes')->insert($invoiceTax);
                        }
                        // create stockMove
                        $stockMove                   = new StockMove();
                        $stockMove->item_id          = $item_id[$key];
                        $stockMove->transaction_type_id = $saleInvoice->id;
                        $stockMove->transaction_type = 'SALESINVOICE';
                        $stockMove->location_id      = $request->location_id;
                        $stockMove->transaction_date = DbDateFormat($request->order_date);
                        $stockMove->user_id          = $userId;
                        $stockMove->transaction_type_detail_id = $saleInvoiceDetails[$key]->id;
                        $stockMove->reference        = 'store_out_' . $saleInvoice->id;
                        $stockMove->quantity         = '-' . $saleInvoiceDetails[$key]->quantity;
                        $stockMove->price            = $saleInvoiceDetails[$key]->unit_price;
                        $stockMove->save();
                    }
                }
            }
            # endregion

            // Custom items
            if (!empty($row_no)) {
                $saleInvoiceDetails = (new SaleOrderDetail)->storeCustomItems($request, $saleInvoice->id, null, $custom_item_description, $custom_item_name, $custom_item_price, 0, $custom_item_qty, 0, $custom_item_discount, $custom_item_discount_type, $custom_item_hsn, $custom_sorting_no, $request->custom_item_tax, $row_no);
                foreach ($custom_item_name as $key => $value) {
                    if ($custom_item_name[$key] != null && $custom_item_qty[$key] > 0) {
                        if ($request->has_tax == 'on' && isset($custom_item_tax[$row_no[$key]])) {
                            $i = 0;
                            $customInvoiceTax = [];
                            foreach ($custom_item_tax[$row_no[$key]] as $tax) {
                                $selectedTax = $taxTable->where('id', $tax)->first();
                                if ($selectedTax) {
                                    $taxAmount = (new TaxType)->calculateTax($selectedTax->tax_rate, $request->tax_type, $request->discount_on, validateNumbers($custom_item_price[$key]) * validateNumbers($custom_item_qty[$key]), $custom_item_discount[$key], $custom_item_discount_type[$key], $request->other_discount_amount, $request->other_discount_type, $request->indivisual_discount_price);
                                    $customInvoiceTax[$i]['sale_order_detail_id'] = $saleInvoiceDetails[$key]->id;
                                    $customInvoiceTax[$i]['tax_type_id'] = $tax;
                                    $customInvoiceTax[$i]['tax_amount'] = $taxAmount;
                                    $i++;
                                }
                            }
                            DB::table('sale_taxes')->insert($customInvoiceTax);
                        }
                    }
                }
            }

            # region store files
            if (!empty($request->attachments)) {
                $path = createDirectory("public/uploads/invoice_order");
                $invoiceFiles = (new File)->store($request->attachments, $path, 'Direct Invoice', $saleInvoice->id, ['isUploaded' => true, 'isOriginalNameRequired' => true]);
            }
            # end region

            // Custom items end
            \Session::flash('success', __('Successfully Saved'));
            if ($request->menu == 'sales') {
                return "invoice/view-detail-invoice/" . $saleInvoice->id;
            } else {
                return "invoice/view-detail-invoice/" . $saleInvoice->id . "?" . $flag;
            }
        });

        return redirect()->intended($url);
    }

    /**
     * Show the form for editing the specified resource.
     * @param  [int] $orderNo [description]
     * @return render view
     */
    public function edit($orderNo)
    {
        $data = [];
        $data['menu'] = 'sales';
        if (isset($_GET['type']) && $_GET['type'] == 'project') {
            $data['menu'] = 'project';
        }
        $data['sub_menu'] = 'sales/direct-invoice';
        $data['page_title'] = __('Edit Invoice');
        $data['url'] = 'invoice/list';

		$data['invoiceData'] = SaleOrder::with(['customer:id'])->find($orderNo);
        if (empty($data['invoiceData'])) {
            \Session::flash('fail', __('The data you are trying to access is not found.'));
            return redirect()->back();
        }
        if (!empty($data['invoiceData']->project_id)) {
            $data['project_id'] = $data['invoiceData']->project_id;
            $data['projects'] = Project::all();
        }
        $data['customerData'] = Customer::with(['currency:id,name,symbol', 'CustomerBranch'])->find($data['invoiceData']->customer_id);
        $data['currencySymbol'] = $data['customerData']->currency->symbol;

        foreach ($data['invoiceData']->saleOrderDetails as $key => $value) {
            if ($data['invoiceData']->has_tax == 1 && $value->quantity > 0 ) {
                $value->taxList = (new SaleTax)->getSaleTaxes($value->id);
            }
        }

        $data['files'] = (new File)->getFiles('Direct Invoice', $orderNo);
        if (!empty($data['files'])) {
            $data['filePath'] = "public/uploads/invoice_order";
            foreach ($data['files'] as $key => $value) {
                $value->icon = getFileIcon($value->file_name);
                $explodes = explode("_", $value->file_name);
                $value->originalName = implode("_", array_slice($explodes, 1, count($explodes) - 1));
            }
        }

        $data['locations']      = Location::getAll()->where('is_active', 1);
        $data['currencies']     = Currency::getAll();
        $data['paymentTerms']   = PaymentTerm::getAll();
        $data['taxTypeList']    = TaxType::getAll();
        $data['currencies']     = Currency::getAll();
        $data['countries']      = Country::getAll();
        $data['default_currency'] = Preference::getAll()->where('field', 'dflt_currency_id')->where('category', 'company')->first();
        $data['default_currency_symbol'] = Currency::find($data['default_currency']->value);

        $data['taxes'] = json_encode($data['taxTypeList']);

        $taxOptions = '';
        $selectStart = "<select name='item_tax[]' class='inputTax form-control bootstrap-select selectpicker' multiple>";
        $selectEnd = "</select>";
        $selectStartCustom = "<select class='inputTax form-control bootstrap-select selectpicker' multiple name='custom_item_tax[1][]'>";
        $selectEndCustom = "</select>";
        $taxHiddenField="";
        foreach ($data['taxTypeList'] as $key => $value) {
            $taxHiddenField.="<input type='hidden' class='itemTaxAmount itemTaxAmount-".$value->id."'>";
            $taxOptions .= "<option title='" . $value->tax_rate . "%' value='" . $value->id . "' taxrate='" . $value->tax_rate . "'>" . $value->name . '(' . $value->tax_rate . ')' . "</option>";
        }
        $data['tax_type'] = $selectStart . $taxOptions . $selectEnd;
        $data['custom_tax_type'] = $selectStartCustom . $taxOptions . $selectEndCustom.$taxHiddenField;
        $data['tax_type_custom'] = $selectStartCustom . $taxOptions . $selectEndCustom.$taxHiddenField;
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['decimal_digits'] = $preference['decimal_digits'];
        $data['thousand_separator'] = $preference['thousand_separator'];
        $data['symbol_position'] = $preference['symbol_position'];
        $data['exchange_rate_decimal_digits'] = $preference['exchange_rate_decimal_digits'];

        return view('admin.invoice.edit', $data);
    }

    /**
     * [update description]
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $userId = Auth::user()->id;
        $invoice_no = $request->order_no;
        $this->validate($request, [
            'location_id' => 'required',
            'order_date' => 'required',
            'currency_id' => 'required',
            'custom_item_name.*' => 'sometimes|required',
            'old_item_name.*' => 'sometimes|required',
            'item_name.*' => 'sometimes|required',
            'order_no' => 'required',
            'exchange_rate' => 'required',
        ]);
        $flag = "";
        if ($request->menu != 'sales') {
            if ($request->menu == 'project') {
                $flag = "?type=project&project_id=".$request->project_id;
            }
        }

        $url = DB::transaction(function () use ($request, $userId, $invoice_no, $flag) {

            /* input field variables for inventory items */
            $item_id = $request->item_id;
            $sorting_no = $request->sorting_no;
            $item_name = $request->item_name;
            $item_qty = $request->item_qty;
            $item_price = $request->item_price;
            $item_hsn = $request->item_hsn;
            $item_discount  = $request->item_discount;
            $item_discount_type = $request->item_discount_type;
            $item_tax = $request->item_tax;
            $item_description = $request->item_description;

            /* input field variables for Custom items */
            $row_no = $request->row_no;
            $custom_sorting_no = $request->custom_sorting_no;
            $custom_item_name = $request->custom_item_name;
            $custom_item_qty = $request->custom_item_qty;
            $custom_item_price = $request->custom_item_price;
            $custom_item_hsn = $request->custom_item_hsn;
            $custom_item_discount = $request->custom_item_discount;
            $custom_item_discount_type = $request->custom_item_discount_type;
            $custom_item_tax = $request->custom_item_tax;
            $custom_item_description = $request->custom_item_description;

            /* old input field variables */
            $item_details_id = $request->item_details_id;
            $old_item_id = $request->old_item_id;
            $old_sorting_no = $request->old_sorting_no;
            $old_item_name = $request->old_item_name;
            $old_item_qty = $request->old_item_qty;
            $old_item_price = $request->old_item_price;
            $old_item_hsn = $request->old_item_hsn;
            $old_item_discount = $request->old_item_discount;
            $old_item_discount_type = $request->old_item_discount_type;
            $old_item_tax = $request->old_item_tax;
            $old_item_description = $request->old_item_description;

            $isError = false;
            if (!empty($item_qty)) {
                foreach ($item_qty as $key => $qty) {
                    if (validateNumbers($qty) <= 0) {
                        Session::flash('danger', __('Item quantity can not be zero.'));
                        $isError = true;
                        break;
                    }
                }
            }
            if (!empty($custom_item_qty)) {
                foreach ($custom_item_qty as $key => $qty) {
                    if (validateNumbers($qty) <= 0) {
                        Session::flash('danger', __('Item quantity can not be zero.'));
                        $isError = true;
                        break;
                    }
                }
            }
            if (!empty($old_item_qty)) {
                foreach ($old_item_qty as $key => $qty) {
                    if (validateNumbers($qty) <= 0) {
                        Session::flash('danger', __('Item quantity can not be zero.'));
                        $isError = true;
                        break;
                    }
                }
            }
            if ($isError) {
                return 'invoice/edit/' . $invoice_no;
            }

            $taxTable = TaxType::getAll();

            # region update sales_order table
            $saleInvoice = (new SaleOrder)->updateOrder($request, $invoice_no);
            $mergedIds = $saleInvoice->saleOrderDetails->pluck('id')->toArray();
            $deleteQuotationTaxes = SaleTax::whereIn('sale_order_detail_id', $mergedIds)->delete();
            # endregion
            if (isset($item_details_id) && count($item_details_id) > 0) {
                # region remove the deleted item
                $orderItemRowIds = SaleOrderDetail::where('sale_order_id', [$saleInvoice->id])->pluck('id');
                foreach ($orderItemRowIds as $key => $orderItemRowId) {
                    if (!in_array($orderItemRowId, $item_details_id)) {
                        $detail = SaleOrderDetail::find($orderItemRowId);
                        if (!empty($detail->item_id)) {
                            DB::table('stock_moves')
                                ->where(['transaction_type_id' => $saleInvoice->id, 'item_id' => $detail->item_id, 'transaction_type_detail_id' => $detail->id])->delete();
                        }
                        $detail->delete();
                    }
                }
                # endregion
                $isAvailable = true;
                # region update the edited items
                foreach ($item_details_id as $key => $value) {
                    $itemDetails = Item::find($old_item_id[$key]);
                    if (isset($itemDetails->is_stock_managed)) {
                        if (!empty($itemDetails->is_stock_managed) && $itemDetails->is_stock_managed == 1) {
                            $saleOrderDetail = SaleOrderDetail::find($value, ['quantity']);
                            $available = StockMove::where(['item_id' => $old_item_id[$key], 'location_id' => $request->location_id])->get()->sum('quantity');
                            if (($saleOrderDetail->quantity + $available) < $old_item_qty[$key]) {
                                $isAvailable = false;
                                break;
                            }
                        }
                    }
                }
                if (!$isAvailable) {
                    Session::flash('fail', __('Item not available in stock.'));
                    return "invoice/view-detail-invoice/" . $saleInvoice->id . $flag;
                }
                $updatedInvoiceList = (new SaleOrderDetail)->updateMassDetails($request, $item_details_id, $old_item_id, $old_item_description, $old_item_name, $old_item_price, $old_item_qty, 0, $old_item_discount, $old_item_discount_type, $old_item_hsn, $old_sorting_no, $old_item_tax);
                $invoiceTax = [];
                $i = 0;
                foreach ($item_details_id as $key => $value) {
                    if ($request->has_tax == 'on' && isset($old_item_tax[$value])) {
                        foreach ($old_item_tax[$value] as $tax) {
                            $selectedTax = $taxTable->where('id', $tax)->first();
                            if ($selectedTax) {
                                $taxAmount = (new TaxType)->calculateTax($selectedTax->tax_rate, $request->tax_type, $request->discount_on, validateNumbers($old_item_price[$key]) * validateNumbers($old_item_qty[$key]), $old_item_discount[$key], $old_item_discount_type[$key], $request->other_discount_amount, $request->other_discount_type, $request->indivisual_discount_price);

                                $invoiceTax[$i]['sale_order_detail_id'] = $value;
                                $invoiceTax[$i]['tax_type_id'] = $tax;
                                $invoiceTax[$i]['tax_amount'] = $taxAmount;
                                $i++;
                            }
                        }
                    }
                    // Update stock_move table
                    if ($old_item_id[$key]) {
                        $stockMove['quantity'] = '-' . validateNumbers($old_item_qty[$key]);
                        DB::table('stock_moves')->where(['item_id' => $old_item_id[$key], 'reference' => 'store_out_' . $invoice_no])->update($stockMove);
                    }
                }
                DB::table('sale_taxes')->insert($invoiceTax);
                # endregion
            } else {
                DB::table('sale_order_details')
                    ->where(['sale_order_id' => $invoice_no])
                    ->delete();
                DB::table('stock_moves')
                    ->where(['transaction_type_id' => $invoice_no, 'transaction_type' => 'SALESINVOICE'])
                    ->delete();
            }

            # region inventory item add
            if (!empty($item_id)) {
                # region update the edited items
                $isAvailable = true;
                foreach ($item_id as $key => $value) {
                    $itemDetails = Item::find($value);
                    if (isset($itemDetails->is_stock_managed)) {
                        if (!empty($itemDetails->is_stock_managed) && $itemDetails->is_stock_managed == 1) {
                            $available = StockMove::where(['item_id' => $value, 'location_id' => $request->location_id])->get()->sum('quantity');
                            if ($available < $item_qty[$key]) {
                                $isAvailable = false;
                                break;
                            }
                        }
                    }
                }
                if (!$isAvailable) {
                    Session::flash('fail', __('Item not available in stock.'));
                    return "invoice/view-detail-invoice/" . $saleInvoice->id . $flag;
                }

                $newInvoiceList = (new SaleOrderDetail)->storeMass($request, $invoice_no, $item_id, $item_description, $item_name, $item_price, 0, $item_qty, 0, $item_discount, $item_discount_type, $item_hsn, $sorting_no, $item_tax);

                $i = 0;
                $invoiceTax = [];
                foreach ($item_id as $key => $item) {
                    if ($item_qty[$key] > 0) {
                        if ($request->has_tax == 'on' && isset($item_tax[$item_id[$key]])) {
                            foreach ($item_tax[$item_id[$key]] as $tax) {
                                $selectedTax = $taxTable->where('id', $tax)->first();
                                if ($selectedTax) {
                                    $taxAmount = (new TaxType)->calculateTax($selectedTax->tax_rate, $request->tax_type, $request->discount_on, validateNumbers($item_price[$key]) * validateNumbers($item_qty[$key]), $item_discount[$key], $item_discount_type[$key], $request->other_discount_amount, $request->other_discount_type, $request->indivisual_discount_price);
                                    $invoiceTax[$i]['sale_order_detail_id'] = $newInvoiceList[$key]->id;
                                    $invoiceTax[$i]['tax_type_id'] = $tax;
                                    $invoiceTax[$i]['tax_amount'] = $taxAmount;
                                    $i++;
                                }
                            }
                        }

                        // create stockMove
                        $stockMove = new StockMove();
                        $stockMove->item_id = $item_id[$key];
                        $stockMove->transaction_type_id = $invoice_no;
                        $stockMove->transaction_type = 'SALESINVOICE';
                        $stockMove->location_id = $request->location_id;
                        $stockMove->transaction_date = DbDateFormat($request->order_date);
                        $stockMove->user_id = $userId;
                        $stockMove->transaction_type_detail_id = $newInvoiceList[$key]->id;
                        $stockMove->reference = 'store_out_' . $newInvoiceList[$key]->id;
                        $stockMove->quantity = '-' . validateNumbers($item_qty[$key]);
                        $stockMove->save();
                    }
                }
                DB::table('sale_taxes')->insert($invoiceTax);
            }
            # endregion

            # region Custom items
            if (!empty($row_no)) {
                $saleInvoiceDetails = (new SaleOrderDetail)->storeCustomItems($request, $saleInvoice->id, null, $custom_item_description, $custom_item_name, $custom_item_price, 0, $custom_item_qty, 0, $custom_item_discount, $custom_item_discount_type, $custom_item_hsn, $custom_sorting_no, $request->custom_item_tax, $row_no);
                $i = 0;
                $customInvoiceTax = [];
                foreach ($custom_item_name as $key => $value) {
                    // custom item order detail
                    if ($custom_item_name[$key] != null && $custom_item_qty[$key] > 0 && $request->has_tax == 'on' && isset($custom_item_tax[$row_no[$key]]))  {
                        foreach ($custom_item_tax[$row_no[$key]] as $tax) {
                            $selectedTax = $taxTable->where('id', $tax)->first();
                            if ($selectedTax) {
                                $taxAmount = (new TaxType)->calculateTax($selectedTax->tax_rate, $request->tax_type, $request->discount_on, validateNumbers($custom_item_price[$key]) * validateNumbers($custom_item_qty[$key]), $custom_item_discount[$key], $custom_item_discount_type[$key], $request->other_discount_amount, $request->other_discount_type, $request->indivisual_discount_price);
                                $customInvoiceTax[$i]['sale_order_detail_id'] = $saleInvoiceDetails[$key]->id;
                                $customInvoiceTax[$i]['tax_type_id'] = $tax;
                                $customInvoiceTax[$i]['tax_amount'] = $taxAmount;
                                $i++;
                            }
                        }
                    }
                }
                DB::table('sale_taxes')->insert($customInvoiceTax);
            }
            # endregion
            // Uploading files
            if (!empty($request->attachments)) {
                $path = createDirectory("public/uploads/invoice_order");
                $invoiceFiles = (new File)->store($request->attachments, $path, 'Direct Invoice', $saleInvoice->id, ['isUploaded' => true, 'isOriginalNameRequired' => true]);
            }
            # endregion
            Session::flash('success', __('Successfully updated'));
            return "invoice/view-detail-invoice/" . $saleInvoice->id . $flag;
        });
        return redirect()->intended($url);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $url = "invoice/list";
        if (isset($id)) {
            try {
                $record = SaleOrder::find($id);
                if ($record) {
                    DB::beginTransaction();
                    $transactionReferenceIds = CustomerTransaction::where('sale_order_id', $id)->pluck('transaction_reference_id')->toArray();
                    $deleteTransactions = Transaction::whereIn('transaction_reference_id', $transactionReferenceIds)->delete();
                    $deleteCustomerTransactions = CustomerTransaction::where('sale_order_id', $id)->delete();
                    $deleteTransactionReferences = TransactionReference::whereIn('id', $transactionReferenceIds)->delete();
                    $deleteStockMoves = StockMove::where(['transaction_type_id' => $record->id, 'transaction_type' => 'SALESINVOICE'])->delete();
                    $deleteInvoiceDetails = SaleOrderDetail::where('sale_order_id', $record->id)->delete();
                    $deleteInvoiceFiles = (new File)->deleteFiles('Direct Order', $record->id, [], $path = 'invoice_order');
                    $deleteOrder = $record->delete();
                    DB::commit();
                    \Session::flash('success', __('Deleted Successfully.'));
                    if ($request->sub_menu == 'customer') {
                        $url = "customer/invoice/".$request->customer;
                    } else if ($request->sub_menu == 'users') {
                        $url = "user/sales-invoice-list/".$user_id;
                    }
                } else {
                    \Session::flash('fail', __('Delete Failed'));
                }
            } catch (Exception $e) {
                DB::rollBack();
                \Session::flash('fail', __('Delete Failed'));
            }
        } else {
            \Session::flash('fail', __('Delete Failed'));
        }
        return redirect()->intended($url);
    }

    /**
     * @param  [int] $orderNo
     * @param  [int] $invoiceNo
     * @return render view
     */
    public function view($invoiceNo)
    {
        $data['menu']     = 'sales';
        if (isset($_GET['type']) && $_GET['type'] == 'project') {
            $data['menu'] = 'project';
            $data['changeSubMenu'] = "type=project&project_id=".$_GET['project_id'];
        }
        $data['sub_menu'] = 'sales/direct-invoice';
        $data['page_title'] = __('View Invoice');
        $data['invoice_no']       = $invoiceNo;
        $preference               = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['exchange_rate_decimal_digits'] = $preference['exchange_rate_decimal_digits'];
        $data['dflt_currency_id'] = $preference['dflt_currency_id'];
        $data['saleInvoiceData']  = SaleOrder::with([
                                                'location:id,name',
                                                'paymentTerm:id,days_before_due',
                                                'currency:id,name,symbol',
                                                'saleOrderDetails',
                                                'customer:id,first_name,last_name,email,phone',
                                                'customerBranch:id,name,billing_street,billing_city,billing_state,billing_zip_code,billing_country_id', 'parent:id,order_reference_id,reference'
                                            ])->whereIn('transaction_type', ['SALESINVOICE', 'POSINVOICE'])->find($invoiceNo);
        if (empty($data['saleInvoiceData'])) {
            Session::flash('fail', __('Invoice not available'));
            return redirect()->intended('invoice/list');
        }
        $data['invoiceShortUrl']  = UrlShortner::shortURL(url('/').'/invoice/view-detail-invoice/'.$invoiceNo);
        $data['saleOrderData']    = $data['saleInvoiceData'] != "POSINVOICE" ? SaleOrder::with(['location:id,name'])->find($data['saleInvoiceData']->order_reference_id) : null;
        foreach ($data['saleInvoiceData']->saleOrderDetails as $key => $value) {
            if ($data['saleInvoiceData']->has_tax == 1 && $value->quantity > 0) {
                $value->taxList = (new SaleTax)->getSaleTaxesInPercentage($value->id);
            }
        }
        $data['taxes']            = (new SaleOrder)->calculateTaxRows($invoiceNo);
        $data['paymentMethods']   = PaymentMethod::getAll()->where('is_active', 1)->toArray();
        $data['paymentsList']     = CustomerTransaction::where('sale_order_id', $invoiceNo)->latest('id')->get();
        $data['currencies']       = Currency::getAll()->pluck('name', 'id')->toArray();
        $emailTemplateId          = $data['saleInvoiceData']->transaction_type == "POSINVOICE" ? 19 : 4;
        $data['emailInfo']        = EmailTemplate::getAll()
                                    ->where('template_id', $emailTemplateId)
                                    ->where('language_short_name', $preference['dflt_lang'])
                                    ->where('template_type', 'email')
                                    ->first();
        $data['billingCountry'] = isset($data['saleInvoiceData']->customer->customerBranch->billing_country_id) && $data['saleInvoiceData']->customer->customerBranch->billing_country_id != 0 ? (new Country)->getCountry($data['saleInvoiceData']->customer->customerBranch->billing_country_id) : '';
        $smsInfo = EmailTemplate::getAll()
                ->where('template_id', $emailTemplateId)
                ->where('language_short_name', $preference['dflt_lang'])
                ->where('template_type', 'sms')
                ->first();
        $bodyInfo = str_replace('{invoice_reference_no}', $data['saleInvoiceData']->reference, $smsInfo->body);
        $bodyInfo = str_replace('{order_date}', formatDate($data['saleInvoiceData']->order_date), $bodyInfo);
        $bodyInfo = str_replace('{company_name}', $preference['company_name'], $bodyInfo);
        $data['smsInformation'] = $bodyInfo;

        $data['accounts']         = Account::where('is_deleted', '!=', 1)->get();
        $data['item_tax_types']   = TaxType::getAll();

        if ($data['saleInvoiceData']->pos_shipping) {
            $data['saleInvoiceData']->shipping_address = json_decode($data['saleInvoiceData']->pos_shipping);
            if ($data['saleInvoiceData']->shipping_address->ship_country_id) {
                $data['saleInvoiceData']->shipping_address->ship_country = Country::where('code', $data['saleInvoiceData']->shipping_address->ship_country_id)
                                                                                ->first()
                                                                                ->country;
            } else {
                $data['saleInvoiceData']->shipping_address->ship_country = "";
            }
        }
        $reference = TransactionReference::where('reference_type', 'INVOICE_PAYMENT')->latest('id')->first();
        if (!empty($reference)) {
            $info  = explode('/', $reference->code);
            $refNo = (int)$info[0];
            $data['reference'] = sprintf("%03d", $refNo + 1) . '/' . date('Y');
        } else {
            $data['reference'] = sprintf("%03d", 1) . '/' . date('Y');
        }

        $data['files'] = (new File)->getFiles($data['saleInvoiceData']->order_type, $invoiceNo);
        if (!empty($data['files'])) {
            $data['filePath'] = "public/uploads/invoice_order";
            foreach ($data['files'] as $key => $value) {
                $value->icon = getFileIcon($value->file_name);
                $value->extension = strtolower(pathinfo($value->file_name, PATHINFO_EXTENSION));
            }
        }

        return view('admin.invoice.view', $data);
    }

    /**
     * @param  [int] $orderNo
     * @param  [int] $invoiceNo
     * @return render view
     */
    public function copy($invoiceNo)
    {
        if (isset($_GET['customer'])) {
            $data['menu'] = 'relationship';
            $data['sub_menu'] = 'customer';
        } else if (isset($_GET['users'])) {
            $data['menu'] = 'relationship';
            $data['sub_menu'] = 'users';
        } else {
            $data['menu'] = 'sales';
            $data['sub_menu'] = 'sales/direct-invoice';
        }
        $data['page_title'] = __('Copy Invoice');
        $data['invoiceData'] = SaleOrder::with(['location:id,name',
                                                'paymentTerm:id,days_before_due',
                                                'currency:id,name,symbol',
                                                'saleOrderDetails'
                                            ])->find($invoiceNo);
        if (empty($data['invoiceData']->saleOrderDetails)) {
            Session::flash('fail', "Invoice data does not exist.");
            return redirect('invoice/list');
        }
        foreach ($data['invoiceData']->saleOrderDetails as $key => $value) {
            if ($data['invoiceData']->has_tax == 1 && $value->quantity > 0 ) {
                $value->taxList = (new SaleTax)->getSaleTaxes($value->id);
            }
        }
        $invoiceReference = SaleOrder::where('transaction_type', 'SALESINVOICE')->latest('id')->first(['reference']);
        if (!empty($invoiceReference)) {
            $ref = explode("-", $invoiceReference->reference);
            $data['invoice_count'] = (int)$ref[1]+1;
        } else {
            $data['invoice_count'] = 1;
        }
        $data['customerData'] = Customer::with('currency:id,name,symbol', 'CustomerBranch')->find($data['invoiceData']->customer_id);
        $data['currencySymbol'] = $data['customerData']->currency->symbol;
        $data['files'] = (new File)->copyFiles("public/uploads/invoice_order", "public/contents/temp", $data['invoiceData']->order_type, $invoiceNo, "Direct Invoice", null, ['isTemporary' => true]);
        $data['files'] = json_decode($data['files']);
        $data['filePath'] = "public/contents/temp";
        $data['locations']      = Location::getAll()->where('is_active', 1);
        $data['currencies']     = Currency::getAll();
        $data['paymentTerms']   = PaymentTerm::getAll();
        $data['taxTypeList']    = TaxType::getAll();
        $data['countries']      = Country::getAll();
        $data['taxes'] = json_encode($data['taxTypeList']);
        $taxOptions = '';
        $selectStart = "<select name='item_tax[]' class='inputTax form-control bootstrap-select selectpicker' multiple>";
        $selectEnd = "</select>";
        $selectStartCustom = "<select class='inputTax form-control bootstrap-select selectpicker' multiple name='custom_item_tax[1][]'>";
        $selectEndCustom = "</select>";
        $taxHiddenField="";
        foreach ($data['taxTypeList'] as $key => $value) {
            $taxHiddenField .="<input type='hidden' class='itemTaxAmount itemTaxAmount-".$value->id."'>";
            $taxOptions .= "<option title='" . $value->tax_rate . "%' value='" . $value->id . "' taxrate='" . $value->tax_rate . "'>" . $value->name . '(' . $value->tax_rate . ')' . "</option>";
        }
        $data['tax_type'] = $selectStart . $taxOptions . $selectEnd;
        $data['custom_tax_type'] = $selectStartCustom . $taxOptions . $selectEndCustom . $taxHiddenField;
        $data['tax_type_custom'] = $selectStartCustom . $taxOptions . $selectEndCustom . $taxHiddenField;
        $data['default_currency'] = Preference::getAll()->where('field', 'dflt_currency_id')->where('category', 'company')->first();
        $data['exchange_rate_decimal_digits'] =  Preference::getAll()->where('field', 'exchange_rate_decimal_digits')->first()->value;

        return view('admin.invoice.copy', $data);
    }

    /**
     * Copy specified invoice
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function invoiceCopy(Request $request)
    {
        $userId = Auth::user()->id;
        $this->validate($request, [
            'reference' => 'required|unique:sale_orders',
            'location_id' => 'required',
            'order_date' => 'required',
            'customer_id' => 'required',
            'currency_id' => 'required',
            'custom_item_name.*'=>'sometimes|required',
            'item_name.*'=>'sometimes|required',
            'exchange_rate'=>'required',
        ]);
        $url = DB::transaction(function () use ($request, $userId) {
            $flag = "";
            if ($request->menu != 'sales') {
                $flag = "?" . $request->sub_menu;
            }
            $customerId = $request->customer_id;
            $customer_branch = CustomerBranch::where('customer_id', $customerId)->first(['id']);
            $request->customer_branch_id = $customer_branch->id;
            $row_counter = $request->row_counter;

            /* input field variables for inventory items */
            $item_id = $request->item_id;
            $sorting_no = $request->sorting_no;
            $item_name = $request->item_name;
            $item_qty = $request->item_qty;
            $item_price = $request->item_price;
            $item_hsn = $request->item_hsn;
            $item_tax = $request->item_tax;
            $item_discount = $request->item_discount;
            $item_discount_type = $request->item_discount_type;

            /* input field variables for Custom items */
            $row_no = $request->row_no;
            $custom_sorting_no = $request->custom_sorting_no;
            $custom_item_name = $request->custom_item_name;
            $custom_item_qty = $request->custom_item_qty;
            $custom_item_price = $request->custom_item_price;
            $custom_item_hsn = $request->custom_item_hsn;
            $custom_item_tax = $request->custom_item_tax;
            $custom_item_discount = $request->custom_item_discount;
            $custom_item_discount_type = $request->custom_item_discount_type;
            $custom_item_description = $request->custom_item_description;
            if (!empty($item_id)) {
                $isAvailable = true;
                foreach ($item_id as $key => $value) {
                    $itemDetails = Item::find($value);
                    if (isset($itemDetails->is_stock_managed)) {
                        if (!empty($itemDetails->is_stock_managed) && $itemDetails->is_stock_managed == 1) {
                            $available = StockMove::where(['item_id' => $value, 'location_id' => $request->location_id])->get()->sum('quantity');
                            if ($available < validateNumbers($item_qty[$key])) {
                                $isAvailable = false;
                                break;
                            }
                        }
                    }
                }
                if (!$isAvailable) {
                    Session::flash('danger', __('Item not available in stock.'));
                    return "invoice/copy/$request->order_no" . $flag;
                }
            }

            $isError = false;
            if (!empty($item_qty)) {
                foreach ($item_qty as $key => $qty) {
                    if (validateNumbers($qty) <= 0) {
                        Session::flash('danger', __('Item quantity can not be zero.'));
                        $isError = true;
                        break;
                    }
                }
            }
            if (!empty($custom_item_qty)) {
                foreach ($custom_item_qty as $key => $qty) {
                    if (validateNumbers($qty) <= 0) {
                        Session::flash('danger', __('Item quantity can not be zero.'));
                        $isError = true;
                        break;
                    }
                }
            }
            if (!empty($old_item_qty)) {
                foreach ($old_item_qty as $key => $qty) {
                    if (validateNumbers($qty) <= 0) {
                        Session::flash('danger', __('Item quantity can not be zero.'));
                        $isError = true;
                        break;
                    }
                }
            }

            if (!empty($old_item_id)) {
                $isAvailable = true;
                foreach ($item_id as $key => $value) {
                    $oldItemDetails = Item::find($value);
                    if ($oldItemDetails->is_stock_managed == 1) {
                        $available = StockMove::where(['item_id' => $value, 'location_id' => $request->location_id])->get()->sum('quantity');
                        if ($available < validateNumbers($item_qty[$key])) {
                            $isAvailable = false;
                            break;
                        }
                    }
                }
                if (!$isAvailable) {
                    Session::flash('danger', __('Item not available in stock.'));
                    return "invoice/copy/" . $request->order_no . $flag;
                }
            }

            if ($isError) {
                return 'invoice/list';
            }

            $taxTable = TaxType::getAll();

            # region salesOrder create
            // create salesOrder start
            $saleInvoice = (new SaleOrder)->store($request, 'SALESINVOICE', 'Direct Invoice', $request->reference);

            if (isset($request->project_id)) {
               // Insert Activity
                (new Activity)->store('Project', $request->project_id, 'user', Auth::user()->id, __('A new invoice has been created.'));
            }
            # endregion

            // Custom items
            $tax_counter = 1;
            if (!empty($row_counter)) {
                foreach ($row_counter as $key => $value) {
                    $itemDescription = $request->has_description == 'on' ? $request->item_description[$key] : "";

                    $item_discount[$key]        = $item_discount[$key] ? $item_discount[$key] : 0;
                    $discountAmount = 0;
                    if ($request->has_item_discount == 'on') {
                        if ($item_discount_type[$key] == '$') {
                            $discountAmount = validateNumbers($item_discount[$key]);
                        } else {
                            $discountAmount = validateNumbers($item_discount[$key]) * validateNumbers($item_price[$key]) * validateNumbers($item_qty[$key]) / 100;
                        }
                    }
                    $itemDiscount      = $request->has_item_discount == 'on' ? validateNumbers($item_discount[$key]) : 0;
                    $itemDiscount_type = $request->has_item_discount == 'on' ? $item_discount_type[$key] : '%';
                    $itemHsn           = $request->has_hsn == 'on' ? $item_hsn[$key] : "";

                    $invoiceItemDetail = (new SaleOrderDetail)->store($saleInvoice->id, $item_id[$key], $itemDescription, $item_name[$key], $item_price[$key], 0, $item_qty[$key], $discountAmount, $itemDiscount, $itemDiscount_type, $itemHsn, $sorting_no[$key]);

                    if (!empty($invoiceItemDetail) && !empty($invoiceItemDetail->id) && !empty($invoiceItemDetail->item_id)) {
                        $stockMove = new StockMove();
                        $stockMove->item_id = $invoiceItemDetail->item_id;
                        $stockMove->transaction_type_id = $saleInvoice->id;
                        $stockMove->transaction_type = 'SALESINVOICE';
                        $stockMove->location_id = $request->location_id;
                        $stockMove->transaction_date = date('Y-m-d');
                        $stockMove->user_id = $userId;
                        $stockMove->transaction_type_detail_id = $invoiceItemDetail->id;
                        $stockMove->reference = 'store_out_' . $saleInvoice->id;
                        $stockMove->quantity = '-'.validateNumbers($invoiceItemDetail->quantity);
                        $stockMove->price = validateNumbers($invoiceItemDetail->unit_price);
                        $stockMove->save();
                    }

                    if ($request->has_tax == 'on' && isset($item_tax[$row_counter[$key]])) {
                        $i = 0;
                        $invoiceTax = [];
                        foreach ($item_tax[$row_counter[$key]] as $tax) {
                            $selectedTax = $taxTable->where('id', $tax)->first();
                            if ($selectedTax) {
                                $taxAmount = (new TaxType)->calculateTax($selectedTax->tax_rate, $request->tax_type, $request->discount_on, validateNumbers($item_price[$key]) * validateNumbers($item_qty[$key]), $item_discount[$key], $item_discount_type[$key], $request->other_discount_amount, $request->other_discount_type, $request->indivisual_discount_price);
                                $invoiceTax[$i]['sale_order_detail_id'] = $invoiceItemDetail->id;
                                $invoiceTax[$i]['tax_type_id'] = $tax;
                                $invoiceTax[$i]['tax_amount'] = $taxAmount;
                                $i++;
                            }
                        }
                        $result = DB::table('sale_taxes')->insert($invoiceTax);
                    }
                }
            }

            if (!empty($row_no)) {
                $saleInvoiceDetails = (new SaleOrderDetail)->storeCustomItems($request, $saleInvoice->id, null, $custom_item_description, $custom_item_name, $custom_item_price, 0, $custom_item_qty, 0, $custom_item_discount, $custom_item_discount_type, $custom_item_hsn, $custom_sorting_no, $request->custom_item_tax, $row_no);

                foreach ($custom_item_name as $key => $value) {
                    if ($custom_item_name[$key] != null && $custom_item_qty[$key] > 0) {
                        if ($request->has_tax == 'on' && isset($custom_item_tax[$row_no[$key]])) {
                            $i = 0;
                            $customSaleTax = [];
                            foreach ($custom_item_tax[$row_no[$key]] as $tax) {
                                $selectedTax = $taxTable->where('id', $tax)->first();
                                if ($selectedTax) {
                                    $taxAmount = (new TaxType)->calculateTax($selectedTax->tax_rate, $request->tax_type, $request->discount_on, validateNumbers($custom_item_price[$key]) * validateNumbers($custom_item_qty[$key]), $custom_item_discount[$key], $custom_item_discount_type[$key], $request->other_discount_amount, $request->other_discount_type, $request->indivisual_discount_price);
                                    $customInvoiceTax[$i]['sale_order_detail_id'] = $saleInvoiceDetails[$key]->id;
                                    $customInvoiceTax[$i]['tax_type_id'] = $customSaleTax[$i]['tax_type_id'] = $tax;
                                    $customInvoiceTax[$i]['tax_amount'] = $customSaleTax[$i]['tax_amount'] = $taxAmount;
                                    $i++;
                                }
                            }
                            DB::table('sale_taxes')->insert($customInvoiceTax);
                        }
                    }
                }
            }
            # region store files
            if (!empty($request->attachments)) {
                $path = createDirectory("public/uploads/invoice_order");
                $invoiceFiles = (new File)->store($request->attachments, $path, 'Direct Invoice', $saleInvoice->id, ['isUploaded' => true, 'isOriginalNameRequired' => true]);
            }
            # end region
            // Custom items end
            if ($request->menu == 'sales') {
                \Session::flash('success', __('Successfully Saved'));
                return "invoice/view-detail-invoice/" . $saleInvoice->id;
            } else {
                \Session::flash('success', __('Successfully Saved'));
                return "invoice/view-detail-invoice/" . $saleInvoice->id ."?". $request->sub_menu;
            }
        });
        return redirect()->intended($url);
    }

    /**
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function sendInvoiceInformationByEmail(Request $request)
    {
        $this->validate($request, [
            'email' => ['required', new CheckValidEmail],
            'subject' => 'required',
            'message' => 'required'
        ]);
        ini_set('max_execution_time', 0);
        $orderNo = $request['order_id'];
        $invoiceNo = $request['invoice_id'];
        $invoiceName = 'invoice_' . time() . '.pdf';
        $emailConfig = DB::table('email_configurations')->first();
        $companyName = Preference::getAll()->where('category','company')->where('field', 'company_name')->first()->value;

        if ($emailConfig->status == 0 && $emailConfig->protocol == 'smtp') {
            return back()->withInput()->withErrors(['email' => "Verify your smtp settings of email"]);
        }
        if (isset($request['invoice_pdf']) && $request['invoice_pdf'] == 'on') {
            createDirectory("public/uploads/invoices");
            $this->invoicePdfEmail($orderNo, $invoiceNo, $invoiceName);
            $emailResponse = $this->email->sendEmailWithAttachment($request['email'], $request['subject'], $request['message'], $invoiceName, $companyName);
            if ($emailResponse['status'] == false) {
                return redirect()->back()->withInput()->withErrors(['fail' => $emailResponse['message']]);
             }

        } else {
            $emailResponse = $this->email->sendEmail($request['email'], $request['subject'], $request['message'], null, $companyName);

            if ($emailResponse['status'] == false) {
                return redirect()->back()->withInput()->withErrors(['fail' => $emailResponse['message']]);
             }
        }
        Session::flash('success', __('Email has been sent successfully.'));
        return redirect()->intended('invoice/view-detail-invoice/' . $invoiceNo);
    }

    /**
     * print specified invoice details
     * @param  [int] $orderNo
     * @param  [int] $invoiceNo
     * @return render view
     */
    public function invoicePrintPdf($invoiceNo)
    {
        $data['invoice_no']       = $invoiceNo;
        $preference               = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['dflt_currency_id'] = $preference['dflt_currency_id'];
        $data['saleInvoiceData']  = SaleOrder::with([
                                                'location:id,name',
                                                'paymentTerm:id,days_before_due',
                                                'currency:id,name,symbol',
                                                'saleOrderDetails',
                                                'customer:id,first_name,last_name,email,phone',
                                                'customerBranch:id,name,billing_street,billing_city,billing_state,billing_zip_code,billing_country_id'
                                            ])->find($invoiceNo);
        $data['saleOrderData']    = $data['saleInvoiceData'] != "POSINVOICE" ? SaleOrder::with(['location:id,name'])->find($data['saleInvoiceData']->order_reference_id) : null;
        if (empty($data['saleInvoiceData'])) {
            Session::flash('fail', __('Invoice not available'));
            return redirect()->intended('invoice/list');
        }
        foreach ($data['saleInvoiceData']->saleOrderDetails as $key => $value) {
            if ($data['saleInvoiceData']->has_tax == 1 && $value->quantity > 0) {
                $value->taxList = (new SaleTax)->getSaleTaxesInPercentage($value->id);
            }
        }
        $data['taxes']            = (new SaleOrder)->calculateTaxRows($invoiceNo);
        $data['paymentMethods']   = PaymentMethod::getAll();
        $data['paymentsList']     = CustomerTransaction::where('sale_order_id', $invoiceNo)->latest('id')->get();
        $data['currencies']       = Currency::getAll()->pluck('name', 'id')->toArray();
        $data['accounts']         = Account::where('is_deleted', '!=', 1)->get();
        $data['item_tax_types']   = TaxType::getAll();

        if ($data['saleInvoiceData']->pos_shipping) {
            $data['saleInvoiceData']->shipping_address = json_decode($data['saleInvoiceData']->pos_shipping);
            if ($data['saleInvoiceData']->shipping_address->ship_country_id) {
                $data['saleInvoiceData']->shipping_address->ship_country = Country::where('code', $data['saleInvoiceData']->shipping_address->ship_country_id)
                                                                                ->first()
                                                                                ->country;
            } else {
                $data['saleInvoiceData']->shipping_address->ship_country = "";
            }
        }
        $reference = TransactionReference::where('reference_type', 'INVOICE_PAYMENT')->latest('id')->first();
        if (!empty($reference)) {
            $info  = explode('/', $reference->code);
            $refNo = (int)$info[0];
            $data['reference'] = sprintf("%03d", $refNo + 1) . '/' . date('Y');
        } else {
            $data['reference'] = sprintf("%03d", 1) . '/' . date('Y');
        }
        $data['company_logo']   = Preference::getAll()->where('category','company')->where('field', 'company_logo')->first('value');
        $data['type'] = request()->get('type') == 'print' || request()->get('type') == 'pdf' ? request()->get('type') : 'print';

        return printPDF($data, 'invoice_' . time() . '.pdf', 'admin.invoice.print', view('admin.invoice.print', $data), $data['type']);
    }

    /**
     *
     * @param  [int] $orderNo     [description]
     * @param  [int] $invoiceNo   [description]
     * @param  [string] $invoiceName [description]
     * @return pdf
     */
    private function invoicePdfEmail($orderNo, $invoiceNo, $invoiceName)
    {
        $data['invoice_no']       = $invoiceNo;
        $preference               = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['dflt_currency_id'] = $preference['dflt_currency_id'];
        $data['saleInvoiceData']  = SaleOrder::with([
                                                'location:id,name',
                                                'paymentTerm:id,days_before_due',
                                                'currency:id,name,symbol',
                                                'saleOrderDetails',
                                                'customer:id,first_name,last_name,email,phone',
                                                'customerBranch:id,name,billing_street,billing_city,billing_state,billing_zip_code,billing_country_id'
                                            ])->find($invoiceNo);
        $data['saleOrderData']    = $data['saleInvoiceData'] != "POSINVOICE" ? SaleOrder::with(['location:id,name'])->find($data['saleInvoiceData']->order_reference_id) : null;
        if (empty($data['saleInvoiceData'])) {
            Session::flash('fail', __('Invoice not available'));
            return redirect()->intended('invoice/list');
        }
        foreach ($data['saleInvoiceData']->saleOrderDetails as $key => $value) {
            if ($data['saleInvoiceData']->has_tax == 1 && $value->quantity > 0) {
                $value->taxList = (new SaleTax)->getSaleTaxesInPercentage($value->id);
            }
        }
        $data['taxes']            = (new SaleOrder)->calculateTaxRows($invoiceNo);
        $data['paymentMethods']   = PaymentMethod::getAll();
        $data['paymentsList']     = CustomerTransaction::where('sale_order_id', $invoiceNo)->latest('id')->get();
        $data['currencies']       = Currency::getAll()->pluck('name', 'id')->toArray();
        $data['accounts']         = Account::where('is_deleted', '!=', 1)->get();
        $data['item_tax_types']   = TaxType::getAll();

        if ($data['saleInvoiceData']->pos_shipping) {
            $data['saleInvoiceData']->shipping_address = json_decode($data['saleInvoiceData']->pos_shipping);
            if ($data['saleInvoiceData']->shipping_address->ship_country_id) {
                $data['saleInvoiceData']->shipping_address->ship_country = Country::where('code', $data['saleInvoiceData']->shipping_address->ship_country_id)
                                                                                ->first()
                                                                                ->country;
            } else {
                $data['saleInvoiceData']->shipping_address->ship_country = "";
            }
        }
        $reference = TransactionReference::where('reference_type', 'INVOICE_PAYMENT')->latest('id')->first();
        if (!empty($reference)) {
            $info  = explode('/', $reference->code);
            $refNo = (int)$info[0];
            $data['reference'] = sprintf("%03d", $refNo + 1) . '/' . date('Y');
        } else {
            $data['reference'] = sprintf("%03d", 1) . '/' . date('Y');
        }

        $data['company_logo']   = Preference::getAll()->where('category','company')->where('field', 'company_logo')->first('value');
        $data['type'] = request()->get('type') == 'print' || request()->get('type') == 'pdf' ? request()->get('type') : 'print';

        return printPDF($data, public_path() . '/uploads/invoices/' . $invoiceName, 'admin.invoice.print', view('admin.invoice.print', $data), null, "email");

    }

    /**
     * Check reference no if exists
     * @param  Request $request
     * @return json
     */
    public function referenceValidation(Request $request)
    {
        $data = [];
        $data['status_no'] = 0;

        if (!empty($request->ref) && !empty(explode('-', $request->ref)[1])) {
            $result = SaleOrder::where('reference', $request->ref)->first();
            if ($result > 0) {
                $data['status_no'] = 1;
            }
            $data['status_no'] = 2;
        }

        return json_encode($data);
    }

    /**
     * Return customer Branches by customer id
     * @param  Request $request
     * @return json
     */
    public function customerBranches(Request $request)
    {
        $debtor_no = $request['debtor_no'];
        $data['status_no'] = 0;
        $branchs = '';
        $result = DB::table('cust_branch')->select('customer_id', 'id', 'br_name')->where('customer_id', $debtor_no)->orderBy('br_name', 'ASC')->get();
        if (!empty($result)) {
            $data['status_no'] = 1;
            foreach ($result as $key => $value) {
                $branchs .= "<option value='" . $value->id . "'>" . $value->br_name . "</option>";
            }
            $data['branchs'] = $branchs;
        }
        return json_encode($data);
    }

    /**
     * Search items
     * @param  Request $request
     * @return json
     */
    public function searchItem(Request $request)
    {
        $data['location']      = $request->loc_code;
        $data['type']          = $request->type;
        $data['currency_id']   = $request->currency_id;
        $data['exchange_rate'] = $request->exchange_rate;
        $data['saleType']      = $request->salesTypeId;
        $data['transactionType'] = 'Invoice';
        $data['key']           = $request->search;
        $result = (new Item)->search(json_encode($data));
    }

    /**
     * Return quantity validation result
     * @param  Request $request
     * @return json
     */
    public function quantityValidation(Request $request)
    {
        $data = array();
        $location = $request['location_id'];
        $setItem = $request['qty'];

        $items = DB::table('items')->where("id", $request['id'])->select('item_id')->first();

        $availableItem = $this->sale->stockValidate($location, $items->item_id);

        $data['availableItem'] = $availableItem;
        $data['message'] = __('Tax') . $availableItem;

        return json_encode($data);
    }

    /**
     *
     * @param  Request $request
     * @return json
     */
    public function checkItemQty(Request $request)
    {
        $data = array();
        $location = $request['loc_code'];
        $item_id = $request['item_id'];
        $itemQty = $this->sale->stockValidate($location, $item_id);

        if ($itemQty <= 0) {
            $data['status_no'] = 1;
        }

        return json_encode($data);
    }

    /**
     *
     * @param  Request $request
     * @return json
     */
    public function quantityValidationWithLocaltion(Request $request)
    {
        $location = $request['location'];
        $items = $request['itemInfo'];
        $data['status_no'] = 0;
        $data['item'] = __('Item is not sufficient.');

        foreach ($items as $result) {
            $qty = DB::table('stock_moves')
                ->select(DB::raw('sum(qty) as total'))
                ->where(['item_id' => $result['stockid'], 'loc_code' => $location])
                ->groupBy('loc_code')
                ->first();
            if (empty($qty)) {
                return json_encode($data);
            } else if ($qty < $result['qty']) {
                return json_encode($data);
            } else {
                $datas['status_no'] = 1;
                return json_encode($datas);
            }
        }
    }

    /**
     *
     * @param  Request $request
     * @return json
     */
    public function quantityValidationEditInvoice(Request $request)
    {
        $location = $request['location_id'];
        $item_id = $request['item_id'];
        $item_id = $request['item_id'];
        $set_qty = $request['qty'];
        $invoice_order_no = $request['invoice_no'];
        $order_reference = $request['order_reference'];
        $order = DB::table('sales_orders')->where('reference', $request['order_reference'])->select('id')->first();
        $orderItemQty = DB::table('sales_order_details')
            ->where(['sales_order_id' => $order->id, 'item_id' => $item_id])
            ->select('quantity')
            ->first();

        $salesItemQty = DB::table('stock_moves')
            ->where(['order_reference' => $order_reference, 'item_id' => $item_id, 'loc_code' => $location])
            ->where('reference', '!=', 'store_out_' . $invoice_order_no)
            ->sum('qty');

        $itemAvailable = $orderItemQty->quantity + ($salesItemQty);

        if ($set_qty > $itemAvailable) {
            $data['status_no'] = 0;
            $data['qty'] = "qty Insufficient";
        } else {
            $data['status_no'] = 1;
            $data['qty'] = "qty available";
        }
        return json_encode($data);
    }

    /**
     * download invoice list in csv format
     * @return \Illuminate\Http\Response
     */
    public function saleListCsv()
    {
        return Excel::download(new allInvoiceExport(), 'invoice_lists_'. time() .'.csv');
    }

    /**
     * download invoice list in pdf format
     * @return \Illuminate\Http\Response
     */
    public function salesListPdf(Request $request)
    {
        $data['from'] = isset($request->from) ? $request->from : null;
        $data['to'] = isset($request->to) ? $request->to : null;
        $data['customer'] = $customer = isset($request->customer) ? $request->customer : null;
        $data['location'] = isset($request->location) ? $request->location : null;
        $data['currency'] = isset($request->currency) ? $request->currency : null;
        $data['status'] = isset($request->status) ? $request->status : null;
        $data['locationName'] = !empty($data['location']) ? Location::getAll()->where('id', $data['location'])->first()->name : null;
        if (isset($customer) && !empty($customer)) {
            $data['customerData'] = Customer::find($customer);
        }
        $data['slaesList'] = $saleInvoices = (new SaleOrder)->getAllInvoices($data['from'], $data['to'], $data['customer'], $data['location'], $data['currency'], $data['status'])->orderBy('id', 'DESC')->get();
        $data['date_range'] = ($data['from'] && $data['to']) ? formatDate($data['from']) . ' To ' . formatDate($data['to']) : 'No Date Selected';

        return printPDF($data, 'invoice_lists_' . time() . '.pdf', 'admin.invoice.list-pdf', view('admin.invoice.list-pdf', $data), 'pdf', 'domPdf');
    }
}
