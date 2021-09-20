@extends('layouts.app')
@section('css')
{{-- Select2  --}}
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('public/dist/css/invoice-style.min.css')}}">
@endsection

@section('content')
  <div class="col-sm-12" id="add-expense-container">
    <div class="card">
      <div class="card-header">
        <h5> <a href="{{ url('expense/list')}}">{{ __('Expense') }}</a> >> {{ __('New Expense') }}</h5>
        <div class="card-header-right">
            
        </div>
      </div>
      <div class="card-body table-border-style">
        <div class="form-tabs">
          <form action="{{ url('expense/save') }}" method="post" id="expense" class="form-horizontal" enctype="multipart/form-data">
            <input type="hidden" value="{{ csrf_token() }}" name="_token" id="token">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item">
                  <a class="nav-link active text-uppercase" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __('Expense Information') }}</a>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="row">
                  <div class="col-sm-9">

                    <div class="form-group row">
                      <label class="col-sm-3 control-label" for="inputEmail3">{{ __('Payment Method') }}</label>
                      <div class="col-sm-9">
                        <select class="form-control select" name="payment_method" id="payment_method">
                          <option value="">{{ __('Select One') }}</option>
                          @foreach($paymentMethods as $method)
                            <option value="{{ $method->name }}" data-methodId="{{ $method->id }}">{{ $method->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <input type="hidden" name="payment_method_id" id="payment_method_id">
                    <input type="hidden" name="homeCurrency" id="homeCurrency" value="{{ $homeCurrency[0] }}">
                    <input type="hidden" name="totalBalance" id="totalBalance">

                    <div class="form-group row display_none" id="account">
                      <label class="col-sm-3 control-label require" for="account_no">{{ __('Account') }}</label>
                      <div class="col-sm-9">
                        <select class="form-control select account" name="account_no" id="account_no">
                          <option value="">{{ __('Select One') }}</option>
                          @foreach($accounts as $account)
                            <option value="{{ $account->id }}" currency-id="{{ $account->currency_id }}" currency-code="{{ $account->currency }}" >{{$account->name}} ({{ $account->currency }})</option>
                          @endforeach
                        </select>
                        <span class="message"></span>
                        <input type="hidden" name="currency_id" id="currency_id" value="">
                      </div>
                      <div class="col-md-3 ml-3"></div>
                      <label id="account_no-error" class="error" for="account_no">{{ __('This field is required.') }}</label>
                    </div>

                    <div class="form-group row" id="currency-div">
                      <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Currency') }}</label>
                      <div class="col-sm-9">
                        <select class="form-control select currency" name="currency" id="currency">
                          <option value="">{{ __('Select One') }}</option>
                          @foreach($currencies as $key => $value)
                            <option value="{{ $key }}" {{ $key == $homeCurrency[0] ? 'selected' : '' }}>{{ $value }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="offset-sm-3 pl-3 mt-0 pt-0 custom_error_show margin-top-neg-15">
                        <label id="currency-error" class="error" for="currency"></label>
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Amount') }}</label>
                      <div class="col-sm-9">
                        <input type="text" placeholder="{{ __('Amount') }}" class="form-control positive-float-number" id="amount" name="amount">
                        <span id="errorMessage" class="color_red"></span>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Category') }}</label>
                      <div class="col-sm-9">
                         <select class="form-control select category" name="category_id" id="category_id">
                          <option value="">{{ __('Select One') }}</option>
                          @foreach($incomeCategories as $cat_id=>$cat_name)
                            <option value="{{ $cat_id }}" >{{ $cat_name }}</option>
                          @endforeach
                          </select>
                      </div>
                      <div class="offset-sm-3 pl-3 mt-0 pt-0 custom_error_show margin-top-neg-15">
                        <label id="category_id-error" class="error" for="category_id"></label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 control-label" for="inputEmail3">{{ __('Description') }}</label>
                      <div class="col-sm-9">
                        <textarea class="form-control" placeholder="{{ __('Description') }}" id="description" name="description" rows="3"></textarea>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Date') }}</label>
                      <div class="col-sm-9">
                      <input type="text" class="form-control" id="trans_date" name="trans_date">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Reference') }}</label>
                      <div class="col-sm-9">
                        <input type="text" placeholder="{{ __('Reference') }}" class="form-control" id="reference" name="reference" value="{{ $reference }}" readonly="true">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-3 control-label">{{ __('Attachment') }}</label>
                      <div class="col-sm-9">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" name="attachment" id="validatedCustomFile">
                          <label class="custom-file-label overflow_hidden" for="validatedCustomFile">{{ __('Upload file...') }}</label>
                        </div>                    
                      </div>
                    </div>

                    <div class="form-group row" id="divNote">
                      <label class="col-sm-3 control-label"></label>
                      <div class="col-sm-9" id='note_txt_1'>
                        <span class="badge badge-danger">{{ __('Note') }}!</span> {{ __('Allowed File Extensions: jpg, png, gif, doc, docx, pdf') }}
                      </div>
                    </div>                    
                  </div>                  
                  <div class="col-sm-9 px-0 m-l-15 mt-2">
                    <button class="btn btn-primary custom-btn-small" type="submit" id="btnSubmit"><i class="comment_spinner spinner fa fa-spinner fa-spin custom-btn-small display_none"></i><span id="spinnerText">{{ __('Submit') }} </span></button>   
                    <a href="{{ url('expense/list') }}" class="btn btn-info custom-btn-small">{{ __('Cancel') }}</a>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection
@section('js')
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script type="text/javascript">
  "use strict";
  var defaultCurrencyID = "{!! $dflt_currency_id !!}"; 
</script>
<script src="{{ asset('public/dist/js/custom/expense.min.js') }}"></script>
@endsection