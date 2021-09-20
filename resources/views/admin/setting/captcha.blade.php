@extends('layouts.app')
@section('css')
{{-- Select2  --}}
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
<!-- Main content -->
<div class="col-sm-12" id="captcha-settings-container">
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
          <div class="card-header">
            <h5><a href="{{ url('item-category') }}">{{ __('General Settings') }} </a> >> {{ __('Captcha Setup') }}
              
            </h5>
          </div>
        <div class="card-body p-l-15">
          <form action="{{ url('captcha/setup') }}" method="post" id="myform1" class="form-horizontal">
            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
            <div class="form-group row">
              <label class="col-sm-3 control-label require">{{ __('Site Key') }}</label>
              <div class="col-sm-8">
                <input type="text" value="{{ isset($captcha->site_key) ? $captcha->site_key : ''}}" class="form-control" name="site_key">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 control-label require">{{ __('Secret Key') }}</label>
              <div class="col-sm-8">
                <input type="text" value="{{ isset($captcha->secret_key) ? $captcha->secret_key : ''}}" class="form-control" name="secret_key">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 control-label require">{{ __('Site Verify URL') }}</label>
              <div class="col-sm-8">
                <input type="text" value="{{ isset($captcha->site_verify_url) ? $captcha->site_verify_url : ''}}" class="form-control" name="site_verify_url">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 control-label require">{{ __('Plugin URL') }}</label>
              <div class="col-sm-8">
                <input type="text" value="{{ isset($captcha->plugin_url) ? $captcha->plugin_url : ''}}" class="form-control" name="plugin_url">
              </div>
            </div>

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