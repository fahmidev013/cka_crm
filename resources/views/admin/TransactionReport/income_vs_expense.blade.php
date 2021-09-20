@extends('layouts.app')
@section('css')
{{-- select2 css --}}
  {{-- Datatable --}}
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/jQueryUI/jquery-ui.min.css') }}">
@endsection
@section('content')
  <div class="col-sm-12" id="inc-vs-exp-report">
    <div class="card">
      <div class="card-header" id="headerDiv">
        <h5><a href="{{ url('transaction/income-vs-expense') }}">{{ __('Income vs Expense') }}</a></h5>
      </div>
      <form class="form-horizontal" action="{{ url('transaction/income-vs-expense') }}" method="GET" accept-charset="UTF-8">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 m-l-10">
          <div class="row mt-3">
            <div class="col-md-12 col-xl-3 col-lg-3 col-sm-12 col-xs-12 mb-2">
              <div class="input-group">                      
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                </div>
                <input type="text" id="startfrom" class="form-control date-pick" name="from" placeholder="{{ __('From') }}" >
              </div>
            </div>
            <div class="col-md-12 col-xl-3 col-lg-3 col-sm-12 col-xs-12 mb-2">
              <div class="input-group">                      
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                </div>
                <input type="text" id="endto" class="form-control date-pick" name="to" placeholder="{{ __('To') }}" >
              </div>
            </div>
            <div class="col-md-12 col-xl-3 col-lg-3 col-sm-12 col-xs-12 mb-2">
              <select class="form-control select2" name="currency" id="currency">
                @if(!empty($currencyList))
                  @foreach($currencyList as $currencyData)
                    <option value="{{$currencyData->id}}" <?= ($currencyData->id == $currency) ? 'selected' : ''?>>{{$currencyData->name}}</option>
                  @endforeach
                @endif
              </select>
            </div>
            <div class="col-md-1 col-xl-1 col-lg-1 col-sm-12 col-xs-12 pr-md-1">
              <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small mt-0 mr-0">Go</button>
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
            <th class="text-center"><a href="#chartJsLineBasic">{{ __('Category') }}</a></th>
            <th class="text-center"><a href="#chartJsLineBasic">{{ __('Amount') }}</a></th>
            </thead>
            <tbody>
              <tr>
                <tr>
                  <td class="text-center">{{ __('Total Income') }}</td>
                  <td class="text-center">{{ $currencySymbol }}<span id="totalIncome"></span></td>
                </tr>
                <tr>
                  <td class="text-center">{{ __('Total Expense') }}</td>
                  <td class="text-center">{{ $currencySymbol }}<span id="totalExpense"></span></td>
                </tr>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <th class="text-center">{{ __('Total Revenue') }}</th>
                <th class="text-center"><span id="totalRevenue"></span></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header">{{ __('Monthly Income vs Expense') }}</div>
      <div class="card-block">
          <div class="table-responsive">
            <table id="incomeVSexpense" class="table table-bordered table-hover table-striped dt-responsive" width="800%">
              <thead>
                <tr>
                  <th class="text-center">{{ __('Category') }}</th>
                  <?php 
                    $totals  = []; 
                    foreach($months as $month) : 
                      $totals[$month] = 0;
                  ?>
                      <th class="text-center">{{ $month }}</th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              
              <?php
                $totalIncome = 0;
                $totalExpense = 0;
                $totalRevenue = 0; 
              ?>
              <tbody>
                <tr>
                  <td class="text-center">{{ __('Income') }}</td>
                  @foreach($months as $key => $month)
                    <?php  $totalIncome += $incomeArray[$month]; ?>
                    <th class="text-center">{{ formatCurrencyAmount($incomeArray[$month]) }}</th>
                  @endforeach
                  <input type="hidden" id="income" value="{{number_format($totalIncome, 2, '.', ',')}}">
                </tr>
                <tr>
                  <td class="text-center">{{ __('Expense') }}</td>
                  @foreach($months as $key => $month)
                    <?php $totalExpense += $expenseArray[$month]; ?>
                    <th class="text-center">{{ formatCurrencyAmount($expenseArray[$month]) }}</th>
                  @endforeach
                  <input type="hidden" id="expense" value="{{formatCurrencyAmount($totalExpense)}}">
                </tr>
                <tr>
                  <th class="text-center">{{ __('Revenue') }}</th>
                  @foreach($months as $key => $month)
                    <?php $totalRevenue += $revenueArray[$month]; ?>
                    <th class="text-center">{{$currency_sign}}{{ formatCurrencyAmount($revenueArray[$month]) }}</th>
                  @endforeach
                  <input type="hidden" id="revenue" value="{{formatCurrencyAmount($totalRevenue)}}">
                </tr>
              </tbody>
            </table>
          </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-12 col-xl-12 p-0" id="ChartJsExpense">
      <div class="card">
        <div class="card-header">
          <h6>{{ __('Overview') }}</h6>
        </div>
        <div class="card-block">
          <canvas id="chart-line-1" class="chartJsLineGraph w-100 h-300"></canvas>
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
<script src="{{ asset('public/datta-able/plugins/chart-chartjs/js/Chart.min.js') }}"></script>
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>

<script type="text/javascript">
  'use strict';
  var startDate = "{!! isset($from) ? $from : 'undefined' !!}";
  var endDate   = "{!! isset($to) ? $to : 'undefined' !!}";
  var dateGraph     = '{!! $dateGraph !!}';
  var incomeGraph   = '{!! $incomeGraph !!}';
  var expenseGraph  = '{!! $expenseGraph !!}';
  var revenueGraph  = '{!! $revenueGraph !!}';
</script>
<script src="{{ asset('public/dist/js/custom/report.min.js') }}"></script>
@endsection