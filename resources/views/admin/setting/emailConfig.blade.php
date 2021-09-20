@extends('layouts.app')
@section('css')
{{-- Select2  --}}
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
<!-- Main content -->
<div class="col-sm-12" id="emailConfig-settings-container">
  <div class="row">
    <div class="col-md-3">
      @include('layouts.includes.sub_menu')
    </div>
    <div class="col-md-9">
      <div class="card card-info">
        @if(session('errorMgs'))
        <div class="alert alert-warning fade in alert-dismissable">
          <strong>Warning!</strong> {{ session('errorMgs') }}. <a class="close" href="#" data-dismiss="alert" aria-label="close" title="close">×</a>
        </div>
        @endif
        <span id="smtp_head">
          <div class="card-header">
            <h5><a href="{{ url('item-category') }}">{{ __('General Settings') }} </a> >> {{ __('SMTP Settings') }} >> {{ __('Sendmail Setting') }}
              @if($emailConfigData)
              @if($emailConfigData->status==1 && $emailConfigData->protocol=='smtp')
              (<span class="color_green"><i class="fa fa-check" aria-hidden="true"></i>
                {{ __('Verified') }}</span>)
              @endif
              @endif
            </h5>
          </div>
        </span>
        <div class="card-body p-l-15">
          <form action="{{ url('save-email-config') }}" method="post" id="myform1" class="form-horizontal">
            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
            <div class="form-group row">
              <label class="col-sm-3 control-label require">{{ __('Email Protocol') }}</label>
              <div class="col-sm-8">
                <select class="form-control select" id="type">
                  <option value="smtp" {{ $emailConfigData && $emailConfigData->protocol == 'smtp' ? 'selected="selected"' : '' }}>{{ __('SMTP') }}</option>
                  <option value="sendmail" {{ $emailConfigData && $emailConfigData->protocol == 'sendmail' ? 'selected="selected"' : '' }}>{{ __('Send Mail') }}</option>
                </select>
              </div>
            </div>
            <!--smtp form start here-->
            <span id="smtp_form">
              <input type="hidden" name="type" value="smtp" id="type_val">
              <div class="form-group row">
                <label class="col-sm-3 control-label require">{{ __('Email Encription') }}</label>

                <div class="col-sm-8">
                  <input type="text" value="{{isset($emailConfigData->encryption) ? $emailConfigData->encryption : ''}}" class="form-control" name="encryption">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 control-label require">{{ __('SMTP Host') }}</label>

                <div class="col-sm-8">
                  <input type="text" value="{{isset($emailConfigData->smtp_host) ? $emailConfigData->smtp_host : ''}}" class="form-control" name="smtp_host">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 control-label require">{{ __('SMTP Port') }}</label>

                <div class="col-sm-8">
                  <input type="text" value="{{isset($emailConfigData->smtp_port) ? $emailConfigData->smtp_port : ''}}" class="form-control" name="smtp_port">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 control-label require">{{ __('SMTP Email') }}</label>

                <div class="col-sm-8">
                  <input type="text" value="{{isset($emailConfigData->smtp_email) ? $emailConfigData->smtp_email : ''}}" class="form-control" name="smtp_email">
                </div>
              </div>
              <div class="form-group  row">
                <label class="col-sm-3 control-label require">{{ __('From Address') }}</label>

                <div class="col-sm-8">
                  <input type="email" value="{{isset($emailConfigData->from_address) ? $emailConfigData->from_address : ''}}" class="form-control" name="from_address">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-3 control-label require">{{ __('From Name') }}</label>

                <div class="col-sm-8">
                  <input type="text" value="{{isset($emailConfigData->from_name) ? $emailConfigData->from_name : ''}}" class="form-control" name="from_name">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 control-label require">{{ __('SMTP username') }}</label>

                <div class="col-sm-8">
                  <input type="email" value="{{isset($emailConfigData->smtp_username) ? $emailConfigData->smtp_username : ''}}" class="form-control" name="smtp_username">
                </div>
              </div>
              <div class="form-group  row">
                <label class="col-sm-3 control-label require">{{ __('SMTP Password') }}</label>

                <div class="col-sm-8">
                  <input type="password" value="{{ isset($emailConfigData->smtp_password) ? 'xxxxxxxxxxxx' : '' }}" class="form-control" name="smtp_password">
                </div>
              </div>
            </span>
        </div>
        <div class="form-group row">

          <label for="btn_save" class="col-sm-0 pl-4 ml-3 control-label"></label>
          <button class="btn btn-primary custom-btn-small float-left" type="submit">{{ __('Update') }}</button>

        </div>
        </form>
      </div>
    </div>
  </div>
</div>
@include('layouts.includes.message_boxes')
@endsection

@section('js')
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/general-settings.min.js') }}"></script>
@endsection