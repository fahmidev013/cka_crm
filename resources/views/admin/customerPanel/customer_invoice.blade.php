@extends('layouts.customer-list-layout')
@section('list-title')
  <h5>{{ __('My Invoices')  }}</h5>
@endsection

@section('list-form-content')
<div id="cus-panel-invoice-list-container">
  <form class="form-horizontal" action="{{ url('customer-panel/invoice') }}" method="GET" id='orderListFilter'>
    <input type="hidden" name="customer" id="customer" value="{{ $customerData->id }}">
    <input class="form-control" id="startfrom" type="hidden" name="from" value="<?= isset($from) ? $from : '' ?>">
    <input class="form-control" id="endto" type="hidden" name="to" value="<?= isset($to) ? $to : '' ?>">
    <div class="col-md-12 col-sm-12 col-xs-12 m-l-10">
      <div class="row mt-3">
        <div class="col-xl-4 col-lg-4 col-md-5 mb-2">
          <div class="input-group">
            <button type="button" class="form-control" id="daterange-btn">
              <span class="float-left">
                <i class="fa fa-calendar"></i>
                <span>{{ __('Pick a date range')  }}</span>
              </span>
              <i class="fa fa-caret-down float-right pt-1"></i>
            </button>
          </div>
        </div>
        <div class="col-md-3 col-sm-4 col-xs-12 mb-2">
          <select class="form-control select2" name="pay_status_type" id="pay_status_type">
            <option value="">{{ __('All Status') }}</option>
            <option value="paid" {{ $payStatus == 'paid' ? 'selected' : '' }}>{{ __('Paid') }}</option>
            <option value="partial" {{ $payStatus == 'partial' ? 'selected' : '' }}>{{ __('Partially paid') }}</option>
            <option value="unpaid" {{ $payStatus == 'unpaid' ? 'selected' : '' }}>{{ __('Unpaid') }}</option>
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
              <span class="f-16">{{ isset($amount->totalInvoice) ?  formatCurrencyAmount($amount->totalInvoice, $amount->currency->symbol) : 0 }}</span><br>
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
              <span class="f-16">{{ isset($amount->totalPaid) ? formatCurrencyAmount($amount->totalPaid, $amount->currency->symbol) : 0 }}</span><br>
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
              <span class="f-16">{{ formatCurrencyAmount($amount->totalInvoice - $amount->totalPaid, $amount->currency->symbol) }}</span><br>
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
                <span class="f-16">{{ isset($overDue) && !empty($overDue) ? formatCurrencyAmount($overDue['totalAmount'] - $overDue['totalPaid'], $overDue->currency->symbol)  : formatCurrencyAmount(0) }}</span><br>
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
<script src="{{ asset('public/dist/js/custom/customerpanel.min.js') }}"></script>
@endsection
