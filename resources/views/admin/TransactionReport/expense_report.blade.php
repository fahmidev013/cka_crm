@extends('layouts.app')
@section('css')
{{-- select2 css --}}
{{-- Datatable --}}
<link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection
@section('content')
  @php
    $mnths=[];
    foreach ($months as $key => $value) {
    $value = sanitize_output($value);
    array_push($mnths, $value);
    }
    $mnths = implode(",",$mnths);
  @endphp
<div class="col-sm-12" id="expense-report-container">
  <div class="card">
    <div class="card-header">
      <h5>{{ __('Expense') }}</h5>
    </div>
    @php
    $purchaseTotal = !empty($purchaseList) ? array_sum($purchaseList) : 0;
    $generalTotal = 0;
    $symbol = '';
    @endphp
    <form class="form-horizontal" action="{{ url('transaction/expense-report') }}" method="GET" accept-charset="UTF-8">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 m-l-10">
        <div class="row mt-3">
          <div class="col-md-12 col-xl-3 col-lg-3 col-sm-12 col-xs-12 mb-2">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
              </div>
              <input type="text" id="startfrom" class="form-control date-pick" name="from">
            </div>
          </div>
          <div class="col-md-12 col-xl-3 col-lg-3 col-sm-12 col-xs-12 mb-2">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
              </div>
              <input type="text" id="endto" class="form-control date-pick" name="to">
            </div>
          </div>
          <div class="col-md-12 col-xl-3 col-lg-3 col-sm-12 col-xs-12 mb-2">
            <select class="form-control select2" name="currency" id="currency">
              @if(!empty($currencyList))
              @foreach($currencyList as $currencyData)
              <option value="{{$currencyData->id}}" <?= ($currencyData->id == $currency) ? 'selected' : '' ?>>{{$currencyData->name}}</option>
              @endforeach
              @endif
            </select>
          </div>
          <div class="col-md-1 col-xl-1 col-lg-1 col-sm-12 col-xs-12 pr-md-1">
            <button type="submit" name="btn" title="{{ __('Click to filter') }}" class="btn btn-primary custom-btn-small mt-0 mr-0">{{ __('Go') }}</button>
          </div>
        </div>
      </div>
    </form>
    @php
    $fromMonth = '';
    $fromYear = '';
    $toMonth = '';
    $toYear = '';
    $fromData = [];
    $from = isset($from) ? $from : '';
    $to = isset($to) ? $to : '';
    if (!empty($from)) {
    $fromMonth = getMonthNumber(date("M", strtotime($from)));
    $fromYear = date("Y", strtotime($from));
    }
    if (!empty($to)) {
    $toMonth = getMonthNumber(date("M", strtotime($to)));
    $toYear = date("Y", strtotime($to));
    }
    @endphp
    <div class="card-block">
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center"><a href="#purchase-expenses-container">{{ __('Purchase') }}</a></th>
              <th class="text-center"><a href="#general-expenses-container">{{ __('General') }}</a></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <table class="table table-bordered">
                  <tr>
                    <td class="text-center">{{ __('Total Purchases') }}</td>
                    <td class="text-center">{{ $purchaseTotal }}</td>
                  </tr>
                </table>
              </td>
              <td>
                <table class="table table-bordered table-dotted">
                  @foreach ($categoryTotals as $key => $value)
                  <tr class="table-dotted">
                    <td class="text-center table-dotted">{{ $key }}</td>
                    <td class="text-center table-dotted">{{ formatCurrencyAmount($value) }}</td>
                  </tr>
                  <?php $generalTotal += $value ?>
                  @endforeach
                  <tr class="table-dotted">
                    <td class="text-right table-dotted"><strong>{{ __('Total General') }}</strong></td>
                    <td class="text-center table-dotted"><strong>{{ $generalTotal }}</strong></td>
                  </tr>
                </table>
              </td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <th class="text-right borderRightNone">{{ __('Total') }}</th>
              <th class="text-left borderLeftNone">{{ $purchaseTotal + $generalTotal }}</th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
  <div class="card" id="general-expenses-container">
    <div class="card-header">
      <h5><a href="{{ url('transaction/expense-report') }}"> {{ __('General Expenses') }} </a></h5>
    </div>
    <div class="card-body p-0">
      <div class="card-block">
        <div class="table-responsive">
          <table id="expenseList" class="table table-bordered table-hover table-striped dt-responsive">
            <thead>
              <tr>
                <th class="text-center">{{ __('Category') }}</th>
                <?php
                $totals  = [];
                foreach ($months as $month) :
                  $totals[$month] = 0;
                  ?>
                  <th class="text-center">{{ $month }}</th>
                <?php endforeach; ?>
              </tr>
            </thead>
            <tbody>
              @foreach($expenseList as $category => $data)
              <tr>
                <td class="text-center">{{ $category }}</td>
                <?php foreach ($months as $month) :
                  $totals[$month] += $data[$month];
                  ?>
                  <th class="text-center">{{ formatCurrencyAmount($data[$month]) }}</th>
                <?php endforeach; ?>
              </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th class="text-right">{{ __('Sub Total') }}</th>
                <?php foreach ($totals as $total) : ?>
                  <th class="text-center">{{ formatCurrencyAmount($total) }}</th>
                <?php endforeach; ?>
              </tr>
            </tfoot>
          </table>
        </div>
        <div class="m-t-30" id="ChartJsExpense">
          <canvas class="ChartJsExpense" id="chartJsLineBasic"></canvas>
        </div>
      </div>

    </div>
  </div>
  <div class="card" id="purchase-expenses-container">
    <div class="card-header">
      <h5>{{ __('Purchase Expenses') }}</h5>
    </div>
    <div class="card-body p-0">
      <div class="card-block">
        <div class="table-responsive">
          <table id="expenseList" class="table table-bordered table-hover table-striped dt-responsive">
            <thead>
              <tr>
                <th class="text-center">{{ __('Date') }}</th>
                <th class="text-center">{{ __('Amount') }}</th>
                <th class="text-center">{{ __('Date') }}</th>
                <th class="text-center">{{ __('Amount') }}</th>
              </tr>
            </thead>
            <?php
            $i = 0;
            $valuePurchase = 0;
            $purchaseTotal = 0;
            ?>
            <tbody>
              @foreach(collect($months)->chunk(2) as $collect)
              <tr>
                <?php foreach ($collect as $month) :
                  $value = $purchaseList[$month];
                  $purchaseTotal += $value;
                  ?>
                  <td class="text-center">{{ $month }}</td>
                  <td class="text-center">{{ formatCurrencyAmount($value) }}</td>
                <?php endforeach ?>
                <?php
                if (count($collect) == 1) {
                  ?>
                  <td class="text-center">-</td>
                  <td class="text-center">-</td>
                <?php } ?>
              </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3" class="text-right"><strong>{{ __('Total') }}</strong></td>
                <td class="text-center"><strong>{{ $purchaseTotal }}</strong></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
{{-- Select2 --}}
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- chartjs js -->
<script src="{{ asset('public/datta-able/plugins/chart-chartjs/js/Chart-2019.min.js') }}"></script>
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>

<script type="text/javascript">
  'use strict';
  var months      = "{{ $mnths }}";
  var expenseData = new Array();
  var startDate = "{!! isset($from) ? $from : 'undefined' !!}";
  var endDate   = "{!! isset($to) ? $to : 'undefined' !!}";
  months          = months.split(',');

  <?php
  $i = 0;
  if (!empty($expenseList)) {
    foreach ($expenseList as $key => $value) {
      $str = [];
      foreach ($months as $month) {
        $str[] = isset($value[$month]) ? $value[$month] : 0;
      }

      $color = $colors[$i++];
      if (empty($color)) {
        $color = '#' . rand(000000, 896543);
      }

      $data = [
        'label' => sanitize_output($key),
        'backgroundColor' => sanitize_output($color),
        'borderColor' => sanitize_output($color),
        'data' => $str,
        'fill' => false
      ];
      ?>
      expenseData.push(<?php echo json_encode($data) ?>);
    <?php } ?>
  <?php } ?>
</script>
<script src="{{ asset('public/dist/js/custom/report.min.js') }}"></script>
@endsection