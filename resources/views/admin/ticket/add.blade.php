@extends('layouts.app')
@section('css')
{{-- select2 css --}}
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('public/dist/css/invoice-style.min.css')}}">
@endsection

@section('content')
  <!-- Main content -->
<div class="col-sm-12" id="add-ticket-container">
  <div class="card user-list">
    <div class="card-header">
      <h5><a href="{{ url('ticket/list') }}">{{ __('Tickets') }}</a> >> {{ __('New Ticket') }}</h5>
      <div class="card-header-right">
        
      </div>
    </div>
    <div class="card-block">
      <div class="form-tabs">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active text-uppercase" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __('Ticket Information') }}</a>
          </li>
          <li class="nav-item"></li>
        </ul>
      </div>
      <div class="tab-content pl-4" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
          <form id="add_ticket_form" class="form-horizontal" action="{{url('ticket/store')}}" method="post" enctype="multipart/form-data">
            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label require">{{ __('Subject') }}</label>
                  <div class="col-sm-8">
                    <input type="text" value="{{ old('subject') }}" class="form-control" name="subject" id="subject">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label require">{{ __('Project Name') }}</label>
                  <div class="col-sm-8">
                    @php 
                      $projectId = isset($getProject) ? (int) $getProject : "";
                    @endphp
                    <select name="project_id" class="form-control select2" id="project_id">
                      <option value="">{{ __('Select One') }}</option>
                      @foreach($projects as $data)
                        <option <?= $data->id == $projectId ? 'selected' : '' ?> value="{{$data->id}}">{{$data->name}}</option>
                      @endforeach
                    </select>
                    <input type="hidden" name="projectId" value="{{ $projectId }}">
                    <label id="project_id-error" class="error display_inline_block" for="project_id"></label>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label require">{{ __('Message') }}</label>
                  <div class="col-sm-8">
                    <textarea class="ticket_message form-control" name="message" id="ticket_messages">{{ old('message') }}</textarea>
                    <label id="ticket_messages-error" class="error" for="ticket_messages"></label>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label require">{{ __('Assignee') }}</label>
                  <div class="col-sm-3">
                    <select name="assign_id" class="form-control select2">
                      <option value="">{{ __('Select One') }}</option>
                      @foreach($assignees as $data)
                        <option value="{{ $data->id }}">{{ $data->full_name }}</option>
                      @endforeach
                    </select>
                    <label id="assign_id-error" class="error display_inline_block" for="assign_id"></label>
                  </div>
                  <label class="col-sm-2 col-form-label pr-0">{{ __('Status') }}</label>
                  <div class="col-sm-3">
                    <select name="status_id" class="form-control select2">
                      @foreach($ticketStatus as $data)
                        <option value="{{$data->id}}">{{$data->name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <!--department-->
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label require">{{ __('Department') }}</label>
                  <div class="col-sm-3">
                    <select name="department_id" class="form-control select2">
                      <option value="">{{ __('Select One') }}</option>
                      @foreach($departments as $data)
                        <option value="{{$data->id}}">{{$data->name}}</option>
                      @endforeach
                    </select>
                    <label id="department_id-error" class="error display_inline_block" for="department_id"></label>
                  </div>
                  <label class="col-sm-2 col-form-label pr-0 require">{{ __('Priority') }}</label>
                  <div class="col-sm-3">
                    <select name="priority_id" class="form-control select2">
                      <option value="">{{ __('Select One') }}</option>
                      @foreach($priorities as $data)
                        <option value="{{$data->id}}">{{$data->name}}</option>
                      @endforeach
                    </select>
                    <label id="priority_id-error" class="error display_inline_block" for="priority_id"></label>
                  </div>
                </div>
                <div class="form-group row select-customer-div">
                  <label class="col-sm-2 col-form-label require">{{ __('Customer') }}</label>
                  <div class="col-sm-3">
                    <div class="ticket-customer">
                        @php 
                          $customerId = isset($getCustomer) ? (int) $getCustomer->customer_id : "";
                        @endphp
                      <select id="customer_id" name="customer_id" class="form-control select2">
                        <option value="">{{ __('Select One') }}</option>
                        @foreach($customers as $data)
                          <option <?= $data->id == $customerId ? 'selected' : '' ?> data-name="{{$data->first_name.' '.$data->last_name}}" data-email="{{$data->email}}" value="{{$data->id}}">{{$data->first_name.' '.$data->last_name}}</option>
                        @endforeach
                      </select>
                      <input type="hidden" name="customerId" value="{{ $customerId }}">
                      <label id="customer_id-error" class="error display_inline_block" for="customer_id"></label>
                    </div>
                  </div>
                </div>
                <div class="form-group row customer-info-div">
                  <label class="col-sm-2 col-form-label">{{ __('To') }}</label>
                  <div class="col-sm-3">
                    <input id="assign_name" type="text" class="form-control" name="to" readonly>
                  </div>
                  <label class="col-sm-2 col-form-label pr-0">{{ __('Email') }}</label>
                  <div class="col-sm-3">
                    <input id="assign_email" type="text" class="form-control" readonly name="email">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label">{{ __('File') }}</label>
                  <div class="col-md-8">
                    <div class="dropzone-attachments" id="reply-attachment">
                      <div class="event-attachments">
                        <div class="add-attachments"><i class="fa fa-plus"></i></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-2"></div>
                  <div class="ml-3" id="uploader-text"></div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 col-form-label"></label>
                  <div class="col-md-8">
                    <span class="badge badge-danger">{{ __('Note') }}!</span> {{ __('Allowed File Extensions: jpg, png, gif, docx, xls, xlsx, csv and pdf') }}
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-8 m-t-15">
                    <button class="btn btn-primary custom-btn-small" type="submit" id="add_ticket_btn"><i class="comment_spinner spinner fa fa-spinner fa-spin custom-btn-small display_none"></i><span id="spinnerText">{{ __('Submit') }} </span></button>
                    <a href="{{ url('ticket/list') }}" class="btn btn-danger custom-btn-small">{{ __('Cancel') }}</a>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="flag" id="flag" value="no">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('js')

<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{ asset('public/dist/plugins/dropzone/dropzone.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/datta-able/plugins/ckeditor/js/ckeditor.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script>
  'use strict';
  var projectId = "{{ !empty($getProject) ? $getProject : '' }}";
</script>
<script src="{{ asset('public/dist/js/custom/ticket.min.js') }}"></script>
@endsection