@extends('layouts.app')
@section('css')
  {{-- Select2  --}}
  <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/codemirror/lib/codemirror.css') }}">
@endsection
@section('content')

  <div class="col-sm-12" id="emailTemplate-settings-container">
    <div class="row">
      <div class="col-sm-3">
        @include('layouts.includes.mail_menu')
      </div>
      <div class="col-sm-9">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title f-18">
              @if($tempId == 1)
                {{ __('Compose Payment Message') }}
              @elseif($tempId == 2)
                {{ __('Compose Shipment Message') }}
              @elseif($tempId == 3)
                {{ __('Compose Purchase Order Message') }}
              @elseif($tempId == 4)
                {{ __('Compose Sales Invoice Message') }}
              @elseif($tempId == 5)
                {{ __('Compose Sales Quotation Message') }}
              @elseif($tempId == 6)
                {{ __('Compose Ordered Item Packing Message') }}
              @elseif($tempId == 7)
                {{ __('Compose Ticket Assignee Message') }}
              @elseif($tempId == 8)
                {{ __('Compose Customer Message') }}
              @elseif($tempId == 9)
                {{ __('Compose Department Message') }}
              @elseif($tempId == 10)
                {{ __('Compose Task Assignee Message') }}
              @elseif($tempId == 12)
                {{ __('Compose Project create Message') }}
              @elseif($tempId == 15)
                {{ __('Compose Purchase Payment Message') }}
              @elseif($tempId == 13)
                {{ __('Compose Project create Message') }}
              @elseif($tempId == 17)
                {{ __('Reset Password') }}
              @elseif($tempId == 18)
                {{ __('Update Password') }}
              @elseif($tempId == 19)
                {{ __('Compose POS Invoice Message') }}
              @elseif ($tempId == 20)
                 {{ __('Compose Task Status Message') }}
              @elseif ($tempId == 21)
                  {{ __('Compose Task Comment Message') }}
              @elseif($tempId == 22)
                  {{ __('Compose Add Customer Message') }}
              @elseif($tempId == 23)
                 {{ __('Compose Add Supplier Message') }}
              @elseif($tempId == 24)
                 {{ __('Compose Add Team Member Message') }}
              @elseif($tempId == 25)
               {{ __('Compose Customer Activation Message After Registration') }}
              @endif
            </h3>
            <div class="card-header-right inline-block">
              <a href="#collapseExample" class="btn btn-outline-primary custom-btn-small" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="true" aria-controls="collapseExample">{{ __('Available Variables') }}</a>
            </div>
          </div>
          <div class="card-body">
            <div class="collapse" id="collapseExample">
              <div>
                @if($tempId == 1)
                <div class="row">
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Invoice Referance Number') }}: <span class="copyButton">{invoice_reference_no}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Order Customer Name') }}: <span class="copyButton">{order_reference_no}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Payment ID') }}: <span class="copyButton">{payment_id}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Customer Name') }}: <span class="copyButton">{customer_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Payment Date') }}: <span class="copyButton">{payment_date}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Payment Method') }}: <span class="copyButton">{payment_method}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Total Amount') }}: <span class="copyButton">{total_amount}</span></span>
                    </div>
                  </div>
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Due Date') }}: <span class="copyButton">{due_date}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Billing City') }}: <span class="copyButton">{billing_city}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Billing State') }}: <span class="copyButton">{billing_state}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Billing Zip Code') }}: <span class="copyButton">{billing_zip_code}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Billing Country') }}: <span class="copyButton">{billing_country}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Company Name') }}: <span class="copyButton">{company_name}</span></span>
                    </div>
                  </div>
                </div>
                @elseif($tempId == 2)
                {{ __('Compose Shipment Message') }}

                @elseif($tempId == 3)
                {{ __('Compose Purchase Order Message') }}

                @elseif($tempId == 4 || $tempId == 19)
                <div class="row">
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Invoice Referance Number') }}: <span class="copyButton">{invoice_reference_no}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Customer Name') }}: <span class="copyButton">{order_reference_no}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Company Name') }}: <span class="copyButton">{company_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Customer Name') }}: <span class="copyButton">{customer_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Currency') }}: <span class="copyButton">{currency}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Total Amount') }}: <span class="copyButton">{total_amount}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Due Date') }}: <span class="copyButton">{due_date}</span></span>
                    </div>
                  </div>
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Billing Street') }}: <span class="copyButton">{billing_street}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Billing City') }}: <span class="copyButton">{billing_city}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Billing State') }}: <span class="copyButton">{billing_state}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Billing Zip Code') }}: <span class="copyButton">{billing_zip_code}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Billing Country') }}: <span class="copyButton">{billing_country}</span></span>
                    </div>
                  </div>
                </div>
                @elseif($tempId == 5)
                <div class="row">
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Quotation Reference Number') }}: <span class="copyButton">{order_reference_no}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Quotation Customer Name') }}: <span class="copyButton">{customer_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Quotation Total Amount') }}: <span class="copyButton">{total_amount}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Quotation Billing Street') }}: <span class="copyButton">{billing_street}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Quotation Billing Country') }}: <span class="copyButton">{billing_country}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Quotation Billing Summary') }}: <span class="copyButton">{order_summery}</span></span>
                    </div>
                  </div>
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Company Name') }}: <span class="copyButton">{company_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Quotation Currency') }}: <span class="copyButton">{currency}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Quotation Date') }}: <span class="copyButton">{order_date}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Quotation Billing City') }}: <span class="copyButton">{billing_city}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Quotation Billing Zip Code') }}: <span class="copyButton">{billing_zip_code}</span></span>
                    </div>
                  </div>
                </div>
                @elseif($tempId == 6)
                <div class="row">
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Purchase Invoice Number') }}: <span class="copyButton">{invoice_reference_no}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Purchase Company Name') }}: <span class="copyButton">{company_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Supplier Name') }}: <span class="copyButton">{supplier_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Purchase Currency') }}: <span class="copyButton">{currency}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Purchase Total Amount') }}: <span class="copyButton">{total_amount}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Purchase Due Date') }}: <span class="copyButton">{due_date}</span></span>
                    </div>
                  </div>
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Purchase Billing Street') }}: <span class="copyButton">{billing_street}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Purchase Billing City') }}: <span class="copyButton">{billing_city}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Purchase Billing State') }}: <span class="copyButton">{billing_state}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Purchase Billing Zip Code') }}: <span class="copyButton">{billing_zip_code}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Purchase Billing Country') }}: <span class="copyButton">{billing_country}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Purchase Summary') }}: <span class="copyButton">{invoice_summery}</span></span>
                    </div>
                  </div>
                </div>
                @elseif($tempId == 7)
                <div class="row">
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Ticket Subject') }}: <span class="copyButton">{ticket_subject}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Ticket Number') }}: <span class="copyButton">{ticket_no}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Customer Name') }}: <span class="copyButton">{customer_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Ticket Message') }}: <span class="copyButton">{ticket_message}</span></span>
                    </div>
                    </div>
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Ticket Status') }}: <span class="copyButton">{ticket_status}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Ticket Details') }}: <span class="copyButton">{details}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Company Name') }}: <span class="copyButton">{company_name}</span></span>
                    </div>
                  </div>
                </div>
                @elseif($tempId == 8)
                <div class="row">
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Ticket Subject') }}: <span class="copyButton">{ticket_subject}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Ticket Number') }}: <span class="copyButton">{ticket_no}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Customer Name') }}: <span class="copyButton">{customer_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Ticket Message') }}: <span class="copyButton">{ticket_message}</span></span>
                    </div>
                    </div>
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Ticket Status') }}: <span class="copyButton">{ticket_status}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Ticket Details') }}: <span class="copyButton">{details}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Company Name') }}: <span class="copyButton">{company_name}</span></span>
                    </div>
                  </div>
                </div>
                @elseif($tempId == 22)
                <div class="row">
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Customer Name') }}: <span class="copyButton">{customer_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Customer Panel URL') }}: <span class="copyButton">{company_url}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Company Name') }}: <span class="copyButton">{company_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Password') }}: <span class="copyButton">{password}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Email') }}: <span class="copyButton">{email}</span></span>
                    </div>
                  </div>
                </div>
                @elseif($tempId == 23)
                <div class="row">
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Supplier Name') }}: <span class="copyButton">{supplier_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Company Name') }}: <span class="copyButton">{company_name}</span></span>
                    </div>
                  </div>
                </div>
                @elseif($tempId == 24)
                <div class="row">
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Team Member Name') }}: <span class="copyButton">{user_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Admin Panel URL') }}: <span class="copyButton">{company_url}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Added by') }}: <span class="copyButton">{assigned_by_whom}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Company Name') }}: <span class="copyButton">{company_name}</span></span>
                    </div>
                  </div>
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('User ID') }}: <span class="copyButton">{user_id}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Password') }}: <span class="copyButton">{user_pass}</span></span>
                    </div>
                  </div>
                </div>
                @elseif($tempId == 9)
                <div class="row">
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Ticket Subject') }}: <span class="copyButton">{ticket_subject}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Ticket Number') }}: <span class="copyButton">{ticket_no}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Customer Name') }}: <span class="copyButton">{customer_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Ticket Message') }}: <span class="copyButton">{ticket_message}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Company Name') }}: <span class="copyButton">{company_name}</span></span>
                    </div>
                  </div>
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Ticket Status') }}: <span class="copyButton">{ticket_status}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Ticket Details') }}: <span class="copyButton">{details}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Member Name') }}: <span class="copyButton">{member_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Project Name') }}: <span class="copyButton">{project_name}</span></span>
                    </div>
                  </div>
                </div>
                @elseif($tempId == 10)
                <div class="row">
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Task Name') }}: <span class="copyButton">{task_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Assignee Name') }}: <span class="copyButton">{assignee_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Start Date') }}: <span class="copyButton">{start_date}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Due Date') }}: <span class="copyButton">{due_date}</span></span>
                    </div>
                    </div>
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Task Priority') }}: <span class="copyButton">{priority}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Status') }}: <span class="copyButton">{ticket_status}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Company Name') }}: <span class="copyButton">{company_name}</span></span>
                    </div>
                  </div>
                </div>
                @elseif($tempId == 12)
                <div class="row">
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Customer Name') }}: <span class="copyButton">{customer_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Project Name') }}: <span class="copyButton">{project_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Start Date') }}: <span class="copyButton">{start_date}</span></span>
                    </div>
                    </div>
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Status') }}: <span class="copyButton">{status}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Details') }}: <span class="copyButton">{details}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Company Name') }}: <span class="copyButton">{company_name}</span></span>
                    </div>
                  </div>
                </div>
                @elseif($tempId == 15)
                <div class="row">
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Purchase Referance No') }}: <span class="copyButton">{purchase_reference_no}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Supplier Name') }}: <span class="copyButton">{supplier_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Purchase Billing Street') }}: <span class="copyButton">{billing_street}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Purchase Billing City') }}: <span class="copyButton">{billing_city}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Purchase Billing Zip Code') }}: <span class="copyButton">{billing_zip_code}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Purchase Billing Country') }}: <span class="copyButton">{billing_country}</span></span>
                    </div>
                    </div>
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Payment ID') }}: <span class="copyButton">{payment_id}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Payment Date') }}: <span class="copyButton">{payment_date}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Payment Method') }}: <span class="copyButton">{payment_method}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Total Amount') }}: <span class="copyButton">{total_amount}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Company Name') }}: <span class="copyButton">{company_name}</span></span>
                    </div>
                  </div>
                </div>
                @elseif($tempId == 17)
                <div class="row">
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Password Reset URL') }}: <span class="copyButton">{password_reset_url}</span></span>
                    </div>
                      <div>
                          <span>{{ __('User Name') }}: <span class="copyButton">{user_name}</span></span>
                      </div>
                    <div>
                      <span>{{ __('Company Name') }}: <span class="copyButton">{company_name}</span></span>
                    </div>
                    </div>
                </div>
                @elseif($tempId == 18)
                <div class="row">
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('User Name') }}: <span class="copyButton">{user_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('User ID') }}: <span class="copyButton">{user_id}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Password') }}: <span class="copyButton">{user_pass}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Company Name') }}: <span class="copyButton">{company_name}</span></span>
                    </div>
                    </div>
                </div>
                @elseif ($tempId == 20)
                <div class="row">
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Assignee Name') }}: <span class="copyButton">{assignee_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Company Name') }}: <span class="copyButton">{company_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Task assignees') }}: <span class="copyButton">{all_assignee_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Task previous status') }}: <span class="copyButton">{task_pre_status}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Task previous status') }}: <span class="copyButton">{task_new_status}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Changed by') }}: <span class="copyButton">{changed_by}</span></span>
                    </div>
                  </div>
                    <div class="col-6 variable">
                      <div>
                        <span>{{ __('Task No') }}: <span class="copyButton">{task_id}</span></span>
                      </div>
                      <div>
                        <span>{{ __('Task Name') }}: <span class="copyButton">{task_name}</span></span>
                      </div>
                      <div>
                        <span>{{ __('Start Date') }}: <span class="copyButton">{start_date}</span></span>
                      </div>
                      <div>
                        <span>{{ __('Due Date') }}: <span class="copyButton">{due_date}</span></span>
                      </div>
                      <div>
                        <span>{{ __('Priority') }}: <span class="copyButton">{task_priority}</span></span>
                      </div>
                    <div>
                      <span>{{ __('Task Link') }}: <span class="copyButton">{task_link}</span></span>
                    </div>
                  </div>
                </div>
                @elseif ($tempId == 21)
                <div class="row">
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Assignee Name') }}: <span class="copyButton">{assignee_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Company Name') }}: <span class="copyButton">{company_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Task assignees') }}: <span class="copyButton">{all_assignee_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Task previous status') }}: <span class="copyButton">{task_pre_status}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Task previous status') }}: <span class="copyButton">{task_new_status}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Commented by') }}: <span class="copyButton">{commented_by}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Comment') }}: <span class="copyButton">{comment}</span></span>
                    </div>
                  </div>
                    <div class="col-6 variable">
                      <div>
                        <span>{{ __('Task No') }}: <span class="copyButton">{task_id}</span></span>
                      </div>
                      <div>
                        <span>{{ __('Task Name') }}: <span class="copyButton">{task_name}</span></span>
                      </div>
                      <div>
                        <span>{{ __('Start Date') }}: <span class="copyButton">{start_date}</span></span>
                      </div>
                      <div>
                        <span>{{ __('Due Date') }}: <span class="copyButton">{due_date}</span></span>
                      </div>
                      <div>
                        <span>{{ __('Priority') }}: <span class="copyButton">{task_priority}</span></span>
                      </div>
                    <div>
                      <span>{{ __('Task Link') }}: <span class="copyButton">{task_link}</span></span>
                    </div>
                  </div>
                </div>
                @elseif($tempId == 13)
                <div class="row">
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Assignee Name') }}: <span class="copyButton">{assignee}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Project Name') }}: <span class="copyButton">{project_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Customer Name') }}: <span class="copyButton">{customer_name}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Start Date') }}: <span class="copyButton">{start_date}</span></span>
                    </div>
                    </div>
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Status') }}: <span class="copyButton">{status}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Details') }}: <span class="copyButton">{details}</span></span>
                    </div>
                    <div>
                      <span>{{ __('Company Name') }}: <span class="copyButton">{company_name}</span></span>
                    </div>
                  </div>
                </div>
                @elseif($tempId == 25)
                <div class="row">
                  <div class="col-6 variable">
                    <div>
                      <span>{{ __('Customer Name') }}: <span class="copyButton">{customer_name}</span></span>
                    </div>
                      <div>
                          <span>{{ __('Activation Link') }}: <span class="copyButton">{activation_link}</span></span>
                      </div>
                      <div>
                          <span>{{ __('Company Name') }}: <span class="copyButton">{company_name}</span></span>
                      </div>
                    </div>
                </div>
              @endif
              </div>
              <hr>
            </div>
            <form action='{{url("customer-invoice-temp/$tempId")}}' method="post" id="template">
              {!! csrf_field() !!}
              <div class="form-group">
                <label for="Subject">{{ __('Subject') }}</label>
                <input class="form-control" name="en[subject]" type="text" value="{{$temp_Data[0]->subject}}">
                <input type="hidden" name="en[id]" value="1">
                @if ($errors->has('en[subject]'))
                      <span class="help-block">
                          <strong>{{ $errors->first('en[subject]') }}</strong>
                      </span>
                @endif
              </div>
              <div class="form-group">
                <label>{{ __('Body') }}</label>
                <button id="firstPreviewButton" title="Email" type="button" class="btn btn-outline-default btn-small float-right previewButton" data-toggle="modal" data-target="#previewModal"><b>{{ __('Preview') }}</b></button>
                <hr>
                <textarea id="firstEditor" name="en[body]" class="form-control temp_data_body">
                  {{$temp_Data[0]->body}}
                </textarea>
                @if ($errors->has('en[body]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('en[body]') }}</strong>
                    </span>
                @endif
              </div>
              <div class="accordion" id="accordion">
                @php $i = 1 @endphp
                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                @foreach($languages as $key => $language)

                <!-- Escape the english details -->
                @php if($language->short_name == 'en'){continue;} @endphp

                <div class="panel card card-info">
                  <div class="card-header with-border pt-0">
                    <h4 class="card-title h-20">
                      <a data-toggle="collapse" class="btn btn-link text-btn collapsed pl-0 lang-js" onclick="card('{{ $language->short_name }}')" data-parent="#accordion" href="#collapse{{ $language->short_name }}" aria-expanded="false">
                        {{ $language->name }}
                      </a>
                    </h4>
                  </div>
                  <div id="collapse{{ $language->short_name }}" class="panel-collapse collapse p-l-5" aria-expanded="false">
                    <div class="card-body ml-4">

                    <div class="form-group">
                      <label for="exampleInputEmail1">{{ __('Subject') }}</label>
                      <input class="form-control" name="{{ $language->short_name }}[subject]" type="text" value="{{isset($temp_Data[$key]->subject)?$temp_Data[$key]->subject:'Subject'}}">

                       <input type="hidden" name="{{ $language->short_name }}[id]" value="{{$language->id}}">
                    </div>

                      <div class="form-group">
                        <label>{{ __('Body') }}</label>
                        <button id="{{$i}}" title="Email" type="button" class="btn btn-outline-default btn-small float-right previewButton previewButton" data-toggle="modal" data-target="#previewModal"><b>{{ __('Preview') }}</b></button>
                        <hr>
                        <textarea id="Editor{{$i}}" name="{{ $language->short_name }}[body]" class="form-control h-300">
                          {{isset($temp_Data[$key]->body)?$temp_Data[$key]->body:'Body'}}
                        </textarea>
                      </div>
                    </div>
                  </div>
                </div>
                @php $i++ @endphp
                @endforeach
              </div>
            </div>
            <div class="card-footer">
              <input type="hidden" name="nthLoop" data-rel="{{$i}}" id="nthLoop">
              <div class="no-padding float-left">
                <button type="submit" class="btn btn-primary custom-btn-small">{{ __('Submit') }}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div id="previewModal" class="modal fade" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title">{{ __('Preview') }}</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div id="preview"></div>
            </div>
          </div>
      </div>
    </div>
  </div>


@endsection

@section('js')
{{-- Classic editor --}}
<script src="{{ asset('public/dist/plugins/codemirror/lib/codemirror.js') }}"></script>
<script src="{{ asset('public/dist/plugins/codemirror/mode/xml.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/finance.min.js') }}"></script>
@endsection
