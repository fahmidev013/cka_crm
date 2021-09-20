@extends('admin.project.main')

@section('projectCSS')

<link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('public/dist/css/project.min.css')}}">

@endsection

@section('add-title')
@if(Helpers::has_permission(Auth::user()->id, 'add_invoice'))
  <a href="{{ url('invoice/add?type=project&project_id='.$project->id.'&customer_id='.$project->customer_id) }}" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Invoice')  }}</a>
@endif
@endsection

@section('projectContent')
<div id="project-invoice-list-container">
  <form class="form-horizontal" action="{{ url('project/invoice/'.$project->id) }}" method="GET" id='orderListFilter'>
    <input id="startfrom" type="hidden" name="from">
    <input id="endto" type="hidden" name="to">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div class="row mt-2">
        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-10 col-xs-12 mb-2">
          <div class="input-group">
            <button type="button" class="form-control" id="daterange-btn">
              <span class="float-left">
                <i class="fa fa-calendar"></i>  {{ __('Pick a date range')  }}
              </span>
              <i class="fa fa-caret-down float-right pt-1"></i>
            </button>
          </div>
        </div>
        <div class="col-xl-1 col-lg-1 col-md-2 col-sm-2 col-xs-12 p-md-0">
          <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small mt-0 mr-0">{{ __('Go') }}</button>
        </div>
      </div>
    </div>
  </form>
  <div class="row m-0 mt-2 mb-3">
    <div class="col-12 col-md-6 col-lg-3 col-xl-3">
      <div class="text-white">
        <div class="p-10 status-border color_0000ff">
          <span class="f-w-700 f-20">{{ __('Total Amount') }}</span><br>
          @forelse($amounts['amounts'] as $amount)
            <span class="f-16">{{ isset($amount->totalInvoice) ?  formatCurrencyAmount($amount->totalInvoice, $amount->currency->symbol) : 0 }}</span><br>
          @empty
            <span class="f-16">{{ formatCurrencyAmount(0) }}</span><br>
          @endforelse
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3 col-xl-3">
      <div class="text-white">
        <div class="p-10 status-border color_4CAF50">
          <span class="f-w-700 f-20">{{ __('Total Paid') }}</span><br>
          @forelse($amounts['amounts'] as $amount)
            <span class="f-16">{{ isset($amount->totalPaid) ? formatCurrencyAmount($amount->totalPaid, $amount->currency->symbol) : 0 }}</span><br>
          @empty
            <span class="f-16">{{ formatCurrencyAmount(0) }}</span><br>
          @endforelse
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3 col-xl-3">
      <div class="text-white">
        <div class="p-10 status-border color_848484">
          <span class="f-w-700 f-20">{{ __('Total Due') }}</span><br>
          @forelse($amounts['amounts'] as $amount)
            <span class="f-16">{{ formatCurrencyAmount($amount->totalInvoice - $amount->totalPaid, $amount->currency->symbol) }}</span><br>
          @empty
            <span class="f-16">{{ formatCurrencyAmount(0) }}</span><br>
          @endforelse
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3 col-xl-3">
      <div class="text-white">
        <div class="p-10 status-border color_ff2d42">
          <span class="f-w-700 f-20">{{ __('Over Due') }}</span><br>
          @if(count($amounts['overDue']) > 0)
            @foreach($amounts['overDue'] as $index => $overDue)
              <span class="f-16">{{ isset($overDue) && !empty($overDue) ? formatCurrencyAmount($overDue['totalAmount'] - $overDue['totalPaid'], $overDue->currency->symbol)  : formatCurrencyAmount(0) }}</span><br>
            @endforeach
            @foreach($allCurrency as $key => $value)
              <span class="f-16">{{ formatCurrencyAmount(0, $value) }}</span><br>
            @endforeach
          @else
            @if (!empty($allCurrency))
              @foreach($allCurrency as $key => $value)
                <span class="f-16">{{ formatCurrencyAmount(0, $value) }}</span><br>
              @endforeach
            @else 
              <span class="f-16">{{ formatCurrencyAmount(0) }}</span><br>
            @endif
          @endif

        </div>
      </div>
    </div>
  </div>
<!--Filtering Box End -->

    <!-- Default box -->
    <div class="col-sm-12">
      <div class="table-responsive">
        {!! $dataTable->table(['class' => 'table table-striped table-hover dt-responsive', 'width' => '100%', 'cellspacing' => '0']) !!}
      </div>
    </div>

  </div>

    @include('layouts.includes.message_boxes')

@endsection

@section('projectJS')

<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>

<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>

{{-- Moment js --}}
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>

<!-- date-range-picker -->
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>

{!! $dataTable->scripts() !!}

<script type="text/javascript">
  'use strict';
  var startDate = "{!! isset($from) ? $from : 'undefined' !!}";
  var endDate = "{!! isset($to) ? $to : 'undefined' !!}";
  var projectId = "{{ $project->id }}";
  </script>
  <script src="{{ asset('public/dist/js/custom/project.min.js') }}"></script>
    
@endsection