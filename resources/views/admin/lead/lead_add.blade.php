@extends('layouts.app')
@section('css')
{{-- Select2  --}}
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content')
  <div class="col-sm-12" id="leadAdd-container">
    <div class="card">
      <div class="card-header">
        <h5> <a href="{{url('lead/list')}}">{{ __('Lead List') }}</a> >> {{ __('New Lead') }}</h5>
        <div class="card-header-right">

        </div>
      </div>
      <div class="card-body table-border-style">
        <div class="form-tabs">
          <form action="{{ url('save-lead') }}" method="post" id="leadAdd" class="form-horizontal">
            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item">
                  <a class="nav-link active text-uppercase" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __('New Information') }}</a>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="row">
                  <div class="col-sm-9">
                    <div class="form-group row">
                      <label class="col-sm-2 control-label require" for="inputEmail3">{{ __('Lead Status') }}</label>
                      <div class="col-sm-10">
                        <select class="form-control select" name="lead_status" id="lead_status">
                          <option value="">{{ __('Select One') }}</option>
                          @foreach ($statuses as $status)
                              <option value="{{ $status->id }}" >{{ $status->name }}</option>
                          @endforeach
                        </select>
                        <label id="lead_status-error" class="error display_inline_block" for="lead_status"></label>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-2 control-label require" for="inputEmail3">{{ __('Lead Source') }}</label>
                      <div class="col-sm-10">
                        <select class="form-control select" name="lead_source" id="lead_source">
                          <option value="">{{ __('Select One') }}</option>
                          @foreach ($sources as $source)
                            <option value="{{ $source->id }}" >{{ $source->name }}</option>
                          @endforeach
                        </select>
                        <label id="lead_source-error" class="error display_inline_block" for="lead_source"></label>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-2 control-label require" for="inputEmail3">{{ __('Assigned') }}</label>
                      <div class="col-sm-10">
                          <select class="form-control select" name="assigned" id="assigned">
                            <option value="">{{ __('Select One') }}</option>
                            @foreach ($users as $user)
                              <option value="{{ $user->id }}" >{{ $user->full_name }}</option>
                            @endforeach
                          </select>
                          <label id="assigned-error" class="error display_inline_block" for="assigned"></label>
                      </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-2 control-label" for="inputEmail3">{{ __('Contact Date') }}</label>
                        <div class="col-sm-10">
                          <input id="contact_date" type="text"  class="form-control" placeholder="{{ __('Contact Date') }}"  name="contact_date" value="{{ old('lead_contact_date') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 control-label require" for="inputEmail3">{{ __('First Name') }}</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="{{ __('First Name') }}"  name="first_name" value="{{ old('first_name')}}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 control-label require" for="inputEmail3">{{ __('Last Name') }}</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="{{ __('Last Name') }}" name="last_name" value="{{ old('last_name') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 control-label" for="inputEmail3">{{ __('Email') }}</label>

                        <div class="col-sm-10">
                            <input type="email" value="{{ old('email') }}" placeholder="{{ __('Email') }}" class="form-control" name="email" id="email">
                            <label for="email_error" class="error display_none" id="val_email"></label>
                        </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-2 control-label" for="inputEmail3">{{ __('Phone') }}</label>
                      <div class="col-sm-10">
                        <input type="text" placeholder="{{ __('Phone') }}" class="form-control" id="phone" name="phone">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-2 control-label" for="inputEmail3">{{ __('Website') }}</label>
                      <div class="col-sm-10">
                        <input type="text" placeholder="{{ __('Website') }}" class="form-control" id="website" name="website">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-2 control-label" for="inputEmail3">{{ __('Company') }}</label>
                      <div class="col-sm-10">
                        <input type="text" placeholder="{{ __('Company') }}" class="form-control" id="company" name="company">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-2 control-label" for="inputEmail3">{{ __('Description') }}</label>
                      <div class="col-sm-10">
                          <textarea placeholder="{{ __('Description') }}" class="form-control" id="description" name="description"></textarea>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-2 control-label" for="inputEmail3">{{ __('Street') }}</label>
                      <div class="col-sm-10">
                        <input type="text" placeholder="{{ __('Street') }}" class="form-control" id="street" name="street">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-2 control-label" for="inputEmail3">{{ __('City') }}</label>
                      <div class="col-sm-10">
                        <input type="text" placeholder="{{ __('City') }}" class="form-control" id="city" name="city">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-2 control-label" for="inputEmail3">{{ __('State') }}</label>
                      <div class="col-sm-10">
                        <input type="text" placeholder="{{ __('State') }}" class="form-control" id="state" name="state">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-2 control-label" for="inputEmail3">{{ __('Zip Code') }}</label>
                      <div class="col-sm-10">
                        <input type="text" placeholder="{{ __('Zip Code') }}" class="form-control" id="zipcode" name="zipcode">
                      </div>
                    </div>



                    <div class="form-group row">
                      <label class="col-sm-2 control-label" for="inputEmail3">{{ __('Country') }}</label>
                      <div class="col-sm-10">
                            <select class="form-control select" name="country" id="country">
                            <option value="">{{ __('Select One') }}</option>
                            @foreach ($countries as $country)
                              <option value="{{ $country->id }}" >{{ $country->name }}</option>
                            @endforeach
                            </select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-2 control-label">{{ __('Tags') }}</label>
                      <div class="col-sm-10">
                        <select class="js-example-responsive" multiple="multiple" id="tags" name="tags[]"></select>
                      </div>
                    </div>

                  </div>
                </div>
                <div class="col-sm-9 px-0 pt-2">
                  <button class="btn btn-primary custom-btn-small" type="submit" id="btnSubmit"><i class="comment_spinner spinner fa fa-spinner fa-spin custom-btn-small display_none"></i><span id="spinnerText">{{ __('Submit') }}</span></button>
                  <a href="{{ url('lead/list') }}" class="btn btn-danger custom-btn-small">{{ __('Cancel') }}</a>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('js')
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/lead.min.js') }}"></script>
<script type="text/javascript">
    "use strict";
    var dateFormat = '{!! $date_format_type !!}';
</script>
@endsection
