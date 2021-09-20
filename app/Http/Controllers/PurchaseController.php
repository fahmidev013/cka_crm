<?php

namespace App\Http\Controllers;

use App\Models\{
    Account,
    Country,
    Currency,
    EmailTemplate,
    File,
    IncomeExpenseCategory,
    Item,
    Location,
    PaymentMethod,
    PaymentTerm,
    Preference,
    PurchaseOrder,
    PurchasePrice,
    PurchaseTax,
    PurchaseReceiveType,
    PurchaseOrderDetail,
    ReceivedOrder,
    ReceivedOrderDetail,
    SaleType,
    Supplier,
    SupplierTransaction,
    TaxType,
    TransactionReference
};
use App\Http\{
    Start\Helpers,
    Controllers\EmailController
};
use App\DataTables\PurchaseListDataTable;
use App\Exports\allPurchaseExport;
use Illuminate\Http\Request;
use App\Rules\CheckValidEmail;
use Validator;
use Auth;
use DB;
use Excel;
use PDF;
use Session;

class PurchaseController extends Controller
{
    public function __construct(EmailController $email)
    {
        $this->email = $email;
    }

    /**
     * Display a listing of the resource.
     *
     * @param PurchaseListDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function index(PurchaseListDataTable $dataTable)
    {
        $data = [];
        $data['menu'] = 'purchase';
        $data['sub_menu'] = 'purchase/list';
        $data['page_title'] = __('Purchases');
        $data['stock_id'] = 'all';

        $data['suppliers']      = Supplier::where(['is_active' => 1])->get(['id', 'name']);
        $data['currencyList']   = Currency::getAll();
        $data['locationList']   = Location::getAll()->where('is_active', 1);


        $data['supplier'] = isset($_GET['supplier']) ? $_GET['supplier'] : 'all';
        $data['currency'] = isset($_GET['currency']) ? $_GET['currency'] : NULL;
        $data['location'] = isset($_GET['location']) ? $_GET['location'] : NULL;
        $data['status']   = isset($_GET['status']) ? $_GET['status'] : NULL;
        $data['from'] = isset($_GET['from']) ? $_GET['from'] : NULL;
        $data['to'] = isset($_GET['to']) ? $_GET['to'] : NULL;

        $row_per_page = Preference::getAll()->where('field', 'row_per_page')->first()->value;

        return $dataTable->with('row_per_page', $row_per_page)->render('admin.purchase.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $data['menu'] = 'purchase';
        $data['sub_menu'] = 'purchase/list';
        $data['page_title'] = __('Create Purchase');
        $data['url'] = 'purchase/list';
        $data['supplierData'] = Supplier::with([
                                    'currency' => function($query) {
                                        $query->select('id', 'name', 'symbol');
                                    }
                                ])->where(['is_active' => 1])
                                ->get();

        $data['countries']      = Country::getAll();
        $data['currencies']     = Currency::getAll();
        $data['purchaseTypes']  = PurchaseReceiveType::getAll();
        $data['paymentTerms']   = PaymentTerm::getAll();
        $data['locations']      = Location::getAll()->where('is_active', 1);
        $data['order']          = PurchaseOrder::latest('id')->first();
        $data['saleTypes']      = SaleType::getAll();

        $order_count            = PurchaseOrder::count();
        if ($order_count > 0) {
            $orderReference     = PurchaseOrder::latest('id')->first(['reference']);
            $ref = explode("-", $orderReference->reference);
            $data['order_count']= (int)$ref[1];
        } else {
            $data['order_count']= 0;
        }

        $taxTypeList            = TaxType::getAll();
        $data['taxes']          = json_encode($taxTypeList);
        $taxOptions = '';
        $selectStart = "<select name='item_tax[]' class='inputTax form-control bootstrap-select selectpicker' multiple>";
        $selectEnd = "</select>";

        $selectStartCustom = "<select class='inputTax form-control bootstrap-select selectpicker' multiple name='custom_item_tax[1][]'>";
        $selectEndCustom = "</select>";
        $taxHiddenField = "";

        foreach ($taxTypeList as $key => $value) {
            $taxHiddenField .= "<input type='hidden' class='itemTaxAmount itemTaxAmount-" . $value->id . "'>";
            $taxOptions .= "<option title='" . $value->tax_rate . "%' value='" . $value->id . "' taxrate='" . $value->tax_rate . "'>" . $value->name . '(' . $value->tax_rate . ')' . "</option>";
        }
        $data['tax_type']        = $selectStart . $taxOptions . $selectEnd;
        $data['custom_tax_type'] = $selectStartCustom . $taxOptions . $selectEndCustom . $taxHiddenField;
        $data['tax_type_custom'] = $selectStartCustom . $taxOptions . $selectEndCustom;
        $preference              = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['exchange_rate_decimal_digits'] = $preference['exchange_rate_decimal_digits'];
        $data['default_currency']= $data['currencies']->where('id', $preference['dflt_currency_id'])->first();
        return view('admin.purchase.add', $data);
    }

    /**
     * Store new purchase order
     *
     * @param  \Illuminate\Http\Request $request
     * @return render view
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'reference' => 'required|unique:purchase_orders,reference',
            'location' => 'required',
            'order_date' => 'required',
            'supplier_id' => 'required',
            'custom_item_name.*' => 'required',
            'item_name.*' => 'required',
            'item_price.*' => 'required',
            'email' => ['nullable','unique:suppliers,email', new CheckValidEmail],
        ]);
        $userId = Auth::user()->id;
        try {
            DB::beginTransaction();
            $isReferenceExist = PurchaseOrder::where(['reference' => $request->reference])->first(['id']);
            if (!empty($isReferenceExist)) {
                Session::flash("fail", __('Reference already exist'));
                return redirect()->back();
            }
            # region assigned variable

            /* custom options */
            $tax_type              = $request->tax_type;
            $invItemTax            = $request->invItemTax == 'on' ? 1 : 0;
            $invItemDetails        = $request->invItemDetails == 'on' ? 1 : 0;
            $invItemDiscount       = $request->invItemDiscount == 'on' ? 1 : 0;
            $invItemHSN            = $request->invItemHSN == 'on' ? 1 : 0;
            $invDiscount           = $request->invDiscount == 'on' ? 1 : 0;
            $invShipping           = $request->invShipping == 'on' ? 1 : 0;
            $invCustomCharge       = $request->invCustomAmount == 'on' ? 1 : 0;

