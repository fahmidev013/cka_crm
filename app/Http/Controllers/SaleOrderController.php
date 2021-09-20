<?php

namespace App\Http\Controllers;

use App\Models\{
    Bank,
    Country,
    Currency,
    Customer,
    CustomerBranch,
    EmailTemplate,
    File,
    InvoicePaymentTerm,
    Item,
    ItemTaxType,
    Location,
    PaymentMethod,
    PaymentTerm,
    Preference,
    Project,
    Reference,
    SaleOrder,
    SaleOrderDetail,
    SaleTax,
    SaleType,
    SmsConfig,
    StockMove,
    TaxType,
    UrlShortner,
    Activity
};
use App\DataTables\SaleOrderDataTable;
use App\Exports\allQuotationExport;
use App\Http\Controllers\EmailController;
use App\Rules\CheckValidEmail;
use App\Http\Requests;
use App\Http\Start\Helpers;
use Auth;
use DB;
use Excel;
use Illuminate\Http\Request;
use PDF;
use Session;

class SaleOrderController extends Controller
{
    public function __construct(EmailController $email)
    {
        $this->email = $email;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SaleOrderDataTable $dataTable)
    {
        $data = array();
        $data['page_title'] = __('Quotations');
        if (isset($_GET['customers'])) {
            $data['menu'] = 'relationship';
            $data['sub_menu'] = 'customer';
        } else {
            $data['menu'] = 'sales';
            $data['sub_menu'] = 'order/list';
        }

        $data['location'] = isset($_GET['location']) ? $_GET['location'] : NULL;
        $data['customer'] = isset($_GET['customer']) ? $_GET['customer'] : NULL;
        $data['currency'] = isset($_GET['currency']) ? $_GET['currency'] : NULL;

        $data['customerList'] = Customer::where('is_active', 1)->get(['id', 'name']);
        $data['locationList'] = Location::getAll()->where('is_active', 1);
        $data['currencyList'] = Currency::getAll();

        $fromDate = SaleOrder::where('transaction_type', 'SALESORDER')
                                ->orderBy('order_date')
                                ->first(['order_date']);

        $data['from'] = isset($_GET['from']) ? $_GET['from'] : null;
        $data['to'] = isset($_GET['to']) ? $_GET['to'] : null;
        $row_per_page = Preference::getAll()->where('field', 'row_per_page')->first()->value;

        return $dataTable->with('row_per_page', $row_per_page)->render('admin.saleOrders.orderListFilter', $data);
    }

