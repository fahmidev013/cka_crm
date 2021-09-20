<!-- Profile Image -->
  <div class="card card-info display_block">
    <div class="card-header">
      <h5><a href="{{ URL::to('item-category') }}">{{ __('Manage General Settings') }}</a></h5>
    </div>
    <ul class="card-body nav nav-pills nav-stacked settings-tab" id="mcap-tab" role="tablist">

      @if(Helpers::has_permission(Auth::user()->id, 'manage_item_category'))
      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'category' ? 'active' : ''}}" href="{{ URL::to('item-category')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Item Categories') }}</a>
      </li>
      @endif
      @if(Helpers::has_permission(Auth::user()->id, 'manage_language'))
      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'language' ? 'active' : ''}}" href="{{ URL::to('languages')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Languages') }}</a>
      </li>
      @endif
      @if(Helpers::has_permission(Auth::user()->id, 'manage_income_expense_category'))
        <li class="nav-item">
          <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'income-expense-category' ? 'active' : ''}}" href="{{ URL::to('income-expense-category/list')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Income Expenses Category') }}</a>
        </li>
      @endif

      @if(Helpers::has_permission(Auth::user()->id, 'manage_unit'))
      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'unit' ? 'active' : ''}}" href="{{ URL::to('unit')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Units') }}</a>
      </li>
      @endif

      @if(Helpers::has_permission(Auth::user()->id, 'manage_db_backup'))
      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'backup' ? 'active' : ''}}" href="{{ URL::to('backup/list')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Database Backup') }}</a>
      </li>
      @endif
      
      @if(Helpers::has_permission(Auth::user()->id, 'manage_captcha_setup'))
       <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'captcha_setup' ? 'active' : ''}}" href="{{ URL::to('captcha/setup')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Captcha Setup') }}</a>
      </li>
      @endif
      
      @if(Helpers::has_permission(Auth::user()->id, 'manage_currency_converter_setup'))
      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'currency_converter' ? 'active' : ''}}" href="{{ URL::to('currency-converter/setup')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Currency Converter Setup') }}</a>
      </li>
      @endif

      @if(Helpers::has_permission(Auth::user()->id, 'manage_email_setup'))
      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'email_setup' ? 'active' : ''}}" href="{{ URL::to('email/setup')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Email Setup') }}</a>
      </li>
      @endif

      @if(Helpers::has_permission(Auth::user()->id, 'manage_sms_setup'))
      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'sms_setup' ? 'active' : ''}}" href="{{ URL::to('sms/setup')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('SMS Setup') }}</a>
      </li>
      @endif

      @if(Helpers::has_permission(Auth::user()->id, 'manage_url_shortner'))
      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'short_url_setup' ? 'active' : ''}}" href="{{ URL::to('short-url/setup')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('URL Shortner') }}</a>
      </li>
      @endif

      @if(Helpers::has_permission(Auth::user()->id, 'manage_lead_status'))
      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'lead_status' ? 'active' : ''}}" href="{{ URL::to('lead-status')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Leads Status') }}</a>
      </li>
      @endif

      @if(Helpers::has_permission(Auth::user()->id, 'manage_lead_source'))
      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'lead_source' ? 'active' : ''}}" href="{{ URL::to('lead-source')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Lead Sources') }}</a>
      </li>
      @endif
      @if(Helpers::has_permission(Auth::user()->id, 'manage_group'))
       <li class="nav-item">
         <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'group' ? 'active' : ''}}" href="{{ URL::to('groups')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Group') }}</a>
       </li>
       @endif
      @if(Helpers::has_permission(Auth::user()->id, 'manage_canned_message'))
         <li class="nav-item">
           <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'canned_message' ? 'active' : ''}}" href="{{ URL::to('canned/messages')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Canned Message') }}</a>
         </li>
      @endif
          @if(Helpers::has_permission(Auth::user()->id, 'manage_canned_link'))
              <li class="nav-item">
                  <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'canned_link' ? 'active' : ''}}" href="{{ URL::to('canned/links')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Canned Link') }}</a>
              </li>
          @endif
    </ul>
  </div>
  <!-- /.card-body -->
