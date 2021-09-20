
<!-- Profile Image -->
  <div class="card crad-info display_block" id="nav">
    <div class="card-header p-t-20">
      <h5><a href="{{ URL::to('company/setting') }}">{{ __('Manage Company Settings') }}</a></h5>
    </div>
    <ul class="card-body nav nav-pills nav-stacked" id="mcap-tab" role="tablist">
      @if(Helpers::has_permission(Auth::user()->id, 'manage_company_setting'))
      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'sys_company' ? 'active' : ''}}" href="{{ URL::to('company/setting')}}" id="s" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Company Settings') }}</a>
      </li>
      @endif

      @if(Helpers::has_permission(Auth::user()->id, 'manage_department'))
        <li class="nav-item"><a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'ticket_setting' ? 'active' : ''}}" href="{{ URL::to("department-setting")}}">{{ __('Department') }}</a></li>
      @endif
            
      @if(Helpers::has_permission(Auth::user()->id, 'manage_role'))
      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'role' ? 'active' : ''}}" href="{{ URL::to('role/list')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('User Roles') }}</a>
      </li>
      @endif
      
      @if(Helpers::has_permission(Auth::user()->id, 'manage_location'))
      <li class="nav-item">
        <a class="nav-link h-lightblue text-left {{ isset($list_menu) &&  $list_menu == 'location' ? 'active' : ''}}" href="{{ URL::to('location')}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Locations') }}</a>
      </li>
      @endif

    </ul>
  </div>
  <!-- /.box-body -->