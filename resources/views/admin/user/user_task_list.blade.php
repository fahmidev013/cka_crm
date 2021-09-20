@extends('layouts.app')
@section('css')
  {{-- Datatable --}}
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
  <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/css/task-list.min.css') }}">
  <link rel="stylesheet" href="{{asset('public/dist/css/task-design.min.css?v=2') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/plugins/lightbox/css/lightbox.min.css') }}">
@endsection
@section('content')

<div class="col-sm-12" id="user-task-container">
  <div class="card">
    <div class="card-header">
      <h5><a href="{{ url('users') }}">{{ __('Users') }}</a>  >> {{ __('Tasks') }} >> {{ isset($user->full_name) ? $user->full_name : '' }}</h5>
      <div class="card-header-right">

      </div>
    </div>
    <div class="card-body p-0">
      @include('layouts.includes.user_menu')
    </div>
    <div class="col-sm-12 mb-2">
      <form class="form-horizontal" action="{{ url('user/task-list/'. $user_id) }}" method="GET">
        <input class="form-control" id="startfrom" type="hidden" name="from" value="<?= isset($from) ? $from : '' ?>">
        <input class="form-control" id="endto" type="hidden" name="to" value="<?= isset($to) ? $to : '' ?>">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 ml-2 margin-btm-1p">
          <div class="row mt-3">
            <div class="col-md-4 col-sm-4 col-xs-12 mb-2">
              <div class="input-group">
                <button type="button" class="form-control" id="daterange-btn">
                  <span class="float-left">
                    <i class="fa fa-calendar"></i>  {{ __('Date Range') }}
                  </span>
                  <i class="fa fa-caret-down float-right pt-1"></i>
                </button>
              </div>
            </div>

            <div class="col-md-4 col-sm-3 col-xs-11 mb-2">
              <select class="form-control js-example-basic-single ddStatus" name="status" id="status">
                <option value="">{{ __('All Status') }}</option>
                @foreach($task_status_all as $data)
                  <option value="{{ $data->id }}" {{$data->id == $allstatus ? "selected": ''}}>{{ $data->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-11 mb-2">
              <select class="form-control js-example-basic-single" name="priority" id="priority">
                <option value="">{{ __('All priorities') }}</option>
                @if(!empty($priorities))
                  @foreach($priorities as $priority)
                    <option value="{{ $priority->id }}" {{ $priority->id == $allpriority ? "selected" : '' }}>{{ $priority->name }}</option>
                  @endforeach
                @endif
              </select>
            </div>
            <div class="col-md-1 col-sm-1 col-xs-12 p-md-0">
              <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small push-sm-right mt-0 mr-0">{{ __('Go') }}</button>
            </div>

          </div>
        </div>
      </form>
     <div class="col-md-12 mb-3">
        <div class="row mt-1">
          @foreach($summary as $key => $data)
          @php
           if ($data['color']) {
                $color = $data['color'];
            } else {
                $color  = '008000';
            }
          @endphp
          <div class="col-md-3 col-xl-3 col-lg-3 ticket-summary text-white m-l-25 pr-md-0 pl-0">
            <div class="text-center p-10 mt-1 status-border">
                <span class="f-14 f-w-600" style="color: {{ $color }} ">{{ $data['name'] }}</span><br>
                <a class="f-20" href="{{ url('user/task-list/' . $user_id . '?from='. $from .'&to='. $to .'&project=&assignee=&status='.$data['id'].'&btn=') }}"><span style="color: {{ $color }};" id="{{str_replace(' ', '', $data['name'])}}">{{ $data['total_status'] }}</span></a><br>

            </div>
          </div>
          @endforeach
        </div>
      </div>
      <div class="card-block">
        <div class="table-responsive">
          {!! $dataTable->table(['class' => 'table table-striped table-hover dt-responsive', 'width' => '100%', 'cellspacing' => '0']) !!}
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmDeleteLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary custom-btn-small" data-dismiss="modal">{{ __('Close') }}</button>
          <button type="button" id="confirmDeleteSubmitBtn" data-task="" class="btn btn-danger custom-btn-small">{{ __('Submit') }}</button>
          <span class="ajax-loading"></span>
        </div>
      </div>
    </div>
  </div>
</div>

@include('admin.task.details')

@endsection

@section('js')
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>

{!! $dataTable->scripts() !!}
@include('admin.task.details_script')
<script type="text/javascript">
  "use strict";
  var userId      = "{{ $user_id }}";
  var startDate   = '{!! $from ? $from : '' !!}';
  var endDate     = '{!! $to ? $to : '' !!}';
  var sessionDate = '{!! $date_format_type !!}';
</script>
<script src="{{ asset('public/dist/js/custom/user.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/dist/js/html5lightbox/html5lightbox.js?v=1.0') }}"></script>
@endsection
