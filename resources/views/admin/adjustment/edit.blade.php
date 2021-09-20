@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/plugins/jQueryUI/jquery-ui.min.css') }}" type="text/css"/>
<link rel="stylesheet" type="text/css" href="{{ asset('public/dist/css/invoice-style.min.css') }}">
{{-- DataTable --}}
<link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
@endsection
@section('content')
<!-- Main content -->
<div id="adjustment-edit-container" class="col-sm-12">
    <div class="col-sm-12" id="card-with-header-buttons">
        <form action="{{ url('adjustment/update') }}" method="POST" id="transferForm">
            <input type="hidden" value="{{ csrf_token()}}" name="_token" id="token">
            <input type="hidden" value="{{ $info->id }}" name="adjustment_id" id="adjustment_id">
            <input type="hidden" value="{{ $info->transaction_type }}" name="trans_type">
            <input type="hidden" value="{{ $info->location_id }}" name="trans_location">
            <div class="card">
                <div class="card-header">
                    <h5> <a href="{{ url('adjustment/list') }}">{{ __('Stock Adjustment') }}</a> >> {{ __('Edit Adjustment') }} >> #{{ sprintf("%04d", $info->id) }}</h5>
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
                                            <input class="form-control" readonly="true" placeholder="{{ __('Date') }}" value="{{ formatDate($info->transaction_date) }}" type="text" name="transfer_date">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label require">{{ __('Type') }}</label>
                                    <select class="js-example-basic-single form-control" readonly="true" name="type" id="type">
                                        @if($info->transaction_type=='STOCKIN')
                                            <option value="{{ 'STOCKIN' }}" selected>{{ __('Stock In') }}</option>
                                        @elseif($info->transaction_type=='STOCKOUT')
                                            <option value="{{ 'STOCKOUT' }}" selected>{{  __('Stock Out') }}</option>
                                        @endif
                                    </select>
                                    <label id="type-error" class="error" for="type"></label>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label require">{{ __('Location') }}</label>
                                    <select class="js-example-basic-single form-control" readonly="true" name="location" id="location">
                                        <option value="{{ $info->location_id }}" selected>{{ $info->location->name }}</option>
                                    </select>
                                    <label id="location-error" class="error" for="location"></label>
                                </div>
                            </div>
                            <div class="row" id="add_stkadjst">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="item">{{ __('Add Item') }}</label>
                                        <input class="form-control auto" placeholder="Search Item" id="search">
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
                            <div class="card-body no-padding" width="100%">
                                <div class="table-responsive">
                                    <table id="transferTbl" class="table table-bordered dt-responsive display nowrap" cellspacing="0">
                                        <thead>
                                            <tr class="tbl_header_color dynamicRows">
                                                <th width="75%" class="text-center">{{ __('Item Name') }}</th>
                                                <th width="20%" class="text-center">{{ __('Quantity') }}</th>
                                                <th width="5%">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="item-body">
                                            <?php $sum = 0; ?>
                                            @foreach($info->stockAdjustmentDetails as $result)
                                                <?php
                                                    $sum += $result->quantity;
                                                    $max = "";
                                                    $stock_managed = "data-is_stock_managed=0";

                                                    if ($info->transaction_type == 'STOCKOUT' && $result->item->is_stock_managed == 1) {
                                                        $stock_managed = "data-is_stock_managed=1";
                                                        $max = "data-max = " . ($result->item->stockMoves->where('location_id', $info->location_id)->sum('quantity') + $result->quantity);
                                                      }
                                                ?>
                                            <input type="hidden" name="item_rowid[]" value="{{ $result->id }}">
                                            <tr class="addedRow" id="rowid{{ $result->item_id }}">
                                                <td class="text-center">
                                                    {{ $result->description }}
                                                    <input type="hidden" name="description[]" value="{{ $result->description }}">
                                                </td>
                                                <td>
                                                    <input class="form-control text-center no_units m-b-5 positive-float-number" data-id="{{ $result->item_id }}" stock-id="{{ $result->item_id }}" id="qty_{{ $result->item_id }}" name="item_quantity[]" value="{{ formatCurrencyAmount($result->quantity) }}" old-qty="{{ $result->quantity }}" type="text" {{ $stock_managed }} {{ $max }}>
                                                    <input type="hidden" name="id[]" value="{{ $result->id }}">
                                                    <input type="hidden" name="item_id[]" value="{{ $result->item_id }}">
                                                </td>
                                                <td class="text-center"><button id="{{ $result->item_id }}" class="btn btn-xs btn-danger delete_item"><i class="feather icon-trash-2"></i></button></td>
                                            </tr>
                                            <?php $stack[] = $result->item_id; ?>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr class="tableInfo">
                                            <td colspan="1" align="right"><strong>{{ __('Total') }}</strong></td>
                                            <td align="center" id="total_qty" class="font-weight-bold no-b-r" width="20%">
                                                {{ formatCurrencyAmount($sum) }}
                                            </td>
                                            <td class="no-b-l"> <input type="hidden" name="total_quantity" id="total_quantity" value="{{ formatCurrencyAmount($sum) }}"> </td>
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
                                <textarea placeholder="{{ __('Description') }} ..." rows="3" class="form-control" name="comments">{{ $info->note }}</textarea>
                            </div>
                            <div class="col-md-8 px-0">
                                @if($info->location->is_active == 1)
                                    <button id="btnSubmit" type="submit" class="btn btn-primary custom-btn-small">{{ __('Update') }}</button>
                                @endif
                                <a href="{{ URL::to('/') }}/adjustment/list" class="btn btn-danger custom-btn-small">{{ __('Cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


@include('layouts.includes.message_boxes')
@endsection
@section('js')
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/jQueryUI/jquery-ui.min.js') }}">></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
<script type="text/javascript">
    'use strict';
    var stack = <?= json_encode($stack); ?>;
    var adjustmentId = {!! $info->id !!};
    var adjustmentType = "{!! $info->transaction_type !!}";
</script>
<script src="{{ asset('public/dist/js/custom/adjustment.min.js') }}"></script> 
@endsection
