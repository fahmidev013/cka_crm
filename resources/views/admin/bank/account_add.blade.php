@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
  <!-- Main content -->
  <div class="col-sm-12" id="bank-add-container">
    <div class="card">
      <div class="card-header">
        <h5> <a href="{{ url('bank/list') }}">{{ __('Bank Accounts') }}</a> >> {{ __('New Account') }}</h5>
        <div class="card-header-right">
            
        </div>
      </div>
      <div class="card-body table-border-style" >
        <div class="form-tabs">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active text-uppercase" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __('Account Information') }}</a>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <form action="{{ url('bank/save-account') }}" method="post" id="bank" class="form-horizontal">
              <input type="hidden" value="{{ csrf_token() }}" name="_token" id="token">
              <div class="form-group row">
                <label class="col-sm-2 control-label require" for="inputEmail3">{{ __('Account Name')}}</label>
                <div class="col-sm-6">
                  <input type="text" placeholder="{{ __('Account Name')}}" class="form-control" id="account_name" name="account_name">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2 control-label require" for="inputEmail3">{{ __('Account Type') }}</label>
                <div class="col-sm-6">
                   <select class="form-control select account_type" name="account_type_id" id="account_type_id">
                    <option value="">{{ __('Select One') }}</option>
                    @foreach($accountTypes as $data)
                      <option value="{{ $data->id }}" >{{ $data->name }}</option>
                    @endforeach
                    </select>
                    <label for="account_type_id" generated="true" class="error display_inline_block" id="error_account_type_id"></label>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2 control-label require" for="inputEmail3">{{ __('Account Number') }}</label>
                <div class="col-sm-6">
                  <input type="text" placeholder="{{ __('Account Number') }}" class="form-control" id="account_no" name="account_no">
                  <label for="account_no" generated="true" class="error" id="error_account_no">{{ __('This field is required.') }}</label>
                </div>
              </div>
              
              <div class="form-group row">
                <label class="col-sm-2 control-label require" for="inputEmail3">{{ __('Bank Name') }}</label>
                <div class="col-sm-6">
                  <input type="text" placeholder="{{ __('Bank Name') }}" class="form-control" id="bank_name" name="bank_name">
                </div>
              </div>
              
              <div class="form-group row">
                <label class="col-sm-2 control-label" for="branch_name">{{ __('Branch Name') }}</label>
                <div class="col-sm-6">
                  <input type="text" placeholder="{{ __('Branch Name') }}" class="form-control" id="branch_name" name="branch_name">
                </div>
              </div>
              
              <div class="form-group row">
                <label class="col-sm-2 control-label" for="branch_city">{{ __('Branch City') }}</label>
                <div class="col-sm-6">
                  <input type="text" placeholder="{{ __('Branch City') }}" class="form-control" id="branch_city" name="branch_city">
                </div>
              </div>
              
              <div class="form-group row">
                <label class="col-sm-2 control-label" for="swift_code">{{ __('Swift Code') }}</label>
                <div class="col-sm-6">
                  <input type="text" placeholder="{{ __('Swift Code') }}" class="form-control" id="swift_code" name="swift_code">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2 control-label require" for="inputEmail3">{{ __('Currency') }}</label>
                <div class="col-sm-6">
                   <select class="form-control select currency" name="currency_id" id="currency_id">
                    <option value="">{{ __('Select One') }}</option>
                    @foreach($currencies as $data)
                      <option value="{{ $data->id }}" >{{ $data->name }}</option>
                    @endforeach
                    </select>
                  <label for="currency_id" generated="true" class="error display_inline_block" id="error_currency_id"></label>
                </div>
              </div> 

              <div class="form-group row">
                <label class="col-sm-2 control-label require" for="inputEmail3">{{ __('Opening Balance') }}</label>
                <div class="col-sm-6">
                  <input type="text" placeholder="{{ __('Opening Balance') }}" class="form-control positive-float-number" id="opening_balance" name="opening_balance">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2 control-label" for="inputEmail3">{{ __('Bank Address') }}</label>
                <div class="col-sm-6">
                  <input type="text" placeholder="{{ __('Bank Address') }}" class="form-control" id="bank_address" name="bank_address">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 control-label require" for="inputEmail3">{{ __('Default Account') }}</label>
                <div class="col-sm-6">
                   <select class="form-control select" name="default_account" id="default_account">
                    <option value="1">{{ __('Yes') }}</option>
                    <option value="0">{{ __('No') }}</option>
                    </select>
                </div>
              </div> 

              <div class="col-sm-8 px-0 pt-2">
                <button class="btn btn-primary custom-btn-small" type="submit" id="btnSubmit"><i class="comment_spinner spinner fa fa-spinner fa-spin custom-btn-small display_none"></i><span id="spinnerText">{{ __('Submit') }} </span></button>   
                <a href="{{ url('bank/list') }}" class="btn btn-info custom-btn-small">{{ __('Cancel') }}</a>
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
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/bank.min.js') }}"></script>
@endsection