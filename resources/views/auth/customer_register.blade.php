<!DOCTYPE html>
<html>
<head>
    <title>{{ env('APP_NAME', '') }} {{!empty($page_title) ? '| ' . ucfirst($page_title) : '' }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Favicon icon -->
    @if(!empty($favicon))
        <link rel='shortcut icon' href="{{ URL::to('/') }}/public/uploads/companyIcon/{{ $favicon }}" type='image/x-icon' />
    @endif
    <link rel="stylesheet" href="{{ asset('public/datta-able/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('public/dist/css/custom.min.css?v=1.0') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-content">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="feather icon-unlock auth-icon"></i>
                    </div>
                    <h3 class="mb-4"><b>{{!empty($companyData) ? $companyData : '' }}</b></h3>
                    <form action="{{ route('customerRegistration.store') }}" method="POST" accept-charset="UTF-8" id="customerRegistration">
                    {!! csrf_field() !!}
                    @foreach (['success', 'danger', 'fail', 'warning', 'info'] as $msg)
                        @if($message = Session::get($msg))
                            <div class="alert alert-{{ $msg == 'fail' ? 'danger' : $msg }}">
                                <button type="button" class="close" data-dismiss="alert">×</button> 
                                <strong>{{ $message }}</strong>
                            </div>
                            @break
                        @endif
                    @endforeach
                    <div class="form-group has-feedback" id="name-group">
                        <input type="text" class="form-control" placeholder="First Name" name="first_name" required='required' value="{{ old('first_name') }}">
                        @if($errors->has('first_name'))
                            <p class="text-danger">{{$errors->first('first_name')}}</p>
                        @endif
                    </div>
                    <div class="form-group has-feedback" id="name-group">
                        <input type="text" class="form-control" placeholder="Last Name" name="last_name" required='required' value="{{ old('last_name') }}">
                        @if($errors->has('last_name'))
                            <p class="text-danger">{{$errors->first('last_name')}}</p>
                        @endif  
                    </div>
                    <div class="form-group has-feedback" id="email-group">
                        <input type="email" class="form-control" placeholder="Email" name="email" required='required' value="{{ old('email') }}">
                        @if($errors->has('email'))
                            <p class="text-danger">{{$errors->first('email')}}</p>
                        @endif
                    </div>
                    <div class="form-group has-feedback" id="password-group">
                        <input type="password" class="form-control" placeholder="Password" name="password" required='required'>
                        @if($errors->has('password'))
                            <p class="text-danger">{{$errors->first('password')}}</p>
                        @endif
                    </div>
                    <div class="form-group has-feedback" id="password-confirmation-group">
                        <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" required='required'>
                        @if($errors->has('password_confirmation'))
                            <p class="text-danger">{{$errors->first('password_confirmation')}}</p>
                        @endif
                    </div>

                    <div class="form-group has-feedback" id="countrySelect">
                        <select class="js-example-basic-single form-control" id="country" name="country">
                            <option value="" class="text-left">{{ __('Select Country')  }}</option>
                            @foreach ($countries as $key => $value)
                              <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select> 
                        <label id="country-error" class="error" for="country"></label>
                    </div>

                     <div class="form-group has-feedback" id="stateVisible">
                        <select class="js-example-basic-single form-control" id="state" name="state">
                            <option value="" class="text-left">{{ __('Select State')  }}</option>
                        </select> 
                    </div>

                    @if ($captcha == "enable" && isset($captchaCredentials->site_key))
                    <div class="row">
                        <div class="col-md-12">
                            <div class="g-recaptcha" data-sitekey="{{ $captchaCredentials->site_key }}"></div>
                            @if ($errors->has('g-recaptcha-response'))
                            <p class="text-danger">{{ $errors->first('g-recaptcha-response') }}</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="form-group" id="sign-up">
                        {!! Form::submit('Sign Up', ['class' => 'btn btn-block btn-primary btn-order', 'id' => 'submitBtn']) !!}
                    </div>
                    <div>
                        <p>{{ __('Already have an account?') }} <a href="{{url('/customer')}}"><span class="color_green">{{ __('Log In') }}</span></a></p>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</body>
<?php if ($captcha == "enable" && isset($captchaCredentials->plugin_url)) { ?>
    <script src='{{ $captchaCredentials->plugin_url }}'></script>
<?php } ?>
<script src="{{ asset('public/datta-able/js/vendor-all.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/countries.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/customer_registration.min.js') }}"></script>
</html>

