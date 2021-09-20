<!DOCTYPE html>
<html>

<head>
    <title>{{ env('APP_NAME', '') }} {{!empty($page_title) ? '| ' . ucfirst($page_title) : '' }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    @include('layouts.includes.meta')
    <!-- Favicon icon -->
    @if(!empty($favicon))
        <link rel='shortcut icon' href="{{ URL::to('/') }}/public/uploads/companyIcon/{{ $favicon }}" type='image/x-icon' />
    @endif
    <link rel="stylesheet" href="{{ asset('public/datta-able/fonts/fontawesome/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/animation/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/datta-able/css/style.css') }}">
    @php
        if (getThemeClass('theme-mode') == 'navbar-dark brand-dark') {
    @endphp
        <link rel="stylesheet" href="{{ asset('public/datta-able/css/layouts/dark.css') }}">
        <link rel="stylesheet" href="{{ asset('public/datta-able/css/layouts/dark-custom.min.css?v=1.0') }}">
    @php } @endphp
    <script type="text/javascript">
        'use strict';
        var SITE_URL              = "{{ URL::to('/') }}";
        var currencySymbol        = '{!! $default_currency->symbol !!}';
        var defaultCurrencySymbol = '{!! $default_currency->symbol !!}';
        var decimal_digits        = "{{ $decimal_digits }}";
        var thousand_separator    = "{{ $thousand_separator }}";
        var symbol_position       = "{!! $symbol_position !!}";
        var dateFormat            = '{!! $date_format_type !!}';
        var token                 = '{!! csrf_token() !!}';
        var app_locale_url        = "{!! url('/resources/lang/' . config('app.locale') . '.json') !!}";
        var row_per_page          = '{!! $row_per_page !!}';
        var txLnSts               = {!! $json !!};
        var language_direction    = '{!! \Cache::get('gb-language-direction') !!}';
    </script>
    <script src="{{ asset('public/datta-able/js/vendor-all.js') }}"></script>
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
                    <div class="">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @foreach (['success', 'danger', 'fail', 'warning', 'info'] as $msg)
                            @if($message = Session::get($msg))
                                <div class="alert alert-{{ $msg == 'fail' ? 'danger' : $msg }}">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>{{ $message }}</strong>
                                </div>
                                @break
                            @endif
                        @endforeach
                    </div>
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('public/datta-able/js/pcoded.min.js') }}"></script>
    @yield('js')
</body>
</html>
