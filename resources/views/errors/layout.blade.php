<!DOCTYPE html>
<head>
    <title>{{ $company_name }}</title>
    <!-- HTML5 Shim and Respond.js IE10 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 10]>
    <script src="{{ asset('public/dist/js/respond.min.js') }}"></script>
    <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Favicon icon -->
    @if(!empty($favicon))
    <link rel='shortcut icon' href="{{ URL::to('/') }}/public/uploads/companyIcon/{{ $favicon }}" type='image/x-icon' />
    @endif
    <!-- fontawesome icon -->
    <link rel="stylesheet" href="{{ asset('public/datta-able/fonts/fontawesome/css/fontawesome-all.min.css') }}">
    <!-- material icon -->
    <link rel="stylesheet" href="{{ asset('public/datta-able/fonts/material/css/materialdesignicons.min.css') }}">
    <!-- flaq icon -->
    <link rel="stylesheet" href="{{ asset('public/datta-able/fonts/flag/css/flag-icon.min.css') }}">
    <!-- animation css -->
    <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/animation/css/animate.min.css') }}">
    <!-- vendor css -->
    <link rel="stylesheet" href="{{ asset('public/datta-able/css/style.css?v=1.0') }}">
    <link rel="stylesheet" href="{{ asset('public/dist/css/custom.min.css') }}">
    @php
    if (getThemeClass('theme-mode') == 'navbar-dark brand-dark') {
    @endphp
    <link rel="stylesheet" href="{{ asset('public/datta-able/css/layouts/dark.css') }}">
    <link rel="stylesheet" href="{{ asset('public/datta-able/css/layouts/dark-custom.min.css') }}">
    @php } @endphp
    <!-- Theme style RTL -->
    @php
    if (\Cache::get('gb-language-direction') == 'rtl') {
    @endphp
    <link rel="stylesheet" href="{{ asset('public/datta-able/css/layouts/rtl.css') }}">
    @php } @endphp
    <!-- Required Js -->
    <script src="{{ asset('public/datta-able/js/vendor-all.js') }}"></script>
</head>
<?php
$appName = env('APP_NAME', '');
$appName = (!empty($appName) && mb_strlen($appName) > 19) ? mb_substr($appName, 0, 17) .'..' : $appName;
$menu = "home";
?>
<body class="{{ getThemeClass('body') }}">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->
    <!-- [ navigation menu ] start -->
    <nav class="pcoded-navbar {{ getThemeClass('navbar') }}">
        <div class="navbar-wrapper">
            <div class="navbar-brand header-logo">
                <a href="" class="b-brand">
                    <span class="b-title" title="{{ $company_name }}">{{ $company_name }}</span>
                </a>
                <a class="mobile-menu" id="mobile-collapse" href="javascript:"><span></span></a>
            </div>
            <div class="navbar-content scroll-div">
                <ul class="nav pcoded-inner-navbar">
                    <li class="nav-item pcoded-menu-caption">
                        <label>{{ __('NAVIGATION') }}</label>
                    </li>
                    <li>
                        <a href="{{ Request::is('customer-panel/*') ?  url('customer/dashboard') : url('/dashboard') }}" class="nav-link "><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">{{ __('Dashboard') }} </span></a>
                    </li>
                    @if(URL::previous() != url('/'))
                    <li>
                        <a href="{{ url()->previous() }}" class="nav-link "><span class="pcoded-micon"><i class="fas fa-sign-out-alt"></i></span><span class="pcoded-mtext">{{ __('Go Back') }} </span></a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    <!-- [ navigation menu ] end -->
    <!-- [ Header ] start -->
    <header class="navbar pcoded-header navbar-expand-lg navbar-light {{ getThemeClass('header') }}">
        <div class="m-header">
            <a class="mobile-menu" id="mobile-collapse1" href="javascript:"><span></span></a>
            <a href="{{ url('dashboard') }}" class="b-brand">
                <div class="b-bg">
                    <i class="feather icon-trending-up"></i>
                </div>
                <span class="b-title">{{ $company_name }}</span>
            </a>
        </div>
        <a class="mobile-menu" id="mobile-header" href="javascript:">
            <i class="feather icon-more-horizontal"></i>
        </a>
    </header>
    <!-- [ Header ] end -->
    <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <div class="main-body">
                        <div class="page-wrapper">
                            <!-- [ Main Content ] start -->
                                @yield('content')
                            <!-- [ Main Content ] end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- Required Js -->
        <script src="{{ asset('public/datta-able/js/pcoded.min.js') }}"></script>
        <!-- Custom Js -->
    </body>
</html>