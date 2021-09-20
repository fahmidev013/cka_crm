<!-- Profile Image -->
  <div class="card card-info display_block">
    <div class="card-header">
      <h5><a href="{{ URL::to('customer-invoice-temp/5') }}">{{ __('Manage General Settings') }}</a></h5>
    </div>
    <ul class="card-body nav nav-pills nav-stacked settings-tab" id="mcap-tab" role="tablist">
      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'menu-5' ? 'active' : ''}}" href="{{ URL::to('customer-invoice-temp/5')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Quotation') }}</a>
      </li>

      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'menu-6' ? 'active' : ''}}" href="{{ URL::to('customer-invoice-temp/6')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Purchase') }}</a>
      </li>

      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'menu-4' ? 'active' : ''}}" href="{{ URL::to('customer-invoice-temp/4')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Invoice') }}</a>
      </li>

      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'menu-1' ? 'active' : ''}}" href="{{ URL::to('customer-invoice-temp/1')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Invoice Payments') }}</a>
      </li>

      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'menu-19' ? 'active' : ''}}" href="{{ URL::to('customer-invoice-temp/19')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('POS Invoice') }}</a>
      </li>

      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'menu-22' ? 'active' : ''}}" href="{{ URL::to('customer-invoice-temp/22')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Add Customer') }}</a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'menu-23' ? 'active' : ''}}" href="{{ URL::to('customer-invoice-temp/23')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Add Supplier') }}</a>
      </li>

      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'menu-24' ? 'active' : ''}}" href="{{ URL::to('customer-invoice-temp/24')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Add Team Member') }}</a>
      </li>

      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'menu-7' ? 'active' : ''}}" href="{{ URL::to('customer-invoice-temp/7')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Ticket Assignee') }}</a>
      </li>

      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'menu-10' ? 'active' : ''}}" href="{{ URL::to('customer-invoice-temp/10')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Task Assignee') }}</a>
      </li>

      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'menu-8' ? 'active' : ''}}" href="{{ URL::to('customer-invoice-temp/8')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Ticket Customer') }}</a>
      </li>

      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'menu-9' ? 'active' : ''}}" href="{{ URL::to('customer-invoice-temp/9')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Ticket Department') }}</a>
      </li>

      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'menu-12' ? 'active' : ''}}" href="{{ URL::to('customer-invoice-temp/12')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Project Create Customer') }}</a>
      </li>

      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'menu-13' ? 'active' : ''}}" href="{{ URL::to('customer-invoice-temp/13')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Project Create Assignee') }}</a>
      </li>

      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'menu-15' ? 'active' : ''}}" href="{{ URL::to('customer-invoice-temp/15')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Purchase Payments') }}</a>
      </li>

      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'menu-17' ? 'active' : ''}}" href="{{ URL::to('customer-invoice-temp/17')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Reset Password') }}</a>
      </li>

      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'menu-18' ? 'active' : ''}}" href="{{ URL::to('customer-invoice-temp/18')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Update Password') }}</a>
      </li>

       <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'menu-25' ? 'active' : ''}}" href="{{ URL::to('customer-invoice-temp/25')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Customer Activation Link') }}</a>
      </li>

      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'menu-20' ? 'active' : ''}}" href="{{ URL::to('customer-invoice-temp/20')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Task Status') }}</a>
      </li>

      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'menu-21' ? 'active' : ''}}" href="{{ URL::to('customer-invoice-temp/21')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Task Comment') }}</a>
      </li>
      
    </ul>
  </div>