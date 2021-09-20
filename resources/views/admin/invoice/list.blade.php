@extends('layouts.list-layout')
@section('list-title')
  <h5>{{ __('Invoices')  }}</h5>
@endsection

@section('list-add-button')
  @if(Helpers::has_permission(Auth::user()->id, 'add_invoice'))
    <a href="{{ url('invoice/add') }}" class="btn btn-outline-primary custom-btn-small"><span class="feather icon-plus"> &nbsp;</span>{{  __('New Invoice')  }}</a>
  @endif
@endsection

@section('list-form-content')
<div id="sales-invoice-list-container">
  <form class="form-horizontal">
    <input class="form-control" id="startfrom" type="hidden" name="from" value="{{ isset($from) ? $from : '' }}">
    <input class="form-control" id="endto" type="hidden" name="to" value="{{ isset($to) ? $to : '' }}">
    <div class="col-md-12 col-sm-12 col-xs-12 m-l-10">
      <div class="row mt-3">
        <div class="col-md-5 col-sm-4 col-xs-12 mb-2 col-xl-4">
          <div class="input-group">
            <button type="button" class="form-control" id="daterange-btn">
              <span class="float-left">
                <i class="fa fa-calendar"></i>  {{  __('Pick a date range')  }}
              </span>
              <i class="fa fa-caret-down float-right pt-1"></i>
            </button>
          </div>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-11 mb-2">
            <select class="form-control select2" name="customer" id="customer">
              <option value="">{{ __('All customers') }}</option>
              @if(!empty($customerList))
                @foreach($customerList as $customerItem)
                  <option value="{{ $customerItem->id }}" {{ $customerItem->id == $customer ? 'selected' : '' }}>{{ $customerItem->name }}</option>
                @endforeach
              @endif
            </select>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-11 mb-2">
          <select class="form-control select2" name="location" id="location">
            <option value="">{{ __('All locations') }}</option>
            @if(!empty($locationList))
              @foreach($locationList as $locationItem)
                <option value="{{ $locationItem->id }}" {{ $locationItem->id == $location ? 'selected' : '' }}> {{ $locationItem->name }}</option>
              @endforeach
            @endif
          </select>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-11 mb-2">
          <select class="form-control select2" name="currency" id="currency">
            <option value="">{{ __('All currencies') }}</option>
            @if(!empty($currencyList))
              @foreach($currencyList as $currencyData)
                <option value="{{ $currencyData->id }}" {{ $currencyData->id == $currency ? 'selected' : '' }}>{{ $currencyData->name }}</option>
              @endforeach
            @endif
          </select>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-11 mb-2">
          <select class="form-control select2" name="status" id="status">
            <option value="" {{ $status == '' ? 'selected' : '' }}>{{ __('All status') }}</option>
            <option value="paid" {{ $status == 'paid' ? 'selected' : '' }}>{{ __('Paid') }}</option>
            <option value="partial" {{ $status == 'partial' ? 'selected' : '' }}>{{ __('Partially paid') }}</option>
            <option value="unpaid" {{ $status == 'unpaid' ? 'selected' : '' }}>{{ __('Unpaid') }}</option>
          </select>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-11 mb-2">
          <select class="form-control select2" name="transactionType">
            <option value="">{{ __('All types') }}</option>
            <option value="SALESINVOICE" {{ $transactionType == 'SALESINVOICE' ? 'selected' : '' }}> {{ __('Invoice') }} </option>
            <option value="POSINVOICE" {{ $transactionType == 'POSINVOICE' ? 'selected' : '' }}> {{ __('POS') }} </option>
          </select>
        </div>
        <div class="col-md-1 col-sm-1 col-xs-12 p-md-0">
          <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small push-sm-right mt-0 mr-0">{{ __('Go') }}</button>
        </div>
      </div>
    </div>
  </form>
  <div class="row m-2">
    <div class="col-12 col-md-6 col-lg-3 col-xl-3">
      <div class="text-white">
        <div class="p-10 status-border color_0000ff">
          <span class="f-w-700 f-20">{{ __('Total Amount') }}</span><br>
          @forelse($amounts['amounts'] as $amount)
            <span class="f-16">{{ isset($amount->totalInvoice) ?  formatCurrencyAmount($amount->totalInvoice, isset($amount->currency->symbol) ? $amount->currency->symbol : '') : 0 }}</span><br>
          @empty
            <span class="f-16">{{ formatCurrencyAmount(0) }}</span><br>
          @endforelse
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3 col-xl-3">
      <div class="text-white">
        <div class="p-10 status-border color_4CAF50">
          <span class="f-w-700 f-20">{{ __('Total Paid') }}</span><br>
          @forelse($amounts['amounts'] as $amount)
            <span class="f-16">{{ isset($amount->totalPaid) ? formatCurrencyAmount($amount->totalPaid, isset($amount->currency->symbol) ? $amount->currency->symbol : '') : 0 }}</span><br>
          @empty
            <span class="f-16">{{ formatCurrencyAmount(0) }}</span><br>
          @endforelse
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3 col-xl-3">
      <div class="text-white">
        <div class="p-10 status-border color_848484">
          <span class="f-w-700 f-20">{{ __('Total Due') }}</span><br>
          @forelse($amounts['amounts'] as $amount)
            <span class="f-16">{{ formatCurrencyAmount($amount->totalInvoice - $amount->totalPaid, isset($amount->currency->symbol) ? $amount->currency->symbol : '') }}</span><br>
          @empty
            <span class="f-16">{{ formatCurrencyAmount(0) }}</span><br>
          @endforelse
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3 col-xl-3">
      <div class="text-white">
        <div class="p-10 status-border color_ff2d42">
          <span class="f-w-700 f-20">{{ __('Over Due') }}</span><br>
          @if(count($amounts['overDue']) > 0)
            @foreach($amounts['overDue'] as $index => $overDue)
              <span class="f-16">{{ isset($overDue) && !empty($overDue) ? formatCurrencyAmount($overDue['totalAmount'] - $overDue['totalPaid'], isset($overDue->currency->symbol) ? $overDue->currency->symbol : '')  : formatCurrencyAmount(0) }}</span><br>
            @endforeach
            @foreach($allCurrency as $key => $value)
              <span class="f-16">{{ formatCurrencyAmount(0, $value) }}</span><br>
            @endforeach
         @else
            @if (!empty($allCurrency))
              @foreach($allCurrency as $key => $value)
                <span class="f-16">{{ formatCurrencyAmount(0, $value) }}</span><br>
              @endforeach
            @else
              <span class="f-16">{{ formatCurrencyAmount(0) }}</span><br>
            @endif
          @endif

        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('list-js')
<script src="{{ asset('public/dist/js/custom/sales.min.js') }}"></script>
@endsection
