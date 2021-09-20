@extends('layouts.app')
@section('css')
  {{-- Select2  --}}
  <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
<!-- Main content -->
  <div class="col-sm-12" id="urlConfig-settings-container">
    <div class="row">
      <div class="col-md-3">
        @include('layouts.includes.sub_menu')
      </div>
      <div class="col-md-9">
        <div class="card card-info">
          <div class="card-header">
            <h5><a href="{{ url('item-category') }}">{{ __('General Settings') }} </a> >> {{ __('URL Shortner Settings') }}
            </h5>
            <div class="card-header-right inline-block" id="cardRightButton">
              <a href="#collapseExample" class="btn btn-outline-primary custom-btn-small" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="true" aria-controls="collapseExample">{{ __('Generate Short URL') }}</a>
            </div>
          </div> 
          <div class="card-body pl-0">
              <div class="collapse" id="collapseExample">
                <div class="row">
                  <div class="col-12">
                    <form method="post" id="url_shortner_form" class="form-horizontal p-l-30">
                      {!! csrf_field() !!}
                      <div class="form-group row">
                        <label class="col-sm-2 control-label require">{{ __('Long URL') }}</label>

                        <div class="col-sm-6">
                          <input type="text" class="form-control" name="long_url" placeholder="Enter your long URL">
                        <label for="long_url" generated="true" class="error"></label>
                        </div>
                      </div>
                        
                        <div class="form-group row display_none" id="showShortUrl">
                          <label class="col-sm-3 control-label">{{ __('Short URL') }}</label>
                          <div class="col-sm-6">
                            <div class="form-group">
                              <a target="_blank" id="generatedUrlHref"><span class="text-success text-right" id="generatedUrl"></span></a>
                            </div>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="btn_save" class="col-sm-0 pl-2 ml-2 control-label"></label>
                              <button type="submit" class="btn btn-primary custom-btn-small float-left" id="create_url">{{ __('Create') }}</button>
                        </div>

                    </form>
                  </div>
                </div>
              </div>
            </div>
            @foreach($shortUrlConfigs as $shortUrlConfig)
            <div class="accordion" id="accordionExample">
              <div class="card">
                <div class="card-header" id="heading{{ isset($shortUrlConfig->id) ? $shortUrlConfig->id : '' }}">
                  <button class="btn btn-link text-btn" type="button" data-toggle="collapse" data-target="#collapse{{ isset($shortUrlConfig->id) ? $shortUrlConfig->id : '' }}" aria-expanded="true" aria-controls="collapse{{ isset($shortUrlConfig->id) ? $shortUrlConfig->id : '' }}">
                    {{ isset($shortUrlConfig->type) ? $shortUrlConfig->type : '' }}
                  </button>
                </div>
                  <div id="collapse{{ isset($shortUrlConfig->id) ? $shortUrlConfig->id : '' }}" class="collapse {{ isset($shortUrlConfig->default) && $shortUrlConfig->default == 'Yes' ? 'show': ''}}" data-parent="#accordionExample">
                <div class="card-body">
                  <span id="smtp_form">
                    <form action="{{ url('short-url/setup') }}" method="post" id="myform1" class="form-horizontal p-l-30">
                      {!! csrf_field() !!}
                      <input type="hidden" name="id" value="{{ isset($shortUrlConfig->id) ? $shortUrlConfig->id : '' }}">
                      <div class="form-group row">
                        <label class="col-sm-3 control-label require">{{ __('Default') }}</label>

                        <div class="col-sm-6">
                          <select class="select form-control" name="default" id="default">
                              <option value="">{{ __('Select One') }}</option>
                              <option value='Yes' {{ isset($shortUrlConfig->default) && $shortUrlConfig->default == 'Yes' ? 'selected' : "" }} >{{ __('Yes') }} </option>
                              <option value='No' {{ isset($shortUrlConfig->default) && $shortUrlConfig->default == 'No' ? 'selected' : "" }} >{{ __('No') }} </option>
                          </select>
                        <label for="default" generated="true" class="error"></label>
                        </div>
                      </div>
                        <div class="clearfix"></div>

                        <div class="form-group row">
                          <label class="col-sm-3 control-label require">{{ __('Status') }}</label>

                          <div class="col-sm-6">
                            <select class="select form-control" name="status" id="status">
                              <option value="">{{ __('Select One') }}</option>
                              <option value='Active' {{ isset($shortUrlConfig->status) &&  $shortUrlConfig->status == 'Active'  ? 'selected' : "" }}>{{ __('Active') }}</option>
                              <option value='Inactive' {{ isset($shortUrlConfig->status) &&  $shortUrlConfig->status == 'Inactive' ? 'selected' : "" }}>{{ __('Inactive') }}</option>
                            </select>
                          <label for="status" generated="true" class="error"></label>
                          </div>
                        </div>
                        
                        <div class="clearfix"></div>
                        <div class="form-group row">
                          <label class="col-sm-3 control-label">{{ __('Key') }}</label>

                          <div class="col-sm-6">
                            <input type="text" value="{{ isset($shortUrlConfig->key) ? $shortUrlConfig->key : '' }}" class="form-control" name="key">
                          </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="form-group row">
                          <label class="col-sm-3 control-label require">{{ __('Secret') }}</label>
                          <div class="col-sm-6">
                            <input type="text" value="{{ isset($shortUrlConfig->secretkey) ? $shortUrlConfig->secretkey : '' }}" class="form-control" name="secret_key">
                          </div>
                        </div>
                        
                        @if(Helpers::has_permission(Auth::user()->id, 'edit_url_shortner'))
                          <div class="form-group row">
                            <label for="btn_save" class="col-sm-0 pl-2 ml-2 control-label"></label>
                                <button type="submit" class="btn btn-primary custom-btn-small float-left">{{ __('Update') }}</button>
                          </div>
                        @endif

                          </form>
                        </span>
                      </div>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
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