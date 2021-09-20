@extends('layouts.app')
@section('css')
{{-- select2 css --}} 
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection
@section('content')

<div class="col-sm-12" id="bank-transfer-detail-container">
  <div class="card user-list">
    <div class="card-header">
      <h5><a href="{{url('transfer/list')}}">{{ __('Transfer Information') }}</a></h5>
      <div class="card-header-right">
        
      </div>
    </div>
    <div class="card-block">

       <div class="row">
        <div class="col-sm-6 mb-2">
            <div class="text-uppercase color_black"><strong class="f-bold">{{ __('Transfer Date') }} : {{ formatDate($fetchData->transaction_date) }}</strong></div>
        </div>
      </div>

      <div class="row pt-0 mb-3">
        <div class="col-sm-8 col-md-6 col-xs-12">
           <div class="col-sm-12">
            <label class="control-label"><strong>{{ __('From Account') }}</strong></label>
          </div>
          <div class="col-sm-12">
            <label class="control-label">{{ __('Bank') }} : {{ $fetchData->fromBank->bank_name }}</label>
          </div>
          <div class="col-sm-12">
            <label class="control-label">{{ __('A/c Name') }} : <a href=" {{ url('bank/edit-account/transaction/' . $fetchData->fromBank->id) }}">&nbsp{{ $fetchData->fromBank->name }}</a> &nbsp{{ $fetchData->fromBank->currency->name }}</label>
          </div>
          <div class="col-sm-12">
            <label class="control-label">{{ __('A/c Number') }} : {{ $fetchData->fromBank->account_number }}</label>
          </div>
        </div>

         <div class="col-sm-4 col-md-6 col-xs-12">
          <div class="col-sm-12">
            <label class="control-label"><strong>{{ __('To Account') }}</strong></label>
          </div>
          <div class="col-sm-12">
            <label class="control-label">{{ __('Bank') }} : {{ $fetchData->toBank->bank_name }}</label>
          </div>
          <div class="col-sm-12">
            <label class=" control-label">{{ __('A/c Name') }} : <a href=" {{ url('bank/edit-account/transaction/' . $fetchData->toBank->id) }}">{{ $fetchData->toBank->name }}</a>&nbsp{{ $fetchData->toBank->currency->name }}</label>
          </div>
          <div class="col-sm-12">
            <label class=" control-label">{{ __('A/c Number') }} : {{ $fetchData->toBank->account_number }}</label>
          </div>
        </div>

      </div>
      <div class="form-tabs m-b-5">
        <ul class="nav nav-tabs ml-5" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link text-uppercase active" data-toggle="tab" href="#transaction" role="tab" aria-expanded="false">&nbsp&nbsp{{ __('Transaction Details') }}</a>
          </li>
        </ul>
      </div>

      <div class="tab-content">
        <div class="tab-pane fade show active" id="transaction">
            <div class="row transactions">
              <div class="col-md-6 transaction-information1">
                <strong class="f-bold">{{ __('Transferred Amount') }}<span> ({{ $fetchData->fromBank->currency->name }})</span></strong>
                </div>
              <div class="col-md-6 f-bold" id="transaction-information2">
                {{ formatCurrencyAmount($fetchData->amount, $fetchData->fromBank->currency->symbol) }}
              </div>
            </div>
            <div class="row transactions">
              <div class="col-md-6 transaction-information1">
                <strong class="f-bold">{{ __('Bank Charge') }}<span> ({{ $fetchData->fromBank->currency->name }})</span></strong>
                </div>
              <div class="col-md-6 f-bold transaction-information2">
                {{ formatCurrencyAmount($fetchData->fee, $fetchData->fromBank->currency->symbol) }}
              </div>
            </div>
            <div class="row transactions">
              <div class="col-md-6 transaction-information1">
                <strong class="f-bold">{{ __('Incoming Amount') }}<span> ({{$fetchData->toBank->currency->name }})</span></strong>
                </div>
              <div class="col-md-6 f-bold transaction-information2">
                {{ formatCurrencyAmount($fetchData->incoming_amount, $fetchData->toBank->currency->symbol) }}</td>
              </div>
            </div>
          </div>
          @if (!empty($fetchData->memo))
          <div class="row">
            <div class="col-sm-6">
                <div class="text-uppercase color_black"><strong class="f-bold">{{ __('Description') }} :</strong> <span>{{ $fetchData->memo }}</span></div>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
@section('js')
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script type="{{ asset('public/dist/js/custom/bank.min.js') }}"></script>
@endsection