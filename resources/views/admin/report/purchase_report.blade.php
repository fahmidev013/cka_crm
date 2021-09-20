@extends('layouts.list-layout')

@section('list-title')
  <h5><a href="{{ url('report/purchase-report') }}">{{ __('Purchase Reports')  }} </a></h5>
@endsection

@section('list-form-content')
  <form class="form-horizontal" action="{{ url('report/purchase-report') }}" method="GET" id='orderListFilter'>
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
                <option value="{{$res->year}}" <?= ($res->year == $year) ? 'selected' : '' ?>>{{$res->year}}</option>
              @endforeach
            @endif
          </select>
        </div>

        <?php
          $monthList = array(
            '01'=>'January',
            '02'=>'February',
            '03'=>'March',
            '04'=>'April',
            '05'=>'May',
            '06'=>'June',
            '07'=>'July',
            '08'=>'August',
            '09'=>'September',
            '10'=>'October',
            '11'=>'November',
            '12'=>'December'
          );
        ?>
        <div class="col-md-2 col-sm-12 col-xs-12 mb-2 monthField display_none">
          <select class="form-control select2 w-100" name="month" id="month">
            <option value="all" <?= ($month == 'all') ? 'selected' : '' ?>>{{ __('All Month') }}</option>
            @foreach($monthList as $key=>$val)
              <option value="{{$key}}" <?= ($month == $key) ? 'selected' : '' ?>>{{$val}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-4 col-sm-12 col-xs-12 mb-2 dateField display_none">
          <div class="input-group">           
            <button type="button" class="form-control" id="daterange-btn">
              <span class="float-left">
                <i class="fa fa-calendar"></i> {{ __('Pick a date range')}}
              </span>
              <i class="fa fa-caret-down float-right pt-1"></i>
            </button> 
          </div>
        </div>
        <div class="col-md-2 col-sm-12 col-xs-12 mb-2">
          <select class="form-control select2" name="location" id="location">
            <option value="all">{{ __('All Location') }}</option>
            @if(!empty($locationList))
              @foreach($locationList as $locationItem)
                <option value="{{$locationItem->id}}" <?= ($locationItem->id == $location) ? 'selected' : ''?>>{{$locationItem->name}}</option>
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
      </div>
      <div class="row">
        <div class="col-md-4 col-sm-12 col-xs-12 mb-2">
          <select class="form-control select2" name="supplier" id="supplier">
            <option value="all">{{ __('All Supplier') }}</option>
            @if(!empty($supplierList))
              @foreach($supplierList as $supplierItem)
                <option value="{{$supplierItem->id}}" <?= ($supplierItem->id == $supplier) ? 'selected' : ''?>>{{$supplierItem->name}}</option>
              @endforeach
            @endif
          </select>
        </div>
        <div class="col-md-4 col-sm-12 col-xs-12 mb-2">
          <select class="form-control select2" name="product" id="product">
            <option value="all">{{ __('All Product') }}</option>
            @if(!empty($itemList))
              @foreach($itemList as $productItem)
                <option value="{{$productItem->id}}" <?= ($productItem->id == $item) ? 'selected' : ''?>>{{$productItem->name}}</option>
              @endforeach
            @endif
          </select>
        </div>
        
        <div class="col-md-1 col-sm-12 col-xs-12 p-md-0">
          <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small push-sm-right mt-0 mr-0">{{ __('Go') }}</button>
        </div>
      </div>
    </div>
  </form>
@endsection

@section('barChart-content')
  <div class="col-sm-12 col-md-12 col-xl-12 barChartDiv px-0" id="ChartJsExpense">
    <div class="card">
      <div class="card-block col-sm-9 col-md-9 col-xl-9">
        <canvas class="chartJsBar" id="chartJsBar"></canvas>
      </div>
    </div>
  </div>
@endsection

@section('list-js')
<!-- chartjs js -->
<script src="{{ asset('public/datta-able/plugins/chart-chartjs/js/Chart-2019.min.js') }}"></script>

<script type="text/javascript">
  'use strict';
  var dates = new Array();
  var costs = new Array();
  <?php  
    foreach ($graphData as $key => $value) {
  ?>
    dates.push('<?php echo sanitize_output($value['date']); ?>');
    costs.push('<?php echo sanitize_output($value['cost']); ?>');
  <?php } ?>
</script> 
<script src="{{ asset('public/dist/js/custom/report.min.js') }}"></script>
@endsection