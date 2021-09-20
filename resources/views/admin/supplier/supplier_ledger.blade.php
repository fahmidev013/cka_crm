@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
<!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection
@section('content')
  <div class="col-sm-12" id="supplier-ledger-container">
    <div class="card">
      <div class="card-header">
        <h5>{{$supplierData->name}}</h5>
        <div class="card-header-right">

        </div>
      </div>
      <div class="card-body p-0" id="no_shadow_on_card">
        @include('admin.supplier.supplier_tab')
        <div class="col-sm-12">
          <form class="form-horizontal" action="{{ url('supplier/payment/ledger/'.$supplierData->id) }}" method="GET" id='orderListFilter'>
            <input class="form-control" id="startfrom" type="hidden" name="from" value="<?= isset($from) ? $from : '' ?>">
            <input class="form-control" id="endto" type="hidden" name="to" value="<?= isset($to) ? $to : '' ?>">
            <input type="hidden" name="supplier" id="supplier" value="{{$supplierData->id}}">
            <div class="col-md-12 col-sm-12 col-xs-12 m-l-10">
            <div class="row mt-3">
              <div class="col-xl-4 col-lg-6 col-md-5 mb-2">
                <div class="input-group">
                  <button type="button" class="form-control" id="daterange-btn">
                    <span class="float-left">
                      <i class="fa fa-calendar"></i>  {{ __('Pick a date range') }}
                    </span>
                    <i class="fa fa-caret-down aero float-right pt-1"></i>
                  </button>
                </div>
              </div>
              <div class="col-md-1 col-sm-1 col-xs-12 p-md-0">
                <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small push-sm-right mt-0 mr-0">{{ __('Go') }}</button>
              </div>
              </div>
            </div>
          </form>
          <div class="row m-10">
            <div class="col-md-6 col-xl-4">
              <div class="card theme-bg bitcoin-wallet">
                <div class="card-block">
                  <h6 class="text-white mb-2">{{ __('Total Amount') }}</h6>
                  <h4 class="text-white mb-2 f-w-300"><strong id="total_purchase_amount1"></strong></h4>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-xl-4">
              <div class="card theme-bg2 bitcoin-wallet">
                <div class="card-block">
                  <h6 class="text-white mb-2">{{ __('Paid Amount') }}</h6>
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
      </div>
      <div class="card-block margin-neg-top-1p">
        <div class="col-sm-12">
          <div class="table-responsive">
            <table id="ledger" class="table table-striped table-hover dt-responsive w-100 supplier-ledger-table supplier-ledgers">
               <thead>
                <tr>
                  <th>#</th>
                  <th>{{ __('Date') }}</th>
                  <th>{{ __('Purchase Order') }}</th>
                  <th>{{ __('Paid Amount') }}</th>
                  <th>{{ __('Bill Amount') }}</th>
                  <th>{{ __('Balance') }}</th>
                </tr>
              </thead>
              <tbody>
                @php
                  $balance = $totalPaid = $totalBill = 0;
                @endphp
                @foreach($supplierLedger as $key=>$data)
                 @php
                   $balance += isset($data['total']) ? $data['total'] : 0;
                   $balance -= isset($data['amount']) ? $data['amount'] : 0;
                   $totalPaid += isset($data['amount']) ? $data['amount'] : 0;
                   $totalBill += isset($data['total']) ? $data['total'] : 0;
                 @endphp
                <tr>
                  <td>{{ ++$key }}</td>
                  <td>{{ formatDate($data['transaction_date']) }}</td>
                  <td>
                    @if (!empty($data['purchase_order_id']))
                      <a href="{{ url('purchase/view-purchase-details/'.$data['purchase_order_id'])}}">{{ $data['purchase_order']['reference'] }} </a>
                    @else
                      <a href="{{ url('purchase/view-purchase-details/'.$data['id'])}}">
                       {{ $data['reference'] }} </a>
                    @endif
                  </td>
                  <td>
                    @if(isset($data['amount']))
                       {{ formatCurrencyAmount($data['amount'], $data['currency']['symbol']) }}
                    @else
                       {{ '-' }}
                    @endif
                  </td>
                  <td>
                    @if(isset($data['total']))
                       {{ formatCurrencyAmount($data['total'], $data['currency']['symbol']) }}
                    @else
                       {{ '-' }}
                    @endif
                  </td>
                  <td>{{ formatCurrencyAmount($balance, $data['currency']['symbol']) }}</td>
                </tr>
                @endforeach
            </tbody>
              <tfoot>
                @if (!empty($data['currency']['symbol']))
                <tr>
                  <td></td>
                  <td></td>
                  <td class="text-right"><strong>Total = </strong></td>
                  <td><strong>{{ formatCurrencyAmount($totalPaid, $data['currency']['symbol']) }}</strong></td>
                  <td><strong>{{ formatCurrencyAmount($totalBill, $data['currency']['symbol']) }}</strong></td>
                  <td><strong>{{ formatCurrencyAmount($balance, $data['currency']['symbol']) }}</strong></td>
                </tr>
                @endif
              </tfoot>
            </table>
            <input type="hidden" id="total_purchase_amount" name="total_purchase_amount" value="{{ formatCurrencyAmount($totalBill, $supplierData->symbol) }}">
            <input type="hidden" id="paid_amount" name="paid_amount" value="{{ formatCurrencyAmount($totalPaid, $supplierData->symbol) }}">
            <input type="hidden" id="balance_amount" name="balance_amount" value="{{ formatCurrencyAmount($balance, $supplierData->symbol) }}">
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('js')
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script type="text/javascript">
    "use strict";
    var startDate   = "{!! isset($from) ? $from : 'undefined' !!}";
    var endDate     = "{!! isset($to) ? $to : 'undefined' !!}";
    var supplier    = "{{ $supplierData->name }}";
    var supplier_id = "{{ $supplierData->id }}";
</script>
<script src="{{ asset('public/dist/js/custom/supplier.min.js') }}"></script>

@endsection
