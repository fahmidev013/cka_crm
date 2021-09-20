@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('public/dist/css/bootstrap-select.min.css')}}">
<link rel="stylesheet" href="{{ asset('public/dist/plugins/jQueryUI/jquery-ui.min.css') }}" type="text/css"/>
@endsection

@section('content')
  <!-- Main content -->
<div class="col-sm-12" id="barcode-container">
    <div class="card">
        <div class="card-header">
            <h5>{{ __('Barcode')}}</h5>
            <div class="card-header-right">
            </div>
        </div>
        <div class="card-block table-border-style">
            <form action="{{ url('barcode/create') }}" method="POST" id="barcodeCreate">
                <input type="hidden" value="{{ csrf_token() }}" name="_token" id="token">
                <div class="card card-body">
                    <div class="row">
                      <div class="col-sm-8 mb-3">
                        <div class="form-group row mb-0">
                            <label class="col-md-2 col-form-label" for="sel1">{{ __('Styles') }}</label>
                            <div class="col-sm-10 barcode-select2">
                              <select class="form-control select" id="sel1" name="perpage">
                                <option value="30" <?= isset($perpage) && ($perpage == 30) ? 'selected' : '' ?>>{{ __('30 per sheet')}} (2.625" x 1")</option>
                                <option value="20" <?= isset($perpage) && ($perpage == 20) ? 'selected' : '' ?>>{{ __('20 per sheet')}} (4" x 1")</option>
                              </select>
                            </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 mb-3">
                          <div class="form-group row">
                              <label class="col-md-2 col-xs-12 col-form-label" for="sel1" class="barcodePrint">{{ __('Print') }} </label>
                              <div class="col-md-4 col-xs-12 checkbox checkbox-success d-inline company_name">
                                  <input type="checkbox" name="site_name" id="checkbox-s-1">
                                  <label for="checkbox-s-1" class="cr">{{ __('Company Name') }}</label>
                              </div>
                              <div class="col-md-4 col-xs-12 checkbox checkbox-success d-inline pt-0">
                                  <input type="checkbox" name="product_name" id="checkbox-s-2">
                                  <label for="checkbox-s-2" class="cr">{{ __('Product Name') }}</label>
                              </div>
                          </div>
                        </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-8 mb-3">
                        <div class="form-group row mb-0">
                          <label class="col-md-2 col-form-label" for="exampleInputEmail1">{{ __('Add Item') }}</label>
                          <input class="form-control auto col-md-10" placeholder="{{ __('Search Item') }}" id="search">
                          <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content noRecord" id="no_div" tabindex="0">
                              <li>{{ __('No record found') }}</li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="alert alert-danger display_none" role="alert" id="alert">
                        {{ __('Please add at least one item') }}
                    </div>
                    <div class="alert alert-danger display_none" role="alert" id="quantity-alert">
                      {{ __('Quantity can not be zero or empty.') }}
                    </div>
                  <div class="row customcss">
                    <table class="table table-bordered table-responsive" id="itemTable">
                      <tbody>
                          <tr height="40px" class="dynamicRows tbl_header_color">
                              <th width="30%" class="text-center">{{ __('Product Name') }}</th>
                              <th width="10%" class="text-center">{{ __('Barcode Quantity') }}</th>
                              <th width="1%"  class="text-center">{{ __('Action') }}</th>
                          </tr>
                      </tbody>
                    </table>
                    <div>
                        <button type="submit" id="btnSubmit" class="btn btn-info float-left custom-btn-small">{{ __('Submit') }}</button>
                    </div>
                  </div>
                </div>
            </form>
        </div>
    </div>

     @if(count($items) > 0)
      <div class="box">
        <div class="box-default">
          <div class="box-header clearfix">
            <a href="javascript:void(0);" id="printBtn" class="btn btn-info float-right custom-btn-small">{{ __('Print') }}</a>
          </div>
          <div class="box-body">
            <div class="form-group col-md-12 barcode w-100">
              @if(!empty($items))
                @foreach ($items as $key => $value)
                  @for($i = 0; $i < $quantities[$key]; $i++)
                    <div class="item style<?= $perpage ?>">
                      @if(isset($companyName))
                        <span class="barcode_site f-10">{{ $companyName }}</span>
                      @endif
                      @if(isset($product_name))
                        <span class="barcode_name f-10">{{ $value }}</span><br>
                      @endif
                      @php
                        $image = DNS1D::getBarcodePNG( $stock_ids[$key] , "C128");
                      @endphp
                      <span class="barcode_image {{ empty($companyName) && empty($product_name) ? 'mt-9p' : ''}}">
                        <img src='data:image/png;base64, {{ $image }}' alt="barcode" class="bcimg"/>
                      </span>
                    </div>
                  @endfor
                @endforeach
              @endif
            </div>
            
          <div class="clearfix"></div>
          </div>
        </div>
      </div>
    @endif
</div>


@endsection
@section('js')
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/jQueryUI/jquery-ui.min.js') }}">></script>
<script src="{{ asset('public/dist/js/jquery.PrintArea.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/barcode.min.js') }}"></script>
@endsection