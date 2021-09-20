@extends('layouts.app')
@section('css')
{{-- Select2  --}}
  <link rel="stylesheet" type="text/css" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')

<!-- Main content -->
  <div class="col-sm-12" id="convert-customer-container">
    <div class="card">
      <div class="card-header">
        <h5><a href="{{url('customer/list')}}">{{ __('Customers') }}</a> >> {{ __('New Customer') }}</h5>
        <div class="card-header-right">
            
        </div>
      </div>
      <div class="card-body table-border-style" >
        <div class="form-tabs">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active text-uppercase" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __('Customer Information') }}</a> 
            </li>
            <li class="nav-item">
              <a class="nav-link text-uppercase" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">{{ __('Shipping Address') }}</a>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <form action="{{ url('save-converted-customer') }}" method="post" id="customerAdd" class="form-horizontal">
              <input type="hidden" value="{{ csrf_token() }}" name="_token" id="token">
              <input type="hidden" value="{{ $leadData->id }}" name="lead_id" id="lead_id">
                    
                <div class="form-group row">
                  <label for="first_name" class="col-sm-2 control-label require">{{ __('First Name') }}</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') ? old('first_name') : $leadData->first_name }}" placeholder="{{__('First Name')}}">
                  </div>
                </div>
                <div class="form-group row">
                    <label for="last_name" class="col-sm-2 control-label require">{{ __('Last Name') }}</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') ? old('last_name') : $leadData->last_name }}" placeholder="{{__('Last Name')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="email" class="col-sm-2 control-label">{{ __('Email') }}</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="email" name="email" value="{{ old('email') ? old('email') : $leadData->email }}" placeholder="{{__('Email')}}">
                        <label for="email_error" class="error display_none" id="val_email"></label>
                    </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2 control-label require" for="currency_id">{{ __('Currency') }}</label>
                  <div class="col-sm-6">
                    <select class="js-example-basic-single form-control" name="currency_id" id="currency_id">
                      <option value="">{{ __('Select One') }}</option>
                      @foreach ($currencies as $data)
                        <option value="{{ $data->id }}">{{ $data->name }}</option>
                      @endforeach
                    </select>
                    <label for="currency_id" generated="true" class="error" id="currency_id_error"></label>
                  </div>
                </div>
                <div class="form-group row">
                    <label for="phone" class="col-sm-2 control-label">{{ __('Phone') }}</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" value="{{ old('phone') ? old('phone') : $leadData->phone }}" id="phone" name="phone" placeholder="{{ __('Phone') }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tax_id" class="col-sm-2 control-label">{{ __('Tax Id') }}</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="tax_id" name="tax_id" placeholder="{{ __('Tax Id') }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="bill_street" class="col-sm-2 control-label">{{ __('Street') }}</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" value="{{ old('bill_street') ? old('bill_street') : $leadData->street }}" id="bill_street" name="bill_street" placeholder="{{ __('Street') }}">
                    </div>
                </div>
                <div class="form-group row">
                  <label for="bill_city" class="col-sm-2 control-label">{{ __('City') }}</label>
                  <div class="col-sm-6">
                      <input type="text" class="form-control" value="{{ old('bill_city') ? old('bill_city') : $leadData->city }}" id="bill_city" name="bill_city" placeholder="{{ __('City') }}">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="bill_state" class="col-sm-2 control-label">{{ __('State') }}</label>
                  <div class="col-sm-6">
                      <input type="text" class="form-control" value="{{ old('bill_state') ? old('bill_state') : $leadData->state }}" id="bill_state" name="bill_state" placeholder="{{ __('State') }}">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="bill_zipCode" class="col-sm-2 control-label">{{ __('Zip Code') }}</label>
                  <div class="col-sm-6">
                      <input type="text" class="form-control" value="{{ old('bill_zipCode') ? old('bill_zipCode') : $leadData->zip_code }}" id="bill_zipCode" name="bill_zipCode" placeholder="Zip Code">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="bill_country_id" class="col-sm-2 control-label">{{ __('Country') }}</label>
                    <div class="col-sm-6">
                      <select class="js-example-basic-single form-control" id="bill_country_id" name="bill_country_id">
                        <option value="">{{ __('Select One') }}</option>
                        @foreach ($countries as $data)
                            <option value="{{ $data->id }}" <?=  $data->id == $leadData->country_id ? 'selected' : '' ?> >{{ $data->name }}</option>
                        @endforeach
                      </select> 
                      <label for="bill_country_id" generated="true" class="error"></label>
                    </div>                          
                </div>
                <div class="form-group row">
                  <label for="password" class="col-sm-2 control-label">{{ __('Password') }}</label>
                  <div class="col-sm-6">
                      <input type="password" class="form-control" id="lead-password" name="password">
                  </div>
                </div>
              </div>
              
              <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group row">
                      <div class="col-sm-8"><span id="copy" data-toggle="tooltip" title="{{ __('Copy') }}" class="badge badge-pill  theme-bg2 text-white float-right">{{ __('Copy Address') }}</span></div>
                    </div>
                    <div class="form-group row">
                      <label for="ship_street" class="col-sm-2 control-label">{{ __('Street') }}</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control" id="ship_street" name="ship_street"  placeholder="Street">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="ship_city" class="col-sm-2 control-label">{{ __('City') }}</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control" id="ship_city" name="ship_city" placeholder="Street">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="ship_state" class="col-sm-2 control-label">{{ __('State') }}</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control" id="ship_state" name="ship_state" placeholder="Street">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="ship_zipCode" class="col-sm-2 control-label">{{ __('Zip Code') }}</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control" id="ship_zipCode" name="ship_zipCode" placeholder="Zip code">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="ship_country_id" class="col-sm-2 control-label">{{ __('Country') }}</label>
                      <div class="col-sm-6">
                        <select class="js-example-basic-single form-control" id="ship_country_id" name="ship_country_id">
                          <option value="">{{ __('Select One') }}</option>
                          @foreach ($countries as $data)
                            <option value="{{ $data->code }}">{{ $data->name }}</option>
                          @endforeach
                        </select>           
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-8 px-0">
                <button class="btn btn-primary custom-btn-small" type="submit" id="submit">{{ __('Submit') }}</button>   
                <a href="{{ url('lead/list') }}" class="btn btn-danger custom-btn-small">{{ __('Cancel') }}</a>
              </div>
            </div>              
          </form> 
        </div>
      </div>
  </div>
</div>
<!-- [ Customer ] end -->

@endsection

@section('js')
<!-- Select2 -->
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
<script src="{{asset('public/dist/plugins/bootstrap-select/dist/js/bootstrap-select.min.js')}}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/lead.min.js') }}"></script>
@endsection