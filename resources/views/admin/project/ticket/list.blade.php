@extends('admin.project.main')

@section('projectCSS')
<link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
@endsection

@section('add-title')
  @if(Helpers::has_permission(Auth::user()->id, 'add_ticket'))
    <a href="{{ url('ticket/add?project_id='.$project->id) }}" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Ticket') }}</a>
  @endif
@endsection

@section('projectContent')
<div id="project-ticket-list-container">
  <form class="form-horizontal" action="{{ url('project/tickets/'.$project->id) }}" method="GET">
    <input class="form-control" id="startfrom" type="hidden" name="from" value="{{ isset($from) ? $from : '' }}">
    <input class="form-control" id="endto" type="hidden" name="to" value="{{ isset($to) ? $to : '' }}">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div class="row mt-2">
        <div class="ticket-filter col-md-12 col-xl-4 col-lg-4 col-sm-12 col-xs-12 mb-2">
          <div class="input-group">
            <button type="button" class="form-control" id="daterange-btn">
              <span class="float-left">
                <i class="fa fa-calendar"></i>  {{  __('Pick a date range')  }}
              </span>
              <i class="fa fa-caret-down float-right pt-1"></i>
            </button>
          </div>          
        </div>           
        <div class="ticket-filter col-md-12 col-xl-4 col-lg-4 col-sm-12 col-xs-12 mb-2">
          <select class="form-control select2" name="department_id" id="department_id">
            <option value="">{{ __('All Department') }}</option>
            @foreach($departments as $data)
            <option value="{{ $data->id }}" {{$data->id == $alldepartment ? ' selected="selected"' : ''}}>{{ $data->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="ticket-filter col-md-12 col-xl-3 col-lg-3 col-sm-12 col-xs-12 mb-2">
          <select class="form-control select2" name="status" id="status">
              <option value="">{{ __('All Status') }}</option>
              @foreach($status as $data)
                <option value="{{$data->id}}" {{$data->id == $allstatus ? ' selected="selected"' : ''}}>{{$data->name}}</option>
              @endforeach
          </select>
        </div>
        <div class="col-md-1 col-lg-1 col-sm-12 col-xs-12 pr-md-1">
          <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small mt-0 mr-0">{{ __('Go') }}</button>                
        </div>
      </div>
    </div>
  </form>
  <!--Filtering Box End -->
  <div class="row mt-2">

    @php
      $assignee = isset($allassignee) ? $allassignee : '';
    @endphp
    @foreach($summary as $data)
      @php
       if ($data->color) {
            $color = $data->color;
        } else {
            $color  = '008000';
        }
      @endphp
        <div class="col-md-2 ticket-summary text-white m-l-15 pr-md-0" id="ticket-name">
          <div class="text-center p-10 mt-1 status-border" style="color: {{ $color }} ">
              <span class="f-w-700">{{ $data->name }}</span><br>
              <a href="{{ url('project/tickets/'.$project->id.'?from=&to=&project=&assignee=&status='.$data->id.'&btn=') }}" style="color: {{ $color }}"><span class="f-20" id="{{ str_replace(' ', '', $data->name) }}">
                @if(! empty ($data->total_status))
                  {{ (int)$data->total_status }}
                @else
                  0
                @endif
              </span></a>
          </div>
        </div>
    @endforeach
  </div>

  <div class="card-block mt-1 pl-3">
    <div class="table-responsive">
      {!! $dataTable->table(['class' => 'table table-striped table-hover dt-responsive tickets', 'width' => '100%', 'cellspacing' => '0']) !!}
    </div>  
  </div>
</div>
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
<script src="{{ asset('public/dist/js/custom/status_priority.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/project.min.js') }}"></script>
@endsection