            /* input field variables for inventory items */
            $item_id               = $request->item_id;
            $sorting_no            = $request->sorting_no;
            $item_name             = $request->item_name;
            $item_qty              = $request->item_qty;
            $item_price            = $request->item_price;
            $item_hsn              = $request->item_shn;
            $item_discount         = $request->item_discount;
            $item_discount_type    = $request->item_discount_type;
            $item_tax              = $request->item_tax;
            $item_description      = $request->item_description;

            /* input field variables for Custom items */
            $row_no                 = $request->row_no;
            $custom_sorting_no      = $request->custom_sorting_no;
            $custom_item_name       = $request->custom_item_name;
            $custom_item_qty        = $request->custom_item_qty;
            $custom_item_price      = $request->custom_item_price;
            $custom_item_hsn        = $request->custom_item_shn;
            $custom_item_discount   = $request->custom_item_discount;
            $custom_item_discount_type = $request->custom_item_discount_type;
            $custom_item_tax        = $request->custom_item_tax;
            $custom_item_description = $request->custom_item_description;

            /* Sub Total input variables */
            $other_discount = $request->other_discount;
            $other_discount_type = $request->other_discount_type;
            $shipping = $request->shipping;
            $custom_amount_title = $request->custom_amount_title;
            $custom_amount = $request->custom_amount;

            $comments = $request->comments;
            $noteCheck = $request->note_check == 'on' ? 1 : 0;
            # endregion
            $taxTable = TaxType::getAll();

            # region Purchase Order create
            $order                  = new PurchaseOrder();
            $order->supplier_id     = $request->supplier_id;
            $order->user_id         = $userId;
            $order->invoice_type    = $request->inv_type;
            $order->discount_on     = $request->discount_on;
            $order->tax_type        = $tax_type;
            $order->has_tax         = $invItemTax;
            $order->has_description = $invItemDetails;
            $order->has_item_discount = $invItemDiscount;
            $order->has_hsn         = $invItemHSN;
            $order->has_other_discount= $request->invOtherDiscount == 'on' ? 1 : 0 ;
            $order->has_shipping_charge = $invShipping;
            $order->has_custom_charge = $invCustomCharge;
            $order->other_discount_amount = validateNumbers($other_discount);
            $order->other_discount_type = $other_discount_type;
            $order->shipping_charge = validateNumbers($shipping);
            $order->custom_charge_title = $custom_amount_title;
            $order->custom_charge_amount = validateNumbers($custom_amount);
            $order->currency_id     = $request->inv_currency;
            $order->exchange_rate   = !empty($request->inv_exchange_rate) ? validateNumbers($request->inv_exchange_rate) : 0;

            $order->purchase_receive_type_id = $request->purchase_receive_type;
            $order->comments        = stripBeforeSave($comments);
            $order->has_comment     = $noteCheck;
            $order->order_date      = DbDateFormat($request->order_date);
            $order->reference       = $request->reference;
            $order->location_id     = $request->location;
            $order->total           = validateNumbers($request->totalValue);
            $order->payment_term_id = $request->payment_term;
            $order->created_at      = date('Y-m-d H:i:s');
            $order->save();
            # end region

            # region inventory item add
            $k = 0;
            if (!empty($item_id)) {
                foreach ($item_id as $key => $item) {
                    if ($item_qty[$key] > 0) {
                        $purchaseDetail                    = new PurchaseOrderDetail();
                        $purchaseDetail->purchase_order_id = $order->id;
                        $purchaseDetail->item_id           = $item_id[$key];
                        $purchaseDetail->description       = $invItemDetails == 1 ? stripBeforeSave($item_description[$key]) : "";
                        $purchaseDetail->item_name         = stripBeforeSave($item_name[$key]);
                        $purchaseDetail->hsn               = $invItemHSN == 1 ? $item_hsn[$key] : "";
                        $purchaseDetail->sorting_no        = $sorting_no[$key];
                        $purchaseDetail->unit_price        = validateNumbers($item_price[$key]);
                        $purchaseDetail->quantity_ordered  = validateNumbers($item_qty[$key]);
                        $purchaseDetail->quantity_received = 0;
                        $discountAmount = 0;
                        $item_discount[$key]             = $item_discount[$key] ? $item_discount[$key] : 0;
                        if ($invItemDiscount == 1) {
                            if ($item_discount_type[$key] == '$') {
                                $discountAmount = validateNumbers($item_discount[$key]);
                            }
                            else {
                                $discountAmount = validateNumbers($item_discount[$key]) * validateNumbers($item_price[$key]) * validateNumbers($item_qty[$key]) / 100;
                            }
                        }
                        $purchaseDetail->discount        = $invItemDiscount ? validateNumbers($item_discount[$key]) : 0;
                        $purchaseDetail->discount_type   = $invItemDiscount ? $item_discount_type[$key] : "%";
                        $purchaseDetail->discount_amount = $discountAmount;
                        $purchaseDetail->save();
                        if ($invItemTax && isset($item_tax[$item_id[$key]])) {
                            $i = $j = 0;
                            $saleTax = $salesInvoiceTax = [];
                            foreach ($item_tax[$item_id[$key]] as $tax) {
                                $selectedTax = $taxTable->where('id', $tax)->first();
                                if ($selectedTax) {
                                    $taxAmount = (new TaxType)->calculateTax($selectedTax->tax_rate, $request->tax_type, $request->discount_on, validateNumbers($item_price[$key]) * validateNumbers($item_qty[$key]), $item_discount[$key], $item_discount_type[$key], $request->other_discount_amount, $request->other_discount_type, $request->indivisual_discount_price);

                                    $saleTax[$i]['purchase_order_detail_id'] = $purchaseDetail->id;
                                    $saleTax[$i]['tax_type_id'] = $tax;
                                    $saleTax[$i]['tax_amount'] = $taxAmount;
                                    $i++;
                                }
                            }
                            DB::table('purchase_taxes')->insert($saleTax);
                        }
                        $k++;
                    }
                    $purchaseDataInfo = PurchasePrice::where('item_id', $item_id[$key])->count();
                    if ($purchaseDataInfo == 0) {
                        $purchasePrice          = new PurchasePrice();
                        $purchasePrice->item_id = $item_id[$key];
                        $purchasePrice->price   = $item_id[$key];
                        $purchasePrice->save();
                    }
                }
            }
            # endregion

