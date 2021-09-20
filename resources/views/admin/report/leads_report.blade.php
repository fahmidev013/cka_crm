@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection
@section('content')
  <div class="col-sm-12" id="leads-report-container">
    <div class="card">
      <div class="card-header" id="headerDiv">
        <h5><a href="{{ url('report/leads-report') }}">{{ __('Leads Report')  }}</a></h5>
      </div>
      <form class="form-horizontal mb-2" action="{{ url('report/leads-report') }}" method="GET" accept-charset="UTF-8">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 m-l-10">
          <div class="row mt-3">
            <div class="col-md-5 col-xl-3 col-lg-3 col-sm-5 col-xs-12 mb-2">
              <div class="input-group">                      
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                </div>
                <input type="text" id="startfrom" class="form-control date-pick" name="from" placeholder="From">
              </div>
            </div>
            <div class="col-md-5 col-xl-3 col-lg-3 col-sm-5 col-xs-12 mb-2">
              <div class="input-group">                      
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                </div>
                <input type="text" id="endto" class="form-control date-pick" name="to" placeholder="To">
              </div>
            </div>
            <div class="col-md-2 col-xl-1 col-lg-1 col-sm-2  col-xs-12 pr-md-1">
              <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small mt-0 mr-0">{{ __('Go') }}</button>
            </div>
          </div>
        </div>
      </form>
      @php
        $from = isset($from) ? $from : '';
        $to = isset($to) ? $to : '';
      @endphp
    <div class="card-block p-0">
      <div class="col-sm-12 px-0" id="smallDevice"> 
        <div class="col-sm-6 px-0" id="smallDevicePx">
          <div class="card">
            <div class="card-header">
              <h6>{{ __('Monthly Lead')  }} </h6>
            </div>
            <div class="card-body">
              <canvas class="ChartJsExpense" id="chartJsLineBasic"></canvas>
            </div>
          </div>
        </div>
        <div class="col-sm-6 smallDevicePx">
          <div class="card">
            <div class="card-header">
              <h6 class="ml-4">{{ __('Leads By Status')  }}</h6>
            </div>
            <div class="card-body">
              <canvas class="float-right canvas-height-width chartPieStatus" id="chart-pie-3"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-block p-0">
      <div class="card mb-0">
        <div class="card-header">
          <h6>{{ __('Leads By Source')  }} </h6>
        </div>
        <div class="card-block col-sm-12">
          <div class="col-sm-12 px-0" id="smallDevice-2"> 
            <div class="col-sm-6 px-0" id="smallDevicePx-2">
              <?php $generalTotal = 0; ?>
              <table class="table table-bordered table-dotted">
                <tr class="table-dotted">
                  <th class="text-center table-dotted">{{ __('Source') }}</th>
                  <th class="text-center table-dotted">{{ __('No. of Lead') }}</th>
                </tr>
                @foreach ($totalLeadBySource as $key => $value)
                  <tr class="table-dotted">
                    <td class="text-center table-dotted">{{ $key }}</td>
                    <td class="text-center table-dotted">{{ $value['count'] }}</td>
                  </tr>
                  <?php $generalTotal += $value['count'] ?>
                @endforeach
                  <tr class="table-dotted">
                    <td class="text-center table-dotted"><strong>{{ __('Total') }}</strong></td>
                    <td class="text-center table-dotted"><strong>{{ $generalTotal }}</strong></td>
                  </tr>
              </table>
            </div>
            <div class="col-sm-6 smallDevicePx">
              <canvas class="float-right canvas-height-width chartPieSource" id="chart-pie-2"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/chart-chartjs/js/Chart.min.js') }}"></script>
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>

<script type="text/javascript">
  'use strict';
  var MONTHS = new Array();
  var startDate = "{!! isset($from) ? $from : 'undefined' !!}";
  var endDate   = "{!! isset($to) ? $to : 'undefined' !!}";
  <?php foreach ($months as $key => $value) { ?>
    MONTHS.push('<?php echo sanitize_output($value); ?>');
  <?php } ?>

  var leadData = new Array();

  <?php 
      $i = 0; 
      if (!empty($monthlyLeads)) {
        foreach($monthlyLeads as $key => $value) {

          $str = [];
          foreach ($months as $month) {
            $str[] = isset($value[$month]) ? $value[$month] : 0;
          }

          if (array_key_exists($key, $totalLeadByStatus)) {
            $color = $totalLeadByStatus[$key]['color'];
          } else {
            $color = $colors[$i++];
            if (empty($color)) {
              $color = '#'. rand(000000, 896543);
            }
          }

        $data = [
          'label' => sanitize_output($key),
          'backgroundColor' => sanitize_output($color),
          'borderColor' => sanitize_output($color),
          'data' => $str,
          'fill' => false
        ];
      }
    }
    if (isset($data) && !empty($data)) { ?> 
      leadData.push(<?php echo json_encode($data) ?>); 
      <?php
    }
  ?>

  var leadDataByStatus = new Array();

  <?php 
    $i = 0; 
    if (!empty($monthlyLeads)) {
      foreach($monthlyLeads as $key => $value) {

        $str = [];
        foreach ($months as $month) {
          $str[] = isset($value[$month]) ? $value[$month] : 0;
        }

        if (array_key_exists($key, $totalLeadByStatus)) {
          $color = $totalLeadByStatus[$key]['color'];
        } else {
          $color = $colors[$i++];
          if (empty($color)) {
            $color = '#'. rand(000000, 896543);
          }
        }
        $leadDataByStatus = [
          'label' => sanitize_output($key),
          'backgroundColor' => sanitize_output($color),
          'borderColor' => sanitize_output($color),
          'data' => $str,
          'fill' => false
        ];
        ?>
        leadDataByStatus.push(<?php echo json_encode($leadDataByStatus) ?>);
<?php }
    }
    ?>

  // Pie chart of lead by status
  var labelsLeadByStatus = new Array();
  var countsLeadByStatus = new Array();
  var colorsLeadByStatus = new Array();
  <?php 
  if (isset($totalLeadByStatus) && !empty($totalLeadByStatus)) {
    foreach ($totalLeadByStatus as $key => $value) {
      ?>
      labelsLeadByStatus.push('<?php echo sanitize_output($key.' ('.$value['count'].')'); ?>');
      countsLeadByStatus.push('<?php echo sanitize_output($value['count']); ?>');
      colorsLeadByStatus.push('<?php echo sanitize_output($value['color']); ?>');
   <?php } }?>

  // Pie chart lead by source
  var labelsLeadBySource = new Array();
  var countsLeadBySource = new Array();
  var colorsLeadBySource = new Array();
  <?php 
    $i = 1;
    foreach ($totalLeadBySource as $key => $value) {
  ?>
    labelsLeadBySource.push('<?php echo sanitize_output($key.' ('.$value['count'].')'); ?>');
    countsLeadBySource.push('<?php echo sanitize_output($value['count']); ?>');
    colorsLeadBySource.push('<?php echo sanitize_output($colors[$i]); ?>');
  <?php $i+=1; } ?>
</script>
<script src="{{ asset('public/dist/js/custom/report.min.js') }}"></script>
@endsection