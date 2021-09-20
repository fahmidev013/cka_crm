@extends('layouts.customer_panel')
@section('css')
{{-- Select2  --}}
  <link rel="stylesheet" type="text/css" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{asset('public/dist/css/invoice-style.min.css')}}">
@endsection

@section('content')
    <div class="col-sm-12" id="cus-panel-ticket-add-container">
        <div class="card">
            <div class="card-header">
                <h5><a href="{{ url('customer-panel/support/list') }}">{{ __('Open Ticket')  }}</a></h5>
                <div class="card-header-right">
                    
                </div>
             </div>
            <div class="card-body">
                <div class="form-tabs">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active text-uppercase" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __('Ticket Information') }}</a>
                        </li>
                        <li class="nav-item"></li>
                    </ul>
                </div>

                <div class="tab-content pl-2" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <form class="form-horizontal" action="{{ url('customer-ticket/store') }}" id="ticket_form" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="customer_id" id="customer_id" value="{{ $customer->id }}">
                            <input type="hidden" name="customer_name" value="{{ $customer->first_name . ' ' . $customer->last_name }}">
                            <input type="hidden" name="customer_email" value="{{ $customer->email }}">
                            <input type="hidden" name="status_id" value="1">
                            <input type="hidden" value="{{ csrf_token() }}" name="_token" id="token">

                            <div class="col-sm-12 p-0">
                                <div class="form-group row">
                                    <label class="col-sm-2 control-label require">{{ __('Subject') }}</label>               
                                    <div class="col-sm-10">
                                        <input type="text" value="{{ old('subject') }}" class="form-control" name="subject" id="subject">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 control-label">{{ __('Project') }}</label>           
                                    <div class="col-sm-10">
                                        <select name="project_id" class="form-control select2">
                                            <option value="">{{ __('Select One')  }}</option> 
                                            @foreach($project as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 control-label require">{{ __('Message') }}</label>
                                    <div class="col-sm-10">
                                        <textarea class="ticket_message form-control" name="message" id="ticket_messages">{{ old('message') }}</textarea>
                                        <label id="ticket_messages-error" class="error" for="ticket_messages"></label>
                                    </div>
                                </div>                        
                            </div>

                            <div class="col-sm-12 p-0">  
                                <div class="row">                        
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4 control-label require">{{ __('Department') }}</label>
                                            <div class="col-sm-8">
                                                <select name="department_id"  class="form-control select2" >
                                                    <option value="">{{ __('Select One')  }}</option>
                                                    @foreach($departments as $data)
                                                        <option value="{{ $data->id }}">{{ $data->name }}</option>
                                                    @endforeach
                                                </select>
                                                <label id="department_id-error" class="error display_inline_block" for="department_id"></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4 control-label">{{ __('Priority') }}</label>
                                            <div class="col-sm-8">
                                                <select name="priority_id" class="form-control select2">
                                                    @foreach($priorities as $data)
                                                        <option value="{{ $data->id }}">{{ $data->name }}</option>
                                                    @endforeach
                                                </select>
                                                <label id="priority_id-error" class="error" for="priority_id"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 p-0">  
                                <div class="form-group row">
                                  <label class="col-sm-2 col-form-label">{{ __('File')  }}</label>
                                  <div class="col-md-10">
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
                                    <label class="col-sm-2 control-label"></label>
                                    <div class="col-sm-8">
                                        <span class="badge badge-danger">{{ __('Note')  }}!</span> {{ __('Allowed File Extensions: jpg, png, gif, docx, xls, xlsx, csv and pdf') }}
                                        </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12 m-t-15 m-15">
                                    <button class="btn btn-primary custom-btn-small" type="submit" id="btnSubmit"><i class="comment_spinner spinner fa fa-spinner fa-spin custom-btn-small display_none"></i><span id="spinnerText">{{ __('Open') }} </span></button>   
                                    <a href="{{ url('customer-panel/support/list') }}" class="btn btn-danger custom-btn-small">{{ __('Cancel') }}</a>
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
<script src="{{ asset('public/dist/js/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/datta-able/plugins/ckeditor/js/ckeditor.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{ asset('public/dist/plugins/dropzone/dropzone.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/customerpanel.min.js') }}"></script>
@endsection