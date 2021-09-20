@extends('layouts.app')
@section('css')
{{-- Select2  --}}
  <link rel="stylesheet" type="text/css" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
<!-- Main content -->
  <div class="col-sm-12" id="supplier-edit-container">
    <div class="card">
      <div class="card-header">
        <h5>{{$supplierData->name}}</h5>
        <div class="card-header-right">
          
        </div>
      </div>
      <div div class="card-body p-0" id="no_shadow_on_card">
        @include('admin.supplier.supplier_tab')
        <div div class="col-sm-12 m-t-20 form-tabs">
          <ul class="nav nav-tabs " id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active text-uppercase" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __('Supplier Information') }}</a>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
              <form action='{{ url("update-supplier/$supplierData->id") }}' method="post" id="editSupplier" class="form-horizontal">
                <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                <input type="hidden" value="{{$supplierData->id}}" name="supplier_id" id="supplier_id">
                <div class="form-group row">
                  <label for="first_name" class="col-sm-2 control-label require">{{ __('Name') }}</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="supp_name" name="supp_name" value="{{ $supplierData->name }}" placeholder="{{ __('Name') }}">
                    <span id="val_name" class="color_red"></span>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="first_name" class="col-sm-2 control-label">{{ __('Email') }}</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="email" name="email" value="{{ old('email') ? old('email') : $supplierData->email }}" placeholder="{{ __('Email') }}">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2 control-label require" for="supp_currency">{{ __('Currency') }}</label>
                  <div class="col-sm-6">
                    <select class="form-control js_select2" name="currency_id" id="currency_id">
                    <option value="">{{ __('Select One') }}</option>
                      @foreach ($currencies as $data)
                        <option value="{{ $data->id }}" <?= $data->id == $supplierData->currency_id ? 'selected' : '' ?>>{{$data->name}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-sm-4"></div>
                  <div class="col-sm-2"></div>
                  <div class="col-sm-2">
                     <label for="currency_id" class="error display_none" id="currency_id-error">{{ __('This field is required.') }}</label>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2 control-label" for="supp_phone">{{ __('Phone') }}</label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ __('Phone') }}" class="form-control" id="contact" name="contact" value="{{ $supplierData->contact }}">                 
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2 control-label" for="supp_tax">{{ __('Tax ID') }}</label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ __('Tax ID') }}" class="form-control" id="tax_id" name="tax_id" value="{{ $supplierData->tax_id }}">
                   
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2 control-label" for="supp_street">{{ __('Street') }}</label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ __('Street') }}" class="form-control" id="street" name="street" value="{{ $supplierData->street }}">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2 control-label" for="supp_city">{{ __('City') }}</label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ __('City') }}" class="form-control" id="city" name="city" value="{{ $supplierData->city }}">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2 control-label" for="supp_state">{{ __('State') }}</label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ __('State') }}" class="form-control" id="state" name="state" value="{{ $supplierData->state }}">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2 control-label" for="supp_zipcode">{{ __('Zipcode') }}</label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ __('Zipcode') }}" class="form-control" id="zipcode" name="zipcode" value="{{ $supplierData->zipcode }}">
                  </div>
                </div>
                 <div class="form-group row">
                  <label class="col-sm-2 control-label" for="supp_status">{{ __('Status') }}</label>
                  <div class="col-sm-6">
                    <select class="form-control js_select2" name="status" id="status" >
                      <option value="1" <?=isset($supplierData->is_active) && $supplierData->is_active ==  1 ? 'selected':""?> >{{ __('Active') }}</option>
                      <option value="0"  <?=isset($supplierData->is_active) && $supplierData->is_active == 0 ? 'selected':""?> >{{ __('Inactive') }}</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2 control-label" for="supp_country">{{ __('Country') }}</label>
                  <div class="col-sm-6">
                    <select class="form-control js_select2" name="country" id="country">
                      <option value="">{{ __('Select One') }}</option>
                      @foreach ($countries as $data)
                        <option value="{{ $data->id }}" <?= ($data->id == $supplierData->country_id) ? 'selected' : ''?>>{{$data->name}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-sm-4"></div>
                  <div class="col-sm-2"></div>
                  <div class="col-sm-2">
                  </div>
                </div>                
                <div class="col-sm-8 px-0">
                  <button class="btn btn-primary custom-btn-small" type="submit" id="submitBtn">{{ __('Submit') }}</button>   
                  <a href="{{ url('supplier') }}" class="btn btn-info custom-btn-small">{{ __('Cancel') }}</a>
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
<script src="{{ asset('public/dist/js/jquery.validate.min.js')}}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/supplier.min.js') }}"></script>
@endsection