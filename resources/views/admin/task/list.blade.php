@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
  <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/css/task-list.css') }}">
  <link rel="stylesheet" href="{{asset('public/dist/plugins/lightbox/css/lightbox.min.css')}}">
  <link rel="stylesheet" href="{{asset('public/dist/css/task-design.min.css?v=1.0') }}">
@endsection
@section('content')
  <!-- Main content -->
<div class="col-sm-12" id="list-task-container">
  <div class="card">
    <div class="card-header">
      <h5>{{ __('Task List') }}</h5>
      <div class="card-header-right d-inline-block">
        @if(Helpers::has_permission(Auth::user()->id, 'add_task'))
          <a href="{{ url('task/add') }}" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Task') }}</a>
        @endif
      </div>
    </div>
    <div class="card-body p-0">
      <form class="form-horizontal" action="{{ url('task/list') }}" method="GET">
        <input class="form-control" id="startfrom" type="hidden" name="from" value="<?= isset($from) ? $from : '' ?>">
        <input class="form-control" id="endto" type="hidden" name="to" value="<?= isset($to) ? $to : '' ?>">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 ml-2">
          <div class="row mt-3">
            <div class="col-md-12 col-xl-3 col-lg-4 col-sm-12 col-xs-12 mb-2">
              <div class="input-group">
                <button type="button" class="form-control" id="daterange-btn">
                  <span class="float-left">
                    <i class="fa fa-calendar"></i>  {{ __('Date Range') }}
                  </span>
                  <i class="fa fa-caret-down float-right pt-1"></i>
                </button>
              </div>
            </div>
            <div class="col-md-12 col-xl-3 col-lg-3 col-sm-12 col-xs-12 mb-2">
                <select class="form-control select2" name="project" id="project">
                  <option value="">{{ __('All projects') }}</option>
                  @if(!empty($projects))
                    @foreach($projects as $data)
                      <option value="{{$data->id}}" {{$data->id == $allproject ? "selected" : ''}}>{{$data->name}}</option>
                    @endforeach
                  @endif
                </select>
            </div>
            <div class="col-md-12 col-xl-3 col-lg-3 col-sm-12 col-xs-12 mb-2">
              <select class="form-control select2" name="assignee" id="assignee">
                <option value="">{{ __('All assignees') }}</option>
                @if(!empty($assignees))
                  @foreach($assignees  as $data)
                    <option value="{{$data->id}}" {{$data->id == $allassignee ? 'selected' : ''}} >
                      {{ ($data->full_name)}}
                    </option>
                  @endforeach
                @endif
              </select>
            </div>
            <div class="col-md-12 col-xl-2 col-lg-2 col-sm-12 col-xs-12 mb-2">
              <select class="form-control select2" name="status" id="status">
                <option value="">{{ __('All status') }}</option>
                @if(!empty($task_statuses_all))
                  @foreach($task_statuses_all as $data)
                    <option value="{{$data->id}}" {{$data->id == $allstatus ? "selected": ''}}>{{$data->name}}</option>
                  @endforeach
                @endif
              </select>
            </div>

            <div class="col-xl-1 col-lg-1 col-md-12 col-sm-1 col-xs-12 pl-md-3">
              <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small mt-0 mr-0">{{ __('Go') }}</button>
            </div>
          </div>
        </div>
      </form>
      <div class="col-sm-12 mb-1">
        <div class="row mt-1">
          @foreach($summary as $key=>$data)
          @php
           if ($data['color']) {
                $color = $data['color'];
            } else {
                $color  = '008000';
            }
          @endphp
          <div class="col-md-3 ticket-summary text-white m-l-25 pr-md-0 pl-0">
            <div class="text-center p-10 mt-1 status-border status-border">
                <span class="f-14 f-w-600" style="color: {{ $color }} ">{{ $data['name'] }}</span><br>
                <a class="f-20" href="{{ url('task/list?from='. $from .'&to='. $to .'&project='. $allproject .'&assignee='. $allassignee .'&status='.$data['id'].'&btn=') }}"><span style="color: {{ $color }};" id="{{str_replace(' ', '', $data['name'])}}">{{ $data['total_status'] }}</span></a><br>
                @if($allassignee != Auth::user()->id)
                    <span style="color: {{ $color }};">{{ __('Assign to me') }}: <b>{{ $assign_to_me[$key] }}</b></span>
                @endif
            </div>
          </div>
          @endforeach
        </div>
      </div>

      <div class="card-block mt-1">
        <div class="table-responsive">
          {!! $dataTable->table(['class' => 'table table-striped table-hover dt-responsive', 'width' => '100%', 'cellspacing' => '0']) !!}
          @if (isset($task) && !empty($task))
            <span class="task-v-preview"><a href=""  class="task_class display_none" data-id="{{ $task->id }}" data-priority-id="{{ $task->priority_id }}" project_id="{{ $task->related_to_type == 1 ? $task->related_to_id : '' }}" data-status-id=" {{ $task->task_status_id }}" type="button" data-toggle="modal" data-target="#task-modal">{{ $task->name }}</a></span>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@include('admin.task.details')
<div class="modal fade" id="theModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="theModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary custom-btn-small" data-dismiss="modal">{{ __('Close') }}</button>
        <button type="button" id="theModalSubmitBtn" data-task="" class="btn btn-danger custom-btn-small">{{ __('Submit') }}</button>
        <span class="ajax-loading"></span>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>
{!! $dataTable->scripts() !!}
@include('admin.task.details_script')
<script type="text/javascript">
  'use strict';
  var startDate = "{!! isset($from) ? $from : 'undefined' !!}";
  var endDate   = "{!! isset($to) ? $to : 'undefined' !!}";
</script>
<script src="{{ asset('public/dist/js/custom/task.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/dist/js/html5lightbox/html5lightbox.js?v=1.0') }}"></script>
@endsection
