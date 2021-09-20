@extends('layouts.app')
@section('css')
@endsection
@section('content')
<div class="col-sm-12">
  <div class="card user-list">
    <div class="card-header">
      <a href="{{url('transaction/list')}}"><h5>{{ __('Transaction Details') }}</h5></a>
      <div class="card-header-right">

      </div>
    </div>
    <div class="card-block p-0">
      <div class="row mb-1">
        <div class="col-md-6 pl-5">
          @if (!empty($fetchData->paymentMethod) && $fetchData->paymentMethod->name == "Bank")
          <div>Bank : {{ !empty($fetchData->account) ? $fetchData->account->bank_name : '' }}</div>
          <div>A/c Name : {{ !empty($fetchData->account) ? $fetchData->account->name : '' }}</div>
          <div>A/c Number : {{ !empty($fetchData->account) ? $fetchData->account->account_number : '' }}</div>
          @else
           <div>Payment Method : {{ !empty($fetchData->paymentMethod) ? $fetchData->paymentMethod->name : ''}}</div>
          @endif
          <div>{{ __('Currency') }} : {{ !empty($fetchData->account->currency) ? $fetchData->account->currency->name : $currency->name }}</div>
          <div>{{ __('Transaction From') }} : {{ formatDate($fetchData->transaction_date) }}</div>
        </div>
      </div>
      <div class="form-tabs m-b-5">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link text-uppercase active" data-toggle="tab" href="#transaction" role="tab" aria-expanded="false">&nbsp&nbsp {{ __('Transaction Details') }}</a>
          </li>
        </ul>
      </div>
      <div class="tab-content">
        <div class="tab-pane fade show active" id="transaction">
          <div class="row">
            <div class="col-md-12 table-responsive">
              <table class="table table-bordered">
                <tbody>
                  <tr class="dynamicRows">
                    <th width="75%" class="text-center transactionDetailsTable">{{ __('Type') }}</th>
                    <th width="20%" class="text-center transactionDetailsTable">{{ __('Amount') }}</th>
                  </tr>

                  <tr class="tbl_header_color">
                    <td class="text-center transactionDetailsTable"><strong class="f-bold align-center">{{ ucwords(str_replace ('_',' ', strtolower($fetchData->transaction_method))) }}
                      @if ($fetchData->transaction_method == "TRANSFER")
                      ({{ $fetchData->transaction_type }})
                      @endif
                    </strong></td>
                    <td class="text-center transactionDetailsTable f-bold align-center">{{ formatCurrencyAmount(abs($fetchData->amount), $currency->symbol) }}</td>
                  </tr>
                  </tbody>
              </table>
            </div>
          </div>
          @if (!empty($fetchData->description))
          <div class="row">
            <div class="col-md-6">
                <div class="text-uppercase color_black"><strong class="f-bold;">{{ __('Description') }} :</strong> <span class="font-italic f-13">{{ $fetchData->description }}</span></div>
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
@endsection
