    <ul class="nav nav-pills" role="tablist">
        <li class="nav-item">
            <a class="nav-link h-lightblue  {{$tab == 'supplier_edit' ? 'active' : ''}}" href="{{url("edit-supplier/$supplierData->id")}}" role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Profile') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link h-lightblue {{$tab == 'supplier_ledger' ? 'active' : ''}}"  href="{{url("supplier/payment/ledger/$supplierData->id")}}" role="tab" aria-controls="mcap-success" aria-selected="false">{{ __('Supplier Ledger') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link h-lightblue {{$tab == 'supplier_orders' ? 'active' : ''}}" href="{{url("supplier/orders/$supplierData->id")}}" role="tab" aria-controls="mcap-success" aria-selected="false">{{ __('Purchase Orders') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link h-lightblue {{$tab == 'supplier_payment' ? 'active' : ''}}" href="{{url("supplier/payment/$supplierData->id")}}" role="tab" aria-controls="mcap-success" aria-selected="false">{{ __('Supplier Payments') }}</a>
        </li>
    </ul>