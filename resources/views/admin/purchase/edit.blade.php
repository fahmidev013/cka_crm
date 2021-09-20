@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('public/dist/css/bootstrap-select.min.css')}}">
<link rel="stylesheet" href="{{ asset('public/dist/plugins/jQueryUI/jquery-ui.min.css') }}" type="text/css"/>
{{-- daterangepicker --}}
<link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('public/dist/css/invoice-style.min.css')}}">
@endsection

@section('content')
<div id="purchase-edit-container">
  <div class="col-sm-12" id="card-with-header-buttons">
    <form id="purchaseForm" action="{{url('purchase/update')}}" method="post">
      {{ csrf_field() }}
      <input type="hidden" name="menu" value="{{$menu}}">
      <input type="hidden" name="sub_menu" value="{{$sub_menu}}">
      <input type="hidden" value="{{ $purchaseData->id }}" name="order_no" id="order_no">
      <input type="hidden" name="inv_currency" id="inv-currency" value="{{ $supplierData->currency_id }}">
      <input type="hidden" name="reference" id="reference_no_write" value="{{ $purchaseData->reference }}">
      <input type="hidden" name="discount_on" id="discount_on" value="{{ $purchaseData->discount_on }}">
      <input type="hidden" name="tax_type" id="tax_type" value="{{ $purchaseData->tax_type }}">
      <input type="hidden" name="purchase_receive_type" id="purchase_receive_type" value="{{ $purchaseData->purchase_receive_type_id }}">
      <input type="hidden" name="invItemTax" id="invItem-Tax" value="{{ $purchaseData->has_tax }}">
      <input type="hidden" name="invItemDetails" id="invItem-Details" value="{{ $purchaseData->has_description }}">
      <input type="hidden" name="invItemDiscount" id="invItem-Discount" value="{{ $purchaseData->has_item_discount }}">
      <input type="hidden" name="invItemHSN" id="invItem-hsn" value="{{ $purchaseData->has_hsn }}">
      <input type="hidden" name="invOtherDiscount" id="inv-other-discount" value="{{ $purchaseData->has_other_discount }}">
      <input type="hidden" name="invShipping" id="inv-shipping" value="{{ $purchaseData->has_shipping_charge }}">
      <input type="hidden" name="invCustomAmount" id="inv-custom-amount" value="{{ $purchaseData->has_custom_charge }}">
      <input type="hidden" name="indivisual_discount_price" id="indivisual-discount-price" value="0">
      <input type="hidden" id="colspan" value="3">

      <div class="card">
        <div class="card-header">
          <h5><a href="{{url('purchase/list')}}">{{ __('Purchases') }}</a> >> {{ __('Edit Purchase')  }}</h5>
        </div>
        <div class="card-body">
          <div class="m-t-10 p-0">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row mb-0">
                  <label class="col-form-label col-md-4">{{ __('Supplier') }}<span class="text-danger"> *</span></label>
                  <div class="col-md-8 p-md-0">
                    <label class="col-form-label">
                    	{{ $supplierData->name }} ({{ isset($supplierData->currency->name) ? $supplierData->currency->name : '' }})
                      @if($supplierData->city)
                        , {{ $supplierData->city }}
                      @endif
                      @if($supplierData->zipcode)
                        -{{ $supplierData->zipcode }}
                      @endif
                      @if($supplierData->state)
                        , {{ $supplierData->state }}
                      @endif
                      @if(isset($supplierData->country->name))
                        , {{ $supplierData->country->name }}
                      @endif
                    </label>
                    <input type="hidden" name="supplier_id" id="supplier" value="{{ $supplierData->id }}" data-currency_id="{{ $supplierData->currency_id }}"  data-symbol="{{ isset($supplierData->currency->symbol) ? $supplierData->currency->symbol : '' }}" data-name="{{ isset($supplierData->currency->name) ? $supplierData->currency->name : '' }}">
                  </div>
                </div>
                <div class="form-group row mt-3">
                  <label class="col-md-4 col-form-label">{{ __('Reference') }}<span class="text-danger"> *</span></label>
                  <div class="input-group date col-md-8 p-md-0">
                    <label class="col-form-label"><a href="{{ url('purchase/view-purchase-details/'.$purchaseData->id) }}"><strong> {{ $purchaseData->reference }} </strong></a></label>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inv-type" class="col-md-4 col-form-label"> {{ __('Purchase Type')  }} </label>
                  <div class="col-md-8 p-md-0">
                    <label class="col-form-label"><strong> {{ $purchaseData->invoice_type == 'hours' ? __('Service') : __('Product') }} </strong></label>
                  </div>
                  <input type="hidden" name="inv_type" id="inv-type" value="{{$purchaseData->invoice_type}}">
                </div>
                <div class="form-group row">
                  <label for="receive-type" class="col-md-4 col-form-label"> {{ __('Purchase Receive') }} </label>
                  <div class="inv-header-type col-md-8 p-md-0">
                    <select name="receive_type" id="receive-type" class="form-control select2">
                      @foreach($purchaseReceiveTypes as $type)
                        <option value="{{$type->id}}" {{$type->id == $purchaseData->purchase_receive_type_id ? 'selected':'' }}>{{ ucfirst($type->receive_type) }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4 col-form-label">{{ __('Exchange Rate') }}<span class="text-danger"> *</span></label>
                  <div class="inv-exchange-rate col-md-8 p-md-0">
                    <input name="inv_exchange_rate" id="inv-exchange-rate" class="form-control positive-float-number" value="">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-form-label col-md-4">{{ __('Location') }}</label>
                  <div class="col-md-8 p-md-0">
                    <select class="select2" name="location" id="location">
                      @foreach($locations as $data)
                        <option value="{{ $data->id }}" {{ $data->id == $purchaseData->location_id ? 'selected':'' }} >{{ $data->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-4">{{ __('Date') }}</label>
                  <div class="input-group date col-md-8 p-md-0">
                    <div class="input-group-prepend">
                      <i class="fas fa-calendar-alt input-group-text"></i>
                    </div>
                    <input class="form-control" id="datepicker" type="text" name="order_date" value="{{ isset($purchaseData->order_date) ? $purchaseData->order_date : '' }}">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4 col-form-label">
                    {{ __('Payment Term') }}
                  </label>
                  <div class="col-md-8 p-md-0">
                    <select class="select2" name="payment_term">
                      @foreach($paymentTerms as $term)
                        <option value="{{ $term->id }}" {{ $term->id == $purchaseData->payment_term_id ? 'selected':'' }}>{{ $term->name }}</option>
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
          <h5>{{ __('Items') }}</h5>
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
                  <label class="col-md-2 col-form-label">{{ __('Add') }}&nbsp;&nbsp;<span class="searchItemTh"> {{ __('Item') }} </span></label>
                  <input class="form-control auto col-md-9" placeholder="{{ __('Search') }}" id="search">
                  <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="no_div" tabindex="0">
                    <li>{{ __('No record found') }} </li>
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
                            <th class="itemName">{{ __('Items') }}</th>
                            <th class="itemHSN text-center w-10">{{ __('HSN') }}</th>
                            <th class="itemQty text-center w-10">
                              <span class="qtyTh text-center">{{ __('Quantity') }}</span>
                              <span class="hourTh">{{ __('Hours') }}</span>
                            </th>
                            <th class="itemPrice text-center w-10">
                              <span class="qtyPriceTh">{{ __('Price') }} ({{$purchaseData->currency->symbol}})</span>
                              <span class="hourPriceTh">{{ __('Rate') }} ({{$purchaseData->currency->symbol}})</span>
                              <span class="amountTh">{{ __('Amount') }} ({{$purchaseData->currency->symbol}})</span>
                            </th>
                            <th class="itemDiscount text-center w-10" colspan="2">{{ __('Discount') }}</th>
                            <th class="itemTax text-center w-15">{{ __('Tax') }}</th>
                            <th class="itemAmount text-center w-10">{{ __('Amount') }} ({{$purchaseData->currency->symbol}})</th>
                            <th class="itemAction w-5">{{ __('Action') }}</th>
                          </tr>
                        </thead>
                        @php $count=1;  $stack = [] @endphp
                        @foreach($purchaseData->purchaseOrderDetails as $result)
                          <tbody id="rowId-{{$count}}">
                            <tr class="itemRow rowNo-{{$count}}" id="{{ 'itemId-'.$result->item_id }}" data-row-no="{{ $count }}">
                              <input type="hidden" class="sorting_no" name="old_sorting_no[]" value="{{$result->sorting_no}}">
                              <input type="hidden" name="old_item_id[]" value="{{ $result->item_id }}">
                              <input type="hidden" name="item_details_id[]" value="{{ $result->id }}">
                              <input type="hidden" name="hidden_price[]" value="{{ $result->unit_price }}">
                              <td class="pl-1">
                                <input name="old_item_name[]" id="old_item_name_{{ $count }}" value="{{ $result->item_name }}" placeholder="Item Name" type="text" class="inputDescription form-control">
                              </td>
                              <td class="itemHSN">
                                <input name="old_item_hsn[]" value="{{ $result->hsn }}" class="inputHSN form-control text-center" type="text" placeholder="{{ __('HSN') }}">
                              </td>
                              <td class="itemQty">
                                <input  value="{{ formatCurrencyAmount($result->quantity_ordered) }}" name="old_item_qty[]" id="old_item_qty_{{$count}}" class="inputQty form-control text-center positive-float-number" type="text" data-min="{{ $result->quantity_received }}">
                              </td>
                              <td class="itemPrice">
                                <input  value="{{ formatCurrencyAmount($result->unit_price) }}" name="old_item_price[]" id="old_item_price_{{ $count }}" class="inputPrice form-control text-center positive-float-number" type="text" placeholder=" {{ formatCurrencyAmount(0) }}">
                              </td>
                              <td class="itemDiscount">
                                <input name="old_item_discount[]" value="{{ formatCurrencyAmount($result->discount) }}" class="inputDiscount form-control text-center positive-float-number" id="old_item_discount_{{ $count }}" type="text" placeholder="{{ formatCurrencyAmount(0) }}">
                              </td>
                              <td class="itemDiscount">
                                <input type="hidden" class="indivisualDiscount" value="0">
                                <select name="old_item_discount_type[]" class="inputDiscountType form-control">
                                  <option {{ $result->discount_type=='%'?'selected':'' }} value="%">%</option>
                                  <option {{ $result->discount_type=='$'?'selected':'' }} value="$">Flat</option>
                                </select>
                              </td>
                              <td class="itemTax"><input type="hidden" class="indivisualTax" value="0">
                                <select data-taxes="{{ $result->taxList }}" name="old_item_tax[{{ $result->id }}][]" id="itemtaxrow-{{ $count }}" class="inputTax form-control bootstrap-select selectpicker" multiple>
                                  @foreach($taxTypeList as $value)
                                    <option title="{{ formatCurrencyAmount($value->tax_rate) . "%" }}" value="{{ $value->id }}" taxrate="{{ formatCurrencyAmount($value->tax_rate) }}">{{ $value->name . '(' . formatCurrencyAmount($value->tax_rate) . ')' }}</option>
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
                                <button type="button" class="closeRow btn btn-xs btn-danger" data-row-id="{{ $count }}" data-deletable="{{ $result->quantity_received > 0 ? 'false' : 'true' }}" data-alert="{{ __('This item already received') }}">
                                  <i class="feather icon-trash-2"></i>
                                </button>
                              </td>
                            </tr>
                            <tr>
                              <td colspan="4" class="des-col">
                                <textarea name="old_item_description[]" placeholder="{{ __('Item Description') }}" class="inputItemDescription form-control">{{$result->description}}</textarea>
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
                          <th colspan="3" class="text-right">{{ __('Subtotal') }}</th>
                          <td>{{ $supplierData->symbol }}<span id="subTotal">0</span></td>
                        </tr>
                        <tr class="itemTaxExtra display_none">
                          <th colspan="3" class="text-right">{{ __('Total Tax') }}</th>
                          <td>{{ $supplierData->symbol }}<span id="taxTotal">0</span></td>
                        </tr>
                        <tr class="itemDiscount">
                          <th colspan="3" class="text-right">{{ __('Item Discounts') }}</th>
                          <td>{{ $supplierData->symbol }}<span id="itemDiscountTotal">0</span></td>
                        </tr>
                        <tr class="otherDiscount">
                          <th class="text-right pr-2">{{ __('Other Discounts') }}</th>
                          <td class="pr-0"><input value="{{ formatCurrencyAmount($purchaseData->other_discount_amount) }}" name="other_discount" class="form-control text-right" placeholder="0.00" id="inputOtherDiscount" type="text"></td>
                          <td class="pl-0">
                            <select name="other_discount_type" id="otherDiscountType" class="form-control select2">
                              <option <?= $purchaseData->other_discount_type=='%'?'selected':''?> value="%">%</option>
                              <option <?= $purchaseData->other_discount_type=='$'?'selected':''?> value="$">Flat</option>
                            </select>
                          </td>
                          <td><span class="currencySymbol">{{ $supplierData->symbol }}</span><span id="otherDiscountTotal">0</span></td>
                        </tr>
                        <tr>
                          <td></td>
                          <td><label id="inputOtherDiscount-error" class="error float-left" for="inputOtherDiscount"></label></td>
                        </tr>
                        <tr class="shippingAmount">
                          <th class="text-right pr-2">{{ __('Shipping') }}</th>
                          <td colspan="2">
                            <input value="{{ !empty($purchaseData->shipping_charge) ? formatCurrencyAmount($purchaseData->shipping_charge) : 0 }}" name="shipping" class="form-control text-right positive-float-number" placeholder="0.00" id="inputShipping" type="text">
                          </td>
                          <td ><span id="shippingTotal">{{ !empty($purchaseData->shipping_charge) ? formatCurrencyAmount($purchaseData->shipping_charge, $currencySymbol) : 0 }}</span></td>
                        </tr>
                        <tr>
                          <td></td>
                          <td><label id="inputShipping-error" class="error float-left" for="inputShipping"></label></td>
                        </tr>
                        @foreach($taxTypeList as $tax)
                          <tr class="taxRow" id="taxRow-{{$tax->id}}">
                            <th class="text-right pr-2">{{$tax->name}}</th>
                            <td colspan="2">
                              <span class="ml-20p">{{ formatCurrencyAmount($tax->tax_rate) }}%</span>
                            </td>
                            <td >{{ $supplierData->symbol }}<span id="taxTotalValue-{{ $tax->id }}">0</span></td>
                          </tr>
                        @endforeach
                        <tr class="customAmount">
                          <th>
                            <input value="{{ $purchaseData->custom_charge_title }}" name="custom_amount_title" class="form-control text-right" type="text" id="customAmountDescription" placeholder="Custom Amount">
                          </th>
                          <td colspan="2">
                            <input value="{{ formatCurrencyAmount($purchaseData->custom_charge_amount) }}" name="custom_amount" class="form-control text-right positive-float-number" placeholder="0.00" id="inputCustomAmount" type="text">
                          </td>
                          <td>{{ $supplierData->symbol }}<span id="customAmountTotal">{{ formatCurrencyAmount($purchaseData->custom_charge_amount) }}</span></td>
                        </tr>
                        <tr>
                          <td>
                            <label id="customAmountDescription-error" class="error float-left" for="customAmountDescription"></label>
                          </td>
                          <td>
                            <label id="inputCustomAmount-error" class="error float-left" for="inputCustomAmount"></label>
                          </td>
                        </tr>
                        <tr class="grandTotal">
                          <input type="hidden" name="grand_total" value="{{ formatCurrencyAmount($purchaseData->total) }}" id="grandTotalInput">
                          <input type="hidden" name="totalValue" id="totalValue" value="{{ formatCurrencyAmount($purchaseData->total) }}">
                          <th colspan="3" class="text-right">Total</th>
                          <td>{{ $supplierData->symbol }}<span id="grandTotal">0</span>
                          </td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group row">
                  <label for="exampleInputEmail1">{{ __('Note') }}</label>
                  <textarea placeholder="{{  __('Description')  }} ..." rows="3" class="form-control" name="comments">{{$purchaseData->comments}}</textarea>
                </div>
                <div class="form-group row">
                  <div class="checkbox checkbox-primary checkbox-fill d-inline">
                    <input type="checkbox" name="note_check" id="checkbox-p-fill-1" <?=$purchaseData->has_comment ? 'checked' : '' ?>>
                    <label for="checkbox-p-fill-1" class="cr"><strong>{{__('Print note on pdf')}}</strong></label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <h5>{{  __('Files')  }}</h5>
        </div>
        <div class="card-body">
          <div class="m-t-10 p-0">
            <div class="form-group row">
              <label class="col-sm-1 col-form-label">{{  __('File')  }}</label>
              <div class="col-md-11">
                <div class="dropzone-attachments" id="reply-attachment">
                  <div class="event-attachments">
                    @forelse($files as $file)
                      <div class="list-attachments">
                        <i class="fa fa-times attachment-item-delete" data-id="{{$file->id}}" data-attachment="{{ $filePath }}/{{ $file->file_name }}" title="Delete Attachment" aria-hidden="true"></i>
                        <br>
                        <i class="{{ $file->icon }}" title="{{ $file->file_name }}"></i>
                        <input type="hidden" name="attachments[]" value="{{ $file->file_name }}">
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
                <span class="badge badge-danger">{{ __('Note')  }}!</span> {{ __('Allowed File Extensions: jpg, png, gif, docx, xls, xlsx, csv and pdf')  }}
              </div>
            </div>
            <div class="form-group row ml-1">
              <button class="btn btn-primary custom-btn-small float-left" type="submit" id="btnSubmit">{{ __('Update') }}</button>
              <a href="{{ url($url) }}" class="btn btn-danger custom-btn-small float-left">{{ __('Cancel')  }}</a>
            </div>
          </div>
        </div>
      </div>
  	</form>
  </div>

  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content ">
        <div class="modal-header">
          <h5 class="modal-title">{{ __('Add custom fields') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          @include('admin.purchase.setting-modal')
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
<script src="{{asset('public/dist/plugins/bootstrap-select/dist/js/bootstrap-select.min.js')}}"></script>
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/jQueryUI/jquery-ui.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/dropzone/dropzone.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>
<script src="{{asset('public/dist/js/custom/invoice-script.min.js')}}"></script>
{!! translateValidationMessages() !!}
<script type="text/javascript">
  'use strict';
  var taxType = "{!! $tax_type!!}";
  var taxes = {!! $taxTypeList !!};
  var defaultCurrencyId='{{$default_currency->value}}';
  var stack = '{{ json_encode($stack) }}';
  var rowNo = {!! $count !!};
  var currencySymbol = '{!! $currencySymbol !!}';
  let exchangeVal = {!! $purchaseData->exchange_rate !!};
  var exchange_rate_decimal_digits = "{{ $exchange_rate_decimal_digits }}";
</script>
<script src="{{ asset('public/dist/js/custom/sales-purchase.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/purchases.min.js') }}"></script>
@endsection
