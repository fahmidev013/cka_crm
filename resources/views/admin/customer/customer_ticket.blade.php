@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
  <!-- daterange picker -->
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
  <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection
@section('content')
<!-- Main content -->
<div class="col-sm-12" id="customer-ticket-container">
  <div class="card">
    <div class="card-header">
      <h5><a href="{{ url('customer/list') }}">{{ __('Customers') }}</a> >> {{ $customerData->first_name }} {{ $customerData->last_name }} >> {{ __('Tickets') }}</h5>
      <div class="card-header-right">

      </div>
    </div>
    <div class="card-body p-0" id="no_shadow_on_card">
    @include('admin.customer.customer_tab')
    <div class="col-sm-12">
      <form class="form-horizontal" action="{{ url('customer/ticket/' . $customerData->id) }}" method="GET" id='orderListFilter'>
        <input class="form-control" id="startfrom" type="hidden" name="from" value="<?= isset($from) ? $from : '' ?>">
        <input class="form-control" id="endto" type="hidden" name="to" value="<?= isset($to) ? $to : '' ?>">
        <input type="hidden" name="customer" id="customer" value="{{ $customerData->id }}">
        <div class="col-md-12 col-sm-12 col-xs-12 m-l-10 margin-btm-1p">
          <div class="row mt-3">
            <div class="col-md-5 col-xl-3 col-lg-4 col-sm-12 col-xs-12 mb-2 customer-ticket">
              <div class="input-group">
                <button type="button" class="form-control" id="daterange-btn">
                  <span class="float-left">
                    <i class="fa fa-calendar"></i>  {{ __('Pick a date range') }}
                  </span>
                  <i class="fa fa-caret-down float-right pt-1"></i>
                </button>
              </div>
            </div>

            <div class="ticket-filter col-md-2 col-xl-3 col-lg-3 col-sm-12 col-xs-12 mb-2">
                <select class="form-control select2" name="project" id="project">
                    <option value="">{{ __('All Project') }}</option>
                  @foreach($projects as $data)
                    <option value="{{ $data->id }}" "{{ $data->id == $allproject ? ' selected="selected"' : '' }}">{{ $data->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="ticket-filter col-md-2 col-xl-2 col-lg-2 col-sm-12 col-xs-12 mb-2">
                <select class="form-control select2" name="department_id" id="department_id">
                  <option value="">{{ __('All Department') }}</option>
                  @foreach($departments as $data)
                  <option value="{{ $data->id }}" "{{ $data->id == $alldepartment ? ' selected="selected"' : '' }}">{{ $data->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="ticket-filter col-md-2 col-xl-2 col-lg-2 col-sm-12 col-xs-12 mb-2">
                <select class="form-control select2" name="status" id="status">
                    <option value=""> {{ __('All Status') }}</option>
                    @foreach($status as $data)
                      <option value="{{ $data->id }}" "{{$data->id == $allstatus ? ' selected="selected"' : ''}}">{{ $data->name }}</option>
                    @endforeach
                </select>
              </div>

            <div class="col-md-1 col-sm-1 col-xs-12 p-md-0">
              <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small push-sm-right mt-0 mr-0">{{ __('Go') }}</button>
            </div>
          </div>
        </div>
      </form>
      <div class="row mt-2">
        @php
          $fromDate       = isset($from) ? $from : '';
          $toDate         = isset($to) ? $to : '';
          $project    = isset($allproject) ? $allproject : '';
          $department = isset($alldepartment) ? $alldepartment : '';
        @endphp
        @foreach($summary as $counter => $data)
          @php
           if ($data->color) {
                $color = $data->color;
            } else {
                $color  = '008000';
            }
          @endphp
            <div class="col-md-2 ticket-summary text-white m-l-25 pr-md-0 mb-3" id="ticket-name">
              <div class="text-center p-10 mt-1 status-border" style="color: {{ $color }} ">
                  <span class="f-w-700">{{ $data->name }}</span><br>
                  <a href="{{ url('customer/ticket/' . $customerData->id . '?from=' . $fromDate . '&to=' . $toDate . '&project=' . $project . '&department=' . $department . '&status=' . $data->id . '&btn=') }}" style="color: {{ $color }}"><span class="f-20" id="{{ str_replace(' ', '', $data->name) }}">{{ $data->total_status }}</span></a>
              </div>
            </div>
        @endforeach
      </div>

    </div>
      <div class="card-block">
          <div class="col-sm-12">
            <div class="table-responsive">
              {!! $dataTable->table(['class' => 'table table-striped table-hover dt-responsive', 'width' => '100%', 'cellspacing' => '0']) !!}
            </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmDeleteLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary custom-btn-small" data-dismiss="modal">{{ __('Close') }}</button>
          <button type="button" id="confirmDeleteSubmitBtn" data-task="" class="btn btn-primary">{{ __('Submit') }}</button>
          <span class="ajax-loading"></span>
        </div>
      </div>
    </div>
  </div>
</div>
  @include('layouts.includes.message_boxes')
@endsection

@section('js')
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
{!! $dataTable->scripts() !!}
<script type="text/javascript">
  "use strict";
  var startDate = "{!! isset($from) ? $from : 'undefined' !!}";
  var endDate   = "{!! isset($to) ? $to : 'undefined' !!}";
</script>
<script src="{{ asset('public/dist/js/custom/status_priority.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/customer.min.js') }}"></script>
@endsection
