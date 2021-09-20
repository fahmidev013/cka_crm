@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('public/dist/css/project.min.css')}}">
@endsection
@section('content')

<!-- Main content -->
<div class="col-sm-12">
  <div class="card user-list">
    <div class="card-header">
      <h5><a href="{{ url('project/list') }}">{{ __('Projects')  }}</a> >> {{ __('New Project')  }}</h5>
      <div class="card-header-right">
        
      </div>
    </div>
    <div class="card-block" id="project-add-container">      
      <!-- [ Tabs ] start -->
      <div class="form-tabs">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link text-uppercase active" id="app_project-tab" data-toggle="tab" href="#app_project" role="tab" aria-controls="app_project" aria-selected="true">{{ __('Project Information')  }}</a>
          </li>
          
        </ul>
      </div>
      <!-- [ Tabs ] end -->
        <form class="form-horizontal" method="post" action="{{ url('project/store') }}" id="project_from">
          <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="app_project" role="tabpanel" aria-labelledby="app_project-tab">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group row rowMb-0 rowMbFireTab-0">
                    <label class="col-sm-2 control-label require">{{ __('Project Name') }}</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" name="project_name" placeholder="{{ __('Please enter project name') }}">
                      <label for="project_name" generated="true" class="error display_inline_block"></label>
                    </div>
                  </div>
      
                  <div class="form-group row rowMbFireTab-0 rowMb-1rem">
                    <label class="col-sm-2 control-label require">{{ __('Project Type')  }}</label>
                    <div class="col-sm-8">
                      <select id="project_type" name="project_type" class="form-control select2">
                        <option value="">{{ __('Select Type') }}</option>
                        <option value="customer">{{ __('Customer') }}</option>
                        <option value="product">{{ __('Product') }}</option>
                        <option value="in_house">{{ __('In House') }}</option>
                      </select>
                      <label for="project_type" id="project_type_error" generated="true" class="error display_inline_block"></label>
                    </div>
                  </div>
                  
                  <div class="form-group row cusDiv rowMbFireTab-0 rowMbLg-0">
                    <label class="col-sm-2 control-label">{{ __('Customers')  }}</label>
                    <div class="col-sm-8">
                      <select id="customer_id" name="customer_id" class="form-control select2">
                        @foreach($customers as $data)
                          <option data-name="{{ $data->name }}" data-email="{{ $data->email }}" value="{{ $data->id }}">{{ $data->first_name .' '. $data->last_name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                
                  <div class="form-group row rowMb-0 rowMbFireTab-0 rowMbLg-0" id="charge-status">
                    <label class="col-sm-2 control-label">{{ __('Charge Type')  }} </label>
                    <div class="col-sm-3">
                      <select id="charge_type" name="charge_type" class="form-control select2">
                        <option value="1">{{ __('Fixed Rate')  }} </option>
                        <option value="2">{{ __('Project Hour')  }} </option>
                        <option value="3">{{ __('Task Hour')  }} </option>
                      </select>
                    </div>
                    <label class="col-sm-2 control-label require">{{ __('Status')  }}</label>
                    <div class="col-sm-3">
                      <select id="status" name="status" class="form-control select2">
                        <option value="">{{ __('Select Status') }}</option>
                        @foreach($projectStatus as $status)
                          <option value="{{$status->id}}">{{$status->name}}</option>
                        @endforeach
                      </select>
                      <label for="status" id="status_error" generated="true" class="error display_inline_block"></label>
                    </div>
                  </div>

                  <div class="form-group row" id="total_cost_div">
                    <label class="col-sm-2 control-label require">{{ __('Total Cost')  }}</label>
                    <div class="col-sm-8">
                      <input type="text" name="total_cost" id="total_cost" class="form-control positive-float-number" placeholder="{{ __('Please enter total project cost') }}">
                    </div>
                  </div>
                  <div class="form-group row" id="rate_per_hour_div">
                    <label class="col-sm-2 control-label require">{{ __('Rate Per Hour')  }}</label>
                    <div class="col-sm-8">
                      <input type="text" name="rate_per_hour" id="rate_per_hour" class="form-control positive-float-number" placeholder="{{ __('Rate Per Hour')  }}">
                    </div>
                  </div>
                  <div class="form-group row rowMbFireTab-0 rowMb-0p">
                    <label class="col-sm-2 control-label">{{ __('Approximate Hour')  }}</label>
                    <div class="col-sm-3">
                      <input type="text" name="project_hours" class="form-control positive-float-number" placeholder="{{ __('Approximate Hour')  }}">
                    </div>
                    <label class="col-sm-2 control-label require">{{ __('Members')  }}</label>
                    <div class="col-sm-3 small-margin-2">
                      <select id="members" name="members[]" class="form-control select2" multiple="multiple">
                        @foreach($users as $data)
                          <option value="{{$data->id}}">{{ $data->full_name }}</option>
                        @endforeach
                      </select>
                      <label for="members" id="members_error" generated="true" class="error display_inline_block"></label>
                    </div>
                  </div>
                 
                  <div class="form-group row">
                    <label class="col-sm-2 control-label require">{{ __('Start Date')  }}</label>
                    <div class="col-sm-3">
                      <input type="text" id="startDate" name="start_date" class="form-control">
                    </div>
                    <label class="col-sm-2 control-label">{{ __('End Date')  }}</label>
                    <div class="col-sm-3">
                      <input type="text" id="endDate" name="end_date" class="form-control">
                      <label for="end_date" id="endDate-error" generated="true" class="error display_inline_block"></label>
                      
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 control-label">{{ __('Tags') }}</label>
                    <div class="col-sm-8">
                      <select class="form-control" id="project_tag" name="tags[]" multiple="multiple">
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 control-label">{{ __('Project Details')  }}</label>
                    <div class="col-sm-8">
                      <textarea name="project_details" class="text-editor form-control"></textarea>
                    </div>
                  </div>

                  <div class="form-group row display_none" id="emailCheckboxDiv">
                    <div class="col-sm-2"></div>
                    <div class="checkbox d-inline col-sm-8">
                        <input type="checkbox" class="customerCheck" name="checkbox" value="1" id="checkbox-2">
                        <label for="checkbox-2" class="cr">{{ __('Send Project Create Email To Customer') }}</label>
                    </div>
                  </div>

                </div>
              </div>
            </div>
            <div class="col-sm-8 px-0">
              <button class="btn btn-primary custom-btn-small" type="button" id="btnSubmit"><i class="comment_spinner spinner fa fa-spinner fa-spin custom-btn-small display_none"></i><span id="spinnerText">{{ __('Submit') }} </span></button>   
              <a href="{{ url('project/list') }}" class="btn btn-danger custom-btn-small">{{ __('Cancel')  }}</a>
            </div>  
          </div>
        </form>
    </div>
  </div>
</div>
</div>
@endsection
@section('js')
<script src="{{ asset('public/dist/js/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{ asset('public/datta-able/plugins/ckeditor/js/ckeditor.js') }}"></script>
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script type="text/javascript">
  'use strict';
  var taskExist = "";
  </script>
<script src="{{ asset('public/dist/js/custom/project.min.js') }}"></script>
@endsection