@extends('layouts.app')
@section('css')
{{-- Select2  --}}
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
  <!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content')
  <!-- Main content -->
  <div class="col-sm-12" id="bank-edit-container">
    <div class="card">
      <div class="card-header">
        <h5> <a href="{{url('bank/list')}}">{{ __('Bank Accounts') }}</a> >> {{ __('Edit Account') }} >> {{ $accountInfo->name }} ({{ $accountInfo->currency['name'] }})</h5>
        <div class="card-header-right">

        </div>
      </div>
      <div class="card-body table-border-style">
        <div class="form-tabs">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link text-uppercase <?= ($tab == 'edit') ? 'active' : '' ?>" href="#edit" id="edit-tab" data-toggle="tab" role="tab" aria-controls="edit" aria-selected="true">{{ __('Edit Account') }}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-uppercase <?= ($tab == 'transaction') ? 'active' : '' ?>" href="#transaction" id="transaction-tab" data-toggle="tab" role="tab" aria-controls="transaction" aria-selected="true">{{ __('Transactions') }}</a>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show <?= ($tab == 'edit') ? 'active' : '' ?>" id="edit" role="tabpanel" aria-labelledby="edit-tab">
              <form action="{{ url('bank/update-account') }}" method="post" id="bank" class="form-horizontal">
                <input type="hidden" value="{{ csrf_token() }}" name="_token">
                <input type="hidden" value="{{ $accountInfo->id }}" name="id" id="account_id">
                  <div class="form-group row">
                    <label class="col-sm-2 control-label require" for="inputEmail3">{{ __('Account Name')}}</label>
                    <div class="col-sm-6">
                      <input type="text" placeholder="{{ __('Account Name')}}" class="form-control" id="account_name" name="account_name" value="{{ $accountInfo->name }}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2 control-label require" for="inputEmail3">{{ __('Account Type') }}</label>
                    <div class="col-sm-6">
                       <select class="form-control select w-100" name="account_type_id" id="account_type_id">
                        @foreach($accountTypes as $data)
                          <option value="{{ $data->id }}" <?= ($data->id == $accountInfo->account_type_id)? 'selected' : ''; ?>>{{ $data->name }}</option>
                        @endforeach
                        </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2 control-label require" for="inputEmail3">{{ __('Account Number') }}</label>
                    <div class="col-sm-6">
                      <input type="text" placeholder="{{ __('Account Number') }}" class="form-control" id="account_no" name="account_no" value="{{ $accountInfo->account_number }}">
                      <label for="account_no" generated="true" class="error display_none" id="error_account_no">{{ __('This field is required.') }}</label>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2 control-label require" for="inputEmail3">{{ __('Currency') }}</label>
                    <div class="col-sm-6" id="currency_div">
                       <select class="form-control select w-100" name="currency_id" id="currency_id" disabled>
                        <option value="">{{ __('Select One') }}</option>
                        @foreach($currencies as $data)
                          <option value="{{ $data->id }}" <?= $accountInfo->currency_id == $data->id ? 'selected' : '' ?> > {{ $data->name }}</option>
                        @endforeach
                        </select>
                        <div class="display_inline_block;" id="currency_error_message"></div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2 control-label require" for="inputEmail3">{{ __('Bank Name') }}</label>
                    <div class="col-sm-6">
                      <input type="text" placeholder="{{ __('Bank Name') }}" class="form-control" id="bank_name" name="bank_name" value="{{ $accountInfo->bank_name }}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2 control-label" for="branch_name">{{ __('Branch Name') }}</label>
                    <div class="col-sm-6">
                      <input type="text" placeholder="{{ __('Branch Name') }}" class="form-control" id="branch_name" name="branch_name" value="{{ $accountInfo->branch_name }}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2 control-label" for="branch_city">{{ __('Branch City') }}</label>
                    <div class="col-sm-6">
                      <input type="text" placeholder="{{ __('Branch City') }}" class="form-control" id="branch_city" name="branch_city" value="{{ $accountInfo->branch_city }}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2 control-label" for="swift_code">{{ __('Swift Code') }}</label>
                    <div class="col-sm-6">
                      <input type="text" placeholder="{{ __('Swift Code') }}" class="form-control" id="swift_code" name="swift_code" value="{{ $accountInfo->swift_code }}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2 control-label" for="inputEmail3">{{ __('Bank Address') }}</label>
                    <div class="col-sm-6">
                      <input type="text" placeholder="{{ __('Bank Address') }}" class="form-control" id="bank_address" name="bank_address" value="{{ $accountInfo->bank_address }}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2 control-label require" for="inputEmail3">{{ __('Default Account') }}</label>
                    <div class="col-sm-6">
                       <select class="form-control select w-100" name="default_account" id="default_account">
                        <option value="1" <?= ( $accountInfo->is_default == "1" ) ? 'selected' : ''?>>{{ __('Yes') }}</option>
                        <option value="0" <?= ( $accountInfo->is_default == "0" ) ? 'selected' : ''?>>{{ __('No') }}</option>
                        </select>
                    </div>
                  </div>

                  <div class="col-sm-8 px-0 pt-2">
                    @if(Helpers::has_permission(Auth::user()->id, 'edit_bank_account'))
                      <button class="btn btn-primary custom-btn-small" type="submit" id="submit">{{ __('Update')}}</button>
                    @endif
                    <a href="{{ url('bank/list') }}" class="btn btn-info custom-btn-small">{{ __('Cancel') }}</a>
                  </div>
              </form>
            </div>

            <div class="tab-pane fade show <?= ($tab == 'transaction') ? 'active' : '' ?>" id="transaction" role="tabpanel" aria-labelledby="transaction-tab">
              <div class="row">
                <div class="col-sm-12 p-0">
                  <form class="form-horizontal" action='{{ url("bank/edit-account/transaction/$account_id") }}' method="GET" id='transactionReport'>
                    <input class="form-control" id="startfrom" type="hidden" name="from" value="<?= isset($startDate) ? $startDate : '' ?>">
                    <input class="form-control" id="endto" type="hidden" name="to" value="<?= isset($endDate) ? $endDate : '' ?>">
                      <div class="col-md-12 col-sm-12 col-xs-12 p-0">
                        <div class="row mt-2 p-0">
                          <div class="col-xl-4 col-md-5 col-sm-6 col-xs-12 mb-2">
                              <div class="input-group">
                                <button type="button" class="form-control" id="daterange-btn">
                                  <span class="float-left">
                                    <i class="fa fa-calendar"></i>
                                    {{ __('Date range picker') }}
                                  </span>
                                  <i class="fa fa-caret-down float-right pt-1"></i>
                                </button>
                              </div>
                            </div>

                             <div class="col-xl-3 col-md-4 col-sm-4 col-xs-12 mb-1">
                              <div class="form-group">
                              <select class="form-control select" name="type" id="type">
                                <option value="">{{ __('All Types') }}</option>
                                @foreach($type as $data)
                                <option value="{{ $data->transaction_method }}" {{ $data->transaction_method == $typeVal  ? 'selected' : ''}} >{{ ucwords(str_replace ('_',' ', strtolower($data->transaction_method))) }}</option>
                                @endforeach
                              </select>
                            </div>
                            </div>

                            <div class="col-xl-3 col-md-2 col-sm-4 col-xs-12 mb-1">
                              <div class="form-group">
                              <select class="form-control select" name="mode" id="mode">
                                <option value="">{{ __('All Modes') }}</option>
                                <option value="1" {{ $modeVal == '1' ? 'selected' : ''}} >{{ __('Cash in (Debit)') }}</option>
                                <option value="2" {{ $modeVal == '2' ? 'selected' : ''}} >{{ __('Cash out (Credit)') }}</option>
                              </select>
                            </div>
                            </div>

                            <div class="col-md-1 col-sm-1 col-xs-12 p-md-0 mb-2">
                            <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small push-sm-right mt-0 mr-0">{{  __('Go')  }}</button>
                          </div>
                        </div>
                      </div>
                  </form>
                </div>
              </div>
              <div class="row">
               <div class="col-md-12 p-0">
                    <div class="col-md-12 m-t-5 p-0">
                        <div class="table-responsive">
                          <table id="dataTableBuilder" class="table table-bordered table-hover table-striped dt-responsive w-100 bank-account-edit">
                            <thead>
                              <tr>
                                <th class="text-center">{{  __('SL')  }}</th>
                                <th class="text-center" id="date">{{  __('Date')  }}</th>
                                <th class="text-center">{{  __('Type')  }}</th>
                                <th class="text-center">{{  __('Description') }}</th>
                                <th>{{ __('Cash out (Credit)') }}</th>
                                <th>{{ __('Cash in (Debit)') }}</th>
                                <th class="text-center">{{  __('Balance')  }}</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <th class="text-center"> {{ __('Balance On') }} {{ isset($startDate) && !empty($startDate) ? formatDate($startDate) : '' }}</th>
                                <td></td>
                                <td></td>
                                <th class="text-center">
                                  @if($amount > 0)
                                    {{ formatCurrencyAmount($amount) }}
                                  @elseif($amount < 0)
                                    {{ formatCurrencyAmount($amount) }}
                                  @elseif($amount == 0)
                                    {{ formatCurrencyAmount($amount) }}
                                  @endif
                                </th>
                              </tr>
                              <?php
                                $totalCredit = 0;
                                $totalDebit = 0;
                                $serial = 1;
                              ?>
                              @foreach($transactionList as $key=>$data)
                                <?php
                                  if ($key == 0) {
                                    $openingBalance = $amount;
                                  }
                                  $newBalance = $openingBalance+$data->amount;
                                  $openingBalance = $newBalance;

                                  if ($data->amount < 0) {
                                    $totalDebit += $data->amount;
                                  } else {
                                    $totalCredit += $data->amount;
                                  }
                                ?>

                                <tr>
                                  <td class="text-center">{{ $serial++ }}</td>
                                  <td class="text-center" width="20%">{{ formatDate($data->transaction_date) }}</td>
                                  <td class="text-center">
                                    {{ ucwords(str_replace ('_',' ', strtolower($data->transaction_method))) }}
                                  </td>

                                  <td class="text-center">
                                    @if(strlen($data->description) > 15)
                                      <span title ="{{ $data->description }}">{{ substr_replace($data->description, "..", 12) }}</span>
                                    @else
                                      {{ $data->description }}
                                    @endif
                                  </td>
                                  <td class="text-center">

                                    @if($data->amount <= 0)
                                      {{ formatCurrencyAmount(abs($data->amount)) }}
                                    @else
                                      {{ formatCurrencyAmount(abs(0)) }}
                                    @endif

                                  </td>
                                  <td class="text-center">

                                    @if($data->amount > 0)
                                      {{ formatCurrencyAmount(abs($data->amount)) }}
                                    @else
                                      0.00
                                    @endif

                                  </td>
                                   <td class="text-center" width="30%">{{ formatCurrencyAmount($newBalance) }}</td>
                                </tr>
                              @endforeach
                            </tbody>
                            <tfoot>
                              <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <th class="text-right">  {{ __('Balance On') }} {{ formatDate(date('Y-m-d')) }}</th>
                                <th class="text-center">{{ formatCurrencyAmount(abs($totalDebit), $presentCurrency->symbol) }}</th>
                                <th class="text-center">{{ formatCurrencyAmount(abs($totalCredit), $presentCurrency->symbol) }}</th>
                                <th class="text-center">{{ formatCurrencyAmount($totalCredit+$totalDebit+$amount, $presentCurrency->symbol) }}</th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('js')
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script type="text/javascript">
    'use strict';
    var account_no = "{{$account_id}}";
    var startDate = "{!! isset($startDate) ? $startDate : 'undefined' !!}";
    var endDate   = "{!! isset($endDate) ? $endDate : 'undefined' !!}";
</script>
<script src="{{ asset('public/dist/js/custom/bank.min.js') }}"></script>
@endsection