    public function create()
    {
        $data = array();
        $data['menu'] = 'sales';
        $data['sub_menu'] = 'order/list';
        $data['page_title'] = __('Create Quotation');
        $data['url'] = 'order/list';
        $data['customerData'] = Customer::with(['currency:id,name,symbol'])->where(['is_active' => 1])->get();

        $data['countries']    = Country::getAll();
        $data['currencies']   = Currency::getAll();
        $data['paymentTerms'] = PaymentTerm::getAll();
        $data['locations']    = Location::getAll()->where('is_active', 1);
        $data['salesType']    = SaleType::select('sale_type', 'id')->get();

        $order_count        = SaleOrder::where('transaction_type', 'SALESORDER')->count();

        if ($order_count > 0) {
            $invoiceReference = SaleOrder::where('transaction_type', 'SALESORDER')
                                        ->latest('id')
                                        ->first(['reference']);
            $ref = explode("-", $invoiceReference->reference);
            $data['order_count'] = (int)$ref[1];
        } else {
            $data['order_count'] = 0;
        }
        $taxTypeList   = TaxType::getAll();
        $data['taxes'] = json_encode($taxTypeList);
        $taxOptions    = '';
        $selectStart   = "<select name='item_tax[]' class='inputTax form-control bootstrap-select selectpicker' multiple>";
        $selectEnd = "</select>";

        $selectStartCustom = "<select class='inputTax form-control bootstrap-select selectpicker' multiple name='custom_item_tax[1][]'>";
        $selectEndCustom   = "</select>";
        $taxHiddenField    ="";

        foreach ($taxTypeList as $key => $value) {
            $taxHiddenField .="<input type='hidden' class='itemTaxAmount itemTaxAmount-".$value->id."'>";
            $taxOptions .= "<option title='" . $value->tax_rate . "%' value='" . $value->id . "' taxrate='" . $value->tax_rate . "'>" . $value->name . '(' . $value->tax_rate . ')' . "</option>";
        }
        $data['tax_type']         = $selectStart . $taxOptions . $selectEnd;
        $data['custom_tax_type']  = $selectStartCustom . $taxOptions . $selectEndCustom . $taxHiddenField;

        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['exchange_rate_decimal_digits'] = $preference['exchange_rate_decimal_digits'];
        $data['default_currency'] = $data['currencies']->where('id', $preference['dflt_currency_id'])->first();
        $data['projects'] = Project::where('customer_id', '!=', 0)->get();

        return view('admin.saleOrders.add', $data);
    }

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
        $url = DB::transaction(function () use ($request, $userId) {
            /* Assign the values */
            $customerId = $request->customer_id;
            $customer_branch = CustomerBranch::where('customer_id', $customerId)->first(['id']);
            $request->customer_branch_id = $customer_branch->id;

            /* input field variables for inventory items */
            $item_id = $request->item_id;
            $sorting_no = $request->sorting_no;
            $item_name = $request->item_name;
            $item_qty = $request->item_qty;
            $item_price = $request->item_price;
            $item_hsn = $request->item_hsn;
            $item_discount = $request->item_discount;
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
            $custom_item_tax = $request->custom_item_tax;
            $custom_item_discount = $request->custom_item_discount;
            $custom_item_description = $request->custom_item_description;
            $custom_item_discount_type = $request->custom_item_discount_type;

            # region salesOrder create
            $orderReference = SaleOrder::where(['transaction_type' => 'SALESORDER', 'reference' => $request->reference])->first();
            if (!empty($orderReference)) {
                Session::flash('fail', __('Reference already used'));
                return "order/list";
            }
            $taxTable = TaxType::getAll();
            $saleOrder = (new SaleOrder)->store($request, 'SALESORDER', 'Direct Order', $request->reference);
            # endregion

            // Insert Activity
            if (isset($request->project_id) && !empty($request->project_id)) {
                (new Activity)->store('Project', $request->project_id, 'user', Auth::user()->id, __('A new invoice has been created'));
            }

            # region inventory item add
            if (!empty($item_id)) {
                $saleOrderDetails = (new SaleOrderDetail)->storeMass($request, $saleOrder->id, $item_id, $item_description, $item_name, $item_price, 0, $item_qty, 0, $item_discount, $item_discount_type, $item_hsn, $sorting_no, $request->item_tax);
                foreach ($item_id as $key => $item) {
                    if ($item_qty[$key] > 0) {
                        if ($request->has_tax == 'on' && isset($item_tax[$item_id[$key]])) {
                            $i = 0;
                            $saleTax = [];
                            foreach ($item_tax[$item][$key] as $tax) {
                                $selectedTax = $taxTable->where('id', $tax)->first();
                                if ($selectedTax) {
                                    $taxAmount = (new TaxType)->calculateTax($selectedTax->tax_rate, $request->tax_type, $request->discount_on, validateNumbers($item_price[$key]) * validateNumbers($item_qty[$key]), $item_discount[$key], $item_discount_type[$key], $request->other_discount_amount, $request->other_discount_type, $request->indivisual_discount_price);

                                    $saleTax[$i]['sale_order_detail_id'] = $saleOrderDetails[$key]->id;
                                    $saleTax[$i]['tax_type_id'] = $tax;
                                    $saleTax[$i]['tax_amount'] = $taxAmount;
                                    $i++;
                                }
                            }
                            DB::table('sale_taxes')->insert($saleTax);
                        }
                    }
                }
            }
            # endregion

            // Custom items
            if (!empty($row_no)) {
                $saleOrderDetails = (new SaleOrderDetail)->storeCustomItems($request, $saleOrder->id, null, $custom_item_description, $custom_item_name, $custom_item_price, 0, $custom_item_qty, 0, $custom_item_discount, $custom_item_discount_type, $custom_item_hsn, $custom_sorting_no, $request->custom_item_tax, $row_no);

                foreach ($custom_item_name as $key => $value) {
                    // custom item order detail
                    if ($custom_item_name[$key] != null && $custom_item_qty[$key] > 0) {
                        if ($request->has_tax == 'on' && isset($custom_item_tax[$row_no[$key]])) {
                            $i = 0;
                            $customSaleTax = [];
                            foreach ($custom_item_tax[$row_no[$key]] as $tax) {
                                $selectedTax = $taxTable->where('id', $tax)->first();
                                if ($selectedTax) {
                                    $taxAmount = (new TaxType)->calculateTax($selectedTax->tax_rate, $request->tax_type, $request->discount_on, validateNumbers($custom_item_price[$key]) * validateNumbers($custom_item_qty[$key]), $custom_item_discount[$key], $custom_item_discount_type[$key], $request->other_discount_amount, $request->other_discount_type, $request->indivisual_discount_price);

                                    $customSaleTax[$i]['sale_order_detail_id'] = $saleOrderDetails[$key]->id;
                                    $customSaleTax[$i]['tax_type_id'] = $tax;
                                    $customSaleTax[$i]['tax_amount'] = $taxAmount;
                                    $i++;
                                }
                            }
                            DB::table('sale_taxes')->insert($customSaleTax);
                        }
                    }
                }
            }

            # region store files
            if (!empty($request->attachments)) {
                $path = createDirectory("public/uploads/invoice_order");
                $fileIdList = (new File)->store($request->attachments, $path, 'Direct Order', $saleOrder->id, ['isUploaded' => true, 'isOriginalNameRequired' => true]);
            }
            # end region

            // Custom items end
            Session::flash('success', __('Successfully Saved'));
            $flag = $request->menu == "?sales" ? "" : "?". $request->sub_menu;
            return "order/view-order-details/". $saleOrder->id . $flag;
        });
        return redirect()->intended($url);
    }

    public function edit($orderNo)
    {
        $data = [];
        $data['menu'] = 'sales';
        $data['sub_menu'] = 'order/list';
        $data['page_title'] = __('Edit Quotation');
        $data['url'] = 'order/list';
        $data['invoiceData'] = SaleOrder::with([
                                                'saleOrderDetails',
                                                'location:id,name',
                                                'customer',
                                                'customerBranch',
                                                'currency',
                                                'paymentTerm',
                                                ])->find($orderNo);
        if (empty($data['invoiceData'])) {
            Session::flash('fail', __('Order does not exist.'));
            return redirect('order/list');
        }
        foreach ($data['invoiceData']->saleOrderDetails as $key => $value) {
            if ($data['invoiceData']->has_tax == 1 && $value->quantity > 0 ) {
                $value->taxList = (new SaleTax)->getSaleTaxes($value->id);
            }
        }
        $data['currencySymbol'] = $data['invoiceData']->currency->symbol;

        $data['files'] = (new File)->getFiles('Direct Order', $orderNo);

        if (!empty($data['files'])) {
            $data['filePath'] = "public/uploads/invoice_order";
            foreach ($data['files'] as $key => $value) {
                $value->icon = getFileIcon($value->file_name);
                $value->extension = strtolower(pathinfo($value->file_name, PATHINFO_EXTENSION));
            }
        }

        $data['locations']      = Location::getAll()->where('is_active', 1);
        $data['currencies']     = Currency::getAll();
        $data['paymentTerms']   = PaymentTerm::getAll();
        $data['countries']      = Country::getAll();
        $data['taxTypeList']    = TaxType::getAll();
        $data['taxes']          = json_encode($data['taxTypeList']);
        $data['default_currency'] = Preference::getAll()->where('field', 'dflt_currency_id')->where('category', 'company')->first();
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

        $data['custom_tax_type'] = $selectStartCustom . $taxOptions . $selectEndCustom . $taxHiddenField;
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['exchange_rate_decimal_digits'] = $preference['exchange_rate_decimal_digits'];

        return view('admin.saleOrders.edit', $data);
    }

    public function update(Request $request)
    {
        $userId = Auth::user()->id;
        $order_no = $request->order_no;
        $order_ref_no = $request->order_reference_id;

        $this->validate($request, [
            'reference' => 'required',
            'location_id' => 'required',
            'order_date' => 'required',
            'currency_id' => 'required',
            'custom_item_name.*' => 'sometimes|required',
            'item_name.*' => 'sometimes|required',
            'order_no' => 'required',
            'exchange_rate' => 'required'
        ]);

        $url = DB::transaction(function () use ($request, $userId, $order_no, $order_ref_no) {
            /* Assign the values */
            $reference = $request->reference;
            $inv_type = $request->invoice_type;
            $grand_total = $request->grand_total;
            $tax_type = $request->tax_type;
            if ($request->payment_method_id) {
                $payment_method_id = implode(',', $request->payment_method_id);
            } else {
                $payment_method_id = null;
            }

            /* input field variables for inventory items */
            $item_id = $request->item_id;
            $sorting_no = $request->sorting_no;
            $item_name = $request->item_name;
            $item_qty = $request->item_qty;
            $item_price = $request->item_price;
            $item_hsn = $request->item_hsn;
            $item_discount = $request->item_discount;
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

            /* Sub Total input variables */
            $other_discount = $request->other_discount;
            $other_discount_type = $request->other_discount_type;
            $shipping = $request->shipping;
            $custom_amount_title = $request->custom_amount_title;
            $custom_amount = $request->custom_amount;
            $saleOrder   = (new SaleOrder)->updateOrder($request, $request->order_no);

            $taxTable = TaxType::getAll();

            if (isset($item_details_id) && count($item_details_id) > 0) {
                $orderItemRowIds = SaleOrderDetail::where('sale_order_id', $order_no)->pluck('id');
                foreach ($orderItemRowIds as $key => $orderItemRowId) {
                    if (!in_array($orderItemRowId, $item_details_id)) {
                        DB::table('sale_taxes')->where('sale_order_detail_id', $orderItemRowId)->delete();
                        DB::table('sale_order_details')->where(['id' => $orderItemRowId, 'sale_order_id' => $order_no])->delete();
                    }
                }
                # endregion

                # region remove tax
                DB::table('sale_taxes')->whereIn('sale_order_detail_id', $orderItemRowIds)->delete();
                #endregion

                # region update the edited items
                $updatedList = (new SaleOrderDetail)->updateMassDetails($request,  $item_details_id, $old_item_id, $old_item_description, $old_item_name, $old_item_price, $old_item_qty, 0, $old_item_discount, $old_item_discount_type, $old_item_hsn, $old_sorting_no, $old_item_tax);
                $i = 0;
                $saleTax = [];
                foreach ($item_details_id as $key => $value) {
                    if ($request->has_tax == 'on' && isset($old_item_tax[$value])) {
                        foreach ($old_item_tax[$value] as $tax) {
                            $selectedTax = $taxTable->where('id', $tax)->first();
                            if ($selectedTax) {
                                $taxAmount = (new TaxType)->calculateTax($selectedTax->tax_rate, $request->tax_type, $request->discount_on, validateNumbers($old_item_price[$key]) * validateNumbers($old_item_qty[$key]), $old_item_discount[$key], $old_item_discount_type[$key], $request->other_discount_amount, $request->other_discount_type, $request->indivisual_discount_price);

                                $saleTax[$i]['sale_order_detail_id'] = $value;
                                $saleTax[$i]['tax_type_id'] = $tax;
                                $saleTax[$i]['tax_amount'] = $taxAmount;
                                $i++;
                            }
                        }
                    }
                }
                DB::table('sale_taxes')->insert($saleTax);
            } else {
                DB::table('sale_order_details')
                    ->where(['sale_order_id' => $order_no])
                    ->delete();
            }

            # region inventory item add
            if (!empty($item_id)) {
                $newList = (new SaleOrderDetail)->storeMass($request, $order_no, $item_id, $item_description, $item_name, $item_price, 0, $item_qty, 0, $item_discount, $item_discount_type, $item_hsn, $sorting_no, $item_tax);
                $i = 0;
                $saleTax = [];
                foreach ($item_id as $key => $item) {
                    if ($item_qty[$key] > 0) {
                        if ($request->has_tax == 'on' && isset($item_tax[$item_id[$key]])) {
                            foreach ($item_tax[$item_id[$key]] as $tax) {
                                $selectedTax = $taxTable->where('id', $tax)->first();
                                if ($selectedTax) {
                                    $taxAmount = (new TaxType)->calculateTax($selectedTax->tax_rate, $request->tax_type, $request->discount_on, validateNumbers($item_price[$key]) * validateNumbers($item_qty[$key]), $item_discount[$key], $item_discount_type[$key], $request->other_discount_amount, $request->other_discount_type, $request->indivisual_discount_price);

                                    $saleTax[$i]['sale_order_detail_id'] = $newList[$key]->id;
                                    $saleTax[$i]['tax_type_id'] = $tax;
                                    $saleTax[$i]['tax_amount'] = $taxAmount;
                                    $i++;
                                }
                            }
                        }
                    }
                }
                DB::table('sale_taxes')->insert($saleTax);
            }
            # endregion

            # region Custom items
            if (!empty($row_no)) {
                $customDetails = (new SaleOrderDetail)->storeCustomItems($request, $order_no, null, $custom_item_description, $custom_item_name, $custom_item_price, 0, $custom_item_qty, 0, $custom_item_discount, $custom_item_discount_type, $custom_item_hsn, $custom_sorting_no, $custom_item_tax, $row_no);
                $i = 0;
                $customSaleTax = [];
                foreach ($custom_item_name as $key => $value) {
                    // custom item order detail
                    if ($custom_item_name[$key] != null && $custom_item_qty[$key] > 0) {
                        if ($request->has_tax == 'on' && isset($custom_item_tax[$row_no[$key]])) {
                            foreach ($custom_item_tax[$row_no[$key]] as $tax) {
                                $selectedTax = $taxTable->where('id', $tax)->first();
                                if ($selectedTax) {
                                    $taxAmount = (new TaxType)->calculateTax($selectedTax->tax_rate, $request->tax_type, $request->discount_on, validateNumbers($custom_item_price[$key]) * validateNumbers($custom_item_qty[$key]), $custom_item_discount[$key], $custom_item_discount_type[$key], $request->other_discount_amount, $request->other_discount_type, $request->indivisual_discount_price);

                                    $customSaleTax[$i]['sale_order_detail_id'] = $customDetails[$key]->id;
                                    $customSaleTax[$i]['tax_type_id'] = $tax;
                                    $customSaleTax[$i]['tax_amount'] = $taxAmount;
                                    $i++;
                                }
                            }
                        }
                    }
                }
                DB::table('sale_taxes')->insert($customSaleTax);
            }
            # endregion

            if (!empty($request->attachments)) {
                $path = createDirectory("public/uploads/invoice_order");
                $fileIdList = (new File)->store($request->attachments, $path, 'Direct Order', $saleOrder->id, ['isUploaded' => true, 'isOriginalNameRequired' => true]);
            }

            # endregion

            $flag = $request->menu == 'sales' ? '' : '?'.$request->sub_menu;
            return "order/view-order-details/".$order_no.$flag;
        });
        Session::flash('success', __('Successfully updated'));
        return redirect()->intended($url);
    }

    /**
     * Remove the specified resource from storage.
     **/
    public function destroy(Request $request, $id)
    {
        if ( isset($id) ) {
            try {
                $record = SaleOrder::find($id);
                if ($record) {
                    DB::beginTransaction();
                    $invoiced = SaleOrder::where(['order_reference_id' => $id])->first();
                    if ( !empty($invoiced) ) {
                        \Session::flash('fail', __('This quotation converted to invoice, please delete invoice to delete this quotation.'));
                        return redirect()->intended('order/list');
                    }
                    $orderDetailIds = SaleOrderDetail::where(['sale_order_id' => $id])->pluck('id')->toArray();
                    $saleTaxes = SaleTax::whereIn('sale_order_detail_id', $orderDetailIds)->delete();
                    $deleteDetails = SaleOrderDetail::where(['sale_order_id' => $id])->delete();
                    $record->delete();
                    DB::commit();
                    Session::flash('success', __('Deleted Successfully.'));
                    if (isset($request->sub_menu) && $request->sub_menu == 'customer') {
                        return redirect()->intended("customer/order/$request->customer");
                    }
                    if (isset($request->sub_menu) && $request->sub_menu == 'users') {
                        return redirect()->intended("user/sales-order-list/$user_id");
                    }
                    return redirect()->intended('order/list');
                }
            }
            catch(Exception $e) {
                DB::rollBack();
                \Session::flash('fail', __('Deleted Fail.'));
            }
        }
        return redirect()->intended('order/list');
    }

    public function searchItem(Request $request)
    {
        $data['type']          = $request->type;
        $data['currency_id']   = $request->currency_id;
        $data['exchange_rate'] = $request->exchange_rate;
        $data['saleType']      = $request->salesTypeId;
        $data['transactionType'] = 'Invoice';
        $data['key']           = $request->search;
        $result = (new Item)->search(json_encode($data));
    }

    /**
    * Check reference no if exists
    */
    public function referenceValidation(Request $request){

        $data = array();
        $ref = $request['ref'];
        $result = DB::table('sales_orders')->where("reference",$ref)->first();

        $data['status_no'] = 0;
        if (count($result) > 0) {
            $data['status_no'] = 1;
        }

        return json_encode($data);
    }

    /**
    * Return customer Branches by customer id
    */
    public function customerBranches(Request $request)
    {
        $data = array();
        $customer_id = $request['customer_id'];
        $data['status_no'] = 0;
        $branchs = '';
        $result = DB::table('cust_branch')->select('customer_id','id','br_name')->where('customer_id', $customer_id)->orderBy('br_name', 'ASC')->get();
        if (!empty($result)) {
            $data['status_no'] = 1;
            foreach ($result as $key => $value) {
                $branchs .= "<option value='". $value->id ."'>". $value->br_name ."</option>";
            }
            $data['branchs'] = $branchs;
        }
        return json_encode($data);
    }

    /**
    * Preview of order details
    * @params order_no
    **/
    public function view($orderNo)
    {
        $data['menu'] = 'sales';
        $data['sub_menu'] = 'order/list';
        $data['page_title'] = __('View Quotation');
        $data['saleOrderData'] = SaleOrder::with([
                                                'saleOrderDetails',
                                                'location:id,name',
                                                'customer',
                                                'CustomerBranch',
                                                'currency',
                                                'paymentTerm',
                                                ])->where('transaction_type', 'SALESORDER')->find($orderNo);
        if (empty($data['saleOrderData'])) {
            Session::flash('fail', __('Quotation not available'));
            return redirect()->intended('invoice/list');
        }
        foreach ($data['saleOrderData']->saleOrderDetails as $key => $value) {
            if ($data['saleOrderData']->has_tax == 1 && $value->quantity > 0) {
                $value->taxList = (new SaleTax)->getSaleTaxesInPercentage($value->id);
            }
        }
        $data['item_tax_types']   = TaxType::getAll();
        $data['taxes']            = (new SaleOrder)->calculateTaxRows($orderNo);
        $fileOrderType = $data['saleOrderData']->order_type == 'Direct Order' ? 'Direct Order' : 'Direct Order';
        $data['filePath'] = "public/uploads/invoice_order";
        $data['files'] = (new File)->getFiles($fileOrderType, $orderNo);
        if (!empty($data['files'])) {
            foreach ($data['files'] as $key => $value) {
                $value->icon = getFileIcon($value->file_name);
				$value->extension = strtolower(pathinfo($value->file_name, PATHINFO_EXTENSION));
            }
        }
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['dflt_currency_id'] = $preference['dflt_currency_id'];
        $lang = $preference['dflt_lang'];
        $data['emailInfo'] = EmailTemplate::getAll()
                            ->where('template_id', 5)
                            ->where('language_short_name', $lang)
                            ->where('template_type', 'email')
                            ->first();
        $data['billingCountry'] = isset($data['saleOrderData']->customer->customerBranch->billing_country_id) && $data['saleOrderData']->customer->customerBranch->billing_country_id != 0 ? (new Country)->getCountry($data['saleOrderData']->customer->customerBranch->billing_country_id) : '';
        $smsInfo = EmailTemplate::getAll()
                           ->where('template_id', 5)
                           ->where('language_short_name', $lang)
                           ->where('template_type', 'sms')
                           ->first();
        $bodyInfo = str_replace('{order_reference_no}', $data['saleOrderData']->reference, $smsInfo->body);
        $bodyInfo = str_replace('{order_date}', formatDate($data['saleOrderData']->order_date), $bodyInfo);
        $bodyInfo = str_replace('{company_name}', $preference['company_name'], $bodyInfo);
        $data['smsInformation'] = $bodyInfo;
        $data['quotationShortUrl']  = UrlShortner::shortURL(url('/') .'/order/view-order-details/'. $orderNo);
        $checkInvoiced = SaleOrder::where('order_reference_id', $orderNo)->first();
        if ($checkInvoiced) {
            $data['invoiced_date'] = $checkInvoiced->created_at;
            $data['invoiced_status'] = 'yes';
            $data['ref_invoice']     = $checkInvoiced->reference;
            $data['order_reference_id'] = $checkInvoiced->order_reference_id;
            $data['order_no']           = $checkInvoiced->id;
        } else {
            $data['invoiced_status'] = 'no';
        }
        return view('admin.saleOrders.view', $data);
    }

    public function copyOrder($orderNo)
    {
        try {
            DB::beginTransaction();
            $orderCount = 0;
            $firstOrder = SaleOrder::where('transaction_type', 'SALESORDER')->first(['id']);
            if (!empty($firstOrder)) {
                $orderReference = SaleOrder::where('transaction_type', 'SALESORDER')->latest('id')->first(['reference']);
                $ref = explode("-", $orderReference->reference);
                $orderCount = (int)$ref[1];
            }

            $saleOrder = SaleOrder::with(['saleOrderDetails'])->find($orderNo);

            if (empty($saleOrder)) {
                Session::flash('fail', __('Order does not exist.'));
                return redirect('order/list');
            }
            if ( count($saleOrder->saleOrderDetails) > 0 ) {
                $newOrder = $saleOrder->replicate();
                $newOrder->order_type = "Direct Order";
                $newOrder->reference = 'QN-' . sprintf("%04d", $orderCount + 1);
                $newOrder->created_at = date('Y-m-d H:i:s');
                $newOrder->save();
                foreach ($saleOrder->saleOrderDetails as $key => $detail) {
                    $newDetail = $detail->replicate();
                    $newDetail->sale_order_id = $newOrder->id;
                    $newDetail->save();
                    if ($detail->saleTaxes) {
                        foreach ($detail->saleTaxes as $key => $tax) {
                            $newTax = $tax->replicate();
                            $newTax->sale_order_detail_id = $newDetail->id;
                            $newTax->save();
                        }
                    }
                }
                $files = (new File)->copyFiles("public/uploads/invoice_order", "public/uploads/invoice_order", $saleOrder->order_type, $orderNo, "Direct Order", $newOrder->id, ['isOriginalNameRequired' => true]);
            }

            DB::commit();
            if ( isset($newOrder) && $newOrder->id ) {
                Session::flash('success', __('Quotation Copied Successfully'));
                $flag = '';
                if (isset($_GET['customer'])) {
                    $flag = "?customer";
                } else if (isset($_GET['users'])) {
                    $flag = "?users";
                }
                return redirect()->intended('order/view-order-details/' . $newOrder->id . $flag);
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('fail', __('Quotation Copy Failed'));
            return redirect()->back();
        }

    }

    /**
    * Create auto invoice
    *@params order_id
    */
    public function autoInvoiceCreate($orderNo)
    {
        $data = [];
        $data['page_title'] = __('Quotation Conversion');
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
        $data['invoiceData'] = SaleOrder::with(['location:id,name',
                                                'paymentTerm:id,days_before_due',
                                                'currency:id,name,symbol',
                                                'saleOrderDetails'
                                            ])->find($orderNo);
        if (empty($data['invoiceData']->saleOrderDetails)) {
            Session::flash('fail', __("Invoice data does not exist."));
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
            $data['invoice_count'] = (int)$ref[1] + 1;
        } else {
            $data['invoice_count'] = 1;
        }

        $data['files'] = (new File)->copyFiles("public/uploads/invoice_order", "public/contents/temp", $data['invoiceData']->order_type, $orderNo, "Indirect Invoice", null, ['isTemporary' => true]);
        $data['files'] = json_decode($data['files']);
        $data['filePath'] = "public/contents/temp";
        $data['locations']      = Location::getAll()->where('is_active', 1);
        $data['currencies']     = Currency::getAll();
        $data['paymentTerms']   = PaymentTerm::getAll();
        $data['taxTypeList']    = TaxType::getAll();
        $data['currencies']     = Currency::getAll();
        $data['countries']      = Country::getAll();
        $data['currencySymbol'] = $data['invoiceData']->currency->symbol;
        $data['default_currency'] = Preference::getAll()->where('field', 'dflt_currency_id')->where('category', 'company')->first();
        $data['exchange_rate_decimal_digits'] =  Preference::getAll()->where('field', 'exchange_rate_decimal_digits')->first()->value;
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

        return view('admin.saleOrders.convert', $data);
    }

    public function autoInvoiceStore(Request $request, $orderNo)
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
            'exchange_rate'=>'required|numeric|min:0.000001',
        ]);
        $url = "order/view-order-details/". $orderNo;
        $url = DB::transaction(function () use ($request, $userId, $orderNo) {
            $flag = "";
            if ($request->menu != 'sales') {
                $flag = "?" . $request->sub_menu;
            }
            $customerId = $request->customer_id;
            $customerBranch = CustomerBranch::where('customer_id', $customerId)->first(['id']);
            $request->customer_branch_id = $customerBranch->id;

            /* input field variables for old items */
            $old_item_id = $request->old_item_id;
            $old_item_details_id = $request->old_item_details_id;
            $old_sorting_no = $request->old_sorting_no;
            $old_item_name = $request->old_item_name;
            $old_item_qty = $request->old_item_qty;
            $old_item_price = $request->old_item_price;
            $old_item_hsn = $request->old_item_hsn;
            $old_item_discount = $request->old_item_discount;
            $old_item_discount_type = $request->old_item_discount_type;
            $old_item_tax = $request->old_item_tax;
            $old_item_description = $request->old_item_description;

            /* input field variables for inventory items */
            $item_id = $request->item_id;
            $item_details_id = $request->item_details_id;
            $sorting_no = $request->sorting_no;
            $item_name = $request->item_name;
            $item_qty = $request->item_qty;
            $item_price = $request->item_price;
            $item_hsn = $request->item_hsn;
            $item_discount = $request->item_discount;
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

            $invoiceReference = SaleOrder::where(['transaction_type' => 'SALESINVOICE', 'reference' => $request->reference])->first();
            if (!empty($invoiceReference)) {
                Session::flash('fail', __('Reference already used'));
                return "order/list";
            }

            if (!empty($old_item_id)) {
                $isAvailable = true;
                foreach ($old_item_id as $key => $value) {
                    $oldItemDetails = Item::find($value);
                    if (isset($oldItemDetails->is_stock_managed) && $oldItemDetails->is_stock_managed == 1) {
                        $available = StockMove::where(['item_id' => $value, 'location_id' => $request->location_id])->get()->sum('quantity');
                        if (isset($item_qty[$key])) {
                            if ($available < validateNumbers($item_qty[$key])) {
                                $isAvailable = false;
                                break;
                            }
                        }
                    }
                }
                if (!$isAvailable) {
                    Session::flash('danger', __('Item not available in stock.'));
                    return "order/auto-invoice-create/" . $request->order_no . $flag;
                }
            }

            if (!empty($item_id)) {
                $isAvailable = true;
                foreach ($item_id as $key => $value) {
                    $ItemDetails = Item::find($value);
                    if (isset($ItemDetails->is_stock_managed)) {
                        if (!empty($ItemDetails->is_stock_managed) && $ItemDetails->is_stock_managed == 1) {
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
                    return "order/auto-invoice-create/" . $request->order_no . $flag;
                }
            }

            $taxTable = TaxType::getAll();
            $saleInvoice = (new SaleOrder)->store($request, 'SALESINVOICE', 'Indirect Invoice', $request->reference, $orderNo);

            // Insert Activity
            if (isset($request->project_id) && !empty($request->project_id)) {
                (new Activity)->store('Project', $request->project_id, 'user', Auth::user()->id, __('A new invoice has been created.'));
            }
            # endregion

            # region old item add
            if (!empty($old_item_details_id)) {
                $saleInvoiceDetails = (new SaleOrderDetail)->storeMass($request, $saleInvoice->id, $old_item_id, $old_item_description, $old_item_name, $old_item_price, 0, $old_item_qty, 0, $old_item_discount, $old_item_discount_type, $old_item_hsn, $old_sorting_no, $request->old_item_tax);
                foreach ($old_item_details_id as $key => $detail_id) {
                    if ($old_item_qty[$key] > 0) {
                        if ($request->has_tax == 'on' && isset($old_item_tax[$detail_id])) {
                            $i = 0;
                            $invoiceTax = [];
                            foreach ($old_item_tax[$detail_id] as $tax) {
                                $selectedTax = $taxTable->where('id', $tax)->first();
                                if ($selectedTax) {
                                    $taxAmount = (new TaxType)->calculateTax($selectedTax->tax_rate, $request->tax_type, $request->discount_on, validateNumbers($old_item_price[$key]) * validateNumbers($old_item_qty[$key]), $old_item_discount[$key], $old_item_discount_type[$key], $request->other_discount_amount, $request->other_discount_type, $request->indivisual_discount_price);

                                    $invoiceTax[$i]['sale_order_detail_id'] = $saleInvoiceDetails[$key]->id;
                                    $invoiceTax[$i]['tax_type_id'] = $tax;
                                    $invoiceTax[$i]['tax_amount'] = $taxAmount;
                                    $i++;
                                }
                            }
                            DB::table('sale_taxes')->insert($invoiceTax);
                        }
                        // create stockMove
                        if (!empty($old_item_id[$key])) {
                            $stockMove                   = new StockMove();
                            $stockMove->item_id          = $old_item_id[$key];
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
            }
            # endregion

            # region inventory items
            if (!empty($item_id)) {
                $saleInvoiceDetails = (new SaleOrderDetail)->storeMass($request, $saleInvoice->id, $item_id, $item_description, $item_name, $item_price, 0, $item_qty, 0, $item_discount, $item_discount_type, $item_hsn, $sorting_no, $request->item_tax);
                foreach ($item_id as $key => $item) {
                    if ($item_qty[$key] > 0) {
                        if ($request->has_tax == 'on' && isset($item_tax[$item_id[$key]])) {
                            $i = 0;
                            $invoiceTax = [];
                            foreach ($item_tax[$item_id[$key]] as $tax) {
                                $selectedTax = $taxTable->where('id', $tax)->first();
                                if ($selectedTax) {
                                    $taxAmount = (new TaxType)->calculateTax($selectedTax->tax_rate, $request->tax_type, $request->discount_on, validateNumbers($item_price[$key]) * validateNumbers($item_qty[$key]), $item_discount[$key], $item_discount_type[$key], $request->other_discount_amount, $request->other_discount_type, $request->indivisual_discount_price);

                                    $invoiceTax[$i]['sale_order_detail_id'] = $saleInvoiceDetails[$key]->id;
                                    $invoiceTax[$i]['tax_type_id'] = $tax;
                                    $invoiceTax[$i]['tax_amount'] = $taxAmount;
                                    $i++;
                                }
                            }
                            DB::table('sale_taxes')->insert($invoiceTax);
                        }
                    }
                }
            }

            // Custom items
            if (!empty($row_no)) {
                foreach ($custom_item_name as $key => $value) {
                    $invoiceOrderDetails = (new SaleOrderDetail)->storeCustomItems($request, $saleInvoice->id, null, $custom_item_description, $custom_item_name, $custom_item_price, 0, $custom_item_qty, 0, $custom_item_discount, $custom_item_discount_type, $custom_item_hsn, $custom_sorting_no, $request->custom_item_tax, $row_no);
                    // custom item order detail
                    if ($custom_item_name[$key] != null && $custom_item_qty[$key] > 0) {
                        if ($request->has_tax == 'on' && isset($custom_item_tax[$row_no[$key]])) {
                            $i = 0;
                            $customSaleTax = [];
                            foreach ($custom_item_tax[$row_no[$key]] as $tax) {
                                $selectedTax = $taxTable->where('id', $tax)->first();
                                if ($selectedTax) {
                                    $taxAmount = (new TaxType)->calculateTax($selectedTax->tax_rate, $request->tax_type, $request->discount_on, validateNumbers($custom_item_price[$key]) * validateNumbers($custom_item_qty[$key]), $custom_item_discount[$key], $custom_item_discount_type[$key], $request->other_discount_amount, $request->other_discount_type, $request->indivisual_discount_price);

                                    $customSaleTax[$i]['sale_order_detail_id'] = $invoiceOrderDetails[$key]->id;
                                    $customSaleTax[$i]['tax_type_id'] = $tax;
                                    $customSaleTax[$i]['tax_amount'] = $taxAmount;
                                    $i++;
                                }
                            }
                            DB::table('sale_taxes')->insert($customSaleTax);
                        }
                    }
                }
            }
            if ( !empty($request->attachments) ) {
                $path = createDirectory("public/uploads/invoice_order");
                $fileIdList = (new File)->store($request->attachments, $path, 'Indirect Invoice', $saleInvoice->id, ['isUploaded' => true, 'isOriginalNameRequired' => true]);
            }
            \Session::flash('success', __('Successfully converted'));
            return "invoice/view-detail-invoice/" . $saleInvoice->id;
        });
        return redirect()->intended($url);
    }

    public function orderPrintPdf($orderNo)
    {
        $data = [];
        $data['invoiceData'] = SaleOrder::with([
                                                'customerBranch:id,name,billing_street,billing_city,billing_zip_code,billing_country_id,billing_state',
                                                'location:id,name',
                                                'currency:id,name,symbol',
                                                'saleOrderDetails'])->find($orderNo);
        foreach ($data['invoiceData']->saleOrderDetails as $key => $value) {
            if ($data['invoiceData']->has_tax == 1 && $value->quantity > 0) {
                $value->taxList = (new SaleTax)->getSaleTaxesInPercentage($value->id);
            }
        }
        $data['item_tax_types']   = TaxType::getAll();
        $data['taxes']            = (new SaleOrder)->calculateTaxRows($orderNo);
        $data['company_logo']   = Preference::getAll()->where('category', 'company')->where('field', 'company_logo')->first('value');
        $data['type'] = request()->get('type') == 'print' || request()->get('type') == 'pdf' ? request()->get('type') : 'print';

        return printPDF($data, 'quotation_' . time() . '.pdf', 'admin.saleOrders.print-order', view('admin.saleOrders.print-order', $data), $data['type']);

    }

    /**
    * Send SMS to customer for Invoice information
    */
    public function sendOrderInformationBySMS(Request $request)
    {

        $response = SmsConfig::sendSMS('+'.$request->phoneno, $request->message);
        if ($response == "queued") {
            \Session::flash('success', __('SMS has been sent successfully.'));
        } else {
            \Session::flash('fail', __('SMS has not been sent.'));
        }
        return redirect()->back();
    }

    /**
    * Send email to customer for Invoice information
    */
    public function sendOrderInformationByEmail(Request $request)
    {
        $this->validate($request, [
            'email' => ['required', new CheckValidEmail],
            'subject' => 'required',
            'message' => 'required'
        ]);
        $orderNo = $request['order_id'];
        $invoiceName = 'quotation-'. $request->reference .'.pdf';
        $emailConfig = DB::table('email_configurations')->first();
        if (isset($emailConfig->protocol) && $emailConfig->protocol =='smtp' ) {
            return back()->withInput()->withErrors(['email' => __("Verify your smtp settings of email")]);
        }
        $companyName = Preference::getAll()->where('category','company')->where('field', 'company_name')->first()->value;

        if (isset($request['quotation_pdf']) && $request['quotation_pdf']=='on') {
            createDirectory("public/uploads/invoices");
            $this->orderPdfEmail($orderNo,$invoiceName);
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

        \Session::flash('success', __('Email has been sent successfully.'));
        return redirect()->intended('order/view-order-details/'. $request['order_id']);
    }

    public function orderPdfEmail($orderNo,$invoiceName)
    {

        $data = [];
        $data['invoiceData'] = SaleOrder::with([
                                                'customerBranch:id,name,billing_street,billing_city,billing_zip_code,billing_country_id',
                                                'location:id,name',
                                                'currency:id,name,symbol',
                                                'saleOrderDetails'])->find($orderNo);
        foreach ($data['invoiceData']->saleOrderDetails as $key => $value) {
            if ($data['invoiceData']->has_tax == 1 && $value->quantity > 0) {
                $value->taxList = (new SaleTax)->getSaleTaxesInPercentage($value->id);
            }
        }
        $data['item_tax_types']   = TaxType::getAll();
        $data['taxes']            = (new SaleOrder)->calculateTaxRows($orderNo);
        $data['company_logo']   = Preference::getAll()->where('category','company')->where('field', 'company_logo')->first('value');

        return printPDF($data, public_path() . '/uploads/invoices/' . $invoiceName, 'admin.saleOrders.print-order', view('admin.saleOrders.print-order', $data), null, "email");

    }

    public function salesOrderPdf()
    {
        $data['from'] = isset($_GET['from']) ? $_GET['from'] : null;
        $data['to'] = isset($_GET['to']) ? $_GET['to'] : null;
        $data['location'] = isset($_GET['location']) ? $_GET['location'] : null;
        $data['customer'] = isset($_GET['customerId']) ? $_GET['customerId'] : null;
        $data['currency'] = isset($_GET['currency']) ? $_GET['currency'] : null;
        $data['locationName'] = !empty($data['location']) ? Location::getAll()->where('id', $data['location'])->first()->name : null;
        if (isset($data['customer']) && !empty($data['customer'])) {
            $data['customerData'] = Customer::find($data['customer']);
        }
        $data['orderList'] = (new SaleOrder)->getAllQuotation($data['from'], $data['to'], $data['location'], $data['customer'], $data['currency'])->latest('id')->get();
        $data['date_range'] = ($data['from'] && $data['to']) ?  formatDate($data['from']) .' To '. formatDate($data['to']) : 'No Date Selected';
        return printPDF($data, 'quotation_list_' . time() . '.pdf', 'admin.saleOrders.order_list_pdf', view('admin.saleOrders.order_list_pdf', $data), 'pdf', 'domPdf');
    }

    public function salesOrderCsv()
    {
        return Excel::download(new allQuotationExport(), 'quotation_list_'. time() .'.csv');
    }

    public function noteText(Request $request)
    {
        $order_id              = $request['order_no'];
        $data['comment_check'] = $request['comment_check'];
        if (isset($order_id)) {
            DB::table('sales_orders')->where('id', $order_id)->update($data);
        }
    }
}
