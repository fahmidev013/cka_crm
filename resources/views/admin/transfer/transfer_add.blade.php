@extends('layouts.app')
@section('css')
{{-- Select2  --}}
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content')
  <!-- Main content -->
  <div class="col-sm-12" id="bank-transfer-add-container">
    <div class="card">
      <div class="card-header">
        <h5> <a href="{{ url('transfer/list') }}">{{ __('Transfer') }}</a> >> {{ __('New Transfer') }}</h5>
        <div class="card-header-right">

        </div>
      </div>
      <div class="card-body table-border-style">
        <div class="form-tabs">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active text-uppercase" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __('Transfer Information') }}</a>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
              <form action="{{ url('transfer/save') }}" method="post" id="transfer" class="form-horizontal">
                <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                <input type="hidden" name="outgoing_currency_id" id="outgoing_currency_id">
                <input type="hidden" name="incoming_currency_id" id="incoming_currency_id">

                  <div class="form-group row">
                    <label class="col-sm-2 control-label require" for="inputEmail3">{{ __('From') }}</label>
                    <div class="col-sm-6">
                       <select class="form-control select2" name="source" id="source">
                        <option value="">{{ __('Choose Account') }}</option>
                        @foreach($accounts as $account)
                            <option value="{{$account->id}}" currency-id="{{ $account->currency_id }}" currency-code="{{ $account->currency }}" >{{ $account->name }} ({{ $account->currency }})</option>
                          @endforeach
                        </select>
                        <span class="message"></span>
                        <label for="source" generated="true" class="error display_inline_block" id="source-error"></label>
                    </div>
                  </div>
                  <input type="hidden" name="from_acount_balance" id="from_acount_balance">
                 <div class="form-group row">
                      <label class="col-sm-2 control-label require"
                             for="inputEmail3">{{ __('To') }}</label>
                      <div class="col-sm-6">
                          <select class="select2" name="destination" id="destination">
                              <option value="">{{ __('Choose Account') }}</option>
                          </select>
                          <label for="destination" generated="true" class="error display_inline_block" id="destination-error"></label>
                          <span class="messageDestination"></span>
                      </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2 control-label require" for="inputEmail3">{{ __('Transfer Amount') }}</label>
                    <div class="col-sm-6">
                      <div class="input-group">
                        <input type="text" placeholder="{{ __('Transfer Amount') }}" class="form-control positive-float-number" id="amount" name="amount">
                        <div class="input-group-prepend display_none" id="currencyCode">
                          <span class="input-group-text" id="transfer_bank_currency"></span>
                        </div>
                      </div>
                      <label for="amount" generated="true" class="error"></label>
                      <span class="insufficientBalance display_none" id='insufficient_amount'></span>
                    </div>
                  </div>

                  <div class="form-group row display_none" id="exchange_rate_div">
                  <label for="amount" class="col-sm-2 control-label require">{{ __('Exchange Rate') }}  </label>
                  <div class="col-sm-6">
                    <div class="input-group">
                      <input type="text" placeholder="{{ __('Exchange Rate') }}" class="form-control positive-float-number" id="exchange_rate" name="exchange_rate">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="exchange_rate_currency_code"></span>
                      </div>
                    </div>
                    <label for="exchange_rate" generated="true" class="error" id="exchange_rate-error"></label>
                  </div>
                  <div class="col-sm-4"></div>
                </div>


                  <div class="form-group row incoming_amount_div">
                    <label class="col-sm-2 control-label require" for="inputEmail3">{{ __('Incoming Amount') }}</label>
                    <div class="col-sm-6">
                      <div class="input-group dynamicRow">
                      </div>
                      <label for="incoming_amount" generated="true" class="error"></label>
                    </div>
                  </div>

                   <div class="form-group row">
                    <label class="col-sm-2 control-label require" for="inputEmail3">{{ __('Bank Charge') }}</label>
                    <div class="col-sm-6">
                      <div class="input-group">
                        <input type="text" placeholder="{{ __('Bank Charge') }}" class="form-control positive-float-number" id="bank_charge" name="bank_charge" value="{{ formatCurrencyAmount(0) }}">
                        <div class="input-group-prepend display_none" id="bankFeeCode">
                          <span class="input-group-text" id="bank_charge_currency"></span>
                        </div>
                      </div>
                      <label for="bank_charge" generated="true" class="error"></label>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2 control-label" for="inputEmail3">{{ __('Description') }}</label>
                    <div class="col-sm-6">
                      <textarea class="form-control" id="description" placeholder="{{ __('Description') }}" name="description" rows="3"></textarea>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2 control-label require" for="inputEmail3">{{ __('Date') }}</label>
                    <div class="col-sm-6">
                    <input type="text" class="form-control" id="trans_date" name="trans_date">
                    </div>
                  </div>



                  <div class="form-group row">
                    <label class="col-sm-2 control-label" for="inputEmail3">{{ __('Reference') }}</label>
                    <div class="col-sm-6">
                      <input type="text" placeholder="{{ __('Reference') }}" class="form-control" id="reference" value="{{ $reference }}" readonly="true" name="reference">
                    </div>
                  </div>
                  <div class="col-sm-8 px-0 pt-2" id="btnSubmit">
                    <button class="btn btn-primary custom-btn-small" type="submit" id="submit">{{ __('Submit') }}</button>
                    <a href="{{ url('transfer/list') }}" class="btn btn-info custom-btn-small">{{ __('Cancel') }}</a>
                  </div>
                </form>
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
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/bank.min.js') }}"></script>
@endsection
