@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection
@section('content')
<!-- Main content -->
<div class="col-sm-12" id="customer-ledger-container">
  <div class="card">
    <div class="card-header">
      <h5><a href="{{ url('customer/list') }}">{{ __('Customers') }}</a> >> {{ $customerData->first_name }} {{ $customerData->last_name }} >> {{  __('Ledger') }}</h5>
      <div class="card-header-right">
        
      </div>
    </div>
    <div class="card-body p-0" id="no_shadow_on_card">
    @include('admin.customer.customer_tab')    
    <div class="col-sm-12">
      <form class="form-horizontal" action="{{ url('customer/ledger/'.$customerData->id) }}" method="GET" id='orderListFilter'>
        <input class="form-control" id="startfrom" type="hidden" name="from" value="<?= isset($from) ? $from : '' ?>">
        <input class="form-control" id="endto" type="hidden" name="to" value="<?= isset($to) ? $to : '' ?>">
        <input type="hidden" name="customer" id="customer" value="{{ $customerData->id }}">
          <div class="col-md-12 col-sm-12 col-xs-12 m-l-10">
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
              <div class="col-md-1 col-sm-1 col-xs-12 p-md-0">
                <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small push-sm-right mt-0 mr-0">{{ __('Go') }}</button>
              </div>
            </div> 
          </div>
      </form>
      <div class="row m-10 margin-top-1p">
        <div class="col-md-6 col-xl-4">
          <div class="card theme-bg bitcoin-wallet">
            <div class="card-block">
              <h6 class="text-white mb-2">{{ __('Total Purchase') }}</h6>
              <h4 class="text-white mb-2 f-w-300"><strong id="total_purchase_amount1"></strong></h4>              
            </div>
          </div>
        </div>
        <div class="col-md-6 col-xl-4">
          <div class="card theme-bg2 bitcoin-wallet">
            <div class="card-block">
              <h6 class="text-white mb-2">{{ __('Paid') }}</h6>
              <h4 class="text-white mb-2 f-w-300"><strong id="paid_amount1"></strong></h4>                            
            </div>
          </div>
        </div>
        <div class="col-md-12 col-xl-4">
          <div class="card bg-c-blue bitcoin-wallet">
            <div class="card-block">
              <h6 class="text-white mb-2">{{ __('Balance') }}</h6>
              <h4 class="text-white mb-2 f-w-300"><strong id="balance_amount1"></strong></h4>              
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-block">
      <div class="col-sm-12">
        <div class="table-responsive">
          <table id="ledger" class="table table-striped table-hover dt-responsive w-100 customer-ledger">
             <thead>
              <tr>
                <th>{{ __('S/N') }}</th>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Invoice No') }}</th>
                <th>{{ __('Paid Amount') }} </th>
                <th>{{ __('Bill Amount') }}</th>
                <th>{{ __('Balance') }}</th>
              </tr> 
            </thead>
            <tbody>
              @php
                $balance = $totalPaid = $totalBill = 0;
              @endphp 
              @foreach($customer_ledger as $key=>$data)
               @php
                 $balance += isset($data['total']) ? $data['total'] : 0;
                 $balance -= isset($data['amount']) ? $data['amount'] : 0;
                 $totalPaid += isset($data['amount']) ? $data['amount'] : 0;
                 $totalBill += isset($data['total']) ? $data['total'] : 0;
               @endphp
              <tr>
                <td>{{ ++$key }}</td>
                <td>{{ formatDate($data['transaction_date']) }}</td>
                <td> <a title="view" href="{{ url('invoice/view-detail-invoice/' . $data['id']) }}">{{ $data['reference'] }}</a></td>
                <td>
                  @if(isset($data['amount']))
                     {{ formatCurrencyAmount($data['amount'], $customerData->currency->symbol) }}
                  @else
                     {{ '-' }}
                  @endif
                </td>
                <td>
                  @if(isset($data['total']))
                     {{ formatCurrencyAmount($data['total'], $customerData->currency->symbol) }}
                  @else
                     {{ '-' }}
                  @endif
                </td>
                <td>{{ formatCurrencyAmount($balance, $customerData->currency->symbol) }}</td>
              </tr>  
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <td></td>
                <td></td>
                <td class="text-right"><strong>{{ __('Total') }} = </strong></td>
                <td><strong>{{ formatCurrencyAmount($totalPaid, $customerData->currency->symbol) }}</strong></td>
                <td><strong>{{ formatCurrencyAmount($totalBill, $customerData->currency->symbol) }}</strong></td>
                <td><strong>{{ formatCurrencyAmount($balance, $customerData->currency->symbol) }}</strong></td>
              </tr>
            </tfoot>
          </table>
          <input type="hidden" id="total_purchase_amount" name="total_purchase_amount" value="{{ formatCurrencyAmount($totalBill, $customerData->currency->symbol) }}">
          <input type="hidden" id="paid_amount" name="paid_amount" value="{{ formatCurrencyAmount($totalPaid, $customerData->currency->symbol) }}">
          <input type="hidden" id="balance_amount" name="balance_amount" value="{{ formatCurrencyAmount($balance, $customerData->currency->symbol) }}">
        </div>
      </div>
      </div>
    </div>    
  </div>
</div>  
@endsection

@section('js')
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script>
  'use strict';
  var startDate = "{!! isset($from) ? $from : 'undefined' !!}";
  var endDate   = "{!! isset($to) ? $to : 'undefined' !!}";
  var ledger_customer_id = "{{ $customerData->id }}";
</script>
<script src="{{ asset('public/dist/js/custom/customer.min.js') }}"></script>
@endsection