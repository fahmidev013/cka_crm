@extends('layouts.app')
@section('css')
  <link rel="stylesheet" type="text/css" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
<!-- Main content -->
<div class="col-sm-12" id="edit-customer-container">
  <div class="card">
    <div class="card-header">
      <h5> <a href="{{ url('customer/list') }}">{{ __('Customers') }}</a> >> {{ $customerData->first_name }} {{ $customerData->last_name }} >> {{ __('Profile') }}</h5>
      <div class="card-header-right">
        
      </div>
    </div>
    <div class="card-body p-0" id="no_shadow_on_card">
    @include('admin.customer.customer_tab')
 
      <div class="col-sm-12 m-t-20 form-tabs">
        <ul class="nav nav-tabs " id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active text-uppercase" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __('Customer Information') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-uppercase" id="profile-tab" data-toggle="tab" data-rel="{{ $customerData->id }}" href="#profile" role="tab" aria-controls="profile" aria-selected="false">{{ __('Update Password') }}</a>
          </li>
        </ul>
        <div class="col-sm-12 tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <form action='{{ url("update-customer/$customerData->id") }}' method="post" class="form-horizontal" id="editCustomer">
              <input type="hidden" value="{{ csrf_token() }}" name="_token" id="token">
              <input type="hidden" value="{{ $customerData->id }}" name="debtor_no" id="debtor_no">
              <input type="hidden" value="{{ $customerData->id }}" name="customer_id">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="first_name" class="col-sm-3 control-label require">{{ __('First Name') }}</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $customerData->first_name }}" placeholder="{{ __('First Name') }}">
                    </div>
                  </div>
                  <div class="form-group row">
                      <label for="last_name" class="col-sm-3 control-label require">{{ __('Last Name') }}</label>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $customerData->last_name }}" placeholder="{{ __('Last Name') }}">
                      </div>
                  </div>
                  <div class="form-group row">
                      <label for="email" class="col-sm-3 control-label">{{ __('Email') }}</label>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="email" name="email" value="{{ old('email') ? old('email') : $customerData->email }}" placeholder="{{ __('Email') }}">
                          <label for="email_error" class="error display_none" id="val_email"></label>
                      </div>
                  </div>
                  <div class="form-group row">
                      <label for="currency" class="col-sm-3 control-label require">{{ __('Currency') }}</label>
                      <div class="col-sm-8">
                        <select class="js-example-basic-single form-control" id="currency" name="currency_id">
                          <option value="">{{ __('Select One') }}</option>
                          @foreach ($currencies as $key => $value)
                            <option value="{{ $key }}" {{ $key == $customerData->currency_id ? 'selected' : '' }}>{{ $value }}</option>
                          @endforeach                          
                        </select>
                        <label for="currency" id="error-currency" generated="true" class="error">{{ __('This field is required.') }}</label>
                      </div>
                  </div>
                  <div class="form-group row">
                      <label for="phone" class="col-sm-3 control-label">{{ __('Phone') }}</label>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" value="{{ $customerData->phone }}" id="phone" name="phone" placeholder="{{ __('Phone') }}">
                      </div>
                  </div>
                  <div class="form-group row">
                      <label for="tax_id" class="col-sm-3 control-label">{{ __('Tax Id') }}</label>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" value="{{ $customerData->tax_id }}" id="tax_id" name="tax_id" placeholder="Tax Id">
                      </div>
                  </div>
                  <div class="form-group row">
                      <label for="bill_street" class="col-sm-3 control-label">{{ __('Street') }}</label>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" value="{{ isset($cusBranchData->billing_street) ? $cusBranchData->billing_street : '' }}" id="bill_street" name="bill_street" placeholder="Bill Street">
                      </div>
                  </div>
                  <div class="form-group row">
                    <label for="bill_city" class="col-sm-3 control-label">{{ __('City') }}</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" value="{{ isset($cusBranchData->billing_city) ? $cusBranchData->billing_city : '' }}" id="bill_city" name="bill_city" placeholder="Bill City">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="bill_state" class="col-sm-3 control-label">{{ __('State') }}</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" value="{{ isset($cusBranchData->billing_state) ? $cusBranchData->billing_state : '' }}" id="bill_state" name="bill_state" placeholder="Bill State">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="bill_zipCode" class="col-sm-3 control-label">{{ __('Zip Code') }}</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" value="{{ isset($cusBranchData->billing_zip_code) ? $cusBranchData->billing_zip_code : '' }}" id="bill_zipCode" name="bill_zipCode" placeholder="Zip Code">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="billing_country_id" class="col-sm-3 control-label">{{ __('Country') }}</label>
                      <div class="col-sm-8">
                        @php 
                          $billingCountryID = !empty($cusBranchData->billing_country_id) ? $cusBranchData->billing_country_id : 0;
                        @endphp 
                        <select class="js-example-basic-single form-control" id="billing_country_id" name="billing_country_id">
                          <option value="">{{ __('Select One') }}</option>
                          @foreach ($countries as $key => $value)
                            <option value="{{ $key }}" {{ ( $billingCountryID == $key ) ? 'selected' : '' }}>{{ $value }}</option>
                          @endforeach
                        </select> 
                      </div>
                  </div>
                  <div class="form-group row">
                    <label for="status" class="col-sm-3 control-label">{{ __('Status') }}</label>
                      <div class="col-sm-8">
                        <select class="js-example-basic-single form-control" id="status" name="status">
                         <option value="1" {{isset($customerData->is_active) && $customerData->is_active ==  1? 'selected':"" }}>{{ __('Active') }}</option>
                          <option value="0"  {{isset($customerData->is_active) && $customerData->is_active == 0 ? 'selected':"" }}>{{ __('Inactive') }}</option>
                        </select>             
                      </div>
                  </div>
                  <div class="form-group row">
                    <label for="is_activated" class="col-sm-3 control-label">{{ __('Verified') }}</label>
                      <div class="col-sm-8">
                        <select class="js-example-basic-single form-control" id="is_activated" name="is_activated">
                          <option value="1" {{ isset($customerData->is_verified) && $customerData->is_verified ==  1? 'selected':"" }}>{{ __('Yes') }}</option>
                          <option value="0"  {{ isset($customerData->is_verified) && $customerData->is_verified == 0 ? 'selected':"" }}>{{ __('No') }}</option>
                        </select>             
                      </div>
                  </div>
                  <div class="form-group row">
                    <label for="walking" class="col-sm-3 control-label" id="walking-customer">{{ __('Walking customer') }}</label>
                      <div class="col-sm-8">
                        <select class="js-example-basic-single form-control" id="is_walking" name="is_walking">
                          <option value="1" {{ isset($customerData->customer_type) && $customerData->customer_type ==  'walking'? 'selected':'' }}>{{ __('Yes') }}</option>
                          <option value="0" {{ isset($customerData->customer_type) && $customerData->customer_type ==  'walking'? '':'selected' }}>{{ __('No') }}</option>
                        </select>             
                      </div>
                  </div>
                </div>
                <div class="col-sm-6" id="shipping-credentials">
                  <h5 class="shipping-address">{{ __('Shipping Address') }}</h5>
                  <div class="form-group row">
                    <div class="col-sm-6">
                      <label type="button" id="copy" class="badge badge-pill theme-bg2 text-white float-right">{{ __('Copy Address') }}</label>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="ship_street" class="col-sm-3 control-label">{{ __('Street') }}</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="ship_street" name="ship_street" value="{{ isset($cusBranchData->shipping_street) ? $cusBranchData->shipping_street : '' }}" placeholder="Street">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="ship_city" class="col-sm-3 control-label">{{ __('City') }}</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="ship_city" name="ship_city" value="{{ isset($cusBranchData->shipping_city) ? $cusBranchData->shipping_city : ''}}" placeholder="City">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="ship_state" class="col-sm-3 control-label">{{ __('State') }}</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="ship_state" name="ship_state" value="{{ isset($cusBranchData->shipping_state) ? $cusBranchData->shipping_state : '' }}" placeholder="Billing State">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="ship_zipCode" class="col-sm-3 control-label">{{ __('Zip Code') }}</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="ship_zipCode" name="ship_zipCode" value="{{ isset($cusBranchData->shipping_zip_code) ? $cusBranchData->shipping_zip_code : ''}}" placeholder="Zip code">
                    </div>
                  </div>
                  <div class="form-group row pb-3">
                      <label for="shipping_country_id" class="col-sm-3 control-label ">{{ __('Country') }}</label>
                      <div class="col-sm-8">
                        @php 
                          $shippingCountryID = !empty($cusBranchData->shipping_country_id) ? $cusBranchData->shipping_country_id : 0;
                        @endphp 
                        <select class="js-example-basic-single form-control" id="shipping_country_id" name="shipping_country_id">
                          <option value="">{{ __('Select One') }}</option>
                          @foreach ($countries as $key => $value)
                            <option value="{{ $key }}" {{ ($shippingCountryID == $key) ? 'selected' : '' }}>{{ $value }}</option>
                          @endforeach
                        </select>           
                      </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-12 px-0">
                <button class="btn btn-primary custom-btn-small float-left" type="submit" id="submitBtn">{{ __('Update') }}</button>   
                <a href="{{ url('customer/list') }}" class="btn btn-info custom-btn-small">{{ __('Cancel') }}</a>
              </div>
            </form>
          </div>
          
          <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="row">
              <div class="col-sm-12">
                <form action='{{ url("customer/update-password") }}' class="form-horizontal" id="password-form" method="POST">
                  <input type="hidden" value="{{ csrf_token() }}" name="_token" id="token2">
                  <input type="hidden" value="{{ $customerData->id }}" name="customer_id">

                  <div class="form-group row">
                    <label for="password" class="col-sm-2 control-label require">{{ __('Password') }}</label>
                    <div class="col-sm-6">
                       <input type="password" class="form-control" name="password" id="password">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="password" class="col-sm-2 control-label require">{{ __('Confirm Password') }}</label>
                    <div class="col-sm-6">
                      <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
                    </div>
                  </div>
                  @if (!empty($customerData->email) && filter_var($customerData->email, FILTER_VALIDATE_EMAIL))
                    <div class="form-group row">
                      <lebel class="col-sm-2 control-label"></lebel>
                      <div class="col-sm-6 checkbox checkbox-primary checkbox-fill d-inline ml-dot80rem">
                        <input type="checkbox" class="form-control" name="sendmail" id="checkbox-p-fill-1">
                        <label for="checkbox-p-fill-1" class="cr">{{ __('Send Email to the Customer') }}</label>
                      </div>
                    </div>
                  @endif
                  @if(Helpers::has_permission(Auth::user()->id, 'edit_customer'))
                    <button class="btn btn-primary custom-btn-small float-left" type="submit" id="submit">{{ __('Submit') }}</button> 
                  @endif
                </form>
              </div>
            </div>
          </div>            
        </div>
      </div>
    </div>
  </div>
</div>

  @include('layouts.includes.message_boxes')

@endsection
@section('js')
  <script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
  <script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
  {!! translateValidationMessages() !!}
  <script src="{{ asset('public/dist/js/custom/customer.min.js') }}"></script>
@endsection