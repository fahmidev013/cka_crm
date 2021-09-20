@extends('layouts.customer-list-layout')

@section('list-title')
  <h5>{{ __('Payments')  }}</h5>
@endsection

@section('list-form-content')
<div id="cus-panel-payment-container">
  <form class="form-horizontal" action="{{ url('customer-panel/payment') }}" method="GET" id='paymentListFilter'>
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
        <div class="col-xl-2 col-lg-3 col-md-3 mb-2">
          <div class="form-group mb-0">
            <div class="input-group">
              <select class="form-control select2" name="method" id="method">
                <option value="">{{ __('All methods') }}</option>
                @if(!empty($paymentMethod))
                  @foreach($paymentMethod as $key => $value)
                    <option value="{{ $key }}" {{ ($key == $method) ? 'selected' : ''}}>{{ $value }}</option>
                  @endforeach
                @endif
              </select>
            </div>
          </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-3 mb-2">
          <select class="form-control select2" name="status" id="status">
            <option value="">{{ __('All status') }}</option>
            <option value="Approved" {{ $status == 'Approved' ? 'selected':'' }}>{{ __('Approved') }}</option>
            <option value="Pending" {{ $status == 'Pending' ? 'selected':'' }}>{{ __('Pending') }}</option>
            <option value="Declined " {{ $status == 'Declined ' ? 'selected':'' }}>{{ __('Declined') }} </option>
          </select>
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
</script>
<script src="{{ asset('public/dist/js/custom/customerpanel.min.js') }}"></script>
@endsection
