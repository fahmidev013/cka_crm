@extends('layouts.app2')

@section('content')
<div class="login-box-body">
    <p class="login-box-msg">{{ __('Reset Password') }}</p>
    <form action='{{ url("password/resets") }}' method="post">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $user->id }}">
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="type" value="{{ $type }}">
        <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="{{ __('Password') }}" name="password"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="{{ __('Confirm password') }}" name="password_confirmation"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <button type="submit" class="btn btn-primary shadow-2 mb-4">{{ __('Reset Password') }}</button>
    </form>
    <a href="{{ $type == 'admin' ? url('/') : url('/customer') }}">{{ __('Log In') }}</a><br>
</div>
@endsection
