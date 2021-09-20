@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('public/dist/plugins/fullcalendar/css/main.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection
@section('content')
<div class="col-sm-12">
  <div class="card">
    <div class="card-header">
      <h5> <a href="{{url('calendar')}}">{{ __('Calender') }}</a> </h5>
    </div>

    <div class="card-body table-border-style">
      <form class="form-horizontal mb-25" action="" method="post" id="form">
          {{ csrf_field() }}
        <div class="row">
          <div class="col-sm-9">
            <div class="form-group row mr-5">
              <div class="checkbox checkbox-success d-inline-block">
                <input <?= isset($checked) && in_array('quotation', $checked) ? 'checked' : '' ?> type="checkbox" class="view-check" name="quotation" value="1" id="quotation" status="quotation">
                <label for="quotation" class="cr">{{ __('Quotation') }}</label>
              </div>
              <div class="checkbox checkbox-success d-inline-block">
                <input <?= isset($checked) && in_array('invoice', $checked) ? 'checked' : '' ?> type="checkbox" class="view-check" name="invoice" value="1" id="invoice" status="invoice">
                <label for="invoice" class="cr">{{ __('Invoice') }}</label>
              </div>
              <div class="checkbox checkbox-success d-inline-block">
                <input <?= isset($checked) && in_array('purchase', $checked) ? 'checked' : '' ?> type="checkbox" class="view-check" name="purchase" value="1" id="purchase" status="purchase">
                <label for="purchase" class="cr">{{ __('Purchase') }}</label>
              </div>
              <div class="checkbox checkbox-success d-inline-block">
                <input <?= isset($checked) && in_array('project', $checked) ? 'checked' : '' ?> type="checkbox" class="view-check" name="project" value="1" id="project" status="project">
                <label for="project" class="cr">{{ __('Projects') }}</label>
              </div>
              <div class="checkbox checkbox-success d-inline-block">
                <input <?= isset($checked) && in_array('task', $checked) ? 'checked' : '' ?> type="checkbox" class="view-check" name="task" value="1" id="task" status="task">
                <label for="task" class="cr">{{ __('Tasks') }}</label>
              </div>
              <div class="checkbox checkbox-success d-inline-block">
                <input <?= isset($checked) && in_array('custom', $checked) ? 'checked' : '' ?> type="checkbox" class="view-check" name="custom" value="1" id="custom" status="custom">
                <label for="custom" class="cr">{{ __('Event') }}</label>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
     
    <div class="card-footer">
      <div id='calendar'></div>
      <div id="addEventModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        <div class="modal-dialog" role="document">
          <form id="eventForm" action="{{ url('calendar/event-store') }}" class="form-horizontal" method="post">
              {{csrf_field()}}
              <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="gridSystemModalLabel">{{ __('Add Event') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>                                
                </div>
                <div class="modal-body">                                
                  <div class="form-group row">
                    <label class="col-md-3 control-label require">{{ __('Event Title') }}</label>
                    <div class="col-md-8">
                      <input id="evtTitle" type="text" class="form-control" name="title"placeholder="{{ __('Event Title') }}">
                    </div>
                  </div>
                   <div class="form-group row">
                      <label class="col-md-3 control-label">{{ __('Description') }}</label>
                      <div class="col-md-8">
                          <textarea id="evtDes" name="description" class="form-control" placeholder="{{ __('Event Description') }}"> 
                          </textarea>
                      </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-3 control-label require">{{ __('Start Date') }}</label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="start_date" id="startDate">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-3 control-label">{{ __('End Date') }}</label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="end_date" id="endDate">
                      <label for="endDate" generated="true" class="error" id="end_date-error"></label>
                    </div>
                  </div>           
                  <div class="form-group row">
                    <input type="hidden" id="eventColor" value="" name="event_color">
                    <label class="col-md-3 control-label">{{ __('Color') }}</label>
                    <div class="col-md-8 picker-wrapper">
                      <div class="display_inline_flex">
                        <button type="button" class="btn btn-default mr-10">{{ __('Select Color') }}</button>
                        <div class="color-picker-styles" id="showColor"></div>
                      </div>
                      <div class="color-picker"></div>
                    </div>
                  </div>
                  <input id="eventId" type="hidden" name="eventId">                     
                </div>

                <div class="modal-footer">
                  @if(Helpers::has_permission(Auth::user()->id, 'delete_calendar_event'))
                    <button class="btn btn-danger custom-btn-small float-right mr-5 display_none" type="button" id="delete">{{ __('Delete') }}</button>
                  @endif

                  @if(Helpers::has_permission(Auth::user()->id, 'add_calendar_event') || Helpers::has_permission(Auth::user()->id, 'edit_calendar_event'))
                    <button class="btn btn-primary custom-btn-small float-right mr-5" type="submit" id="submit">{{ __('Submit') }}</button>
                  @endif
                </div>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
    <script src="{{ asset('public/dist/plugins/fullcalendar/js/main.min.js') }}"></script>
    <script src="{{ asset('public/dist/js/piklor.min.js') }}"></script>
    <script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('public/dist/js/moment.min.js')}}"></script>
    <script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>
    <script>
    'use strict';
    var initialDate = '{{ $initialDate }}';
    var calendarEvents = '{!! $information !!}';
    </script>
    <script src="{{ asset('public/dist/js/custom/calendar.min.js') }}"></script>
@endsection
