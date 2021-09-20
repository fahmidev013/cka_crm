@extends('layouts.list-layout')

@section('list-title')
  <h5><a href="{{ url('report/sales-report') }}"> {{ __('Sales report') }} </a></h5>
@endsection

@section('list-form-content')
  <form class="form-horizontal" action="{{ url('report/sales-report') }}" method="GET" id='salesReport'>
    <input class="form-control" id="startfrom" type="hidden" name="from" value="<?= isset($from) ? $from : '' ?>">
    <input class="form-control" id="endto" type="hidden" name="to" value="<?= isset($to) ? $to : '' ?>">
    <div class="col-md-12 col-sm-12 col-xs-12 px-4">
      <div class="row mt-3">
        <div class="col-md-2 col-sm-12 col-xs-12 mb-2 searchTypeDiv">
          <select class="form-control select2" name="searchType" id="searchType">
            <option value="daily" <?= ($searchType=='daily') ? 'selected' : ''?>>{{ __('Daily') }}</option>
            <option value="monthly" <?= ($searchType=='monthly') ? 'selected' : ''?>>{{ __('Monthly') }}</option>
            <option value="yearly" <?= ($searchType=='yearly') ? 'selected' : ''?>>{{ __('Yearly') }}</option>
            <option value="custom" <?= ($searchType=='custom') ? 'selected' : ''?>>{{ __('Custom') }}</option>
          </select>
        </div>
        <div class="col-md-2 col-sm-12 col-xs-12 mb-2 yearField display_none">
          <select class="form-control select2 w-100" name="year" id="year">
            <option value="all" <?= ($year=='all') ? 'selected' : ''?>>{{ __('All Year') }}</option>
            @if(!empty($yearList))
              @foreach($yearList as $res)
                <option value="{{$res->year}}" <?= ($res->year == $year) ? 'selected' : '' ?>>{{ $res->year }}</option>
              @endforeach
            @endif
          </select>
        </div>

        <?php
          $monthList = getMonthList();
        ?>
        <div class="col-md-2 col-sm-12 col-xs-12 mb-2 monthField display_none">
          <select class="form-control select2 w-100" name="month" id="month">
            <option value="all" <?= ($month == 'all') ? 'selected' : '' ?>>{{ __('All Month') }}</option>
            @foreach($monthList as $key=>$val)
            <option value="{{$key}}" <?= ($month == $key) ? 'selected' : '' ?>>{{ $val }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3 col-sm-12 col-xs-12 mb-2 dateField display_none">
          <div class="input-group">           
            <button type="button" class="form-control" id="daterange-btn">
              <span class="float-left">
                <i class="fa fa-calendar"></i> {{ __('Pick a date range') }}
              </span>
              <i class="fa fa-caret-down float-right pt-1"></i>
            </button> 
          </div>
        </div>
        <div class="col-md-3 col-sm-12 col-xs-12 mb-2">
          <select class="form-control select2" name="location" id="location">
            <option value="all">{{ __('All Location') }}</option>
            @if(!empty($locationList))
              @foreach($locationList as $key => $value)
                <option value="{{$value->id}}" <?= ($value->id == $location) ? 'selected' : ''?>>{{$value->name}}</option>
              @endforeach
            @endif
          </select>
        </div>
        <div class="col-md-3 col-sm-12 col-xs-12 mb-2">
          <select class="form-control select2" name="currency" id="currency">
            @if(!empty($currencyList))
              @foreach($currencyList as $curr)
                <option value="{{$curr->id}}" <?= ($curr->id == $currency) ? 'selected' : ''?>>{{$curr->name}}</option>
              @endforeach
            @endif
          </select>
        </div>
        <div class="col-md-3 col-sm-12 col-xs-12 mb-2">
          <select class="form-control select2" name="customer" id="customer">
            <option value=''>{{ __('Please select customer') }}</option>
            @if(!empty($customerList))
              @foreach($customerList as $data)
                <option value="{{$data->id}}" <?= ($data->id == $customer) ? 'selected' : ''?>>{{$data->name}}</option>
              @endforeach
            @endif
          </select>
        </div>
        <div class="col-md-1 col-sm-12 col-xs-12 p-md-0">
          <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small push-sm-right mt-0 mr-0">{{ __('Go') }}</button>
        </div>
    </div>
  </form>
@endsection

@section('special-note')
<div class="shadow-sm mb-3 rounded alert pl-1" id="on_sale_msg"><span class="badge badge-danger mx-1">{{ __('Note')  }} </span>{{__('Sales values has calculated including all charges (eg.: Same as invoice calculation).') }}</div>
@endsection

@section('barChart-content')
  <div class="col-sm-12 col-md-12 col-xl-12 p-0" id="ChartJsExpense">
    <div class="card">
      <div class="card-header">
        <h6>{{ __('Overview')  }}</h6>
      </div>
      <div class="card-block">
        <canvas id="chart-line-1" class="chartJsLineGraph w-100 h-300"></canvas>
      </div>
    </div>
  </div>
  <div class="col-sm-12 col-md-12 col-xl-12 p-0">
    <div class="card m-t-15">
      <div class="card-body row m-0">
        <div class="col-md-3 col-xs-6">
            <h5 class="bold amount-font">{{formatCurrencyAmount($totalQuantity)}}</h5>
            <span class="text-info">{{ __('Total Invoiced Quantity')  }}</span>
        </div>
        <div class="col-md-3 col-xs-6">
            <h5 class="bold amount-font">
             {{ formatCurrencyAmount($totalPurchase, $currency_sign)}} 
             </h5>
            <span class="text-info">{{ __('Cost Value of Invoices')  }}</span>
        </div>
        <div class="col-md-3 col-xs-6">
            <h5 class="bold amount-font">
              {{ formatCurrencyAmount($totalActualSale, $currency_sign)}} 
            </h5>
            <span class="text-info">{{ __('Retail Value of Invoices')  }} </span>
        </div>
        <div class="col-md-3 col-xs-6">
            <h5 class="bold amount-font">
              {{ formatCurrencyAmount($totalProfitAmount, $currency_sign)}}
            </h5>
            <span class="text-info">{{ __('Profit Value of Invoices')  }}</span>
        </div>
        <input type="hidden" id="sign" value="{{ $currency_sign }}">          
      </div>
    </div>
  </div>
@endsection

@section('list-js')
<!-- chartjs js -->
<script src="{{ asset('public/datta-able/plugins/chart-chartjs/js/Chart.min.js') }}"></script>
<script type="text/javascript">
  'use strict';
  var date = '{!! $date !!}';
  var totalActualSale = {!! $totalActualSale !!};
  var sale = '{!! $sale !!}';
  var totalPurchase = {!! $totalPurchase !!};
  var purchase = '{!! $purchase !!}';
  var totalProfitAmount = {!! $totalProfitAmount !!};
  var profit = '{!! $profit !!}';
  var totalSaleTax = {!! $totalSaleTax !!};
  var tax = '{!! $tax !!}';
</script>
<script src="{{ asset('public/dist/js/custom/report.min.js') }}"></script>
@endsection