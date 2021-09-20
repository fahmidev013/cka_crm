@extends('layouts.list-layout')

@section('list-title')
  <h5>{{ __('Quotations') }}</h5>
@endsection

@section('list-add-button')
  @if(Helpers::has_permission(Auth::user()->id, 'add_quotation'))
    <a href="{{ url('order/add') }}" class="btn btn-outline-primary custom-btn-small"><span class="feather icon-plus"> &nbsp;</span>{{ __('New Quotation') }}</a>
  @endif
@endsection

@section('list-form-content')
<div id="sales-quotation-list-container">
  <form class="form-horizontal" action="{{ url('order/list') }}" method="GET" id='orderListFilter'>
    <input class="form-control" id="startfrom" type="hidden" name="from">
    <input class="form-control" id="endto" type="hidden" name="to">
    <div class="col-md-12 col-sm-12 col-xs-12 m-l-10">
      <div class="row mt-3">
        <div class="col-md-4 col-sm-4 col-xs-12 mb-2">
          <div class="input-group">
            <button type="button" class="form-control" id="daterange-btn">
              <span class="float-left">
                <i class="fa fa-calendar"></i>
                <span>{{ __('Pick a date range') }}</span>
              </span>
              <i class="fa fa-caret-down float-right pt-1"></i>
            </button>
          </div>
        </div>

        <div class="col-md-3 col-sm-3 col-xs-11 mb-2">
            <select class="form-control select2" name="customer" id="customer">
              <option value="">{{ __('All customers') }}</option>
              @if(!empty($customerList))
                @foreach($customerList as $customerItem)
                  <option value="{{ $customerItem->id }}" <?= ($customerItem->id == $customer) ? 'selected' : ''?>>{{ $customerItem->name }}</option>
                @endforeach
              @endif
            </select>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-11 mb-2">
          <select class="form-control select2" name="location" id="location">
            <option value="">{{ __('All locations') }}</option>
            @if(!empty($locationList))
            @foreach($locationList as $locationItem)
            <option value="{{ $locationItem->id }}" <?= ($locationItem->id == $location) ? 'selected' : ''?>>{{ $locationItem->name }}</option>
            @endforeach
            @endif
          </select>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-11 mb-2">
          <select class="form-control select2" name="currency" id="currency">
            <option value="">{{ __('All currencies') }}</option>
            @if(!empty($currencyList))
              @foreach($currencyList as $currencyData)
                <option value="{{ $currencyData->id }}" <?= ($currencyData->id == $currency) ? 'selected' : ''?>>{{ $currencyData->name }}</option>
              @endforeach
            @endif
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
<script src="{{ asset('public/dist/js/custom/sales.min.js') }}"></script>
@endsection
