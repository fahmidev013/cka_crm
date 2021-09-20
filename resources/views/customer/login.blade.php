@extends('layouts.app2')
@section('content')

<div class="login-box-body">
    <p class="login-box-msg">{{ __('Sign in to start your session') }}</p>
    <form action="{{ url('customer/authenticate') }}" method="post" id="customerSignInform">
        {!! csrf_field() !!}
        <div class="input-group mb-3">
            <input type="email" class="form-control" value="{{ old('email') }}" name="email" placeholder="Email">
        </div>
        <div class="input-group mb-4">
            <input type="password" class="form-control" name="password" placeholder="password">
        </div>

        @if ($captcha == "enable" && isset($captchaCredentials->site_key))
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="g-recaptcha" data-sitekey="{{ $captchaCredentials->site_key }}"></div>
            </div>
        </div>
        @endif

        <div class="form-group">
            <div class="form-group">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="switch switch-primary d-inline m-r-10">
                            <input type="checkbox" id="switch-p-1" name="remember" checked="">
                            <label for="switch-p-1" class="cr"></label>
                        </div>
                        <label>{{ __('Remember me') }}</label>
                    </div>

                    <div>
                        <button class="btn btn-primary w-100 custom-btn-small" type="button" id="btnSubmit"><i
                                class="comment_spinner spinner sign_in_spin fa fa-spinner fa-spin custom-btn-small display_none"></i><span
                                id="spinnerText">{{ __('Sign In') }} </span></button>
                    </div>
                </div>
            </div>

            <p class="mb-2 text-muted text-left">{{ __('Forgot your password?') }} <a
                    href="{{ route('login.reset', 'customer') }}">{{ __('Reset') }}</a></p>
            <p class="mb-2 text-muted text-left">{{ __('Don\'t have account?') }} <a
                    href="{{ route('customerRegistration.create') }}">{{ __('Join now') }}</a></p>
        </div>
        </form>

</div>

@endsection
@section('js')
    <script src="{{ asset('public/dist/js/custom/customer-login.min.js') }}"></script>
<?php if ($captcha == "enable" && isset($captchaCredentials->plugin_url)) { ?>
    <script src="{{ $captchaCredentials->plugin_url }}"></script>
<?php } ?>
@endsection
