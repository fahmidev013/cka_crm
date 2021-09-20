@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/dist/css/item.min.css') }}">
@endsection

@section('content')
    <!-- Main content -->
<div class="col-sm-12" id="item-variant-container">
  <div class="card user-list">
    <div class="card-header">
      <h5><a href="{{ url('item') }}">{{ __('Item') }}</a> >> <a href='{{ url("edit-item/variant/" . $itemInfo->id) }}'> {{ $itemInfo->name }} </a> >> {{ __('New Variant') }} </h5>
      <div class="card-header-right">
        
      </div>
    </div>
    <div class="card-block">
      <div class="form-tabs">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active text-uppercase" id="setting-tab" data-toggle="tab" href="#setting" role="tab" aria-controls="setting" aria-selected="true">{{ __('Item Info') }}</a>
          </li>
        </ul>
      </div>      
        <div class="tab-content">
          <div class="tab-pane fade show active" id="setting">
            <form action="{{ url('save-variant') }}" method="post" id="variantAddForm" class="form-horizontal" enctype="multipart/form-data">
              <input type="hidden" value="{{ csrf_token() }}" name="_token" id="token">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-2 control-label require">{{ __('Item Name') }}</label>
                  <div class="col-sm-8">
                    <input type="hidden" name="item_id" value="{{ $itemInfo->id }}">
                    <input type="text" placeholder="{{ __('Item Name') }}" class="form-control valdation_check" name="item_name" id="item_name" value="{{ old('item_name') }}">
                    <span id="checkMsg" class="text-danger"></span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-5">
                    <div class="form-group row ">
                      <label class="col-sm-5 col-form-label require pr-0">{{ __('Item ID') }}</label>
                      <div class="col-sm-7 pl-md-2">
                        <input type="text" placeholder="{{ __('Item ID') }}" class="form-control" name ="stock_id" id="stock_id" value="{{ old('stock_id') }}">
                        <span id="errMsg" class="text-danger"></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-5">
                    <div class="form-group row ">
                      <label class="col-sm-5 col-form-label pr-0">{{ __('HSN/SAC') }}</label>
                      <div class="col-sm-7 pl-md-2">
                        <input type="text" placeholder="{{ __('HSN/SAC') }}" class="form-control" name="hsn" value="{{ old('item_name') }}">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2 control-label">{{ __('Item Description') }}</label>
                  <div class="col-sm-8">
                    <textarea class="form-control" placeholder="{{ __('Item Description') }}" name="description" id="description"></textarea>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-2 control-label require">{{ __('Purchase Price') }}</label>
                  <div class="col-sm-8">
                    <div class="input-group">                      
                      <div class="input-group-prepend">
                        <span class="input-group-text">{{ $currency_symbol }}</span>
                      </div>
                      <input type="text" class="form-control positive-float-number" placeholder="{{ __('Purchase Price') }}" name="purchase_price" id="purchase_price" value="{{ old('purchase_price') ? old('purchase_price') : 0 }}">
                    </div>
                    <label id="purchase_price-error" class="error" for="purchase_price"></label>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2 control-label">{{ __('Retail Price') }}</label>
                  <div class="col-sm-8">
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">{{ $currency_symbol }}</span>
                          </div>
                          <input type="text" class="form-control positive-float-number" placeholder="{{ __('Retail Price') }}" name="retail_price" id="retail_price" value="{{ old('retail_price') ? old('purchase_price') : 0 }}">
                        </div>
                        <label id="retail_price-error" class="error" for="retail_price"></label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2 control-label">{{ __('Picture') }}</label>
                  <div class="custom-file col-sm-8">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" name="item_image" id="validatedCustomFile">
                      <label class="custom-file-label overflow-hidden" for="validatedCustomFile">{{ __('Upload Image') }}</label>
                    </div>                    
                  </div>
                </div>

                <div class="form-group row" id="prvw" hidden="true">
                  <div class="col-md-4 offset-md-2">
                    <img id="blah" src="#" alt="" class="img-responsive img-thumbnail" hidden="true" />
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-2 control-label"></label>
                  <div class="col-md-8" id='note_txt_1'>
                    <span class="badge badge-danger">{{ __('Note') }}!</span> {{ __('Allowed File Extensions: jpg, png, gif, bmp') }}
                  </div>
                  <div class="col-md-8" id='note_txt_2'>                      
                  </div>
                </div>
                @if ($hasVariants)
                    <div class="form-group row">
                      <h4 class="m-l-10 swtich-label"> {{ __('Attribute') }} </h4>
                    </div>
                    <div id="multiVariantsBlock">
                    @if(isset($size))
                      <div class="form-group row">
                        <label class="col-sm-2 control-label require">{{ __('Size') }}</label>
                        <div class="col-sm-8">
                          <input value="" type="text" placeholder="{{ __('Size') }}" name="size" id="sizeInput" class="form-control">
                        </div>
                      </div>
                    @endif
                    @if(isset($color))
                      <div class="form-group row">
                        <label class="col-sm-2 control-label require">{{ __('Color') }}</label>
                        <div class="col-sm-8">
                          <input value="" type="text" name="color" placeholder="{{ __('Color') }}" id="colorInput" class="form-control">
                        </div>
                      </div>
                    @endif
                    @if(isset($weight))
                      <div class="form-group row">
                        <label class="col-sm-2 control-label require">{{ __('Weight') }}</label>
                        <div class="col-sm-8">
                          <div class="row">                                
                            <div class="col-sm-6">
                              <input value="" type="text" name="weight" placeholder="{{ __('Weight') }}" id="weightInput" class="form-control positive-float-number">
                            </div>
                            <div class="col-sm-6" id="weightUnitDiv">
                              <select name="weight_unit" class="form-control custom_select_2">
                                @foreach($unitData as $unit)
                                  <option {{ $unit->id == $item_unit_id ? 'selected' : '' }} value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                          <label for="weightInput" generated="true" class="error"></label>
                        </div>
                      </div>
                    @endif
                    @if(isset($customVariantItem))
                      <div id="customVariantBlock">
                          <?php $i = 1; ?>
                          @foreach($customVariantItem as $cusVariant)
                            <div class="form-group row variantDiv" id="rowid-{{ $cusVariant->id }}">
                                <input type="hidden" name="custom_variant_id[]" value="{{ $cusVariant->id }}">
                                <input type="hidden" name="variant_title[]" value="{{ $cusVariant->variant_title }}">
                                <label class="col-sm-2 require control-label">{{ $cusVariant->variant_title }}</label>
                              <div class="col-sm-8">
                                  <input type="text" id="variant-value-{{ $i }}" placeholder="{{ $cusVariant->variant_title }}" name="variant_value[]" class="form-control custom-variant-value">
                                  <span id="extra-variant-value-{{ $i }}" class="validationMsg"></span>
                              </div>
                            </div>
                            <?php $i++; ?>
                          @endforeach
                        </div>
                      </div>
                    @endif
                @endif
                <div class="form-group row">
                  <label class="col-sm-10 control-label">
                    <div class="switch switch-primary d-inline m-r-10">
                        <input class="minimal" type="checkbox" id="manageStock" name="manage_stock">
                        <label for="manageStock" class="cr"></label>
                    </div>
                    <span class="m-l-10 swtich-label"> {{ __('Manage Stock Level') }} </span>
                  </label>
                </div>
                  <div id="initialStockBlock">
                    <div class="form-group row">
                      <label class="col-sm-2 control-label require">{{ __('Initial Stock') }}</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control positive-float-number" placeholder="{{ __('Initial Stock') }}" name="initial_stock" id="initialStock" value="{{ old('initial_stock') ? old('initial_stock') : 0}}">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 control-label require">{{ __('Cost Price') }}</label>
                      <div class="col-sm-8">
                        <div class="input-group p-0">
                          <span class="input-group-text"> {{ $currency_symbol }} </span>
                          <input type="text" class="form-control positive-float-number" placeholder="{{ __('Cost Price') }}" name="cost_price" id="costPrice" value="{{ old('cost_price') ? old('cost_price') : 0 }}">
                        </div>
                        <label for="costPrice" generated="true" class="error"></label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 control-label">{{ __('Alert Quantity') }}</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="{{ __('Quantity') }}" name="alert_quantity" id="alert_quantity" value="{{ old('alert_quantity') ? old('alert_quantity') : 0 }}">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 control-label">{{ __('Stock Location') }}</label>
                      <div class="col-sm-8">
                        <select class="form-control custom_select_2 width-100" name="stock_location" id="stockLocation">
                          @foreach($locData as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-8 px-0 mobile-margin">
                    <button class="btn btn-primary custom-btn-small" type="submit" id="btnSubmit">{{ __('Submit') }}</button>   
                    <a href="{{ url('edit-item/variant') . '/' . $itemInfo->id  }}" class="btn btn-danger custom-btn-small">{{ __('Cancel') }}</a>
                  </div>
                </div>
                <!-- /.box-body -->
              </div>
            </form>
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
<script src="{{ asset('public/dist/js/custom/item.min.js') }}"></script>
@endsection