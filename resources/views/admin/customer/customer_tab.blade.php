      <ul class="nav nav-pills" role="tablist">
        <li class="nav-item">
            <a class="nav-link h-lightblue {{ (Request::is('customer/edit/*') ? 'active' : '') }}"  href="{{ url("customer/edit/$customerData->id" )}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Profile') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link h-lightblue {{ (Request::is('customer/ledger/*') ? 'active' : '') }}"  href="{{ url("customer/ledger/$customerData->id" )}}" role="tab" aria-controls="mcap-success" aria-selected="false">{{ __('Customer Ledger') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link h-lightblue {{ (Request::is('customer/order/*') ? 'active' : '') }}"  href="{{ url("customer/order/$customerData->id" )}}" role="tab" aria-controls="mcap-success" aria-selected="false">{{ __('Quotations') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link h-lightblue {{ (Request::is('customer/invoice/*') ? 'active' : '') }}"  href="{{ url("customer/invoice/$customerData->id" )}}" role="tab" aria-controls="mcap-success" aria-selected="false">{{ __('Invoices') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link h-lightblue {{ (Request::is('customer/payment/*') ? 'active' : '') }}"  href="{{ url("customer/payment/$customerData->id" )}}" role="tab" aria-controls="mcap-success" aria-selected="false">{{ __('Payments')  }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link h-lightblue {{ (Request::is('customer/notes/*') ? 'active' : '') }}"  href="{{ url("customer/notes/$customerData->id" )}}" role="tab" aria-controls="mcap-success" aria-selected="false">{{ __('Notes') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link h-lightblue {{ (Request::is('customer/project/*') ? 'active' : '') }}"  href="{{ url("customer/project/$customerData->id" )}}" role="tab" aria-controls="mcap-success" aria-selected="false">{{ __('Projects')  }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link h-lightblue {{ (Request::is('customer/task/*') ? 'active' : '') }}"  href="{{ url("customer/task/$customerData->id" )}}" role="tab" aria-controls="mcap-success" aria-selected="false"> {{ __('Tasks') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link h-lightblue {{ (Request::is('customer/ticket/*') ? 'active' : '') }}"  href="{{ url("customer/ticket/$customerData->id" )}}" role="tab" aria-controls="mcap-success" aria-selected="false">{{ __('Tickets') }}</a>
        </li>
      </ul>