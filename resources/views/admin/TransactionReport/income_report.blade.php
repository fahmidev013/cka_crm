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
  <div class="col-sm-12" id="income-report-container">
    <div class="card">
      <div class="card-header">
        <h5><a href="{{ url('transaction/income-report') }}">{{ __('Income') }} </a></h5>
      </div>
      <form class="form-horizontal" action="{{ url('transaction/income-report') }}" method="GET" accept-charset="UTF-8">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 m-l-10">
          <div class="row mt-3">
            <div class="col-md-12 col-xl-4 col-lg-4 col-sm-12 col-xs-12 mb-2">
              <div class="input-group">                      
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                </div>
                <input type="text" id="startfrom" class="form-control date-pick" name="from" placeholder="From">
              </div>
            </div>
            <div class="col-md-12 col-xl-4 col-lg-4 col-sm-12 col-xs-12 mb-2">
              <div class="input-group">                      
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                </div>
                <input type="text" id="endto" class="form-control date-pick" name="to" placeholder="To">
              </div>
            </div>
            <div class="col-md-12 col-xl-2 col-lg-2 col-sm-12 col-xs-12 mb-2">
              <select class="form-control select2" name="currency" id="currency">
                @if(!empty($currencyList))
                  @foreach($currencyList as $currencyData)
                    <option value="{{$currencyData->id}}" <?= ($currencyData->id == $currency) ? 'selected' : ''?>>{{$currencyData->name}}</option>
                  @endforeach
                @endif
              </select>
            </div>
            <div class="col-md-1 col-xl-1 col-lg-1 col-sm-12 col-xs-12">
              <button type="submit" name="btn" title="{{ __('Click to filter') }}" class="btn btn-primary custom-btn-small mt-0 mr-0">Go</button>
            </div>
          </div>
        </div>
      </form>
      <div class="card-block">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
            <th class="text-center"><a href="#chartJsLineBasic">Category</a></th>
            <th class="text-center"><a href="#chartJsLineBasic">Amount</a></th>
            </thead>
            <tbody>
              <tr>
                @foreach ($categoryTotals as $key => $value)
                <tr>
                  <td class="text-center">{{ $key }}</td>
                  <td class="text-center">{{ $currencySymbol }}{{ formatCurrencyAmount($value) }}</td>
                </tr>
                  <?php $generalTotal += $value ?>
                @endforeach
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <th class="text-center">Total</th>
                @if (isset($generalTotal) && !empty($generalTotal))
                  <th class="text-center">{{ $currencySymbol }}{{ formatCurrencyAmount($generalTotal) }}</th>
                @else 
                  <th class="text-center">-</th>
                @endif
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header" id="headerDiv">
        <h5><a href="#chartJsLineBasic">{{ __('Monthly Income') }} </a></h5>
      </div>
      <div class="card-body p-0">
        @php
          $generalTotal  = 0;
        @endphp
        <div class="card-block">
          <div class="table-responsive">
            <table id="incomeList" class="table table-bordered table-hover table-striped dt-responsive" width="800%">
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
              <tbody>
                @foreach($incomeList as $category => $data)
                  <tr>
                    <td class="text-center">{{ $category }}</td>
                    <?php foreach($months as $month) : 
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
                  <?php foreach($totals as $total) : ?>
                  <th class="text-center">{{ $currencySymbol }}{{ formatCurrencyAmount($total) }}</th>
                  <?php endforeach ;?>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        <div class="card-block">
          <canvas class="ChartJsIncome" id="chartJsLineBasic"></canvas>
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
  var startDate = "{!! isset($from) ? $from : 'undefined' !!}";
  var endDate   = "{!! isset($to) ? $to : 'undefined' !!}";
  var months     = "{{ $mnths }}";
  months         = months.split(',');
  var incomeData = new Array();
  
  <?php 
      $i = 0;
      if (!empty($incomeList)) {
        foreach($incomeList as $key => $value) {
          $str = [];
          foreach ($months as $month) {
            $str[] = isset($value[$month]) ? $value[$month] : 0;
          }

          $color = $colors[$i++];
          if (empty($color)) {
            $color = '#'. rand(000000, 896543);
          }

          $data = [
            'label' => sanitize_output($key),
            'backgroundColor' => sanitize_output($color),
            'borderColor' => sanitize_output($color),
            'data' => $str,
            'fill' => false
          ];
      ?>
      incomeData.push(<?php echo json_encode($data) ?>);
      <?php } ?>
  <?php } ?>
</script>
<script src="{{ asset('public/dist/js/custom/report.min.js') }}"></script>
@endsection
