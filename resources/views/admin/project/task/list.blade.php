@extends('admin.project.main')

@section('projectCSS')
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
  <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/css/task-list.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/dist/css/task-design.min.css?v=1.0') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/css/project.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/plugins/lightbox/css/lightbox.min.css') }}">
@endsection

@section('add-title')
  @if(Helpers::has_permission(Auth::user()->id, 'add_task'))
      <a href="{{ url('project/task/add/'.$project->id) }}" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Task')  }}</a>
  @endif
@endsection

@section('projectContent')
  <div id="project-task-container">
    <form class="form-horizontal m-0" action="{{ url('project/tasks/'.$project->id) }}" method="GET" id="taskProject">
      <input class="form-control" id="startfrom" type="hidden" name="from" value="<?= isset($from) ? $from : '' ?>">
      <input class="form-control" id="endto" type="hidden" name="to" value="<?= isset($to) ? $to : '' ?>">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row mt-2">
          <div class="col-md-4 col-xl-4 col-lg-4 col-sm-12 col-xs-12 mb-2">
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

          <div class="col-md-3 col-xl-3 col-lg-3 col-sm-12 col-xs-12 mb-2">
            <select class="form-control js-example-basic-single" name="assignee" id="assignee">
              @if(!empty($assignees))
              <option value="">{{ __('All Assignee') }}</option>
                @foreach($assignees  as $data)
                <option value="{{ $data->id }}" {{ $data->id == $allassignee ? 'selected' : ''}} >
                  {{mb_substr($data->full_name, 0, 20)}}
                  @if( mb_strlen($data->full_name) > 20 )
                    ..
                  @endif
                </option>
                @endforeach
              @else
                 <option value="">{{ __('No results found') }}</option>
              @endif
            </select>
          </div>
          <div class="col-md-2 col-xl-2 col-lg-2 col-sm-12 col-xs-12 mb-2">
            <select class="form-control js-example-basic-single" name="status" id="status">
              <option value="">{{ __('All Status') }}</option>
              @foreach($taskStatus as $key => $value)
              <option value="{{ $key }}" {{ $key == $allstatus ? 'selected': ''}}>{{ $value }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-2 col-xl-2 col-lg-2 col-sm-12 col-xs-12 mb-2">
            <select class="form-control js-example-basic-single" name="priority" id="priority">
              <option value="">{{ __('All Priority') }}</option>
              @foreach($task_priority_all as $priority)
              <option value="{{ $priority->id }}" {{ $priority->id == $allpriority ? "selected" : ''}}>{{ $priority->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-1 col-xl-1 col-lg-1 col-sm-12 col-xs-12 mb-2">
            <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small mt-0">{{ __('Go') }}</button>
          </div>
        </div>
      </div>
    </form>
    <div class="col-md-12 m-t-10 mb-3 p-0">
      <div class="row" id="taskStatus_summary">

        @foreach($summary as $key=>$data)
          @php
           if ($data['color']) {
                $color = $data['color'];
            } else {
                $color  = '008000';
            }
          @endphp
            <div class="col-md-3 ticket-summary text-white m-l-25 pr-md-0 pl-0">
              <div class="text-center p-10 mt-1 status-border" style="color: {{ $color }} ">
                  <span class="f-w-700">{{ $data['name'] }}</span><br>
                    <a class="f-20" href="{{ url('project/tasks/'.$project->id.'?from='. $from .'&to='. $to .'&assignee='. $allassignee .'&status='.$data['id'].'&priority='. $allpriority .'&btn=') }}"><span style="color: {{ $color }} ;" id="{{str_replace(' ', '', $data['name'])}}">{{ $data['total_status'] }}</span></a><br>
                    @if($allassignee != Auth::user()->id)
                      <span>{{ __('Assign to me') }}:  <a style="color: {{ $color }} ;" href="{{ url('project/tasks/'.$project->id.'?from='. $from .'&to='. $to .'&assignee='. Auth::user()->id .'&status='.$data['id'].'&priority='. $allpriority .'&btn=') }}"><b>{{ $assign_to_me[$key] }}</b></a></span>
                    @endif
              </div>
            </div>
        @endforeach
      </div>
    </div>

    <div class="col-sm-12">
      <div class="table-responsive">
        {!! $dataTable->table(['class' => 'table table-striped table-hover dt-responsive', 'width' => '100%', 'cellspacing' => '0']) !!}
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
  </div>
@endsection

@section('projectJS')

<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>

<script src="{{ asset('public/datta-able/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
{!! $dataTable->scripts() !!}
@include('admin.task.details_script')
<script>
  'use strict';
  var projectId = "{{ $project->id }}";
  var startDate = "{!! isset($from) ? $from : 'undefined' !!}";
  var endDate = "{!! isset($to) ? $to : 'undefined' !!}";
</script>
<script src="{{ asset('public/dist/js/custom/project.min.js') }}"></script>
@endsection
