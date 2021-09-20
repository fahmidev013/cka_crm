    <ul class="nav nav-pills" role="tablist">
        <li class="nav-item">
            <a class="nav-link h-lightblue  {{ isset($profile) ? $profile : '' }}" href='{{url("user/team-member-profile/$user_id")}}' role="tab" aria-controls="mcap-default" aria-selected="true">{{ __('Profile') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link h-lightblue {{ isset($po_status) ? $po_status : '' }}"  href='{{url("user/purchase-list/$user_id")}}' role="tab" aria-controls="mcap-success" aria-selected="false">{{ __('Purchases') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link h-lightblue {{ isset($so_status) ? $so_status : '' }}" href='{{url("user/sales-order-list/$user_id")}}' role="tab" aria-controls="mcap-success" aria-selected="false">{{ __('Quotations') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link h-lightblue {{ isset($invoice) ? $invoice : '' }}" href='{{url("user/sales-invoice-list/$user_id")}}' role="tab" aria-controls="mcap-success" aria-selected="false">{{ __('Invoices') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link h-lightblue {{ isset($invoicePayment) ? $invoicePayment : '' }}" href='{{url("user/user-payment-list/$user_id")}}' role="tab" aria-controls="mcap-success" aria-selected="false">{{ __('Invoice Payments') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link h-lightblue {{ isset($purchPayment) ? $purchPayment : '' }}" href='{{url("user/user-purchase-payment-list/$user_id")}}' role="tab" aria-controls="mcap-success" aria-selected="false">{{ __('Purchase Payments') }}</a>
        </li>

        <li class="nav-item">
            <a class="nav-link h-lightblue {{ isset($transfer) ? $transfer : '' }}" href='{{url("user/user-transfer-list/$user_id")}}' role="tab" aria-controls="mcap-success" aria-selected="false">{{ __('Transfers') }}</a>
        </li>

        <li class="nav-item">
            <a class="nav-link h-lightblue {{ isset($projectAssign) ? $projectAssign : '' }}" href='{{url("user/project-list/$user_id")}}' role="tab" aria-controls="mcap-success" aria-selected="false">{{ __('Projects') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link h-lightblue {{ isset($taskAssign) ? $taskAssign : '' }}" href='{{url("user/task-list/$user_id")}}' role="tab" aria-controls="mcap-success" aria-selected="false">{{ __('Tasks') }}</a>
        </li>
    </ul>