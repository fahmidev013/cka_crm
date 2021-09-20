@extends('layouts.list-layout')

@section('list-title')
<h5>{{ __('Project List')  }}</h5>
@endsection

@section('list-add-button')

@if(Helpers::has_permission(Auth::user()->id, 'add_project'))
  <a href="{{ url('project/add') }}" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Project')  }}</a>
@endif

@endsection

@section('list-form-content')
  <div id="project-list-container">
    <form class="form-horizontal" action="{{ url('project/list') }}" method="GET">
      <input class="form-control" id="startfrom" type="hidden" name="from" value="<?= isset($from) ? $from : '' ?>">
      <input class="form-control" id="endto" type="hidden" name="to" value="<?= isset($to) ? $to : '' ?>">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 m-l-10">
        <div class="row mt-3">
          <div class="col-md-12 col-xl-4 col-lg-5 col-sm-12 col-xs-12 mb-2">
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
          <div class="col-md-12 col-xl-4 col-lg-3 col-sm-12 col-xs-12 mb-2">
              <select class="form-control select2" name="status" id="status">
                <option value=""> {{ __('All Status')  }}</option>
                @foreach($status as $data)
                  <option value="{{$data->id}}" "{{$data->id == $allstatus ? ' selected="selected"' : ''}}">{{$data->name}}</option>
                @endforeach
              </select>
          </div>
          <div class="col-md-12 col-xl-3 col-lg-3 col-sm-12 col-xs-12 mb-1">
              <select class="form-control selectpicker" name="project_type[]" id="project_type" multiple>
                <option value="customer" {{ in_array('customer', $project_type) ? 'selected="selected"' : ''}}>{{ __('Customer') }}</option>
                <option value="product" {{ in_array('product', $project_type) ? 'selected="selected"' : ''}}>{{ __('Product') }}</option>
                <option value="in_house" {{ in_array('in_house', $project_type) ? 'selected="selected"' : ''}}>{{ __('In house') }}</option>
              </select>
          </div>
          <div class="col-md-1 col-sm-12 col-xs-12 pr-md-2">
            <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small mt-0 mr-0">{{ __('Go') }}</button>
          </div>
        </div>
      </div>
    </form>
@endsection
@section('list-js')
<script>
  'use strict';
  var startDate = "{!! isset($from) ? $from : 'undefined' !!}";
  var endDate   = "{!! isset($to) ? $to : 'undefined' !!}";
</script>
<script src="{{ asset('public/dist/js/custom/project.min.js') }}"></script>
@endsection