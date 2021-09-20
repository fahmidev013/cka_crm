
<!-- Profile Image -->
  <div class="card card-info display_block" id="nav">
    <div class="card-header p-t-20">
      <h5><a href="{{ URL::to('setting-preference') }}" id="general-settings">{{ __('Manage Company Settings') }}</a></h5>
    </div>
    <ul class="card-body nav nav-pills nav-stacked" id="mcap-tab" role="tablist">
      @if(Helpers::has_permission(Auth::user()->id, 'manage_company_setting'))
      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'general_preference' ? 'active' : ''}}" href="{{ URL::to('setting-preference')}}" id="s" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('General Preference') }}</a>
      </li>
      @endif

      @if(Helpers::has_permission(Auth::user()->id, 'manage_theme_preference'))
        <li class="nav-item"><a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'theme_preference' ? 'active' : ''}}" href="{{ URL::to('setting-appearance')}}">{{ __('Theme Preference') }}</a></li>
      @endif

    </ul>
  </div>
  <!-- /.box-body -->