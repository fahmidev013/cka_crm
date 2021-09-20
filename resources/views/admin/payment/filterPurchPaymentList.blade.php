@extends('layouts.list-layout')

@section('list-title')
  <h5>{{ __('Payments') }}</h5>
@endsection

@section('list-form-content')
<div id="purchase-payment-list-container">
    <!-- Main content -->
  <form class="form-horizontal" action="{{ url('purchase_payment/list') }}" method="GET" id='orderListFilter'>
    <input class="form-control" id="startfrom" type="hidden" name="from" value="<?= isset($from) ? $from : '' ?>">
    <input class="form-control" id="endto" type="hidden" name="to" value="<?= isset($to) ? $to : '' ?>">

    <div class="col-md-12 col-sm-12 col-xs-12 pl-4">
      <div class="row mt-3">
        <div class="col-xl-4 col-md-5 col-sm-12 col-xs-12 mb-2">
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
        <div class="col-xl-3 col-md-2 col-sm-12 col-xs-12 mb-2">
          <div class="input-group">
            <select class="form-control select2" name="supplier" id="supplier">
              <option value="">{{ __('All suppliers') }}</option>
              @if(!empty($supplierList))
                @foreach($supplierList as $supplierData)
                  <option value="{{$supplierData->id}}" <?= ($supplierData->id == $supplier) ? 'selected' : ''?>>{{$supplierData->name}}</option>
                @endforeach
              @endif
            </select>
          </div>
        </div>
        <div class="col-xl-2 col-md-2 col-sm-12 col-xs-12 mb-2">
          <div class="input-group">
            <select class="form-control select2" name="method" id="method">
              <option value="">{{ __('All methods') }}</option>
              @if(!empty($methodList))
                @foreach($methodList as $methodItem)
                  <option value="{{$methodItem->id}}" <?= ($methodItem->id == $method) ? 'selected' : ''?>>{{$methodItem->name}}</option>
                @endforeach
              @endif
            </select>
          </div>
        </div>
        <div class="col-xl-2 col-md-2 col-sm-12 col-xs-12 mb-2">
          <select class="form-control select2" name="currency" id="currency">
            <option value="">{{ __('All currencies') }}</option>
            @if(!empty($currencyList))
              @foreach($currencyList as $currencyData)
                <option value="{{$currencyData->id}}" <?= ($currencyData->id == $currency) ? 'selected' : ''?>>{{$currencyData->name}}</option>
              @endforeach
            @endif
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
<script src="{{ asset('public/dist/js/custom/purchases.min.js') }}"></script>
@endsection