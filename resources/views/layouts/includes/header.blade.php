<header class="navbar pcoded-header navbar-expand-lg navbar-light {{ getThemeClass('header') }}">
        <div class="m-header">
            <a class="mobile-menu" id="mobile-collapse1" href="javascript:"><span></span></a>
            <a href="{{ url('dashboard') }}" class="b-brand">
                   <div class="b-bg">
                       <i class="feather icon-trending-up"></i>
                   </div>
                   <span class="b-title" title="
                   ">{{ $company_name }}</span>
               </a>
        </div>
        <a class="mobile-menu" id="mobile-header" href="javascript:">
            <i class="feather icon-more-horizontal"></i>
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li><a href="javascript:" class="full-screen" onclick="javascript:toggleFullScreen()"><i class="feather icon-maximize"></i></a></li>
            </ul>
            <?php
                $flag = config('app.locale');
            ?>
            <ul class="navbar-nav ml-auto">
                <li>
                    <div class="dropdown" data-flag="{{ getSVGFlag($flag) }}">
                        <a class="dropdown-toggle" href="javascript:" data-toggle="dropdown" id="itemNotifications">
                            <i class="icon feather icon-bell"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right notification">
                            <div class="noti-head">
                                <h6 class="d-inline-block m-b-0">{{ __('Items Quantity Alert') }} (<span id="itemCount"></span>)</h6>
                            </div>
                            <ul class="noti-body scroll-noti" id="notifications"></ul>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="dropdown">
                        <a class="dropdown-toggle flag flag-icon-background flag-icon-{{ getSVGFlag($flag) }}" id="dropdown-flag" href="javascript:" data-toggle="dropdown"></a>
                        <div class="dropdown-menu dropdown-menu-right notification w-200p">
                            <div class="noti-head">
                                <h6 class="d-inline-block m-b-0">{{ __('Select Language') }}</h6>
                            </div>
                            <ul class="noti-body scroll-noti">
                                @if (!empty($languages))
                                    @foreach($languages as $language)
                                    <li class="notification">
                                      
                                      <div class="media lang" id="{{ $language->short_name }}">
                                        <img class="img-radius" src='{{ url("public/datta-able/fonts/flag/flags/4x3/". getSVGFlag($language->short_name) .".svg") }}' alt="{{ $language->flag }}">
                                        <div class="media-body">
                                            <p><span>{{ $language->name }}</span></p>
                                        </div>
                                      </div>
                                    </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="dropdown drp-user">
                        <a href="javascript:" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon feather icon-settings"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-notification">
                            <div class="pro-head">
                                <img src="{{ getUserProfilePicture(Auth::user()->id) }}" class="img-radius" alt="User-Profile-Image">
                                <span>{{ Auth::user()->full_name }}</span>
                                <a href="{{ url('/logout') }}" class="dud-logout" title="Logout">
                                    <i class="feather icon-log-out"></i>
                                </a>
                            </div>
                            <ul class="pro-body">
                                <li><a href="{{ url('profile') }}" class="dropdown-item"><i class="feather icon-user"></i> {{ __('Profile') }}</a></li>
                                <li><a href="{{ url('/logout') }}" class="dropdown-item"><i class="feather icon-lock"></i> {{ __('Sign Out') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </header>