@extends('layouts.list-layout')

@section('content')
<div id="ticket-list-container">
  <div class="col-sm-12" id="card-with-header-buttons">
    <div class="card">
      <div class="card-header" id="headerDiv">
        <h5><a href="{{ url('ticket/list') }}">{{ __('Ticket') }}</a></h5>
        <div class="card-header-right">
          @if(Helpers::has_permission(Auth::user()->id, 'add_ticket'))
            <a href="{{ url('ticket/add') }}" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Ticket') }}</a>
          @endif

        </div>
      </div>
      <div class="card-body p-0">
        <div class="col-sm-12 m-t-10">
          <form class="form-horizontal" action="{{ url('ticket/list') }}" method="GET" accept-charset="UTF-8">
            <input class="form-control" id="startfrom" type="hidden" name="from" value="<?= isset($from) ? $from : '' ?>">
            <input class="form-control" id="endto" type="hidden" name="to" value="<?= isset($to) ? $to : '' ?>">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 m-l-10">
              <div class="row mt-3">
                <div class="ticket-filter col-md-12 col-xl-4 col-lg-4 col-sm-12 col-xs-12 mb-2">
                  <div class="input-group">
                    <button type="button" class="form-control" id="daterange-btn">
                      <span class="float-left">
                        <i class="fa fa-calendar"></i> Date range picker
                      </span>
                      <i class="fa fa-caret-down float-right pt-1"></i>
                    </button>
                  </div>
                </div>
                 <div class="ticket-filter col-md-12 col-xl-3 col-lg-2 col-sm-12 col-xs-12 mb-2">
                  <select class="form-control select2" name="assignee" id="assignee">
                    <option value="">{{ __('All Assignee') }}</option>
                    @foreach($assignees as $assignee)
                      <option value="{{ $assignee->assigned_member_id }}" {{ $assignee->assigned_member_id == $allassignee ? ' selected="selected"' : ''}}>{{ $assignee->assignedMember->full_name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="ticket-filter col-md-2 col-xl-3 col-lg-3 col-sm-12 col-xs-12 mb-2">
                  <select class="form-control select2" name="project" id="project">
                      <option value="">{{ __('All Project') }}</option>
                    @foreach($projects as $data)
                      <option value="{{$data->id}}" {{$data->id == $allproject ? ' selected="selected"' : ''}}>{{$data->name}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="ticket-filter col-md-12 col-xl-2 col-lg-2 col-sm-12 col-xs-12 mb-2">
                  <select class="form-control select2" name="department_id" id="department_id">
                    <option value="">{{ __('All Department') }}</option>
                    @foreach($departments as $data)
                    <option value="{{ $data->id }}" {{$data->id == $alldepartment ? ' selected="selected"' : ''}}>{{ $data->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="ticket-filter col-md-12 col-xl-2 col-lg-2 col-sm-12 col-xs-12 mb-2 mt-2">
                  <select class="form-control select2" name="status" id="status">
                      <option value="">{{ __('All Status') }}</option>
                      @foreach($status as $data)
                        <option value="{{$data->id}}" {{$data->id == $allstatus ? ' selected="selected"' : ''}}>{{$data->name}}</option>
                      @endforeach
                  </select>
                </div>
                <div class="col-md-1 col-lg-1 col-sm-12 col-xs-12 p-md-0 mt-2">
                  <button type="submit" name="btn" title="{{ __('Click to filter') }}" class="btn btn-primary custom-btn-small mt-0 mr-0">{{ __('Go') }}</button>
                </div>
              </div>
            </div>
          </form>
          <div class="row mt-2">
            @php
              $fromDate = isset($from) ? $from : '';
              $toDate   = isset($to) ? $to : '';
              $project = isset($allproject)?$allproject:'';
              $department = isset($alldepartment)?$alldepartment:'';
            @endphp
            @foreach($summary as $counter => $data)
              @php
               if ($data->color) {
                    $color = $data->color;
                } else {
                    $color  = '008000';
                }
              @endphp
                <div class="col-md-2 ticket-summary text-white m-l-25 pr-md-0" id="ticket-name">
                  <div class="text-center p-10 mt-1 status-border" style="color: {{ $color }} ">
                    <span class="f-w-700">{{ $data->name }}</span><br>
                    <a href="{{ url('ticket/list?from='.$fromDate.'&to='.$toDate.'&project='.$project.'&department='.$department.'&status='.$data->id.'&btn=') }}" style="color: {{ $color }}"><span class="f-20" id="{{ str_replace(' ', '', $data->name) }}">{{ $data->total_status }}</span></a>
                  </div>
                </div>
            @endforeach
          </div>
          <div class="card-block mt-1">
            <div class="table-responsive">
              {!! $dataTable->table(['class' => 'table table-striped table-hover dt-responsive tickets ticket-list-styles', 'width' => '100%', 'cellspacing' => '0']) !!}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
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
@section('js')
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
{!! $dataTable->scripts() !!}
<script type="text/javascript">
  'use strict';
  var startDate = "{!! isset($from) ? $from : 'undefined' !!}";
  var endDate   = "{!! isset($to) ? $to : 'undefined' !!}";
</script>
<script src="{{ asset('public/dist/js/custom/ticket.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/status_priority.min.js') }}"></script>
@endsection
