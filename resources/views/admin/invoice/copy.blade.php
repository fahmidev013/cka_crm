@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/dist/css/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/plugins/jQueryUI/jquery-ui.min.css') }}" type="text/css"/>
<link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/dist/css/invoice-style.min.css') }}">
@endsection

@section('content')
<div id="sales-invoice-copy-container">
  <div class="col-sm-12" id="card-with-header-buttons">
    <form id="invoiceForm" method="post">
      {{ csrf_field() }}
      <input type="hidden" name="menu" value="{{ $menu }}">
      <input type="hidden" name="sub_menu" value="{{ $sub_menu }}">
      <input type="hidden" value="{{ $invoiceData->id }}" name="order_no" id="order_no">
      <input type="hidden" name="currency_id" id="inv-currency" value="{{ $invoiceData->currency_id }}">
      <input type="hidden" value="{{ $invoiceData->order_reference_id }}" name="order_reference_id" id="order_reference_id">
      <input type="hidden" name="discount_on" id="discount_on" value="{{ $invoiceData->discount_on }}">
      <input type="hidden" name="tax_type" id="tax_type" value="{{ $invoiceData->tax_type }}">
      <input type="hidden" name="has_tax" id="invItem-Tax" value="{{ $invoiceData->has_tax ? 'on' : ''}}">
      <input type="hidden" name="has_description" id="invItem-Details" value="{{ $invoiceData->has_description ? 'on' : '' }}">
      <input type="hidden" name="has_item_discount" id="invItem-Discount" value="{{ $invoiceData->has_item_discount ? 'on' : '' }}">
      <input type="hidden" name="has_hsn" id="invItem-hsn" value="{{ $invoiceData->has_hsn ? 'on' : '' }}">
      <input type="hidden" name="has_other_discount" id="inv-other-discount" value="{{ $invoiceData->has_other_discount ? 'on' : '' }}">
      <input type="hidden" name="has_shipping_charge" id="inv-shipping" value="{{ $invoiceData->has_shipping_charge ? 'on' : '' }}">
      <input type="hidden" name="has_custom_charge" id="inv-custom-amount" value="{{ $invoiceData->has_custom_charge ? 'on' : '' }}">
      <input type="hidden" name="indivisual_discount_price" id="indivisual-discount-price" value="0">
      <input type="hidden" id="colspan" value="3">

      <div class="card">
        <div class="card-header">
          <h5><a href="{{ url('invoice/list') }}">{{ __('Invoices')  }}</a> >> {{  __('Copy Invoice') }}</h5>
        </div>
        <div class="card-body">
          <div class="m-t-10 p-0">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-form-label col-md-4">{{ __('Customer') }}</label>
                  <div class="col-md-8 p-md-0">
                    <label class="col-form-label">
                      @if ($customerData)
                        {{ $customerData->first_name . ' ' . $customerData->last_name }} ({{ $customerData->currency->name }})
                        @if (isset($customerData->customerBranch->billing_city))
                          , {{ $customerData->customerBranch->billing_city }}
                        @endif
                        @if(isset($customerData->customerBranch->billing_zip_code))
                          -{{ $customerData->customerBranch->billing_zip_code }}
                        @endif
                        @if ($customerData->customerBranch->billing_state)
                          , {{ $customerData->customerBranch->billing_state }}
                        @endif
                        @if ($customerData->customerBranch && $customerData->customerBranch->billingCountry)
                          , {{ $customerData->customerBranch->billingCountry->name }}
                        @endif
                      @endif
                    </label>
                    <input type="hidden" name="customer_id" id="customers" value="{{ $invoiceData->customer_id }}" data-currency_id="{{ isset($invoiceData->currency->id) ? $invoiceData->currency->id : '' }}"  data-symbol="{{ isset($invoiceData->currency->symbol) ?  $invoiceData->currency->symbol : '' }}" data-name="{{ isset($invoiceData->currency->name ) ? $invoiceData->currency->name : '' }}">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inv-type" class="col-md-4 col-form-label"> {{  __('Invoice Type') }} </label>
                  <div class="col-md-8 p-md-0">
                    <label class="col-form-label">{{ $invoiceData->invoice_type == 'hours' ?  __('Service') : __('Product') }}</label>
                  </div>
                  <input type="hidden" name="invoice_type" id="inv-type" value="{{ $invoiceData->invoice_type }}">
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-4">{{ __('Location') }}</label>
                  <label class="col-form-label">{{ isset($invoiceData->location->name) ? $invoiceData->location->name : '' }}</label>
                  <input type="hidden" name="location_id" id="location" value="{{ $invoiceData->location_id }}">
                </div>
                <div class="form-group row" id="exchangeRateBlock">
                  <label for="inv-exchange-rate" class="col-md-4 col-form-label"> {{ __('Exchange Rate') }} </label>
                  <div class="inv-exchange-rate col-md-8 p-md-0">
                    <input type="text" name="exchange_rate" id="inv-exchange-rate" class="form-control positive-float-number" value="{{ $invoiceData->exchange_rate }}" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-md-4 col-form-label">{{ __('Reference') }}<span class="text-danger"> *</span></label>
                  <div class="input-group date col-md-8 p-md-0">
                    <input id="reference_no" class="form-control" name="reference" value='INV-{{ sprintf("%04d", $invoice_count) }}' type="text">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-4">{{ __('Date') }}<span class="text-danger"> *</span></label>
                  <div class="input-group date col-md-8 p-md-0">
                    <div class="input-group-prepend">
                      <i class="fas fa-calendar-alt input-group-text"></i>
                    </div>
                    <input class="form-control" id="datepicker" type="text" name="order_date" value="{{ isset($invoiceData->order_date) ? formatDate($invoiceData->order_date) : '' }}">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4 col-form-label">
                    {{ __('Payment Term') }}
                  </label>
                  <div class="col-md-8 p-md-0">
                    <select class="select2" name="payment_term_id">
                      @foreach($paymentTerms as $term)
                        <option value="{{ $term->id }}" {{ $term->id == $invoiceData->payment_term_id ? 'selected' : '' }}>{{ $term->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    	</div>
    	<div class="card">
        <div class="card-header">
          <h5 class="itemName">{{ __('Items') }}</h5>
          <div class="card-header-right">
            <div class="btn-group card-option">
              <i class="feather icon-settings btn color-04a9f5" data-toggle="modal" data-target="#myModal"></i>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="m-t-10 p-0">
            <div class="row">
              <div class="col-sm-8 mb-3">
                <div class="form-group row mb-0">
                  <label class="col-md-2 col-form-label">{{ __('Add')  }}&nbsp;&nbsp;<span class="searchItemTh"> {{ __('Item')  }} </span></label>
                  <input class="form-control auto col-md-9" placeholder="{{ __('Search Item') }}" id="search">
                  <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="no_div" tabindex="0">
                      <li>{{ __('No record found')  }} </li>
                  </ul>
                </div>
                <div class="row">
                  <div class="col-md-2"></div>
                  <div id="error_message" class="text-danger col-md-10 p-0"></div>
                </div>
              </div>
              <div class="col-sm-4">
                <div id="addRow-1" class="addRowContainer float-right">
                  <span data-row-no="1" class="addRow">{{ __('Add Custom Item') }}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12 m-t-10 p-2">
                <div class="inv-container">
                  <div class="inv-content" id="inv-content">
                    <div id="itemInputContainer" class="table-responsive">
                      <table class="table" id="product-table">
                        <thead>
                          <tr class="tbl_header_color">
                            <th class="itemName">{{ __('Items')  }}</th>
                            <th class="itemHSN text-center w-10">{{ __('HSN')  }}</th>
                            <th class="itemQty text-center w-10">
                              <span class="qtyTh text-center">{{ __('Quantity')  }}</span>
                              <span class="hourTh">{{ __('Hours')  }}</span>
                            </th>
                            <th class="itemPrice text-center w-10">
                              <span class="qtyPriceTh">{{ __('Price')  }} ({{ isset($invoiceData->currency->symbol) ? $invoiceData->currency->symbol : '' }})</span>
                              <span class="hourPriceTh">{{ __('Rate')  }} ({{ isset($invoiceData->currency->symbol) ? $invoiceData->currency->symbol : '' }})</span>
                              <span class="amountTh">{{ __('Amount')  }} ({{ isset($invoiceData->currency->symbol) ? $invoiceData->currency->symbol : '' }})</span>
                            </th>
                            <th class="itemDiscount text-center w-10" colspan="2">{{ __('Discount')  }}</th>
                            <th class="itemTax text-center w-15">{{ __('Tax')  }}</th>
                            <th class="itemAmount text-center w-10">{{ __('Amount')  }} ({{ $invoiceData->currency->symbol }})</th>
                            <th class="itemAction w-10">{{  __('Action') }}</th>
                          </tr>
                        </thead>
                        @php $count=1; @endphp
                        @foreach($invoiceData->saleOrderDetails as $result)
                          @php
                            $data_max = '';
                            $error = "";
                            if(isset($result->item['is_stock_managed']) && $result->item['is_stock_managed'] == 1) {
                              $max = $result->item->stockMoves->where('location_id', $invoiceData->location_id)->sum('quantity');
                              $data_max = 'data-max = ' . $max;
                              if($max < $result->quantity) {
                                $error = "error";
                              }
                            }
                          @endphp
                          <tbody id="rowId-{{ $count }}">
                            <tr class="itemRow rowNo-{{ $count }}" id="itemId-{{ $result->item_id }}" data-row-no="{{ $count }}">
                              <input type="hidden" name="row_counter[]" value="{{ $count }}">
                              <input type="hidden" class="sorting_no" name="sorting_no[]" value="{{ $result->sorting_no }}">
                              <input type="hidden" name="item_id[]" value="{{ $result->item_id }}">
                              <input type="hidden" name="item_details_id[]" value="{{ $result->id }}">
                              <input type="hidden" name="hidden_price[]" value="{{ $result->unit_price }}">
                              <td class="pl-1">
                                <input name="item_name[]" id="item_name_{{ $count }}" value="{{ $result->item_name }}" placeholder="Item Name" type="text" class="inputDescription form-control">
                              </td>
                              <td class="itemHSN">
                                <input name="item_hsn[]" value="{{ $result->hsn }}" class="inputHSN form-control text-center" type="text" placeholder="{{ __('HSN')  }}">
                              </td>
                              <td class="itemQty">
                                <input  value="{{ isInt($result->quantity) ? intval($result->quantity) : formatCurrencyAmount($result->quantity) }}" name="item_qty[]" id="item_qty_{{ $count }}" class="inputQty form-control text-center positive-float-number" type="text" data-is_stock_managed = "{{ isset($result->item['is_stock_managed']) ? $result->item['is_stock_managed'] : '' }}" {{ $data_max }}>
                              </td>
                              <td class="itemPrice">
                                <input  value="{{ formatCurrencyAmount($result->unit_price) }}" name="item_price[]" id="item_price_{{ $count }}" class="inputPrice form-control text-center positive-float-number" type="text" placeholder="0.00">
                              </td>
                              <td class="itemDiscount">
                                <input name="item_discount[]" value="{{ formatCurrencyAmount($result->discount) }}" class="inputDiscount form-control text-center positive-float-number {{ $error }}" id="item_discount_{{ $count }}" type="text" placeholder="0.00">
                              </td>
                              <td class="itemDiscount">
                                <input type="hidden" class="indivisualDiscount" value="0">
                                <select name="item_discount_type[]" class="inputDiscountType form-control">
                                  <option {{ $result->discount_type == '%' ? 'selected' : '' }} value="%">&nbsp; % &nbsp;</option>
                                  <option {{ $result->discount_type == '$' ? 'selected' : '' }} value="$">Flat</option>
                                </select>
                              </td>
                              <td class="itemTax">
                                <input type="hidden" class="indivisualTax" value="0">
                                <select data-taxes="{{ $result->taxList }}" name="item_tax[{{ $result->sorting_no }}][]" id="itemtaxrow-{{ $count }}" class="inputTax form-control bootstrap-select selectpicker" multiple>
                                  @foreach($taxTypeList as $value)
                                    <option title="{{ formatCurrencyAmount($value->tax_rate) . '%' }}" value="{{ $value->id }}" taxrate="{{ formatCurrencyAmount($value->tax_rate) }}">{{ $value->name . '(' . formatCurrencyAmount($value->tax_rate) . ')' }}</option>
                                  @endforeach
                                </select>
                                @foreach($taxTypeList as $value)
                                  <input type="hidden" class="itemTaxAmount itemTaxAmount-{{ $value->id }}">
                                @endforeach
                              </td>
                              <td class="itemAmount">
                                <span class="indivisualTotal">0</span>
                              </td>
                              <td class="text-center">
                                <button type="button" class="closeRow btn btn-xs btn-danger" data-row-id="{{ $count }}">
                                  <i class="feather icon-trash-2"></i>
                                </button>
                              </td>
                            </tr>
                            <tr>
                              <td colspan="4" class="des-col">
                                <textarea name="item_description[]" placeholder={{ __('Item Description') }} class="inputItemDescription form-control">{{ $result->description }}</textarea>
                              </td>
                              <td colspan="3" class="des-col"></td>
                            </tr>
                          </tbody>
                          @php
                            $count++;
                            $stack[] = $result->item_id;
                          @endphp
                        @endforeach
                      </table>
                    </div>
                    <div id="itemInputFooter" class="table-responsive">
                      <table>
                        <tr>
                          <th colspan="3" class="text-right">Subtotal</th>
                          <td>
                            <span id="subTotal">0</span>
                          </td>
                        </tr>
                        @foreach(json_decode($taxes) as $tax)
                          <tr class="taxRow" id="taxRow-{{ $tax->id }}">
                            <th class="text-right pr-2">{{ $tax->name }}</th>
                            <td colspan="2">
                              <span class="ml-2">{{ formatCurrencyAmount($tax->tax_rate) }}%</span>
                              <input value="{{ formatCurrencyAmount($tax->tax_rate) }}" name="tax" class="form-control no-display" id="inputTaxPercentage-{{ $tax->id }}" type="text">
                            </td>
                            <td >
                              <span id="taxTotalValue-{{ $tax->id }}">0</span>
                            </td>
                          </tr>
                        @endforeach
                        <tr class="itemTaxExtra" class="text-right">
                          <th colspan="3" class="text-right">{{ __('Total Tax') }}</th>
                          <td>
                            <span id="taxTotal">0</span>
                          </td>
                        </tr>
                        <tr class="itemDiscount">
                          <th colspan="3" class="text-right">{{ __('Item Discounts') }}</th>
                          <td>
                            <span id="itemDiscountTotal">0</span>
                          </td>
                        </tr>
                        <tr class="otherDiscount">
                          <th class="text-right pr-2">{{ __('Other Discounts') }}</th>
                          <td class="pl-0">
                            <input value="{{ formatCurrencyAmount($invoiceData->other_discount_amount) }}" name="other_discount_amount" class="form-control text-right positive-float-number" placeholder="0.00" id="inputOtherDiscount" type="text">
                          </td>
                          <td class="text-right pl-0">
                            <select name="other_discount_type" id="otherDiscountType" class="form-control select2">
                              <option <?= $invoiceData->other_discount_type == '%' ? 'selected' : ''?> value="%">%</option>
                              <option <?= $invoiceData->other_discount_type == '$' ? 'selected' : ''?> value="$">Flat</option>
                            </select>
                          </td>
                          <td>
                            <span id="otherDiscountTotal">0</span>
                          </td>
                        </tr>
                        <tr class="shippingAmount">
                          <th class="text-right pr-2">{{ __('Shipping') }}</th>
                          <td colspan="2">
                            <input value="{{ formatCurrencyAmount($invoiceData->shipping_charge) }}" name="shipping_charge" class="form-control text-right positive-float-number" placeholder="0.00" id="inputShipping" type="text">
                          </td>
                          <td >
                            <span id="shippingTotal">
                              {{ !empty($invoiceData->shipping_charge) ? formatCurrencyAmount($invoiceData->shipping_charge, $currencySymbol) : 0 }}
                            </span>
                          </td>
                        </tr>
                        <tr class="customAmount">
                          <th>
                            <input value="{{ $invoiceData->custom_charge_title }}" name="custom_charge_title" class="form-control text-right" type="text" id="customAmountDescription" placeholder="Custom Amount">
                          </th>
                          <td colspan="2">
                            <input value="{{ formatCurrencyAmount($invoiceData->custom_charge_amount) }}" name="custom_charge_amount" class="form-control text-right positive-float-number" placeholder="0.00" id="inputCustomAmount" type="text">
                          </td>
                          <td>
                            <span id="customAmountTotal">{{ formatCurrencyAmount($invoiceData->custom_charge_amount, $currencySymbol) }}</span>
                          </td>
                        </tr>
                        <tr class="grandTotal">
                          <input type="hidden" name="total" value="{{ $invoiceData->total }}" id="grandTotalInput">
                          <input type="hidden" name="totalValue" id="totalValue">
                          <th colspan="3" class="text-right">{{ __('Total') }}</th>
                          <td>
                            <span id="grandTotal">0</span>
                          </td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group row">
                  <label for="exampleInputEmail1">{{ __('Note')  }}</label>
                  <textarea placeholder="{{ __('Description') }} ..." rows="3" class="form-control" name="comments">{{ $invoiceData->comments }}</textarea>
                </div>
                <div class="form-group row">
                  <div class="checkbox checkbox-primary checkbox-fill d-inline">
                    <input type="checkbox" name="has_comment" id="checkbox-p-fill-1" <?= $invoiceData->has_comment ? 'checked' : ''?>>
                    <label for="checkbox-p-fill-1" class="cr"><strong>{{ __('Print note on pdf') }}</strong></label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  		<div class="card">
        <div class="card-header">
          <h5>{{ __('Files') }}</h5>
        </div>
        <div class="card-body">
          <div class="m-t-10 p-0">
            <div class="form-group row">
              <label class="col-sm-1 col-form-label">{{  __('File') }}</label>
              <div class="col-md-11">
                <div class="dropzone-attachments" id="reply-attachment">
                  <div class="event-attachments">
                    @forelse($files->files as $file)
                      <div class="list-attachments">
                        <i class="fa fa-times attachment-item-delete" data-id="0" data-attachment="{{ $filePath }}/{{ $file }}" title="Delete Attachment" aria-hidden="true"></i>
                        <br>
                        <i class="{{ getFileIcon($file) }}" title="{{ $file }}"></i>
                        <input type="hidden" name="attachments[]" value="{{ $file }}">
                      </div>
                    @empty
                    @endforelse
                    <div class="add-attachments"><i class="fa fa-plus"></i></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-1"></div>
              <div class="col-md-11" id="uploader-text"></div>
            </div>
            <div class="form-group row">
              <label class="col-md-1 control-label"></label>
              <div class="col-md-8">
                <span class="badge badge-danger">{{ __('Note') }}!</span> {{ __('Allowed File Extensions: jpg, png, gif, docx, xls, xlsx, csv and pdf') }}
              </div>
            </div>
            <div class="form-group row ml-1">
              <button class="btn btn-primary custom-btn-small" type="button" id="btnSubmit"><i class="comment_spinner spinner fa fa-spinner fa-spin custom-btn-small display_none"></i><span id="spinnerText">{{ __('Submit') }} </span></button>
              <a href="{{ url('/invoice/list') }}" class="btn btn-danger custom-btn-small float-left">{{ __('Cancel') }}</a>
            </div>
          </div>
        </div>
      </div>
  	</form>
  </div>
</div>

@include('admin.invoice.setting-modal')

@endsection

@section('js')
<script src="{{ asset('public/dist/plugins/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/jQueryUI/jquery-ui.min.js') }}">></script>
<script src="{{ asset('public/dist/js/moment.min.js')}}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/dropzone/dropzone.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/invoice-script.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script>
  'use strict';
  var taxType = "{!! $tax_type!!}";
  var taxes = {!! $taxes !!};
  var defaultCurrencyId = '{{ $default_currency->id }}';
  var exchange_rate_decimal_digits = "{{ $exchange_rate_decimal_digits }}";
  var filePath = '{{ $filePath }}';
  var stack = "{{ json_encode($stack) }}";
  var rowNo = {!! $count !!}
  var currencySymbol = '{!! $currencySymbol !!}';
  var exchangeRate = {!! $invoiceData->exchange_rate !!}
</script>
<script src="{{ asset('public/dist/js/custom/sales-purchase.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/sales.min.js') }}"></script>
@endsection