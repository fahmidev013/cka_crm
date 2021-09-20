@extends('layouts.app')
@section('css')
{{-- Select2  --}}
  <link rel="stylesheet" type="text/css" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
  <div class="col-sm-12" id="supplier-add-container">
    <div class="card">
      <div class="card-header">
        <h5><a href="{{url('supplier')}}">{{ __('Supplier') }}</a> >> {{ __('Add New Supplier') }}</h5>
        <div class="card-header-right">

        </div>
      </div>
      <div class="card-body table-border-style" >
        <div class="form-tabs">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active text-uppercase" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __('Supplier Information') }}</a>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <form action="{{ url('save-supplier') }}" method="post" id="addSupplier" class="form-horizontal">
                <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                <div class="form-group row">
                  <label class="col-sm-2  control-label require" for="supp_name">{{ __('Name') }}</label>
                  <div class="col-sm-6">
                      <input type="text" placeholder="{{ __('Name') }}" class="form-control valdation_check" id="supp_name" name="supp_name">
                      <span id="val_fname" class="color_red"></span>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="email" class="col-sm-2  control-label">{{ __('Email') }}
                  </label>
                  <div class="col-sm-6">
                  <input type="text" placeholder="{{ __('Email') }}" class="form-control valdation_check" id="email" name="email" value="{{ old('email') }}">
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-2 control-label require" for="currency_id">{{ __('Currency') }}</label>
                  <div class="col-sm-6">
                    <select class="js_select2 form-control" name="currency_id" id="currency_id">
                      <option value="">{{ __('Select One') }}</option>
                      @foreach ($currencies as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-sm-4"></div>
                  <div class="col-sm-2"></div>
                  <div class="col-sm-2">
                     <label for="currency_id" generated="true" class="error" id="currency_id-error"></label>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-2  control-label" for="supp_phone">{{ __('Phone') }}</label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ __('Phone') }}" class="form-control" id="contact" name="contact">
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-2  control-label" for="supp_tax">{{ __('Tax ID') }}</label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ __('Tax ID') }}" class="form-control" id="tax_id" name="tax_id">

                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-2  control-label" for="supp_street">{{ __('Street') }}</label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ __('Street') }}" class="form-control" id="street" name="street">
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-2  control-label" for="supp_city">{{ __('City') }}</label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ __('City') }}" class="form-control" id="city" name="city">
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-2  control-label" for="supp_state">{{ __('State') }}</label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ __('State') }}" class="form-control" id="state" name="state">
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-2  control-label" for="supp_zipcode">{{ __('Zipcode') }}</label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ __('Zipcode') }}" class="form-control" id="zipcode" name="zipcode">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2 control-label" for="supp_status">{{ __('Status') }}</label>
                  <div class="col-sm-6">
                    <select class="form-control js_select2" name="status" id="status" >
                      <option value="1">{{ __('Active') }}</option>
                      <option value="0">{{ __('Inactive') }}</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2  control-label" for="supp_country">{{ __('Country') }}</label>
                  <div class="col-sm-6">
                    <select class="form-control js_select2" name="country" id="country">
                    <option value="">{{ __('Select One') }}</option>
                    @foreach ($countries as $key => $value)
                      <option value="{{ $key }}" >{{ $value }}</option>
                    @endforeach
                    </select>
                  </div>
                  <div class="col-sm-4"></div>
                  <div class="col-sm-2"></div>
                  <div class="col-sm-2">
                    <label for="country" generated="true" class="error" id="country-error"></label>
                  </div>
                </div>

                <div class="form-group row">
                  <lebel class="col-sm-2 control-label"></lebel>
                  <div class="col-sm-6 checkbox checkbox-primary checkbox-fill d-inline ml-dot80rem">
                    <input type="checkbox" class="form-control" name="sendMail" id="checkbox-p-fill-1">
                    <label for="checkbox-p-fill-1" class="cr">{{ __('Send Email to the Supplier') }}</label>
                  </div>
                </div>

                <div class="col-sm-8 px-0">
                  <button class="btn btn-primary custom-btn-small" type="submit" id="submitBtn"><i class="comment_spinner spinner fa fa-spinner fa-spin custom-btn-small display_none"></i><span id="spinnerText">{{ __('Submit') }}</span></button>
                  <a href="{{ url('supplier') }}" class="btn btn-info custom-btn-small">{{ __('Cancel') }}</a>
                </div>
              </form>
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
<script src="{{ asset('public/dist/js/custom/supplier.min.js') }}"></script>
@endsection