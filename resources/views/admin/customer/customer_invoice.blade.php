@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
  <!-- daterange picker -->
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection
@section('content')

<!-- Main content -->
<div class="col-sm-12" id="customer-invoice-container">
  <div class="card">
    <div class="card-header">
      <h5> <a href="{{ url('customer/list') }}">{{ __('Customers') }}</a> >> {{ $customerData->first_name }} {{ $customerData->last_name }} >> {{ __('Invoices') }}</h5>
      <div class="card-header-right">

      </div>
    </div>
    <div class="card-body p-0" id="no_shadow_on_card">
    @include('admin.customer.customer_tab')
      <div class="col-sm-12 mb-1p5">
        <form class="form-horizontal" action="{{ url('customer/invoice/' . $customerData->id) }}" method="GET" id='orderListFilter'>
          <input class="form-control" id="startfrom" type="hidden" name="from" value="<?= isset($from) ? $from : '' ?>">
          <input class="form-control" id="endto" type="hidden" name="to" value="<?= isset($to) ? $to : '' ?>">
          <input type="hidden" name="customer" id="customer" value="{{ $customerData->id }}">
          <div class="col-md-12 col-sm-12 col-xs-12 m-l-10 margin-btm-1p">
            <div class="row mt-3">
              <div class="col-xl-4 col-lg-6 col-md-5 mb-2">
                <div class="input-group">
                  <button type="button" class="form-control" id="daterange-btn">
                    <span class="float-left">
                      <i class="fa fa-calendar"></i>  {{ __('Pick a date range') }}
                    </span>
                    <i class="fa fa-caret-down float-right pt-1"></i>
                  </button>
                </div>
              </div>

              <div class="col-md-3 col-sm-4 col-xs-12 mb-2">
                <select class="form-control select2" name="pay_status_type" id="pay_status_type">
                  <option value="">{{ __('All') }}</option>
                  <option value="paid" {{ $payStatus == 'paid' ? 'selected' : '' }}>{{ __('Paid') }}</option>
                  <option value="partial" {{ $payStatus == 'partial' ? 'selected' : '' }}>{{ __('Partially paid') }}</option>
                  <option value="unpaid" {{ $payStatus == 'unpaid' ? 'selected' : '' }}>{{ __('Unpaid') }}</option>
                </select>
              </div>

              <div class="col-md-1 col-sm-1 col-xs-12 p-md-0">
                <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small push-sm-right mt-0 mr-0">{{  __('Go') }}</button>
              </div>
            </div>
          </div>
        </form>
        <div class="row m-2">
          <div class="col-12 col-md-6 col-lg-3 col-xl-3">
            <div class="text-white">
              <div class="p-10 status-border" id="inv-total-amount">
                <span class="f-w-700 f-20">{{ __('Total Amount') }}</span><br>
                <span class="f-16">{{ formatCurrencyAmount(isset($invoiceSummery['totalInvoice']) ?  $invoiceSummery['totalInvoice'] : 0, isset($customerData->currency->symbol) && !empty($customerData->currency->symbol) ? $customerData->currency->symbol : '') }}
                </span><br>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-3 col-xl-3">
            <div class="text-white">
              <div class="p-10 status-border" id="inv-paid-amount">
                <span class="f-w-700 f-20">{{ __('Total Paid') }}</span><br>
                <span class="f-16">
                  {{ formatCurrencyAmount(isset($invoiceSummery['totalPaid']) ?  $invoiceSummery['totalPaid'] : 0, isset($customerData->currency->symbol) && !empty($customerData->currency->symbol) ? $customerData->currency->symbol : '') }}
                </span><br>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-3 col-xl-3">
            <div class="text-white">
              <div class="p-10 status-border" id="inv-due-amount">
                <span class="f-w-700 f-20">{{ __('Total Due') }}</span><br>
                <span class="f-16">
                  {{ formatCurrencyAmount(isset($invoiceSummery['totalDue']) ?  $invoiceSummery['totalDue'] : 0, isset($customerData->currency->symbol) && !empty($customerData->currency->symbol) ? $customerData->currency->symbol : '') }}
                </span><br>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-3 col-xl-3">
            <div class="text-white">
              <div class="p-10 status-border" id="inv-over-due-amount">
                <span class="f-w-700 f-20">{{ __('Over Due') }}</span><br>
                <span class="f-16">
                  {{ formatCurrencyAmount(isset($invoiceSummery['overDue']) ?  $invoiceSummery['overDue'] : 0, isset($customerData->currency->symbol) && !empty($customerData->currency->symbol) ? $customerData->currency->symbol : '') }}
                </span><br>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card-block">
        <div class="col-sm-12">
          <div class="table-responsive">
            {!! $dataTable->table(['class' => 'table table-striped table-hover dt-responsive customer-invoices', 'width' => '100%', 'cellspacing' => '0']) !!}
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="confirmDeleteLabel"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary custom-btn-small" data-dismiss="modal">{{ __('Close') }}</button>
            <button type="button" id="confirmDeleteSubmitBtn" data-task="" class="btn btn-danger custom-btn-small">{{ __('Submit') }}</button>
            <span class="ajax-loading"></span>
          </div>
        </div>
      </div>
  </div>
@include('layouts.includes.message_boxes')
@endsection

@section('js')
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
{!! $dataTable->scripts() !!}
<script type="text/javascript">
  "use strict";
  var startDate = "{!! isset($from) ? $from : 'undefined' !!}";
  var endDate   = "{!! isset($to) ? $to : 'undefined' !!}";
</script>
<script src="{{ asset('public/dist/js/custom/customer.min.js') }}"></script>
@endsection
