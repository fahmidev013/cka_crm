<nav class="pcoded-navbar {{ getThemeClass('navbar') }}">
    <div class="navbar-wrapper">
        <div class="navbar-brand header-logo">
            <a href="{{ url('dashboard') }}" class="b-brand">

                <span class="b-title" title="{{ $company_name }}">{{ $company_name }}</span>
            </a>
            <a class="mobile-menu" id="mobile-collapse" href="javascript:"><span></span></a>
        </div>
        <div class="navbar-content scroll-div">
            <ul class="nav pcoded-inner-navbar">
                <li class="nav-item pcoded-menu-caption">
                    <label>{{ __('NAVIGATION') }}</label>
                </li>

                <li data-username="dashboard" class="nav-item {{ $menu == 'dashboard' ? 'active' : '' }}">
                    <a href="{{ url('dashboard') }}" class="nav-link "><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">{{ __('Dashboard') }} </span></a>

                </li>

                @if(Helpers::has_permission(Auth::user()->id, 'manage_customer|manage_supplier|manage_team_member'))
                <li data-username="Customer Supplier Team" class="nav-item pcoded-hasmenu {{ $menu == 'relationship' ? 'pcoded-trigger active' : '' }}">
                    <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-users"></i></span><span class="pcoded-mtext">{{ __('Relationships') }}</span></a>
                    <ul class="pcoded-submenu">
                        @if(Helpers::has_permission(Auth::user()->id, 'manage_customer'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'customer' ? 'active' : '' }}"><a href="{{ url('customer/list') }}" class="">{{ __('Customers') }}</a></li>
                        @endif

                        @if(Helpers::has_permission(Auth::user()->id, 'manage_supplier'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'supplier' ? 'active' : '' }}"><a href="{{ url('supplier') }}" class="">{{ __('Suppliers') }}</a></li>
                        @endif

                        @if(Helpers::has_permission(Auth::user()->id, 'manage_team_member'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'users' ? 'active' : '' }}"><a href="{{ url('users') }}" class="">{{ __('Team Members') }}</a></li>
                        @endif
                    </ul>
                </li>
                @endif

                @if(Helpers::has_permission(Auth::user()->id, 'manage_item'))
                <li data-username="Item" class="nav-item {{ $menu == 'item' ? 'active' : '' }}">
                    <a href="{{ url('item') }}" class="nav-link"><span class="pcoded-micon"><i class="feather icon-layers"></i></span><span class="pcoded-mtext">{{ __('Items') }}</span></a>
                </li>
                @endif

                @if(Helpers::has_permission(Auth::user()->id, 'manage_pos'))
                <li data-username="POS" class="nav-item {{ $menu == 'pos' ? 'active' : '' }}">
                    <a href="{{ url('pos') }}" class="nav-link"><span class="pcoded-micon"><i class="feather icon-briefcase"></i></span><span class="pcoded-mtext">{{ __('Point of Sale') }}</span></a>
                </li>
                @endif

                @if(Helpers::has_permission(Auth::user()->id, 'manage_quotation|manage_invoice|manage_payment|own_quotation|own_invoice|own_payment'))
                <li data-username="Sales Quotations Invoices Payments" class="nav-item pcoded-hasmenu {{$menu == 'sales' ? 'pcoded-trigger active' : ''}}">
                    <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-box"></i></span><span class="pcoded-mtext">{{ __('Sales') }}</span></a>
                    <ul class="pcoded-submenu">
                        @if(Helpers::has_permission(Auth::user()->id, 'manage_quotation|own_quotation'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'order/list' ? 'active' : '' }}"><a href="{{ url('order/list') }}" class="">{{ __('Quotations') }}</a></li>
                        @endif

                        @if(Helpers::has_permission(Auth::user()->id, 'manage_invoice|own_invoice'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'sales/direct-invoice' ? 'active' : '' }}"><a href="{{ url('invoice/list') }}" class="">{{ __('Invoices') }}</a></li>
                        @endif

                        @if(Helpers::has_permission(Auth::user()->id, 'manage_payment|own_payment'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'payment/list' ? 'active' : '' }}"><a href="{{ url('payment/list') }}" class="">{{ __('Payments') }}</a></li>
                        @endif
                    </ul>
                </li>
                @endif

                @if(Helpers::has_permission(Auth::user()->id, 'manage_purchase|own_purchase|manage_purch_receive|own_purchase_receive|manage_purch_payment|own_purchase_payment|manage_stock_transfer|own_stock_transfer|manage_stock_adjustment|own_stock_adjustment'))
                <li data-username="Purchase purchase_receive payments stock_transfer stock_adjustment" class="nav-item pcoded-hasmenu {{$menu == 'purchase' ? 'pcoded-trigger active' : ''}}">
                    <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-shopping-cart"></i></span><span class="pcoded-mtext">{{ __('Purchases/Stocks') }}</span></a>
                    <ul class="pcoded-submenu">
                        @if(Helpers::has_permission(Auth::user()->id, 'manage_purchase|own_purchase'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'purchase/list' ? 'active' : '' }}"><a href="{{ url('purchase/list') }}" class="">{{ __('Purchases') }}</a></li>
                        @endif

                        @if(Helpers::has_permission(Auth::user()->id, 'manage_purch_receive|own_purchase_receive'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'purchase_receive/list' ? 'active' : '' }}"><a href="{{ url('purchase_receive/list') }}" class="">{{ __('Purchases Receive') }}</a></li>
                        @endif

                        @if(Helpers::has_permission(Auth::user()->id, 'manage_purch_payment|own_purchase_payment'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'purchase_payment/list' ? 'active' : '' }}"><a href="{{ url('purchase_payment/list') }}" class="">{{ __('Payments') }}</a></li>
                        @endif

                        @if(Helpers::has_permission(Auth::user()->id, 'manage_stock_transfer|own_stock_transfer'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'stock_transfer' ? 'active' : '' }}"><a href="{{ url('stock_transfer/list') }}" class="">{{ __('Stock Transfers') }}</a></li>
                        @endif

                        @if(Helpers::has_permission(Auth::user()->id, 'manage_stock_adjustment|own_stock_adjustment'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'adjustment' ? 'active' : '' }}"><a href="{{ url('adjustment/list') }}" class="">{{ __('Stock Adjustment') }}</a></li>
                        @endif
                    </ul>
                </li>
                @endif

                @if(Helpers::has_permission(Auth::user()->id, 'manage_bank_account|manage_deposit|manage_balance_transfer|own_balance_transfer|manage_transaction|own_transaction'))
                <li data-username="transcation bank_accounts bank_account_deposit bank_account_transfer transaction gl_transaction" class="nav-item pcoded-hasmenu {{$menu == 'transaction' ? 'pcoded-trigger active' : ''}}">
                    <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="fas fa-piggy-bank"></i></span><span class="pcoded-mtext">{{ __('Banking & Transactions') }}</span></a>
                    <ul class="pcoded-submenu">
                        @if(Helpers::has_permission(Auth::user()->id, 'manage_bank_account'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'bank/list' ? 'active' : '' }}"><a href="{{ url('bank/list') }}" class="">{{ __('Bank Accounts') }}</a></li>
                        @endif

                        @if(Helpers::has_permission(Auth::user()->id, 'manage_deposit'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'deposit/list' ? 'active' : '' }}"><a href="{{ url('deposit/list') }}" class="">{{ __('Bank Account Deposits') }}</a></li>
                        @endif

                        @if(Helpers::has_permission(Auth::user()->id, 'manage_balance_transfer|own_balance_transfer'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'transfer/list' ? 'active' : '' }}"><a href="{{ url('transfer/list') }}" class="">{{ __('Bank Account Transfer') }}</a></li>
                        @endif

                        @if(Helpers::has_permission(Auth::user()->id, 'manage_transaction|own_transaction'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'transaction/list' ? 'active' : '' }}"><a href="{{ url('transaction/list') }}" class="">{{ __('Transactions') }}</a></li>
                        @endif
                    </ul>
                </li>
                @endif

                @if(Helpers::has_permission(Auth::user()->id, 'manage_project|own_project'))
                <li data-username="Projects" class="nav-item {{ $menu == 'project' ? 'active' : '' }}"><a href="{{ url('project/list') }}" class="nav-link"><span class="pcoded-micon"><i class="fas fa-adjust"></i></span><span class="pcoded-mtext">{{ __('Projects') }}</span></a></li>
                @endif

                @if(Helpers::has_permission(Auth::user()->id, 'manage_task|own_task'))
                <li data-username="Task" class="nav-item {{ $menu == 'task' ? 'active' : '' }}"><a href="{{ url('task/list') }}" class="nav-link"><span class="pcoded-micon"><i class="feather icon-clipboard"></i></span><span class="pcoded-mtext">{{ __('Tasks') }}</span></a></li>
                @endif

                @if(Helpers::has_permission(Auth::user()->id, 'manage_timesheet|own_timesheet'))
                <li data-username="Task" class="nav-item {{ $menu == 'time_sheet' ? 'active' : '' }}"><a href="{{ url('time-sheet/list') }}" class="nav-link"><span class="pcoded-micon"><i class="mdi mdi-calendar-clock"></i></span><span class="pcoded-mtext">{{ __('Timesheets') }}</span></a></li>
                @endif

                @if(Helpers::has_permission(Auth::user()->id,'manage_ticket|own_ticket'))
                <li data-username="Ticket" class="nav-item {{ $menu == 'ticket' ? 'active' : '' }}"><a href="{{ url('ticket/list') }}" class="nav-link"><span class="pcoded-micon"><i class="feather icon-file-plus"></i></span><span class="pcoded-mtext">{{ __('Tickets') }}</span></a></li>
                 @endif

                @if(Helpers::has_permission(Auth::user()->id,'manage_calendar'))
                <li data-username="Calendar" class="nav-item {{ $menu == 'calendar' ? 'active' : '' }}"><a href="{{ url('calendar') }}" class="nav-link"><span class="pcoded-micon"><i class="feather icon-calendar"></i></span><span class="pcoded-mtext">{{ __('Calender') }}</span></a></li>
                @endif

                @if(Helpers::has_permission(Auth::user()->id, 'manage_expense|own_expense'))
                <li data-username="Expense" class="nav-item {{ $menu == 'expense' ? 'active' : '' }}"><a href="{{ url('expense/list') }}" class="nav-link"><span class="pcoded-micon"><i class="fas fa-hand-holding-usd"></i></span><span class="pcoded-mtext">{{ __('Expenses') }}</span></a></li>
                @endif

                <!--Add Leads Menu here (Arif 24-01-2019)-->
                @if(Helpers::has_permission(Auth::user()->id, 'manage_lead|own_lead'))
                <li data-username="Lead" class="nav-item {{ $menu == 'lead' ? 'active' : '' }}"><a href="{{ url('lead/list') }}" class="nav-link"><span class="pcoded-micon"><i class="feather icon-phone"></i></span><span class="pcoded-mtext">{{ __('Leads') }}</span></a></li>
                @endif

                @if(Helpers::has_permission(Auth::user()->id, 'manage_knowledge_base|own_knowledge_base'))
                    <li data-username="Knowledge" class="nav-item {{ $menu == 'knowledge_base' ? 'active' : '' }}"><a href="{{ url('knowledge-base') }}" class="nav-link"><span class="pcoded-micon"><i class="feather icon-book"></i></span><span class="pcoded-mtext">{{ __('Knowledge Base') }}</span></a></li>
                @endif

                @if(Helpers::has_permission(Auth::user()->id, 'manage_stock_on_hand|manage_sale_report|manage_purchase_report|manage_expense_report|manage_income_report|manage_lead'))
                <li data-username="Report stock_on_hand sales_report sales_history_report purchase_report expense_report income_report income_vs_expense" class="nav-item pcoded-hasmenu {{ $menu == 'report' ? 'pcoded-trigger active' : '' }}">
                    <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-bar-chart-2"></i></span><span class="pcoded-mtext">{{ __('Reports') }}</span></a>
                    <ul class="pcoded-submenu">
                        @if(Helpers::has_permission(Auth::user()->id, 'manage_stock_on_hand'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'report/inventory-stock-on-hand' ? 'active' : '' }}"><a href="{{ url('report/inventory-stock-on-hand') }}" class="">{{ __('Inventory Stock on Hand') }}</a></li>
                        @endif

                        @if(Helpers::has_permission(Auth::user()->id, 'manage_sale_report'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'report/sales-report' ? 'active' : '' }}"><a href="{{ url('report/sales-report') }}" class="">{{ __('Sales Report') }}</a></li>
                        @endif

                        @if(Helpers::has_permission(Auth::user()->id, 'manage_purchase_report'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'purchase-report' ? 'active' : '' }}"><a href="{{ url('report/purchase-report') }}" class="">{{ __('Purchases Report') }}</a></li>
                        @endif

                        @if(Helpers::has_permission(Auth::user()->id, 'manage_expense_report'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'transaction/expense-report' ? 'active' : '' }}"><a href="{{ url('transaction/expense-report') }}" class="">{{ __('Expenses Report') }}</a></li>
                        @endif

                        @if(Helpers::has_permission(Auth::user()->id, 'manage_income_report'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'transaction/income-report' ? 'active' : '' }}"><a href="{{ url('transaction/income-report') }}" class="">{{ __('Income Report') }}</a></li>
                        @endif

                        @if(Helpers::has_permission(Auth::user()->id, 'manage_income_vs_expense'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'transaction/income-vs-expense' ? 'active' : '' }}"><a href="{{ url('transaction/income-vs-expense') }}" class="">{{ __('Income vs Expenses') }}</a></li>
                        @endif

                        @if(Helpers::has_permission(Auth::user()->id, 'manage_lead'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'leads-report' ? 'active' : '' }}"><a href="{{ url('report/leads-report') }}" class="">{{ __('Leads Report') }}</a></li>
                        @endif
                    </ul>
                </li>
                @endif

                @if(Helpers::has_permission(Auth::user()->id, 'manage_company_setting|manage_department|manage_role|manage_location|manage_general_setting|manage_item_category|manage_language|manage_income_expense_category|manage_unit|manage_db_backup|manage_email_setup|manage_sms_setup|url_shortner|manage_lead_status|manage_lead_source|manage_finance|manage_tax|manage_currency|manage_account_type|manage_payment_term|manage_payment_method|manage_payment_gateway|manage_email_template|manage_sms_template|manage_preference|manage_barcode'))
                <li data-username="form elements advance componant validation masking wizard picker select" class="nav-item pcoded-hasmenu {{ $menu == 'setting' ? 'pcoded-trigger active' : '' }}">
                    <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-settings"></i></span><span class="pcoded-mtext">{{ __('Settings') }}</span></a>
                    <ul class="pcoded-submenu">
                        @if(Helpers::has_permission(Auth::user()->id, 'manage_company_setting|manage_department|manage_role|manage_location'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'company' ? 'active' : '' }}"><a href="{{ url('company/setting') }}" class="">{{ __('Company Details') }}</a></li>
                        @endif

                        @if(Helpers::has_permission(Auth::user()->id, 'manage_general_setting|manage_item_category|manage_language|manage_income_expense_category|manage_unit|manage_db_backup|manage_email_setup|manage_sms_setup|url_shortner|manage_lead_status|manage_lead_source'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'general' ? 'active' : '' }}"><a href="{{ url('item-category') }}" class="">{{ __('General Settings') }}</a></li>
                        @endif

                        @if(Helpers::has_permission(Auth::user()->id, 'manage_finance|manage_tax|manage_currency|manage_account_type|manage_payment_term|manage_payment_method|manage_payment_gateway'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'finance' ? 'active' : '' }}"><a href="{{ url('tax') }}" class="">{{ __('Finance') }}</a></li>
                        @endif

                        @if(Helpers::has_permission(Auth::user()->id, 'manage_email_template'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'mail-temp' ? 'active' : '' }}"><a href="{{ url('customer-invoice-temp/5') }}" class="">{{ __('Email Templates') }}</a></li>
                        @endif

                        @if(Helpers::has_permission(Auth::user()->id, 'manage_sms_template'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'sms-temp' ? 'active' : '' }}"><a href="{{ url('customer-sms-temp/5') }}" class="">{{ __('SMS Templates') }}</a></li>
                        @endif

                        @if(Helpers::has_permission(Auth::user()->id, 'manage_preference|manage_theme_preference'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'preference' ? 'active' : '' }}"><a href="{{ url('setting-preference') }}" class="">{{ __('Preference') }}</a></li>
                        @endif

                        @if(Helpers::has_permission(Auth::user()->id, 'manage_barcode'))
                        <li class="{{ isset($sub_menu) && $sub_menu == 'barcode' ? 'active' : '' }}"><a href="{{ url('barcode/create') }}" class="">{{ __('Print Barcode/Level') }}</a></li>
                        @endif
                    </ul>
                </li><br><br>
                @endif
            </ul>
        </div>
    </div>
</nav>
