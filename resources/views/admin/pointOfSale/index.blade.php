@extends('layouts.pos')
@section('css')
<link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/plugins/jQueryUI/jquery-ui.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('public/dist/css/bootstrap-select.min.css')}}">

@endsection
@section('content')
  <div class="col-sm-12">
    <a href="{{ url('dashboard') }}" class="btn btn-secondary custom-btn-small"><i class="feather icon-home"></i>{{ __('Dashboard') }}</a>
    <a class="btn btn-secondary custom-btn-small" href="{{url('pos/set-location')}}"><i class="feather icon-map-pin"></i>{{ $location->name }}</a>
    <button type="button" class="btn btn-secondary custom-btn-small" data-remote="{{ url('pos/getHold-items') }}" data-label="getHold-items" data-toggle="modal" data-target="#holdModal"><i class="feather icon-rotate-ccw"></i>{{ __('Hold') }}</button>
    <a class="btn btn-secondary custom-btn-small logout" href="{{url('logout')}}"><i class="feather icon-log-out"></i>{{ __('Logout') }}</a>
    <button type="button" class="btn btn-secondary custom-btn-small full-screen" onclick="javascript:toggleFullScreen()"><i class="feather icon-maximize"></i></button>
  </div>
  <div class="col-md-6 pr-0">
    <div class="card">
      <div class="card-body p-0">
        <div class="col-sm-12">
          <div class="input-group m-t-10">
            <select class="select2 invoice-customers" id="customers">
              @foreach($customers as $customer)
              <option value="{{ $customer->id }}" data-currency_id="{{ $customer->currency->id }}">{{ $customer->first_name }} {{ $customer->last_name }} ({{ $customer->currency->name }})</option>
              @endforeach
            </select>
            <div class="input-group-append">
              <button class="btn btn-outline-secondary" type="button" data-remote="{{ url('pos/add-customer') }}?type=pos" data-label="add-customer" data-toggle="modal" data-target="#holdModal"><i class="feather icon-user-plus"></i>{{ __('Add Customer')  }}</button>
              <button class="btn btn-outline-secondary" type="button" data-remote="{{ url('pos/add-note') }}" data-label='add-note' data-toggle="modal" data-target="#holdModal"><i class="feather icon-edit-1"></i>{{ __('Note')  }}</button>
              <button class="btn btn-outline-secondary" type="button" data-remote="{{ url('pos/add-shipping') }}" data-label="add-shipping" data-toggle="modal" data-target="#holdModal"><i class="fas fa-truck"></i>{{ __('Shipping')  }}</button>
              <button class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target=".add-item"><i class="feather icon-plus"></i>{{ __('Items')  }}</button>
            </div>
          </div>
        </div>
        <div class="table-responsive m-t-10">
          <table class="table theme-bg2 text-white m-b-0">
            <thead>
              <tr>
                <th width="3%"></th>
                <th width="37%">{{ __('Items')  }}</th>
                <th width="18%">{{ __('Unit Price')  }}</th>
                <th width="22%">{{ __('Quantity')  }}</th>
                <th width="10%">{{ __('Total Price')  }}</th>
                <th width="10%"></th>
              </tr>
            </thead>
          </table>
          <form id="itemAdded">
            <input type="hidden" id="order_id" name="order_id" value="">
            <input type="hidden" name="indivisual_discount_price" id="indivisual-discount-price">
            <div class="scrollable">
              <table class="table">
                <tbody>
                  <tr class="please_add"><th>{{ __('Please add an item') }}</th></tr>
                </tbody>
              </table>
            </div>
          </form>
        </div>
        <table class="table no-border pos-total-table">
          <tbody id="order-section">
            <tr>
              <td colspan="3" class="text-right">{{ __('Sub Total') }}</td>
              <td class="text-right"><span id="subTotal">0</span></td>
            </tr>
            <tr>
              <td colspan="3" class="text-right">{{ __('Tax')  }}</td>
              <td class="text-right"><span id="item_tax">0</span></td>
            </tr>
            <tr>
              <td colspan="3" class="text-right">{{ __('Shipping')  }}</td>
              <td class="text-right"><input type="text" onfocus="this.select();" id='shipping_cost' class="positive-float-number" value="0"></td>
            </tr>
            <tr>
              <td colspan="3" class="text-right">{{ __('Discount on Cart')  }}</td>
              <td class="text-right">
                <input type="hidden" name="net_discount" id="net_discount" value="0">
                <input type="hidden" name="net_discount_type" id="net_discount_type" value="$">
                <span></span><span id="discount">0</span>
              </td>
            </tr>
            <tr class="text-white theme-bg-r">
              <td>{{ __('Products count')  }} ( <span class="item_count">0</span> )</td>
              <td colspan="2" class="text-right">{{ __('Net Payable') }}</td>
              <td class="text-right"><span id="net_payable">0</span></td>
              <td class="text-right d-none"><span id="net_payable_no_currency"></span></td>
            </tr>
          </tbody>
        </table>
        <div class="col-sm-12">
          <div class="btn-group mb-2" role="group" aria-label="Basic example">
            <button type="button" onclick="location.reload();" class="btn btn-outline-secondary"><i class="feather icon-refresh-cw"></i> {{ __('Cancel')  }}</button>
            <button type="button" class="btn btn-outline-secondary" data-remote="{{ url('pos/settings') }}" data-label="pos-settings" data-toggle="modal" data-target="#holdModal"><i class="feather icon-settings"></i> {{ __('Settings')  }}</button>
            <button type="button" class="btn btn-outline-secondary" id="holdBtn" data-remote="{{ url('pos/order-onhold') }}" data-label="order-onhold" data-toggle="modal" data-target="#holdModal" disabled=""><i class="fas fa-hand-paper"></i> {{ __('Hold')  }}</button>
            <button type="button" class="btn btn-outline-secondary" id="discountBtn" data-remote="{{ url('pos/add-discount') }}" data-label="add-discount" data-toggle="modal" data-target="#holdModal" disabled=""><i class="fas fa-gift"></i> {{ __('Discount')  }}</button>
            <button type="button" class="btn btn-outline-secondary" id="paymentBtn" data-remote="{{ url('pos/order-payment') }}" data-label="order-payment" data-toggle="modal" data-target="#holdModal" disabled=""><i class="fas fa-money-bill-alt"></i> {{ __('Pay')  }}</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-body p-0">
        <div class="col-sm-12">
          <div class="input-group mb-3 serchItem">
              <div class="input-group-prepend">
                <button class="btn btn-outline-secondary custom-btn-small" type="button"><i class="feather icon-search"></i></button>
                <button class="btn btn-outline-secondary custom-btn-small" type="button"><i class="fas fa-barcode"></i></button>
              </div>
              <input type="text" class="form-control" id="search" placeholder="{{ __('Barcode, SKU, Product Name...') }}">
          </div>
        </div>
        <div class="col-sm-12">
          <div class="slider">
            <div class="slides">
              <div id="scroll-start" class="scroll">
                <span><</span>
              </div>
              <li class="btn btn-outline-secondary custom-btn-small" id="refresh-btn"><i class="feather icon-refresh-cw"></i>{{ __('Refresh')  }}</li>
              @foreach($categories as $counter => $category)
                <li class="btn btn-outline-secondary custom-btn-small item_category" id="{{ $category->id }}" style="{{ $counter == 0 ? 'margin-left: 96px' : '' }}">{{ $category->name }}</li>
              @endforeach
              <div id="scroll-end" class="scroll">
                <span>></span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-12 m-t-10">
          <div class="item_section">
            <div id="empty_items_list" class="no-display">
              <div id="empty-content">
                <img src="{{ asset('public/dist/img/pos-cart.png') }}">
                <div class="text-center"><h3> {{ __("No products available.") }} </h3></div>
              </div>
            </div>
            <div id="items_list">
              @foreach($items as $key => $item)
              <div class="single_item">
                @if($item->is_stock_managed == 1 && $item->stockMoves->sum('quantity')  <= 0)
                  <span class="feather icon-alert-octagon stock-out text-danger p-1"></span>
                @endif
                @if(isset($item->image->file_name) && !empty($item->image->file_name) && file_exists(public_path('uploads/items/' . $item->image->file_name)))
                  <img src="{{ url('public/uploads/items/' . $item->image->file_name) }}" alt="">
                @else
                <img src="{{ url('public/dist/img/default-image.png') }}"/>
                @endif
                <div class="item_info" data-id="{{ $item->id }}" data-tax="{{ isset($item->taxType->tax_rate) ? formatCurrencyAmount($item->taxType->tax_rate) : formatCurrencyAmount(0)}}" data-tax-type="{{ isset($item->taxType->id) ? $item->taxType->id : '' }}" data-stock-management="{{$item->is_stock_managed}}" data-stock="{{$item->stockMoves->sum('quantity')}}">
                  <p class="item_name" data-desc="{{ $item->description }}">{{ $item->name }}</p>
                  <p class="item_price">{{ isset($item->salePrices->price) ? formatCurrencyAmount($item->salePrices->price) : formatCurrencyAmount(0) }}</p>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

