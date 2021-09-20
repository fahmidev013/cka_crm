@extends('layouts.app')
@section('css')
{{-- DataTable --}}
  {{-- Select2  --}}
  <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
<!-- Main content -->
  <div class="col-sm-12" id="smsConfig-settings-container">
    <div class="row">
      <div class="col-md-3">
        @include('layouts.includes.sub_menu')
      </div>
      <div class="col-md-9">
        <div class="card card-info">
          <div class="card-header">
            <h5><a href="{{ url('item-category') }}">{{ __('General Settings') }} </a> >> {{ __('SMS Settings') }}</h5>
            <div class="card-header-right">
              
            </div>
          </div> 
          <div class="card-body p-l-15">
            <div class="accordion" id="accordionExample">
              <div class="card">
                <div class="card-header pl-0" id="headingOne">
                  <button class="btn btn-link text-btn" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Twilio
                  </button>
                </div>
                  <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body">
                  <span id="smtp_form">
                    <form action="{{ url('sms/setup') }}" method="post" id="myform1" class="form-horizontal pl-0">
                      {!! csrf_field() !!}
                      <input type="hidden" name="type" value="twilio">
                      <div class="form-group row">
                        <label class="col-sm-3 control-label require align-left">{{ __('Default') }}</label>
                        <div class="col-sm-8">
                          <select class="select form-control" name="default" id="default">
                              <option value="">{{ __('Select One') }}</option>
                              <option value='Yes' {{ isset($smsConfig->default) && $smsConfig->default == 'Yes' ? 'selected' : "" }} >{{ __('Yes') }} </option>
                              <option value='No' {{ isset($smsConfig->default) && $smsConfig->default == 'No' ? 'selected' : "" }} >{{ __('No') }} </option>
                          </select>
                        <label for="default" generated="true" class="error"></label>
                        </div>
                      </div>
                        <div class="clearfix"></div>

                        <div class="form-group row">
                          <label class="col-sm-3 control-label require align-left">{{ __('Status') }}</label>

                          <div class="col-sm-8">
                            <select class="select form-control" name="status" id="status">
                              <option value="">{{ __('Select One') }}</option>
                              <option value='Active' {{ isset($smsConfig->status) && $smsConfig->status == 'Active' ? 'selected':""}}>{{ __('Active') }}</option>
                              <option value='Inactive' {{ isset($smsConfig->status) && $smsConfig->status== 'Inactive' ? 'selected':""}}>{{ __('Inactive') }}</option>
                            </select>
                          <label for="status" generated="true" class="error"></label>
                          </div>
                        </div>
                        
                        <div class="clearfix"></div>
                        <div class="form-group row">
                          <label class="col-sm-3 control-label require align-left">{{ __('ACCOUNT SID') }}</label>

                          <div class="col-sm-8">
                            <input type="text" value="{{ isset($smsConfig->key) && !empty($smsConfig->key) ? $smsConfig->key : '' }}" class="form-control" name="key">
                          </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="form-group row">
                          <label class="col-sm-3 control-label require align-left">{{ __('AUTH TOKEN') }}</label>
                          <div class="col-sm-8">
                            <input type="text" value="{{ isset($smsConfig->secretkey) && !empty(isset($smsConfig->secretkey)) ? $smsConfig->secretkey : '' }}" class="form-control" name="secret_key">
                          </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="form-group row">
                          <label class="col-sm-3 control-label require align-left">{{ __('Default Number') }}</label>
                          <div class="col-sm-8">
                            <input type="text" value="{{ isset($smsConfig->default_number) && !empty($smsConfig->default_number) ? $smsConfig->default_number : '' }}" class="form-control" name="number">
                          </div>
                        </div>

                        <div class="form-group row pt-1">
                          <label for="btn_save" class="col-sm-0 pl-2 ml-2 control-label"></label>
                              <button type="submit" class="btn btn-primary custom-btn-small float-left">{{ __('Update') }}</button>
                        </div>

                          </form>
                        </span>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('js')
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/general-settings.min.js') }}"></script>
@endsection