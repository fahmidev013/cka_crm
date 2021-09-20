@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/plugins/jQueryUI/jquery-ui.min.css') }}" type="text/css"/>
<link rel="stylesheet" type="text/css" href="{{asset('public/dist/css/invoice-style.min.css')}}">
{{-- DataTable --}}
<link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
@endsection
@section('content')
<!-- Main content -->
<div class="col-md-12" id="card-with-header-buttons">
  <form action="{{url('stock_transfer/update')}}" method="POST" id="transferForm">
    <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
    <input type="hidden" value="{{$stock_transfer_id}}" name="transfer_id" id="transfer_id">
    <input type="hidden" value="{{$Info->source_location_id}}" name="trans_source">
    <input type="hidden" value="{{$Info->destination_location_id}}" name="trans_destination">
    <div class="card">
      <div class="card-header">
        <h5> <a href="{{url('stock_transfer/list')}}">{{ __('Transfer') }}</a> >> {{ __('Edit Transfer') }}>> #{{ sprintf("%04d", $stock_transfer_id) }}</h5>
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
                    <input class="form-control" readonly="true" placeholder="{{ __('Date') }}" id="datepicker" value="{{formatDate($Info->transfer_date)}}" type="text" name="transfer_date">
                  </div>
                  <label id="datepicker-error" class="error" for="datepicker"></label>
                </div>
              </div>
              <div class="col-md-4">
                <label class="control-label require">{{ __('Source') }}</label>
                <select class="js-example-basic-single form-control" readonly="true" name="source" id="source">
                  @foreach($locationList as $data)
                    @if($data->id==$Info->source_location_id)
                      <option value="{{$data->id}}" {{($data->id == $Info->source_location_id ) ? 'selected' : ''}}>{{ $Info->sourceLocation->name }}</option>
                    @endif
                  @endforeach
                </select>
                <label id="source-error" class="error" for="source"></label>
              </div>
              <div class="col-md-4">
                <label class="control-label require">{{ __('Destination') }}</label>
                <select class="js-example-basic-single form-control" readonly="true" name="destination" id="destination">
                  @foreach($locationList as $data)
                    @if($data->id==$Info->destination_location_id)
                      <option value="{{$data->id}}" {{( $data->id == $Info->destination_location_id ) ? 'selected' : '' }}>{{ $Info->destinationLocation->name }}</option>
                    @endif
                  @endforeach
                </select>
                <label id="destination-error" class="error" for="destination"></label>
              </div>
            </div>
            <div class="row" id="add_trnsfr">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="item">{{ __('Add Item') }}</label>
                  <input class="form-control auto" placeholder="Search Item" id="search">
                  <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content noRecord" id="no_div" tabindex="0">
                    <li>{{ __('Record not found') }}</li>
                  </ul>
                </div>
                <div id="error_message" class="text-danger"></div>
              </div>
            </div>
          </div>
          
          <hr>
          <div class="row">
            <div class="card-body w-100">
              <div class="table-responsive">
                <table id="transferTbl" class="table table-bordered dt-responsive display nowrap" cellspacing="0">
                  <thead>
                    <tr class="tbl_header_color dynamicRows">
                      <th width="75%" class="text-center">{{ __('Name') }}</th>
                      <th width="20%" class="text-center">{{ __('Quantity') }}</th>
                      <th width="5%">{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody id="item-body">
                    <?php
                      $sum = 0;
                    ?>
                    @foreach($List as $result)
                    <?php
                      $sum += $result->quantity;
                      $max = "";
                      if ($result->item->is_stock_managed == 1) {
                        $max = "data-max = " . ($result->item->stockMoves->where('location_id', $Info->source_location_id)->sum('quantity') + $result->quantity);
                      }
                    ?>
                    <input type="hidden" name="stkMove_rowid[]" value="{{$result->id}}">
                    <tr class="addedRow" id="rowid-{{$result->item->id}}">
                      <td class="text-center">
                        {{$result->item->name}}
                        <input type="hidden" name="description[]" value="{{$result->item->name}}">
                      </td>
                      
                      <td>
                        <input class="form-control text-center no_units positive-float-number m-b-5" data-id="{{$result->item->id}}" stock-id="{{$result->item->id}}" id="qty_{{$result->item->id}}" name="item_quantity[]" value="{{formatCurrencyAmount($result->quantity)}}" old-qty="{{ $result->quantity }}" type="text" data-is_stock_managed="{{ $result->item->is_stock_managed }}" {{ $max }}>
                        <label id="{{$result->item->id}}-error" class="error labelMinMax" for="qty_{{$result->item->id}}"></label>
                        <div class="availableStock color_red f-bold" id="errorMessage-{{$result->item->id}}"></div>
                        <input type="hidden" name="item_id[]" value="{{$result->item->id}}">
                      </td>
                      <td class="text-center"><button id="{{$result->id}}" data-id="{{$result->item_id}}" class="btn btn-xs btn-danger delete_item"><i class="feather icon-trash-2"></i></button></td>
                    </tr>
                    <?php
                    $stack[] = $result->item_id;
                    ?>
                    @endforeach
                  </tbody>
                  <tfoot>
                    <tr class="tableInfo">
                      <td colspan="1" align="right"><strong>{{ __('Total') }}</strong></td>
                      <td align="center" id="total_qty" class="font-weight-bold no-b-r" width="20%">
                        {{formatCurrencyAmount($sum)}}
                      </td>
                      <td class="no-b-l"><input type="hidden" name="total_quantity" id="total_quantity" value="{{$sum}}"></td>
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
              <textarea placeholder="{{ __('Description') }} ..." rows="3" value="{{$Info->note}}" class="form-control" name="comments">{{$Info->note}}</textarea>
            </div>
            <div class="col-md-8 px-0">
              @if($Info->sourceLocation->is_active == 1 && $Info->destinationLocation->is_active == 1)
              <button class="btn btn-primary custom-btn-small" type="submit" id="btnSubmit">{{ __('Update') }}</button>
              @endif
              <a href="{{URL::to('/')}}/stock_transfer/list" class="btn btn-danger custom-btn-small">{{ __('Cancel') }}</a>
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
<script src="{{ asset('public/dist/plugins/jQueryUI/jquery-ui.min.js') }}">></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
{{--DataTable JS--}}
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script type="text/javascript">
  'use strict';
  var stack = <?= json_encode($stack); ?>;
  var info_id = {!! $Info->id !!};
</script>
<script src="{{ asset('public/dist/js/custom/stock-transfer.min.js') }}"></script>
@endsection