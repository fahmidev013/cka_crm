@extends('layouts.app2')
@section('content')
<div class="login-box-body">
    <p class="login-box-msg">{{ __('Enter your email to send password reset link') }}</p>
    <form  method="POST" action="{{ route('login.sendResetLink', $type)}}">
        {{ csrf_field() }}
        <div class="input-group mb-3">
            <input type="email" class="form-control" value="{{ old('email') }}" name="email" placeholder="{{ __('Email') }}">
        </div>
        <button type="submit" class="btn btn-primary shadow-2 mb-4">Send</button>
        <p class="mb-2 text-muted">{{ __('Click here to') }} <a href="{{ $type == 'admin' ? url('/') : url('/customer') }}">{{ __('Log In') }}</a></p>
    </form>
</div>
@endsection



