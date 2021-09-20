@extends('layouts.list-layout')

@section('listCSS')
  
@endsection

@section('list-title')
  <h5><a href="{{ url('time-sheet/list') }}">{{ __('Timesheet') }}</a></h5>
@endsection

@section('list-add-button')
 
@endsection

@section('list-form-content')
  <form class="form-horizontal" action="{{ url('time-sheet/list') }}" method="GET" accept-charset="UTF-8" id="timesheet-container">
    <input class="form-control" id="startfrom" type="hidden" name="from" value="<?= isset($from) ? $from : '' ?>">
    <input class="form-control" id="endto" type="hidden" name="to" value="<?= isset($to) ? $to : '' ?>">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 m-l-10">
      <div class="row mt-3">
        <div class=" col-xl-3 col-lg-5 col-md-5 col-sm-12 col-xs-12 pr-0">
          <div class="input-group">           
            <button type="button" class="form-control" id="daterange-btn">
              <span class="float-left">
                <i class="fa fa-calendar"></i> {{ __('Date range picker') }}
              </span>
              <i class="fa fa-caret-down float-right pt-1"></i>
            </button> 
          </div>           
        </div> 

        <div class=" col-xl-3 col-lg-2 col-md-2 col-sm-12 col-xs-12 pr-0">
          <select class="form-control select2" name="project" id="project">
            <option value="">{{ __('All Project') }}</option>
            @foreach($projects as $data)
              <option value="{{ $data->id }}" {{ $data->id == $project ? ' selected="selected"' : ''}}>{{ $data->name }}</option>
            @endforeach
          </select>
        </div>

        <div class=" col-xl-3 col-lg-2 col-md-2 col-sm-12 col-xs-12 pr-0 mb-2">
          <select class="form-control select2" name="assignee" id="assignee">
            <option value="">{{ __('All Assignee') }}</option>
            @foreach($assignees as $data)
              <option value="{{ $data->id }}" {{ $data->id == $assignee ? ' selected="selected"' : ''}}>{{ $data->full_name }}</option>
            @endforeach
          </select>
        </div>

        <div class=" col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12 pr-0 mb-2">
          <select class="form-control select2" name="running" id="running">
            <option value="">{{ __('Running') }}</option>
            <option value="yes" {{ "yes" == $running ?  "selected" : ''}}>{{ __('Yes') }}</option>
            <option value="no" {{ ("no" == $running) ? "selected" : ''}}>{{ __('No') }}</option>
          </select>
        </div>
        <div class="col-md-1 col-lg-1 col-sm-1 col-xs-12 pr-md-1">
          <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small mt-0 mr-0">{{ __('Go') }}</button>        
        </div>
      </div>
    </div>
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 m-l-10">
    <div class="row mt-3">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-4">
        <div class="card project-task status-border">
          <div class="card-block">
            <div class="row  align-items-center">
              <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
                  <span class="f-16 color_03a9f4"><i class="fas fa-clock m-r-10 f-4"></i>{{ __('Total Time Spent') }}</span>
              </div>
              <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-xs-12 text-right">
                <div class="row card-active">
                  <div class="col-md-4 col-6">
                    <span class="text-c-blue f-20 @if($hours == 0) {{'text-muted'}} @endif"><strong>{{ $hours }}</strong></span>
                    <span class="text-muted">Hours</span>
                  </div>
                  <div class="col-md-4 col-6">
                    <span class="text-c-blue f-20 @if($minutes == 0) {{'text-muted'}} @endif"><strong>{{ $minutes }}</strong></span>
                    <span class="text-muted">Minutes</span>
                  </div>
                  <div class="col-md-4 col-6">
                    <span class="text-c-blue f-20 @if($seconds == 0) {{'text-muted'}} @endif"><strong>{{ $seconds }}</strong></span>
                    <span class="text-muted">Seconds</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </form>
@endsection
@section('list-js')
<script src="{{ asset('public/dist/js/custom/timesheet.min.js') }}"></script>
@endsection