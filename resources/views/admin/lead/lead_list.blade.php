@extends('layouts.list-layout')

@section('list-title')
  <h5>{{ __('Lead List') }}</h5>
@endsection

@section('list-add-button')

  @if(Helpers::has_permission(Auth::user()->id, 'add_expense'))
    <a href="{{ url('create-lead') }}" class="btn btn-outline-primary custom-btn-small"><span class="feather icon-plus"> &nbsp;</span>{{ __('New Lead') }}</a>
  @endif

@endsection

@section('list-form-content')
  <form class="form-horizontal" enctype='multipart/form-data' action="{{ url('lead/list') }}" method="GET" accept-charset="UTF-8" id="lead-container">
    <input class="form-control" id="startfrom" type="hidden" name="from">
    <input class="form-control" id="endto" type="hidden" name="to">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 m-l-10">
      <div class="row mt-3">
        <div class="col-md-12 col-xl-4 col-lg-5 col-sm-12 col-xs-12 mb-2">

          <div class="input-group">
            <button type="button" class="form-control" id="daterange-btn">
              <span class="float-left">
                <i class="fa fa-calendar"></i>
                <span>{{ __('Pick a date range') }}</span>
              </span>
              <i class="fa fa-caret-down float-right pt-1"></i>
            </button>
          </div>
        </div>

        <div class="col-md-12 col-xl-2 col-lg-2 col-sm-12 col-xs-12 mb-2">
          <select class="form-control select2" name="assignee" id="assignee">
            <option value="" >{{ __('All Assignee') }}</option>
            @if(!empty($users))
              @foreach($users as $data)
                <option value="{{$data->id}}" {{$data->id == $allAssignee ? ' selected' : ''}}>{{$data->full_name}}</option>
              @endforeach
            @endif
          </select>
        </div>            
        <div class="col-md-12 col-xl-2 col-lg-3 col-sm-12 col-xs-12 mb-2">
          <select class="form-control selectpicker" name="leadStatus[]" id="leadStatus" multiple>           
            @if(!empty($leadStatus))
              @foreach($leadStatus as $data)
                @php
                  $flag = 0;
                  if($allLeadStatus) {
                    foreach ($allLeadStatus as $value) {
                      if($data->id == $value) {
                        $flag = 1;
                      }
                    }     
                  }
                @endphp
                @if($flag==1)
                  <option value="{{$data->id}}" selected="selected">{{$data->name}}</option>
                @elseif($flag==0)
                  <option value="{{$data->id}}">{{$data->name}}</option>
                @endif
              @endforeach
            @endif
          </select>
        </div>
        <div class="col-md-12 col-xl-3 col-lg-2 col-sm-12 col-xs-12 mb-2">
          <select class="form-control select2" name="leadSource" id="leadSource">
            <option value="" >{{ __('All Lead Source') }}</option>
            @if(!empty($leadSource))
              @foreach($leadSource as $data)
                    <option value="{{$data->id}}" {{$data->id == $allLeadSource ? ' selected' : ''}}>{{$data->name}}</option>
              @endforeach
            @endif
          </select>
        </div>
        <div class="col-md-1 col-lg-1 col-sm-12 col-xs-12 mb-2">
          <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small mt-0 mr-0">{{ __('Go') }}</button>
        </div>
      </div>
    </div>
  </form>
    <!--Filtering Box End -->
    <!-- Top Box-->
    

@endsection

@section('list-js')
<script src="{{ asset('public/dist/js/custom/lead.min.js') }}"></script>
@endsection