            # region custom item add
            if (!empty($row_no)) {
                foreach ($custom_item_name as $key => $value) {
                    if ($custom_item_name[$key] != null && $custom_item_qty[$key] > 0) {
                        $customItem                    = new PurchaseOrderDetail();
                        $customItem->purchase_order_id = $order->id;
                        $customItem->description       = $invItemDetails == 1 ? stripBeforeSave($custom_item_description[$key]) : "";
                        $customItem->item_name         = stripBeforeSave($custom_item_name[$key]);
                        $customItem->hsn               = $invItemHSN == 1 ? $custom_item_hsn[$key] : "";
                        $customItem->sorting_no        = $custom_sorting_no[$key];
                        $customItem->unit_price        = validateNumbers($custom_item_price[$key]);
                        $customItem->quantity_ordered  = validateNumbers($custom_item_qty[$key]);
                        $customItem->quantity_received = 0;
                        $discountAmount = 0;
                        $custom_item_discount[$key]    = $custom_item_discount[$key] ? $custom_item_discount[$key] : 0;
                        if ($invItemDiscount == 1) {
                            if ($custom_item_discount_type[$key] == '$') {
                                $discountAmount = validateNumbers($custom_item_discount[$key]);
                            } else {
                                $discountAmount = validateNumbers($custom_item_discount[$key]) * validateNumbers($custom_item_price[$key]) * validateNumbers($custom_item_qty[$key]) / 100;
                            }
                        }
                        $customItem->discount         = $invItemDiscount ? validateNumbers($custom_item_discount[$key]) : 0;
                        $customItem->discount_type    = $invItemDiscount ? $custom_item_discount_type[$key] : "%";
                        $customItem->discount_amount  = $discountAmount;
                        $customItem->save();
                        if ($invItemTax && isset($custom_item_tax[$row_no[$key]])) {
                            $i = 0;
                            $customPurchTax = [];
                            foreach ($custom_item_tax[$row_no[$key]] as $tax) {
                                $selectedTax = $taxTable->where('id', $tax)->first();
                                if ($selectedTax) {
                                    $taxAmount = (new TaxType)->calculateTax($selectedTax->tax_rate, $request->tax_type, $request->discount_on, validateNumbers($custom_item_price[$key]) * validateNumbers($custom_item_qty[$key]), $custom_item_discount[$key], $custom_item_discount_type[$key], $request->other_discount_amount, $request->other_discount_type, $request->indivisual_discount_price);

                                    $customPurchTax[$i]['purchase_order_detail_id'] = $customItem->id;
                                    $customPurchTax[$i]['tax_type_id'] = $tax;
                                    $customPurchTax[$i]['tax_amount'] = $taxAmount;
                                    $i++;
                                }
                            }
                            DB::table('purchase_taxes')->insert($customPurchTax);
                        }

                    }
                }
            }
            # region store files
            if (!empty($request->attachments)) {
                $path = createDirectory("public/uploads/purchase_order");
                (new File)->store($request->attachments, $path, 'Purchase Order', $order->id, ['isUploaded' => true, 'isOriginalNameRequired' => true]);
            }
            # endregion
            DB::commit();

