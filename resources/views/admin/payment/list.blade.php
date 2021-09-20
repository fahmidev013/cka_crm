@extends('layouts.list-layout')
@section('list-title')
  <h5>{{ __('Payments') }}</h5>
@endsection

@section('list-form-content')
<div id="sales-payment-list-container">
  <form class="form-horizontal" action="{{ url('payment/list') }}" method="GET" id='orderListFilter'>
    <input class="form-control" id="startfrom" type="hidden" name="from" value="<?= isset($from) ? $from : ''?>">
    <input class="form-control" id="endto" type="hidden" name="to" value="<?= isset($to) ? $to : ''?>">
    <div class="col-md-12 col-sm-12 col-xs-12 pl-4">
      <div class="row mt-3">
        <div class="col-xl-4 col-md-5 col-sm-12 col-xs-12 minimize-width-xl-4 mb-2">
          <div class="input-group">
            <button type="button" class="form-control" id="daterange-btn">
              <span class="float-left">
                <i class="fa fa-calendar"></i>
                {{ __('Pick a date range')  }}
              </span>
              <i class="fa fa-caret-down float-right pt-1"></i>
            </button>
          </div>
        </div>
        <div class="col-xl-2 col-md-2 col-sm-12 col-xs-12 minimize-width-xl-2 mb-2">
          <div class="input-group">
            <select class="form-control select2" name="customer" id="customer">
              <option value="">{{ __('All customers') }}</option>
              @if(!empty($customerList))
                @foreach($customerList as $customerItem)
                  <option value="{{ $customerItem->id }}" <?= ($customerItem->id == $customer) ? 'selected' : ''?>>{{ $customerItem->name }}</option>
                @endforeach
              @endif
            </select>
          </div>
        </div>
        <div class="col-xl-2 col-md-2 col-sm-12 col-xs-12 minimize-width-xl-2 mb-2">
          <div class="input-group">
            <select class="form-control select2" name="method" id="method">
              <option value="">{{ __('All methods') }}</option>
              @if(!empty($methodList))
                @foreach($methodList as $methodItem)
                  <option value="{{ $methodItem->id }}" <?= ($methodItem->id == $method) ? 'selected' : ''?>>{{ $methodItem->name }}</option>
                @endforeach
              @endif
            </select>
          </div>
        </div>
        <div class="col-xl-2 col-md-2 col-sm-12 col-xs-12 minimize-width-xl-2 mb-2">
          <select class="form-control select2" name="currency" id="currency">
            <option value="">{{ __('All currencies') }}</option>
            @if(!empty($currencyList))
              @foreach($currencyList as $currencyData)
                <option value="{{ $currencyData->id }}" <?= ($currencyData->id == $currency) ? 'selected' : ''?>>{{ $currencyData->name }}</option>
              @endforeach
            @endif
          </select>
        </div>
        <div class="col-xl-2 col-md-2 col-sm-12 col-xs-12 minimize-width-xl-2 mb-2">
          <select class="form-control select2" name="status" id="status">
            <option value="">{{ __('All status') }}</option>
            <option value="Approved" {{ $status == 'Approved' ? 'selected':'' }}>{{ __('Approved') }}</option>
            <option value="Pending" {{ $status == 'Pending' ? 'selected':'' }}>{{ __('Pending') }}</option>
            <option value="Declined" {{ $status == 'Declined ' ? 'selected':'' }}>{{ __('Declined') }} </option>
          </select>
        </div>
        <div class="col-md-1 col-sm-12 col-xs-12 p-md-0">
          <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small push-sm-left mt-0 mr-0">{{ __('Go') }}</button>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

@section('list-js')
<script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/sales.min.js') }}"></script>
@endsection