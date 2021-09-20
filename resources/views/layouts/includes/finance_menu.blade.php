<!-- Profile Image -->
  <div class="card card-info display_block" id="finance">
    <div class="card-header">
    <h5><a href="{{ URL::to('tax') }}">{{ __('Manage Finance Settings') }}</a></h5>
    </div>
    <ul class="card-body nav nav-pills nav-stacked settings-tab" id="mcap-tab" role="tablist">
      @if(Helpers::has_permission(Auth::user()->id, 'manage_tax'))
      <li class="nav-item">
        <a class="nav-link h-lightblue text-left display_table {{ isset($list_menu) &&  $list_menu == 'tax' ? 'active' : ''}}" href="{{ URL::to('tax')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Taxes') }}</a>
      </li>
      @endif

      @if(Helpers::has_permission(Auth::user()->id, 'manage_account_type'))
      <li class="nav-item">
        <a class="nav-link h-lightblue text-left display_table {{ isset($list_menu) &&  $list_menu == 'account_type' ? 'active' : ''}}" href="{{ URL::to('bank/account-type')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Bank Account Type') }}</a>
      </li>
      @endif
      
      @if(Helpers::has_permission(Auth::user()->id, 'manage_currency'))
        <li class="nav-item">
          <a class="nav-link h-lightblue text-left display_table {{ isset($list_menu) &&  $list_menu == 'currency' ? 'active' : ''}}" href="{{ URL::to('currency')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Currencies') }}</a>
        </li>
      @endif

      @if(Helpers::has_permission(Auth::user()->id, 'manage_payment_term'))
        <li class="nav-item">
          <a class="nav-link h-lightblue text-left display_table {{ isset($list_menu) &&  $list_menu == 'payment_term' ? 'active' : ''}}" href="{{ URL::to('payment/terms')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Payments Terms') }}</a>
        </li>
      @endif

      @if(Helpers::has_permission(Auth::user()->id, 'manage_payment_method'))
        <li class="nav-item">
          <a class="nav-link h-lightblue text-left display_table {{ isset($list_menu) &&  $list_menu == 'payment_method' ? 'active' : ''}}" href="{{ URL::to('payment/method')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Payments Method') }}</a>
        </li>
      @endif
    </ul>
  </div>
