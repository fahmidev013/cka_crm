@extends('layouts.customer-list-layout')
@section('list-title')
  <h5>{{ __('My Quotations')  }}</h5>
@endsection

@section('list-form-content')
<div id="cus-panel-sale-order-container">
  <form class="form-horizontal" action="{{ url('customer-panel/order') }}" method="GET" id='orderListFilter'>
    <input type="hidden" name="customer" id="customer" value="{{ $customerData->id }}">
    <input class="form-control" id="startfrom" type="hidden" name="from" value="<?= isset($from) ? $from : '' ?>">
    <input class="form-control" id="endto" type="hidden" name="to" value="<?= isset($to) ? $to : '' ?>">
    <div class="col-md-12 col-sm-12 col-xs-12 m-l-10">
      <div class="row mt-3">
        <div class="col-md-5 col-xl-4 col-sm-4 col-xs-12 mb-2">
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
        <div class="col-md-1 col-sm-1 col-xs-12 p-md-0">
          <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small push-sm-right mt-0 mr-0">{{ __('Go') }}</button>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

@section('list-js')
<script>
  "use strict";
  var customerID = "{{ $customerData->id }}";
  var customerName = "{{ $customerData->name }}";
</script>
<script src="{{ asset('public/dist/js/custom/customerpanel.min.js') }}"></script>
@endsection
