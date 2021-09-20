@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/plugins/jQueryUI/jquery-ui.min.css') }}" type="text/css"/>
<link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('public/dist/css/invoice-style.min.css')}}">
@endsection
@section('content')
<!-- Main content -->
<div class="col-md-12" id="adjustment-add-container">
  <form action="{{url('adjustment/save')}}" method="POST" id="transferForm">
    <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
    <div class="card">
      <div class="card-header">
        <h5> <a href="{{url('adjustment/list')}}">{{ __('Stock Adjustment') }}</a> >> {{ __('New Adjustment') }}</h5>
        <div class="card-header-right">
          
        </div>
      </div>
      <div class="card-body">
        <div class="p-0">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-4">
                <label class="control-label require">{{ __('Date') }}</label>
                <div class="form-group smallMarginZero">
                  <div class="input-group date">
                    <div class="input-group-prepend">
                      <i class="fas fa-calendar-alt input-group-text"></i>
                    </div>
                    <input class="form-control" placeholder="{{ __('Date') }}" id="datepicker" type="text" name="date">
                  </div>
                  <label id="datepicker-error" class="error" for="datepicker"></label>
                </div>
              </div>
              <div class="col-md-4">
                <label class="control-label require">{{ __('Type') }}</label>
                <select class="js-example-basic-single form-control" name="type" id="type">
                  <option value="">{{ __('All Types') }}</option>
                  <option value="{{'STOCKIN'}}">{{ __('Stock In') }}</option>
                  <option value="{{'STOCKOUT'}}">{{ __('Stock Out') }}</option>
                </select>
                <label id="type-error" class="error" for="type"></label>
              </div>
              <div class="col-md-4">
                <label class="control-label require">{{ __('Source') }}</label>
                <select class="js-example-basic-single form-control" name="location" id="location">
                  <option value="">{{ __('All Sources') }}</option>
                  @foreach($locationList as $data)
                  <option value="{{ $data->id }}" data-name="{{$data->name}}">{{ $data->name }}</option>
                  @endforeach
                </select>
                <label id="location-error" class="error mb-0" for="location"></label>
              </div>
            </div>
            <div class="row mt-1">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="item">{{ __('Add Item') }}</label>
                  <input class="form-control auto" placeholder="{{ __('Search Item')}}" id="search">
                  <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content ui-autocomplete-css" id="no_div" tabindex="0">
                    <li>{{ __('No record found') }} </li>
                  </ul>
                </div>
                <div id="error_message" class="text-danger"></div>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="card-body no-padding">
              <div class="table-responsive">
                <table table id="transferTbl" class="table table-bordered dt-responsive" width="100%">
                  <thead>
                    <tr class="tbl_header_color dynamicRows">
                      <th width="75%" class="text-center">{{ __('Item Name') }}</th>
                      <th width="20%" class="text-center">{{ __('Quantity') }}</th>
                      <th width="5%">{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody id="item-body">
                  </tbody>
                  <tfoot>
                    <tr class="tableInfo">
                      <td colspan="1" align="right"><strong>{{ __('Total') }}</strong></td>
                      <td align="center" id="total_qty" class="font-weight-bold no-b-r"> {{ formatCurrencyAmount(0) }} </td>
                      <td class="no-b-l"> &nbsp </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
              <br><br>
            </div>
          </div>
          <!-- /.card-body -->
          <div class="col-md-12">
            <div class="form-group">
              <label for="exampleInputEmail1">{{ __('Note') }}</label>
              <textarea placeholder="{{ __('Description') }} ..." rows="3" class="form-control" name="comments"></textarea>
            </div>
            <div class="col-sm-8 px-0">
              <button class="btn btn-primary custom-btn-small" type="submit" id="btnSubmit">{{ __('Submit') }}</button>
              <a href="{{URL::to('adjustment/list')}}" class="btn btn-danger custom-btn-small">{{ __('Cancel') }}</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
@include('layouts.includes.message_boxes')
@endsection

@section('js')
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/jQueryUI/jquery-ui.min.js') }}"></script>
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/adjustment.min.js') }}"></script>
@endsection