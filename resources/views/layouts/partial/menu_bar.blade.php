<ul class="nav navbar-nav">
  <?php  
  	$id = Auth::guard('customer')->user()->id;
  ?>
  <li <?= isset($menu) && $menu == 'home' ? ' class="active"' : ''?> >
  	<a href='{{url("customer/dashboard")}}'>{{ __('Home') }}</a>
  </li>
  <li <?= isset($menu) && $menu == 'order' ? ' class="active"' : ''?> >
  	<a href='{{url("customer-panel/order/$id")}}'>{{ __('Quotations') }}</a>
  </li>
  <li  <?=isset($menu) && $menu == 'invoice' ? ' class="active"' : ''?> >
  	<a href="{{url("customer-panel/invoice/$id")}}">{{ __('Invoices') }}</a>
  </li>
  <li  <?=isset($menu) && $menu == 'payment' ? ' class="active"' : ''?> >
  	<a href="{{url("customer-panel/payment/$id")}}">{{ __('Payments') }}</a>
  </li>
  <li  <?=isset($menu) && $menu == 'ticket' ? ' class="active"' : ''?> >
  	<a href="{{url("customer-ticket/list/$id")}}">{{ __('Supports') }}</a>
  </li>

  <li  <?=isset($menu) && $menu == 'project' ? ' class="active"' : ''?> >
    <a href="{{url("customer-project/list/$id")}}">{{ __('Projects') }}</a>
  </li>
  
</ul>