@extends('layouts.app')
@section('css')
{{-- Select2  --}}
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content')
  <!-- Main content -->
  <div class="col-sm-12" id="deposit-add-container">
    <div class="card">
      <div class="card-header">
        <h5> <a href="{{ url('deposit/list') }}">{{ __('Bank Account Deposits')  }}</a> >> {{ __('New Deposit')  }}</h5>
        <div class="card-header-right">
            
        </div>
      </div>
      <div class="card-body table-border-style">
        <div class="form-tabs">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active text-uppercase" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __('Deposit Information') }}</a>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <form action="{{ url('deposit/save') }}" method="post" id="deposit" class="form-horizontal" enctype="multipart/form-data">
              <input type="hidden" value="{{ csrf_token() }}" name="_token" id="token">
                <div class="row">
                  <div class="col-sm-9">
                    <div class="form-group row">
                      <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Account')  }}</label>
                      <div class="col-sm-9">
                         <select class="form-control select2" name="account_no" id="account_no" >
                            <option value="">{{ __('Select One')  }}</option>
                          @foreach($accounts as $account)
                            <option value="{{ $account->id }}" currency-id="{{ $account->currency_id }}" currency-code="{{ $account->currency }}" >{{ $account->name }} ({{ $account->currency }})</option>
                          @endforeach
                          </select>
                          <span class="message"></span>
                          <input type="hidden" name="currency_id" id="currency_id" value="">
                          <label for="account_no" id="account-error" generated="true" class="error display_inline_block"></label>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Amount')  }}</label>
                      <div class="col-sm-9">
                        <input type="text" placeholder="{{ __('Amount')  }}" class="form-control positive-float-number" id="amount" name="amount">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-3 control-label" for="inputEmail3">{{ __('Category')  }}</label>
                      <div class="col-sm-9">
                         <select class="form-control select2" name="category_id" id="category_id">
                          @foreach($incomeCategories as $cat_id=>$cat_name)
                            <option value="{{ $cat_id }}" >{{ $cat_name }}</option>
                          @endforeach
                          </select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-3 control-label" for="inputEmail3">{{ __('Payment Method')  }}</label>
                      <div class="col-sm-9">
                         <select class="form-control select2" name="payment_method" id="payment_method">
                          @foreach($payment_methods as $method_id => $method_name)
                            <option value="{{ $method_id }}" >{{ $method_name }}</option>
                          @endforeach
                          </select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-3 control-label" for="description">{{ __('Description')  }}</label>
                      <div class="col-sm-9">
                        <textarea class="form-control" id="description" placeholder="{{ __('Description')  }}" name="description" rows="3"></textarea>
                      </div>
                    </div>   

                    <div class="form-group row">
                      <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Date')  }}</label>
                      <div class="col-sm-9">
                      <input type="text" class="form-control" id="trans_date" name="trans_date">
                      </div>
                    </div>             

                    <div class="form-group row">
                      <label class="col-sm-3 control-label" for="inputEmail3">{{ __('Reference')  }}</label>
                      <div class="col-sm-9">
                        <input type="text" value="{{ $reference }}" placeholder="{{ __('Reference')  }}" class="form-control" id="reference" name="reference" readonly="true">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-3 control-label">{{ __('Attachment') }}</label>
                      <div class="col-sm-9">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" name="attachment" id="validatedCustomFile">
                          <label class="custom-file-label overflow_hidden" for="validatedCustomFile">{{ __('Upload file...') }}</label>
                          <label id="validatedCustomFile-error" class="error" for="validatedCustomFile"></label>
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
                    <button class="btn btn-primary custom-btn-small" type="submit" id="btnSubmit">{{ __('Submit') }}</button>   
                    <a href="{{ url('deposit/list') }}" class="btn btn-info custom-btn-small">{{ __('Cancel') }}</a>
                  </div>
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
<script src="{{ asset('public/dist/js/custom/deposit.min.js') }}"></script>
@endsection