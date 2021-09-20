<!-- Profile Image -->
  <div class="card card-info display_block" id="sms-menu">
    <div class="card-header">
      <h5><a href="{{ URL::to('customer-sms-temp/5') }}">{{ __('Manage SMS Template Settings') }}</a></h5>
    </div>
    <ul class="card-body nav nav-pills nav-stacked" id="mcap-tab" role="tablist">
      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'menu-5' ? 'active' : ''}}" href="{{ URL::to('customer-sms-temp/5')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Quotation') }}</a>
      </li>

      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'menu-4' ? 'active' : ''}}" href="{{ URL::to('customer-sms-temp/4')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Invoice') }}</a>
      </li>

      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'menu-19' ? 'active' : ''}}" href="{{ URL::to('customer-sms-temp/19')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('POS Invoice') }}</a>
      </li>
    </ul>
  </div>