            $flag = $request->menu == "purchase" ? "" : "?" . $request->sub_menu;
            if ($request->purchase_receive_type == 1) {
                return redirect()->intended("purchase/receive/all/" . $order->id . $flag);
            }
            if (!empty($order->id)) {
                Session::flash('success', __('Successfully Saved'));
            }
            if ($request->menu == 'supplier') {
            return redirect()->intended('purchase/view-purchase-details/'.$order->id.'?supplier');
            } else if ($request->menu == 'users') {
                return redirect()->intended('purchase/view-purchase-details/'.$order->id.'?users');
            }
            return redirect()->intended('purchase/view-purchase-details/'.$order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('fail', $e->getMessage());
            return redirect()->intended('purchase/list');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['menu'] = 'purchase';
        $data['sub_menu'] = 'purchase/list';
        $data['page_title'] = __('Edit Purchase');
        $data['url'] = 'purchase/list';
        $data['saleTypes']            = SaleType::getAll();
        $data['purchaseData']         = PurchaseOrder::with([
                                                                'supplier:id,name,email,street,contact,city,state,zipcode,country_id',
                                                                'purchaseOrderDetails',
                                                                'location:id,name',
                                                                'currency:id,name,symbol'])
                                                        ->find($id);
        if (empty($data['purchaseData'])) {
            \Session::flash('fail', __('The data you are trying to access is not found.'));
            return redirect()->back();
        }
        foreach ($data['purchaseData']->purchaseOrderDetails as $key => $value) {
            if ($data['purchaseData']->has_tax == 1 && $value->quantity_ordered > 0) {
                $value->taxList = (new PurchaseTax)->getPurchaseTaxes($value->id);
            }
        }
        $data['supplierData']         = Supplier::with('currency','country')->find($data['purchaseData']->supplier_id);
        $data['currencySymbol'] = $data['supplierData']->currency->symbol;
        $data['locations']            = Location::getAll()->where('is_active', 1);
        $data['paymentTerms']         = PaymentTerm::getAll();
        $data['purchaseReceiveTypes'] = PurchaseReceiveType::getAll();
        $data['taxTypeList']          = TaxType::getAll();
        $data['receiveData']          = (new ReceivedOrder)->getReceivedData($id);
        $data['currencies']           = Currency::all();
        $data['countries']            = Country::getAll();
        $data['default_currency']     = Preference::getAll()->where('field', 'dflt_currency_id')->where('category', 'company')->first();

        $taxOptions = '';
        $selectStart = "<select name='item_tax[]' class='inputTax form-control bootstrap-select selectpicker' multiple>";
        $selectEnd = "</select>";
        foreach ($data['taxTypeList'] as $key => $value) {
            $taxOptions .= "<option title='" . $value->tax_rate . "%' value='" . $value->id . "' taxrate='" . $value->tax_rate . "'>" . $value->name . '(' . $value->tax_rate . ')' . "</option>";
        }
        $data['tax_type'] = $selectStart . $taxOptions . $selectEnd;

        $data['files'] = (new File)->getFiles('Purchase Order', $id);
        if (!empty($data['files'])) {
            $data['filePath'] = "public/uploads/purchase_order";
            foreach ($data['files'] as $key => $value) {
                $value->fileName = implode(" ",array_slice(explode('_', $value->file),2));
                $value->icon = getFileIcon($value->file_name);
            }
        }
        $preference               = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['exchange_rate_decimal_digits'] = $preference['exchange_rate_decimal_digits'];

        return view('admin.purchase.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $userId = Auth::user()->id;
        $order_no = $request->order_no;
        $this->validate($request, [
            'order_date' => 'required',
            'inv_type' => 'required',
            'discount_on' => 'required',
            'custom_item_name.*'=>'required',
            'item_name.*'=>'required',
            'item_price.*' => 'required',
            'order_no'=>'required',
            'inv_exchange_rate'=>'required'

        ]);
        $url = DB::transaction(function () use ($request, $userId,$order_no) {
            /* Assign the values */
            $invItemTax     = $request->invItemTax == 'on' ? 1 : 0;
            $invItemDetails = $request->invItemDetails == 'on' ? 1 : 0;
            $invItemDiscount= $request->invItemDiscount == 'on' ? 1 : 0;
            $invItemHSN     = $request->invItemHSN == 'on' ? 1 : 0;
            $invOtherDiscount = $request->invOtherDiscount == 'on' ? 1 : 0;
            $invShipping    = $request->invShipping == 'on' ? 1 : 0;
            $invCustomAmount= $request->invCustomAmount == 'on' ? 1 : 0;
            $noteCheck      = $request->note_check == 'on' ? 1 : 0;

            /* input field variables for inventory items */
            $item_id = $request->item_id;
            $item_qty = $request->item_qty;
            $item_price = $request->item_price;
            $item_discount = $request->item_discount;
            $item_discount_type = $request->item_discount_type;
            $item_tax = $request->item_tax;

            /* input field variables for Custom items */
            $row_no = $request->row_no;
            $custom_item_qty = $request->custom_item_qty;
            $custom_item_price = $request->custom_item_price;
            $custom_item_discount = $request->custom_item_discount;
            $custom_item_discount_type = $request->custom_item_discount_type;
            $custom_item_tax = $request->custom_item_tax;

            /* old input field variables */
            $item_details_id = $request->item_details_id;
            $old_item_qty = $request->old_item_qty;
            $old_item_price = $request->old_item_price;
            $old_item_discount = $request->old_item_discount;
            $old_item_discount_type = $request->old_item_discount_type;
            $old_item_tax = $request->old_item_tax;

            /* Sub Total input variables */

            $purchaseOrder = PurchaseOrder::find($order_no);
            if (! $purchaseOrder) {
                Session::flush('fail', __('Update failed'));
                return redirect()->back();
            }

            $taxTable = TaxType::getAll();

            # region update sales_order table
            $purchaseOrder->user_id             = $userId;
            $purchaseOrder->invoice_type        = $request->inv_type;
            $purchaseOrder->discount_on         = $request->discount_on;
            $purchaseOrder->tax_type            = $request->tax_type;
            $purchaseOrder->has_tax             = $invItemTax;
            $purchaseOrder->has_description     = $invItemDetails;
            $purchaseOrder->has_item_discount   = $invItemDiscount;
            $purchaseOrder->has_hsn             = $invItemHSN;
            $purchaseOrder->has_other_discount  = $invOtherDiscount;
            $purchaseOrder->has_shipping_charge = $invShipping;
            $purchaseOrder->has_custom_charge   = $invCustomAmount;

            if ($invOtherDiscount == 1) {
                $purchaseOrder->other_discount_amount = validateNumbers($request->other_discount);
                $purchaseOrder->other_discount_type = $request->other_discount_type;
            } else {
                $purchaseOrder->other_discount_amount = 0;
            }

            if ($invShipping == 1) {
                $purchaseOrder->shipping_charge = validateNumbers($request->shipping);
            } else {
                $purchaseOrder->shipping_charge = 0;
            }

            if ($invCustomAmount == 1) {
                $purchaseOrder->custom_charge_title = $request->custom_amount_title;
                $purchaseOrder->custom_charge_amount= validateNumbers($request->custom_amount);
            } else {
                $purchaseOrder->custom_charge_title = "";
                $purchaseOrder->custom_charge_amount = 0;
            }

            $purchaseOrder->currency_id         = $request->inv_currency;
            $purchaseOrder->exchange_rate       = !empty($request->inv_exchange_rate) ? validateNumbers($request->inv_exchange_rate) : 0;
            $purchaseOrder->purchase_receive_type_id = $request->receive_type;
            $purchaseOrder->has_comment         = $noteCheck;
            $purchaseOrder->comments            = stripBeforeSave($request->comments);
            $purchaseOrder->order_date          = DbDateFormat($request->order_date);
            $purchaseOrder->location_id         = $request->location;
            $purchaseOrder->total               = validateNumbers($request->totalValue);
            $purchaseOrder->payment_term_id     = $request->payment_term;
            $purchaseOrder->updated_at          = date('Y-m-d H:i:s');
            $purchaseOrder->save();
            # endregion
            if (isset($item_details_id) && count($item_details_id) > 0) {
                # region remove the deleted item
                $purchaseItemRowIds = PurchaseOrderDetail::where('purchase_order_id', $order_no)->pluck('id');
                foreach ($purchaseItemRowIds as $key => $purchaseItemRowId) {
                    if (!in_array($purchaseItemRowId, $item_details_id)) {
                        DB::table('received_order_details')->where(array('purchase_order_detail_id' => $purchaseItemRowId, 'purchase_order_id' => $order_no))->delete();
                        DB::table('purchase_order_details')->where(array('id' => $purchaseItemRowId, 'purchase_order_id' => $order_no))->delete();
                    }
                }
                #e ndregion

                # region remove tax
                foreach ($purchaseOrder->purchaseOrderDetails as $key => $purchaseOrderDetail) {
                    DB::table('purchase_taxes')->where(['purchase_order_detail_id' => $purchaseOrderDetail->id])->delete();
                }
                # endregion
                # region update the edited items
                foreach ($item_details_id as $key => $value) {
                    $purchaseDetail               = PurchaseOrderDetail::find($value);
                    $purchaseDetail->sorting_no   = $request->old_sorting_no[$key];
                    $purchaseDetail->item_id      = $request->old_item_id[$key] ? $request->old_item_id[$key] : null;
                    $purchaseDetail->description  = $invItemDetails ? stripBeforeSave($request->old_item_description[$key]) : "";
                    $purchaseDetail->quantity_ordered = validateNumbers($old_item_qty[$key]);
                    $discountAmount = 0;
                    $old_item_discount[$key]      = $old_item_discount[$key] ? $old_item_discount[$key] : 0;
                    if ($invItemDiscount == 1) {
                        if ($old_item_discount_type[$key] == '$') {
                            $discountAmount = validateNumbers($old_item_discount[$key]);
                        } else {
                            $discountAmount = validateNumbers($old_item_discount[$key]) * validateNumbers($old_item_price[$key]) * validateNumbers($old_item_qty[$key]) / 100;
                        }
                    }
                    $purchaseDetail->discount        = $invItemDiscount ? validateNumbers($old_item_discount[$key]) : 0;
                    $purchaseDetail->discount_type   = $invItemDiscount ? $old_item_discount_type[$key] : "%";
                    $purchaseDetail->discount_amount = $discountAmount;
                    $purchaseDetail->unit_price      = validateNumbers($old_item_price[$key]);
                    $purchaseDetail->hsn             = $invItemHSN ? $request->old_item_hsn[$key] : "";
                    $purchaseDetail->item_name       = stripBeforeSave($request->old_item_name[$key]);
                    if ($invItemTax && isset($old_item_tax[$value])) {
                        $i = 0;
                        $old_purchase_tax = [];
                        foreach ($old_item_tax[$value] as $tax) {
                            $selectedTax = $taxTable->where('id', $tax)->first();
                            if ($selectedTax) {
                                $taxAmount = (new TaxType)->calculateTax($selectedTax->tax_rate, $request->tax_type, $request->discount_on, validateNumbers($old_item_price[$key]) * validateNumbers($old_item_qty[$key]), $old_item_discount[$key], $old_item_discount_type[$key], $request->other_discount_amount, $request->other_discount_type, $request->indivisual_discount_price);

                                $old_purchase_tax[$i]['purchase_order_detail_id'] = $value;
                                $old_purchase_tax[$i]['tax_type_id'] = $tax;
                                $old_purchase_tax[$i]['tax_amount'] = $taxAmount;
                                $i++;
                            }
                        }
                        DB::table('purchase_taxes')->insert($old_purchase_tax);
                    }
                    $purchaseDetail->save();
                }
                # endregion
            } else {
                DB::table('purchase_order_details')
                    ->where(['purchase_order_id' => $order_no])
                    ->delete();
            }

            # region insert the new items
            # region inventory item add
            if (!empty($item_id)) {
                foreach ($item_id as $key => $item) {
                    if ($item_qty[$key] > 0) {
                        $purchaseDetail                    = new PurchaseOrderDetail();
                        $purchaseDetail->purchase_order_id = $order_no;
                        $purchaseDetail->sorting_no        = $request->sorting_no[$key];
                        $purchaseDetail->item_id           = $item_id[$key];
                        if ($invItemDetails) {
                            $purchaseDetail->description   = stripBeforeSave($request->item_description[$key]);
                        }
                        $purchaseDetail->quantity_ordered  = validateNumbers($item_qty[$key]);
                        $discountAmount = 0;
                        $item_discount[$key]               = $item_discount[$key] ? $item_discount[$key] : 0;
                        if ($invItemDiscount == 1) {
                            if ($item_discount_type[$key] == '$') {
                                $discountAmount = validateNumbers($item_discount[$key]);
                            } else {
                                $discountAmount = validateNumbers($item_discount[$key]) * validateNumbers($item_price[$key]) * validateNumbers($item_qty[$key]) / 100;
                            }
                        }
                        $purchaseDetail->discount        = $invItemDiscount ? validateNumbers($item_discount[$key]) : 0;
                        $purchaseDetail->discount_type   = $invItemDiscount ? $item_discount_type[$key] : "%";
                        $purchaseDetail->discount_amount = $discountAmount;
                        $purchaseDetail->unit_price      = validateNumbers($item_price[$key]);
                        $purchaseDetail->hsn             = $invItemHSN ? $request->item_hsn[$key] : "";
                        $purchaseDetail->item_name       = stripBeforeSave($request->item_name[$key]);
                        $purchaseDetail->save();
                        if ($invItemTax && isset($item_tax[$item_id[$key]])) {
                            $i = $j = 0;
                            $purchase_tax = [];
                            foreach ($item_tax[$item_id[$key]] as $tax) {
                                $selectedTax = $taxTable->where('id', $tax)->first();
                                if ($selectedTax) {
                                    $taxAmount = (new TaxType)->calculateTax($selectedTax->tax_rate, $request->tax_type, $request->discount_on, validateNumbers($item_price[$key]) * validateNumbers($item_qty[$key]), $item_discount[$key], $item_discount_type[$key], $request->other_discount_amount, $request->other_discount_type, $request->indivisual_discount_price);

                                    $purchase_tax[$i]['purchase_order_detail_id'] = $purchaseDetail->id;
                                    $purchase_tax[$i]['tax_type_id'] = $tax;
                                    $purchase_tax[$i]['tax_amount'] = $taxAmount;
                                    $i++;
                                }
                            }
                            DB::table('purchase_taxes')->insert($purchase_tax);
                        }
                    }
                }
            }
            // Inventory Items End
            # endregion
            # region Custom items
            if (!empty($row_no)) {
                foreach ($request->custom_item_name as $key => $value) {
                    if ($request->custom_item_name[$key] != null && $request->custom_item_qty[$key] > 0) {
                        $purchaseDetail                    = new PurchaseOrderDetail();
                        $purchaseDetail->purchase_order_id = $order_no;
                        $purchaseDetail->sorting_no        = $request->custom_sorting_no[$key];
                        if ($invItemDetails == 1) {
                            $purchaseDetail->description   = stripBeforeSave($request->custom_item_description[$key]);
                        }
                        $purchaseDetail->quantity_ordered  = validateNumbers($custom_item_qty[$key]);
                        $discountAmount = 0;
                        $custom_item_discount[$key]        = $custom_item_discount[$key] ? $custom_item_discount[$key] : 0;
                        if ($invItemDiscount == 1) {
                            if ($custom_item_discount_type[$key] == '$') {
                                $discountAmount = validateNumbers($custom_item_discount[$key]);
                            } else {
                                $discountAmount = validateNumbers($custom_item_discount[$key]) * validateNumbers($custom_item_price[$key]) * validateNumbers($custom_item_qty[$key]) / 100;
                            }
                        }
                        $purchaseDetail->discount        = $invItemDiscount ? validateNumbers($custom_item_discount[$key]) : 0;
                        $purchaseDetail->discount_type   = $invItemDiscount ? $custom_item_discount_type[$key] : "%";
                        $purchaseDetail->discount_amount = $discountAmount;
                        $purchaseDetail->unit_price      = validateNumbers($custom_item_price[$key]);
                        $purchaseDetail->hsn             = $invItemHSN ? $request->custom_item_hsn[$key] : "";
                        $purchaseDetail->item_name       = stripBeforeSave($request->custom_item_name[$key]);
                        $purchaseDetail->save();
                        if ($invItemTax && isset($custom_item_tax[$row_no[$key]])) {
                            $i = 0;
                            $customSaleTax = [];
                            foreach ($custom_item_tax[$row_no[$key]] as $tax) {
                                $selectedTax = $taxTable->where('id', $tax)->first();
                                if ($taxTable) {
                                    $taxAmount = (new TaxType)->calculateTax($selectedTax->tax_rate, $request->tax_type, $request->discount_on, validateNumbers($custom_item_price[$key]) * validateNumbers($custom_item_qty[$key]), $custom_item_discount[$key], $custom_item_discount_type[$key], $request->other_discount_amount, $request->other_discount_type, $request->indivisual_discount_price);

                                    $customSaleTax[$i]['purchase_order_detail_id'] = $purchaseDetail->id;
                                    $customSaleTax[$i]['tax_type_id'] = $tax;
                                    $customSaleTax[$i]['tax_amount'] = $taxAmount;
                                    $i++;
                                }
                            }
                            DB::table('purchase_taxes')->insert($customSaleTax);
                        }
                    }
                }
            }
            # endregion
            if (!empty($request->attachments)) {
                $path = createDirectory("public/uploads/purchase_order");
                (new File)->store($request->attachments, $path, 'Purchase Order', $purchaseOrder->id, ['isUploaded' => true, 'isOriginalNameRequired' => true]);
            }
            $flag = $request->menu == "purchase" ? "" : "?".$request->sub_menu;

            # endregion
            if ($request->receive_type == 1) {
                return "purchase/receive/all/".$purchaseOrder->id.$flag;
            }
            return "purchase/view-purchase-details/$order_no$flag";
        });
        Session::flash('success', __('Successfully updated'));
        return redirect()->intended($url);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if (isset($id)) {
            try {
                DB::beginTransaction();
                $record = PurchaseOrder::find($id);
                if ( !empty($record) ) {
                    if ( count($record->supplierTransactions) > 0) {
                        foreach ($record->supplierTransactions as $key => $value) {
                            DB::table('general_ledger_transactions')->where('reference_id', $value->transaction_reference_id)->delete();
                            DB::table('transactions')->where('transaction_reference_id', $value->transaction_reference_id)->delete();
                            DB::table('supplier_transactions')->where('id', $value->id)->delete();
                            DB::table('transaction_references')->where('id', $value->transaction_reference_id)->delete();
                        }
                        DB::table('supplier_transactions')->where('purchase_order_id', $id)->delete();
                    }
                    DB::table('stock_moves')->where('reference', '=', 'store_in_' . $record->id)->delete();
                    DB::table('received_order_details')->where('purchase_order_id', $id)->delete();
                    DB::table('received_orders')->where('purchase_order_id', $id)->delete();
                    DB::table('purchase_order_details')->where('purchase_order_id', $id)->delete();
                    DB::table('purchase_orders')->where('id', $id)->delete();

                    Session::flash('success', __('Deleted sucessfully'));
                    if ($request->sub_menu == 'users') {
                        return redirect()->intended("user/purchase-list/$request->user");
                    } else if ($request->sub_menu == 'supplier') {
                        return redirect()->intended("supplier/orders/$request->supplier");
                    }
                }
                DB::commit();
                (new File)->deleteFiles('Purchase Order', $id, [], 'public/uploads/purchase_order');
            } catch (Exception $e) {
                DB::rollBack();
            }
            return redirect()->intended("purchase/list");
        }
    }

    public function searchItem(Request $request)
    {
        $data = [];
        $data['type']          = $request->type;
        $data['currency_id']   = $request->currency_id;
        $data['exchange_rate'] = $request->exchange_rate;
        $data['saleType']      = $request->salesTypeId;
        $data['transactionType'] = 'Purchase';
        $data['key']           = $request->search;
        $result = (new Item)->search(json_encode($data));
    }

    /**
     * View purchase details
     * @param  [int] $id
     * @return render view
     */
    public function view($id)
    {
        $data = [];
        $data['menu'] = 'purchase';
        $data['sub_menu'] = 'purchase/list';
        $data['page_title'] = __('View Purchase');
        $data['invoiceType'] = 'directPurchase';
        $data['itemSummery'] = "";
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['dflt_currency_id'] = $preference['dflt_currency_id'];
        $data['exchange_rate_decimal_digits'] = $preference['exchange_rate_decimal_digits'];

        $data['purchaseData']  = PurchaseOrder::with(['purchaseOrderDetails',
                                                        'supplier:id,name,email,street,contact,city,state,zipcode,country_id',
                                                        'location:id,name',
                                                        'currency:id,name,symbol'])
                                                ->find($id);
        if (empty($data['purchaseData'])) {
            Session::flash('fail', __('The data you are trying to access is not found.'));
            return redirect()->intended('purchase/list');
        }

        foreach ($data['purchaseData']->purchaseOrderDetails as $key => $value) {
            if ($data['purchaseData']->has_tax == 1 && $value->quantity_ordered > 0) {
                $value->taxList = (new PurchaseTax)->getAllPurchaseTaxesInPercentage($value->id);
            }
            $data['itemSummery'] .= $value->item_name."|| Quantity: ".$value->quantity_ordered."pcs"."|| Amount: ".$data['purchaseData']->currency->symbol.$value->quantity_ordered*$value->unit_price." <br>";
        }

        $data['taxes'] = (new PurchaseOrder)->calculateTaxRows($id);

        $data['accounts'] = Account::where('is_deleted','!=',1)->get();
        $data['paymentMethods'] = PaymentMethod::getAll()->where('is_active', 1)->toArray();
        $data['currencies'] = Currency::getAll()->pluck('name', 'id')->toArray();
        $data['expenseCategories'] = IncomeExpenseCategory::where('category_type', 'expense')->pluck('name', 'id');
        $data['emailInfo'] = EmailTemplate::getAll()
                            ->where('template_id', 6)
                            ->where('language_short_name', $preference['dflt_lang'])
                            ->first();
        $data['purchasePaymentsList'] = SupplierTransaction::where('purchase_order_id',$id)->latest('transaction_date')->orderBy('id', 'desc')->get();

        $data['taxTypes']   = TaxType::getAll();
        $reference = TransactionReference::where('reference_type', 'PURCHASE_PAYMENT')->latest('id')->first();

        if (!empty($reference)) {
            $info = explode('/', $reference->code);
            $refNo = (int)$info[0];

            $data['reference'] = sprintf("%03d", $refNo + 1) . '/' . date('Y');
        } else {
            $data['reference'] = sprintf("%03d", 1) . '/' . date('Y');
        }

        $data['files'] = (new File)->getFiles('Purchase Order', $id);

        if (!empty($data['files'])) {
            $data['filePath'] = "public/uploads/purchase_order";
            foreach ($data['files'] as $key => $value) {
                $value->fileName = implode(" ",array_slice(explode('_', $value->file),2));
                $value->icon = getFileIcon($value->file_name);
                $value->extension = strtolower(pathinfo($value->file_name, PATHINFO_EXTENSION));
            }
        }

        return view('admin.purchase.view', $data);
    }

    public function copy($id)
    {
        $data = [];
        if (isset($_GET['customer'])) {
            $data['menu'] = 'relationship';
            $data['sub_menu'] = 'customer';
        } else if (isset($_GET['supplier'])) {
            $data['menu'] = 'relationship';
            $data['sub_menu'] = 'supplier';
        } else if (isset($_GET['users'])) {
            $data['menu'] = 'relationship';
            $data['sub_menu'] = 'users';
        } else {
            $data['menu'] = 'purchase';
            $data['sub_menu'] = 'purchase/list';
        }
        try {
            DB::beginTransaction();
            $purchaseOrder = PurchaseOrder::with(['purchaseOrderDetails'])->find($id);
            if ( empty($purchaseOrder) ) {
                Session::flash('fail', __('Order does not exist.'));
                return redirect('order/list');
            }
            $purchaseCount = 0;
            $firstPurchase = PurchaseOrder::first(['id']);
            if ( !empty($firstPurchase)) {
                $purchaseReference = PurchaseOrder::latest('id')->first(['reference']);
                $ref = explode("-", $purchaseReference->reference);
                $purchaseCount = (int)$ref[1];
            }
            if ( count($purchaseOrder->purchaseOrderDetails) > 0 ) {
                $newPurchase = $purchaseOrder->replicate();
                $newPurchase->reference = 'PO-' . sprintf("%04d", $purchaseCount + 1);
                $newPurchase->created_at = date('Y-m-d H:i:s');
                $newPurchase->save();
                foreach ($purchaseOrder->purchaseOrderDetails as $key => $detail) {
                    $newDetail = $detail->replicate();
                    $newDetail->purchase_order_id = $newPurchase->id;
                    $newDetail->save();
                    if ($detail->purchaseTaxes) {
                        foreach ($detail->purchaseTaxes as $key => $tax) {
                            $newTax = $tax->replicate();
                            $newTax->purchase_order_detail_id = $newDetail->id;
                            $newTax->save();
                        }
                    }
                }
                $files = (new File)->copyFiles("public/uploads/purchase_order", "public/uploads/purchase_order", "Purchase Order", $id, "Purchase Order", $newPurchase->id, ['isUploaded' => true, 'isOriginalNameRequired' => true]);
            }
            DB::commit();
            if (isset($newPurchase) && $newPurchase->id) {
                Session::flash('success', __('Purchase Copied Successfully'));
                $flag = '';
                if (isset($_GET['customer'])) {
                    $flag = "?customer";
                } else if (isset($_GET['users'])) {
                    $flag = "?users";
                }
                return redirect()->intended('purchase/view-purchase-details/' . $newPurchase->id . $flag);
            } else {
                Session::flash('fail', __('Purchase Copy Failed'));
                return redirect()->back();
            }
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('fail', __('Purchase Copy Failed'));
            return redirect()->back();
        }
    }

    /**
     * [purchasePrintPdf description]
     * @param  [int] $id [description]
     * @return render view
     */
    public function purchasePrintPdf($id, $type = null)
    {
        $data = [];
        $data['invoiceType'] = 'directPurchase';
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $data['dflt_currency_id'] = $preference['dflt_currency_id'];

        $data['purchaseData']  = PurchaseOrder::with(['supplier:id,name,email,street,contact,city,state,zipcode,country_id',
                                                    'purchaseOrderDetails',
                                                    'location:id,name',
                                                    'currency:id,name,symbol'])->find($id);
        if (empty($data['purchaseData'])) {
            Session::flash('fail', __('No Purchase'));
            return redirect()->intended('purchase/list');
        }
        foreach ($data['purchaseData']->purchaseOrderDetails as $key => $value) {
            if ($data['purchaseData']->has_tax == 1 && $value->quantity_ordered > 0) {
                $value->taxList = (new PurchaseTax)->getAllPurchaseTaxesInPercentage($value->id);
            }
        }
        $data['taxes'] = (new PurchaseOrder)->calculateTaxRows($id);
        $data['accounts'] = Account::where('is_deleted','!=',1)->get();
        $data['payments'] = PaymentTerm::getAll();
        $data['expenseCategories'] = IncomeExpenseCategory::where('category_type', 'expense')->pluck('name', 'id');
        $data['purchasePaymentsList'] = SupplierTransaction::where('purchase_order_id',$id)->latest('transaction_date')->get();

        $data['taxTypes']   = TaxType::getAll();
        $reference = TransactionReference::where('reference_type', 'PURCHASE_PAYMENT')->latest('id')->first();

        if (!empty($reference)) {
            $info = explode('/', $reference->reference);
            $refNo = (int)$info[0];

            $data['reference'] = sprintf("%03d", $refNo + 1) . '/' . date('Y');
        } else {
            $data['reference'] = sprintf("%03d", 1) . '/' . date('Y');
        }

         $data['company_logo']   = Preference::getAll()->where('category','company')->where('field', 'company_logo')->first('value');
        $data['type'] = request()->get('type') == 'print' || request()->get('type') == 'pdf' ? request()->get('type') : 'print';
        if ($type === 'emailPurchasePDF') {
            return printPDF($data, public_path() . '/uploads/invoices/' . 'purchase_' . time() . '.pdf', 'admin.purchase.PrintPdf', view('admin.purchase.PrintPdf', $data), null, "email");
        } else {
            return printPDF($data, 'purchase_invoice_' . time() . '.pdf', 'admin.purchase.PrintPdf', view('admin.purchase.PrintPdf', $data), $data['type']);
        }

    }

    /**
     * [referenceValidation description]
     * @param  Request $request
     * @return json
     */
    public function referenceValidation(Request $request)
    {
        $ref = $request->ref;
        $result = DB::table('purchase_orders')->where("reference", $ref)->first();
        $data = [];
        if (!empty($result)) {
            $data['status_no'] = 1;
        } else {
            $data['status_no'] = 0;
        }
        return json_encode($data);
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function emailPurchaseDetails(Request $request)
    {
        $this->validate($request, [
            'email' => ['required', new CheckValidEmail],
            'subject' => 'required',
            'message' => 'required'
        ]);
        ini_set('max_execution_time', 0);
        $orderNo = $request['order_id'];
        $orderName = 'purchase_' . time() . '.pdf';
        $emailConfig = DB::table('email_configurations')->first();
        $companyName = Preference::getAll()->where('category','company')->where('field', 'company_name')->first()->value;

        if ($emailConfig->status == 0 && $emailConfig->protocol == 'smtp') {
            return back()->withInput()->withErrors(['email' => "Verify your smtp settings of email"]);
        }
        if (isset($request['purchase_pdf']) && $request['purchase_pdf'] == 'on') {
            createDirectory("public/uploads/invoices");
            $this->purchasePrintPdf($orderNo, 'emailPurchasePDF');
            $emailResponse = $this->email->sendEmailWithAttachment($request['email'], $request['subject'], $request['message'], $orderName, $companyName);

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
        return redirect()->intended('purchase/view-purchase-details/' . $orderNo);
    }

    /**
     * [purchasePdf description]
     * @return render view
     */
    public function purchasePdf()
    {
        $data = [];
        $from = isset($_GET['from']) ? $_GET['from'] : '';
        $to = isset($_GET['to']) ? $_GET['to'] : '';
        $supplier = isset($_GET['supplier']) ? $_GET['supplier'] : '';
        $currency = isset($_GET['currency']) ? $_GET['currency'] : '';
        $location = isset($_GET['location']) ? $_GET['location'] : '';
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        if (!empty($supplier)) {
            $data['supplierData'] = Supplier::find($supplier);
        }
        $data['purchData'] = $purchaseList = (new PurchaseOrder)->getAll($supplier, $currency, $location, $status, $from, $to)->orderBy('id','DESC')->get();
        $data['date_range'] = ($from && $to) ? formatDate($from) . ' to ' . formatDate($to) : 'No Date Selected';
        return printPDF($data, 'purchase_invoice_' . time() . '.pdf', 'admin.purchase.purchasePdf', view('admin.purchase.purchasePdf', $data), 'pdf', 'domPdf');
    }

    public function purchaseCsv()
    {
        return Excel::download(new allPurchaseExport(), 'purchase_list'.time().'.csv');
    }

    public function addSupplier()
    {
        $data = [];
        $data['countries']   = Country::orderBy('name')->get();
        $data['currencies']  = Currency::orderBy('name')->get();
        return view('admin.purchase.add-supplier', $data);
    }
}

class CustomException extends \Exception
{
    private $arrayMessage = null;

    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        if (is_array($message)) {
            $this->arrayMessage = $message;
            $message = null;
        }
        $this->exception = new \Exception($message, $code, $previous);
    }

    public function getCustomMessage()
    {
        return $this->arrayMessage ? $this->arrayMessage : $this->getMessage();
    }
}
