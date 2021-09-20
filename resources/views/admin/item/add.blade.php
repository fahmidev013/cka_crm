@extends('layouts.app')
@section('css')
{{-- select2 css --}}
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}"> 
<link rel="stylesheet" type="text/css" href="{{ asset('public/dist/css/item.min.css') }}">
@endsection
@section('content')
  <!-- Main content -->
<div class="col-sm-12" id="add-item-container">
  <div class="card user-list">
    <div class="card-header">
      <h5><a href="{{ url('item') }}">{{ __('Items') }}</a> >> {{ __('New Item') }}</h5>
      <div class="card-header-right">
        
      </div>
    </div>
    <div class="card-block">
      <div class="form-tabs">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active text-uppercase" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="true">{{ __('Item Information') }}</a>
          </li>
          
        </ul>
      </div>
      <form id="itemAddForm" class="form-horizontal" action="{{ url('save-item') }}" method="post" enctype="multipart/form-data">
        <input type="hidden" value="{{ csrf_token() }}" name="_token" id="token">
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group row ">
                  <label class="col-sm-2 control-label require">{{ __('Item Name') }}</label>
                  <div class="col-sm-8 pl-sm-3-custom">
                    <input type="text" class="form-control" placeholder="{{ __('Item Name')  }}" name="item_name" id="item_name" value="{{ old('item_name') }}">
                    <span id="checkMsg" class="text-danger"></span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-5">
                    <div class="form-group row ">
                      <label class="col-sm-5 col-form-label require pr-0">{{ __('Item ID')  }}</label>
                      <div class="col-sm-7 pl-md-2">
                        <input type="text" class="form-control" placeholder="{{ __('Item ID') }}" name="item_id" id="item_id" value="{{ old('item_id') }}">
                        <span id="errMsg" class="text-danger"></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-5">
                    <div class="form-group row ">
                      <label class="col-sm-5 col-form-label pr-0">{{ __('HSN/SAC') }}</label>
                      <div class="col-sm-7 pl-md-2">
                        <input type="text" class="form-control" placeholder="{{ __('HSN/SAC') }}" name="hsn" id="hsn" value="{{ old('hsn') }}">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-5">
                    <div class="form-group row ">
                      <label class="col-sm-5 col-form-label pr-0">{{ __('Item Type')  }}</label>
                      <div class="col-sm-7 pl-md-2">
                        <select name="item_type" class="form-control select2" id="itemType">
                          <option value="product">{{ __('Product') }}</option>
                          <option value="service">{{ __('Service') }}</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-5">
                    <div class="form-group row ">
                      <label class="col-sm-5 col-form-label pr-0">{{ __('Category')  }}</label>
                      <div class="col-sm-7 pl-md-2">
                        <select name="category_id" class="form-control select2" id="category_id">
                          @foreach ($categoryData as $data)
                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-5">
                    <div class="form-group row ">
                      <label class="col-sm-5 col-form-label pr-0">{{ __('Units')  }}</label>
                      <div class="col-sm-7 pl-md-2">
                        <select name="units" class="form-control select2" id="units">
                          @foreach ($unitData as $data)
                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-5">
                    <div class="form-group row">
                      <label class="col-sm-5 col-form-label pr-0">{{ __('Tax Type')  }}</label>
                      <div class="col-sm-7 pl-md-2">
                        <select name="tax_type_id" class="form-control select2">
                          @foreach ($taxTypes as $taxType)
                            <option value="{{ $taxType->id }}">{{ $taxType->name }} ({{ formatCurrencyAmount($taxType->tax_rate) }}%)</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2 control-label">{{ __('Item Description')  }}</label>
                  <div class="col-sm-8 pl-sm-3-custom">
                    <textarea class="form-control" placeholder="{{ __('Item Description')  }}" name="description" id="description">{{ old('description') }}</textarea>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-2 control-label require">{{ __('Purchase Price')  }}</label>
                  <div class="col-sm-8 pl-sm-3-custom">
                    <div class="input-group">                      
                      <div class="input-group-prepend">
                        <span class="input-group-text">{{ $currency_symbol }}</span>
                      </div>
                      <input type="text" class="form-control positive-float-number" placeholder="{{ __('Purchase Price')  }}" name="purchase_price" id="purchase_price" value="{{ old('purchase_price') ? old('purchase_price') : formatCurrencyAmount(0) }}">
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2 control-label">{{ __('Retail Price')  }}</label>
                  <div class="col-sm-8 pl-sm-3-custom">
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">{{ $currency_symbol }}</span>
                          </div>
                          <input type="text" class="form-control positive-float-number" placeholder="{{ __('Retail Price')  }}" name="retail_price" id="retail_price" value="{{ old('retail_price') ? old('purchase_price') : formatCurrencyAmount(0) }}">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                <label class="col-sm-2 control-label">{{  __('Status')  }}</label>
                <div class="col-sm-8 pl-sm-3-custom">
                  <select class="form-control validation_select select2" name="inactive">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                  </select>
                </div>
              </div>
                <div class="form-group row mb-xs-2">
                  <label class="col-sm-2 control-label">{{ __('Picture')  }}</label>
                  <div class="custom-file col-sm-8 pl-sm-3-custom">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" name="item_image" id="validatedCustomFile">
                      <label class="custom-file-label overflow-hidden" for="validatedCustomFile">{{ __('Upload Photo')  }}</label>
                    </div>                    
                  </div>
                </div>
                <div class="form-group row" id="prvw" hidden="true">
                  <div class="col-md-4 offset-md-2">
                    <img id="blah" src="#" alt="" class="img-responsive img-thumbnail" hidden="true" />
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-8 offset-sm-2" id='note_txt_1'>
                    <span class="badge badge-danger">{{ __('Note')  }}!</span> {{ __('Allowed File Extensions: jpg, png, gif')  }}
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group row" id="stock-tab">
              <div class="switch switch-primary d-inline ml-3">
                <input class="minimal" type="checkbox" id="manageStock" name="manage_stock">
                    <label for="manageStock" class="cr"></label>
              </div>
              <label id="rdoStock" class="m-l-5 swtich-label"> {{ __('Current Stocks') }} </label>
            </div>
            <div id="initialStockBlock">
              <div class="form-group row">
                <label class="col-sm-2 control-label require">{{ __('Initial Stock')  }}</label>
                <div class="col-sm-8 pl-sm-3-custom">
                  <input type="text" value="{{ old('initial_stock') ? old('initial_stock') : formatCurrencyAmount(0)}}" class="form-control positive-float-number" name="initial_stock" id="initialStock">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 control-label require">{{ __('Cost Price')  }}</label>
                <div class="col-sm-8 pl-sm-3-custom">
                  <div class="input-group p-0">
                    <span class="input-group-text"> {{ $currency_symbol }} </span>
                    <input type="text" class="form-control positive-float-number" value="{{ old('cost_price') ? old('cost_price') : formatCurrencyAmount(0) }}" name="cost_price" id="costPrice">
                  </div>
                  <label for="costPrice" generated="true" class="error"></label>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 control-label">{{ __('Alert Quantity')  }}</label>
                <div class="col-sm-8 pl-sm-3-custom">
                  <input type="text" value="{{ old('alert_quantity') ? old('alert_quantity') : formatCurrencyAmount(0) }}" class="form-control  positive-float-number" name="alert_quantity" id="alert_quantity">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 control-label">{{ __('Stock Location')  }}</label>
                <div class="col-sm-8 pl-sm-3-custom">
                  <select class="form-control select2 width-100" name="stock_location" id="stockLocation">
                    @foreach($locData as $loc)
                      <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          
            <div class="form-group row" id="variant-tab">
              <div class="switch switch-primary d-inline ml-3">
                <input class="minimal" type="checkbox" id="multi_variants" name="multi_variants">
                <label for="multi_variants" class="cr"></label>
              </div>
              <label id="rdoAttribute" class="m-l-5 swtich-label"> {{ __('Enable Attribute') }} </label>
            </div>
            <div id="multiVariantsBlock">
              <div class="form-group row">
                <label class="col-sm-2 control-label require">{{ __('Size') }}</label>
                <div class="col-sm-8 pl-sm-3-custom">
                  <div class="input-group p-0">
                    <span class="checkbox checkbox-primary checkbox-fill d-inline p-0">
                      <input type="checkbox" name="variant_size" id="sizeVariant" checked="">
                      <label for="sizeVariant" class="cr  control-label"></label>
                    </span>
                    <input type="text" value="{{ old('size') }}" class="form-control" name="size" id="sizeInput">
                    <label for="sizeInput" generated="true" class="error" id="variant-size"></label>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 control-label require">{{ __('Color') }}</label>
                <div class="col-sm-8 pl-sm-3-custom">
                  <div class="input-group p-0">
                    <span class="checkbox checkbox-primary checkbox-fill d-inline p-0">
                      <input type="checkbox" name="variant_color" id="colorVariant" checked="">
                      <label for="colorVariant" class="cr  control-label"></label>
                    </span>
                    <input type="text" value="{{ old('color') }}" class="form-control" name="color" id="colorInput">
                    <label for="colorInput" generated="true" class="error" id="variant-color"></label>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2 control-label require">{{ __('Weight') }}</label>
                 <div class="col-sm-4 pl-sm-3-custom">
                  <div class="input-group p-0">
                    <span class="checkbox checkbox-primary checkbox-fill d-inline p-0">
                      <input type="checkbox" name="variant_weight" id="weightVariant" checked="">
                      <label for="weightVariant" class="cr  control-label"></label>
                    </span>
                    <input type="text" value="{{ old('weight') }}" class="form-control positive-float-number" name="weight" id="weightInput">
                    <label for="weightInput" generated="true" class="error" id="variant-weight"></label>
                  </div>
                </div>
                <div class="col-sm-4" id="weightUnitDiv">
                  <select class="form-control select2 weight_unit" name="weight_unit" id="weightUnit">
                    @foreach($unitData as $unit)
                      <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div id="customVariantBlock">
              </div>
              <div class="form-group row">
                <div class="col-md-2"></div>
                <div class="col-md-2 mt-1">
                  <button id="customVariantBtn" type="button" class="btn btn-outline-secondary"><span class="fa fa-plus"> &nbsp;{{ __('New Custom Variant') }}</span></button>
                </div>
              </div>
            </div>
          
            
          </div>
          <div class="col-sm-8 pl-sm-3-custom px-0 mobile-margin">
            <button class="btn btn-primary custom-btn-small custom-variant-title-validation" type="submit" id="btnSubmit">{{  __('Submit')  }}</button>   
            <a href="{{ url('item') }}" class="btn btn-danger custom-btn-small">{{ __('Cancel') }}</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('js')

<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js')}}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/item.min.js') }}"></script>
</script>
@endsection