{{-- Add Item Modal --}}

<div class="modal fade add-item">
  <div class="modal-dialog" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <h5 class="modal-title">{{__('Add Custom Item') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body" id="add-item-body">
        <div class="modal-body-content">
          <div class="form-group row">
            <label for="item-name" class="col-sm-3 col-form-label">{{__('Item Name') }}</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="item-name" name="item_name" placeholder="{{__('Item Name') }}">
            </div>
          </div>
          <div class="form-group row">
            <label for="item-desc" class="col-sm-3 col-form-label">{{__('Description') }}</label>
            <div class="col-sm-9">
              <textarea class="form-control" id="item-desc" name="item_description"></textarea>
            </div>
          </div>
          <div class="form-group row">
            <label for="unit-price" class="col-sm-3 col-form-label">{{__('Unit Price') }}</label>
            <div class="col-sm-9">
              <input type="text" class="form-control positive-float-number" id="unit-price" name="unit_price" placeholder="{{__('Unit Price') }}" min="0">
            </div>
          </div>
          <div class="form-group row">
            <label for="quantity" class="col-sm-3 col-form-label">{{__('Quantity') }}</label>
            <div class="col-sm-9">
              <input type="text" class="form-control positive-float-number" id="quantity-price" name="quantity" placeholder="{{__('Quantity') }}" min="0">
            </div>
          </div>
          <div class="form-group row">
            <label for="item-discount" class="col-sm-3 col-form-label">{{__('Discount') }}</label>
            <div class="col-sm-4">
              <input type="text" class="form-control positive-float-number" id="add-item-discount" name="add_item_discount">
            </div>
            <div class="col-sm-5 px-0">
              <select name="add_discount_type" id="add-discount-type" class="form-control">
                <option value="%">%</option>
                <option value="$">Flat</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="item-tax" class="col-sm-3 col-form-label">{{__('Tax') }}</label>
            <div class="col-sm-9">
              <select name="individual_tax" id="add_individual_tax" class="inputTax control bootstrap-select selectpicker" multiple>
                @foreach($taxTypeList as $tax)
                  <option title="{{formatCurrencyAmount($tax->tax_rate)}}%" value="{{$tax->id}}" taxrate="{{formatCurrencyAmount($tax->tax_rate)}}">{{$tax->name}} ({{formatCurrencyAmount($tax->tax_rate)}})</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn custom-btn-small btn-danger" data-dismiss="modal">{{ __('Close') }}</button>
        <button type="button" id="custom-product-add" class="custom-btn-small btn btn-primary">{{  __('Add Product To Cart')  }}</button>
        <span class="ajax-loading"></span>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editItemModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editItemModalLabel">{{ __('Edit item information') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body" id="editItemModalBody">
        <div class="form-group row">
          <label for="edit-item-name" class="col-sm-2 col-form-label">{{__('Name') }}</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="edit-item-name" name="edit_item_name">
          </div>
        </div>
        <div class="form-group row">
          <label for="edit-item-desc" class="col-sm-2 col-form-label">{{__('Description') }}</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="edit-item-desc" name="edit_item_description"></textarea>
          </div>
        </div>
        <div class="form-group row">
          <label for="edit-item-discount" class="col-sm-2 col-form-label">{{__('Discount') }}</label>
          <div class="col-sm-5">
            <input type="text" class="form-control positive-float-number" id="edit-item-discount" name="edit_item_discount[]">
          </div>
          <div class="col-sm-5 px-0">
            <select name="edit_discount_type[]" id="edit-discount-type" class="form-control">
              <option value="$">{{ __('Flat') }}</option>
              <option value="%">%</option>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="edit-item-tax" class="col-sm-2 col-form-label">{{__('Tax') }}</label>
          <div class="col-sm-10">
            <select name="individual_tax" id="individual_tax" class="inputTax control bootstrap-select selectpicker" multiple>
              @foreach($taxTypeList as $tax)
                <option title="{{formatCurrencyAmount($tax->tax_rate)}}%" value="{{$tax->id}}" taxrate="{{formatCurrencyAmount($tax->tax_rate)}}">{{$tax->name}} ({{formatCurrencyAmount($tax->tax_rate)}})</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger custom-btn-small" data-dismiss="modal">{{ __('Close') }}</button>
        <button type="button" id="editItemModalSubmitBtn" class="btn btn-primary custom-btn-small">{{ __('Submit') }}</button>
        <span class="ajax-loading"></span>
      </div>
    </div>
  </div>
</div>

{{-- HoldPayModal --}}

<div class="modal fade" id="holdPayModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <h5 class="modal-title" id="holdPayModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body mt-3">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger custom-btn-small" data-dismiss="modal">{{ __('Close') }}</button>
        <button type="button" id="holdPayModalSubmitBtn" class="btn btn-primary custom-btn-small order_payment">{{ __('Pay') }}</button>
        <span class="ajax-loading"></span>
      </div>
    </div>
  </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="holdModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <h5 class="modal-title" id="holdModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body mt-3" id="holdModal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger custom-btn-small" data-dismiss="modal">{{ __('Close') }}</button>
        <button type="button" id="holdModalResetBtn" class="btn btn-warning custom-btn-small display_none">{{ __('Reset') }}</button>
        <button type="button" id="holdModalSubmitBtn" class="btn btn-primary custom-btn-small">{{ __('Submit') }}</button>
        <span class="ajax-loading"></span>
      </div>
    </div>
  </div>
</div>
{{-- Modal end --}}

@endsection
@section('js')

<!-- Select2 -->
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('public/dist/plugins/jQueryUI/jquery-ui.min.js') }}"></script>
<script src="{{asset('public/dist/plugins/bootstrap-select/dist/js/bootstrap-select.min.js')}}"></script>
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script>
  'use strict';
  var invoice_type     = 'quantity';
  var defaultCurrencySymbol = '{!! $default_currency->symbol !!}';
  var dflt_currency_id = '{!! $default_currency->id !!}';
  var decimal_digits = "{{ $decimal_digits }}";
  var thousand_separator = "{{ $thousand_separator }}";
  var symbol_position = "{!! $symbol_position !!}";
</script>
<script src="{{ asset('public/dist/js/custom/pos-script.min.js') }}"></script>
@endsection
