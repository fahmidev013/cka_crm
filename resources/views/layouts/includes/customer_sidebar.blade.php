<nav class="pcoded-navbar {{ getThemeClass('navbar') }}">
    <div class="navbar-wrapper">
        <div class="navbar-brand header-logo">
            <a href="{{ url('customer/dashboard') }}" class="b-brand">

                <span class="b-title" title="{{ $company_name }}">{{ $company_name }}</span>
            </a>
            <a class="mobile-menu" id="mobile-collapse" href="javascript:"><span></span></a>
        </div>
        <div class="navbar-content scroll-div">
            <ul class="nav pcoded-inner-navbar">
                <li class="nav-item pcoded-menu-caption">
                    <label>{{ __('NAVIGATION') }}</label>
                </li>
                @php
                    $id = Auth::guard('customer')->user()->id;
                @endphp
                <li data-username="dashboard" class="nav-item {{ $menu == 'home' ? 'active' : '' }}">
                    <a href="{{url('customer/dashboard')}}" class="nav-link "><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">{{ __('Home') }} </span></a>

                </li>

                <li data-username="quotation" class="nav-item {{ $menu == 'order' ? 'active' : '' }}">
                    <a href='{{url("customer-panel/order")}}' class="nav-link "><span class="pcoded-micon"><i class="fa fa-shopping-cart"></i></span><span class="pcoded-mtext">{{ __('Quotations') }} </span></a>
                </li>

                <li data-username="invoice" class="nav-item {{ $menu == 'invoice' ? 'active' : '' }}">
                    <a href='{{url("customer-panel/invoice")}}' class="nav-link "><span class="pcoded-micon"><i class="fas fa-cart-arrow-down f-15"></i></span><span class="pcoded-mtext">{{ __('Invoices') }}</span></a>
                </li>

                <li data-username="payment" class="nav-item {{ $menu == 'payment' ? 'active' : '' }}">
                    <a href='{{url("customer-panel/payment")}}' class="nav-link "><span class="pcoded-micon"><i class="fas fa-money-check-alt"></i></span><span class="pcoded-mtext">{{ __('Payments') }}</span></a>
                </li>

                <li data-username="ticket" class="nav-item {{ $menu == 'customer-panel-support' ? 'active' : '' }}">
                    <a href='{{url("customer-panel/support/list")}}' class="nav-link "><span class="pcoded-micon"><i class="mdi mdi-monitor-multiple"></i></span><span class="pcoded-mtext">{{ __('Supports') }}</span></a>
                </li>

                <li data-username="project" class="nav-item {{ $menu == 'project' ? 'active' : '' }}">
                    <a href='{{url("customer-panel/project")}}' class="nav-link "><span class="pcoded-micon"><i class="fas fa-project-diagram"></i></span><span class="pcoded-mtext">{{ __('Projects') }}</span></a>
                </li>
                <li data-username="project" class="nav-item {{ $menu == 'knowledge_base' ? 'active' : '' }}">
                    <a href='{{url("customer-panel/knowledge-base")}}' class="nav-link "><span class="pcoded-micon"><i class="fas fa-book"></i></span><span class="pcoded-mtext">{{ __('Knowledge Base') }}</span></a>
                </li>
            </ul>
        </div>
    </div>
</nav>
