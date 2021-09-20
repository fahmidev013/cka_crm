@extends('layouts.customer_panel')
@section('css')
{{-- select2 css --}}
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css')}}">
@endsection

@section('content')
  <div class="col-md-12" id="cus-panel-profile-container">
    <div class="card">
      <div class="card-header">
        <h5><a href="{{ url('customer/profile') }}">{{ __('Your profile') }}</h5>
      </div>

      <div class="card-block" id="no_shadow_on_card">
        <div class="form-tabs">
          <ul class="nav nav-tabs " id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active text-uppercase" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __('User Information')  }}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-uppercase" id="profile-tab" data-toggle="tab" data-rel="{{ $userData->id }}" href="#profile" role="tab" aria-controls="profile" aria-selected="false">{{ __('Update Password')  }}</a>
            </li>
          </ul>

          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
              <form action='{{ url("customer/profile") }}' method="post" class="form-horizontal" enctype="multipart/form-data" id="user-form">
                <input type="hidden" value="{{ csrf_token() }}" name="_token" id="token">
                <input type="hidden" value="{{ $userData->id }}" id="user-id" name="user_no">

                <div class="form-group row">
                  <label class="col-sm-2 control-label require" for="inputName">{{ __('First Name')  }}</label>
                  <div class="col-sm-10">
                    <input type="text" value="{{ $userData->first_name }}" class="form-control valdation_check" id="fname" name="first_name">
                    <span id="val_fname" class="color_red"></span>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-2 control-label require" for="inputName">{{ __('Last Name')  }}</label>
                  <div class="col-sm-10">
                    <input type="text" value="{{ $userData->last_name }}" class="form-control valdation_check" id="lname" name="last_name">
                  <span id="val_lname" class="color_red"></span>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-2 control-label" for="inputEmail">{{ __('Email') }}</label>
                  <div class="col-sm-10">
                    <input type="email" value="{{ old('email') ? old('email') : $userData->email }}" class="form-control valdation_check" id="em" name="email" readonly>
                  <span id="val_em" class="color_red"></span>
                  </div>
                </div>
                
                <div class="form-group row">
                  <label class="col-sm-2 control-label" for="inputName">{{ __('Phone')  }}</label>
                  <div class="col-sm-10">
                    <input type="text" value="{{ $userData->phone }}" class="form-control valdation_check" id="phn" name="phone">
                  <span id="val_phn" class="color_red"></span>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-2 control-label" for="inputEmail3">{{ __('Timezone') }}</label>
                    <?php $timezones = timeZoneList(); ?>
                  <div class="col-sm-10">
                    <select class="form-control select2" name="timezone" >
                    @foreach($timezones as $timezone)
                      <option value="{{ $timezone['zone'] }}" <?= isset($userData->timezone) && $userData->timezone == $timezone['zone'] ? 'selected' : "" ?>>
                        {{ $timezone['diff_from_GMT'] . ' - ' . $timezone['zone'] }}
                      </option>
                    @endforeach
                    </select>
                  </div>
                </div>

                <div class="col-md-12 px-0">
                  <button class="btn btn-primary custom-btn-small" type="submit" id="btnSubmit">{{ __('Update')  }}</button>   
                  <a href="{{ url('customer/dashboard') }}" class="btn btn-danger custom-btn-small">{{  __('Cancel')  }}</a>
                </div>
              </form>
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
              <div class="row">
                <div class="col-md-12">
                  <form action='{{ url("customer/update-customer-password") }}' method="post" class="form-horizontal" id="password-form">
                    <input type="hidden" value="{{ csrf_token() }}" name="_token">
                    <input type="hidden" value="{{ $userData->id }}" name="customer_id">
                    <div class="form-group row">
                      <label class="col-sm-2 control-label require" for="inputName">{{ __('Password') }}</label>
                      <div class="col-sm-10">
                        <input type="password" class="form-control" name="password" id="password">
                        <span id="password-error" class="color_red"></span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 control-label require" for="inputName">{{ __('Confirm Password') }}</label>
                      <div class="col-sm-10">
                        <input type="password" class="form-control" name="password_confirmation" id="con_password">
                         <span id="confirm_password-error" class="color_red"></span>
                      </div>
                    </div>

                    <div class="col-md-12 px-0">
                      <button class="btn btn-primary custom-btn-small" type="submit" id="btnPassSubmit">{{ __('Update')  }}</button> 
                      <a href="{{ url('customer/dashboard') }}" class="btn btn-danger custom-btn-small">{{  __('Cancel')  }}</a>
                    </div>
                  </form>
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
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/customerpanel.min.js') }}"></script>
@endsection