@extends('layouts.customer-list-layout')

@section('list-title')
  <h5>{{ __('Support Tickets')}}</h5>
@endsection

@section('list-add-button')
  <a href="{{ url('customer-panel/support/add') }}" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Ticket')  }}</a>
@endsection

@section('list-form-content')
<div id="cus-panel-ticket-list-container">
  <form class="form-horizontal" action="{{ url('customer-panel/support/list') }}" method="GET" id='orderListFilter'>
    <input type="hidden" name="customer" id="customer" value="{{ $customerData->id }}">
    <input class="form-control" id="startfrom" type="hidden" name="from" value="<?= isset($from) ? $from : '' ?>">
    <input class="form-control" id="endto" type="hidden" name="to" value="<?= isset($to) ? $to : '' ?>">
    @php
      $from = isset($from) ? $from : '';
      $to = isset($to) ? $to : '';
      $project = isset($allproject) ? $allproject : '';
      $assignee = isset($allassignee) ? $allassignee : '';
    @endphp
    <div class="col-md-12 col-sm-12 col-xs-12 m-l-10">
      <div class="row mt-3">
        <div class="col-md-5 col-xl-4 col-lg-5 col-sm-12 col-xs-12 mb-2">
          <div class="input-group">
            <button type="button" class="form-control" id="daterange-btn">
              <span class="float-left">
                <i class="fa fa-calendar"></i>
                <span>{{ __('Pick a date range')  }}</span>
              </span>
              <i class="fa fa-caret-down float-right pt-1"></i>
            </button>
          </div>
        </div>


        <div class="col-md-2 col-xl-3 col-sm-12 col-xs-12 mb-2">
          <select class="form-control select2" name="project" id="project">
              <option value="">{{ __('All Project') }}</option>
            @foreach($projects as $data)
              <option value="{{ $data->id }}" "{{ $data->id == $allproject ? ' selected="selected"' : ''}}">{{ $data->name }}</option>
            @endforeach
          </select>
        </div>
       <div class="ticket-filter col-md-2 col-xl-2 col-lg-2 col-sm-12 col-xs-12 mb-2">
        <select class="form-control select2" name="department_id" id="department_id">
          <option value="">{{ __('All Department') }}</option>
          @foreach($departments as $data)
          <option value="{{ $data->id }}" "{{ $data->id == $alldepartment ? ' selected="selected"' : ''}}">{{ $data->name }}</option>
          @endforeach
        </select>
      </div>
        <div class="col-md-2 col-xl-2 col-sm-12 col-xs-12 mb-2">
          <select class="form-control select2" name="status" id="status">
              <option value="">{{ __('All Status') }}</option>
              @foreach($status as $key => $value)
                <option value="{{ $key }}" "{{ $key == $allstatus ? ' selected="selected"' : '' }}"> {{ $value }} </option>
              @endforeach
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
<script>
  "use strict";
  var customerID = "{{ $customerData->id }}";
  var customerName = "{{ $customerData->name }}";
</script>
<script src="{{ asset('public/dist/js/custom/status_priority.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/customerpanel.min.js') }}"></script>
@endsection
