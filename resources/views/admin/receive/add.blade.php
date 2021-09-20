@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content')
<div class="col-sm-12" id="add-purchase-receive-container">
	<form method="post" action="{{url('purchase/receive/manual_store')}}" id="receiveForm">
		{{csrf_field()}}
		<input type="hidden" value="{{$orderInfo->id}}" name="order_no" id="order_no">
    <input type="hidden" value="{{$orderInfo->reference}}" name="order_reference" id="order_reference">
    <input type="hidden" value="{{$orderInfo->location_id}}" name="location">
    <input type="hidden" value="{{$orderInfo->supplier_id}}" name="supplier_id">

		<div class="card">
	    <div class="card-header">
	      <h5><a href="{{ url('purchase_receive/list') }}">{{ __("Purchase Receives") }}</a> >> <a href="{{ url('purchase/view-purchase-details/' . $orderInfo->id) }}">#{{$orderInfo->reference}}</a></h5>
	    </div>
	    <div class="card-body">
	    	<div class="row">
		    	<div class="col-md-6">
		    		<div class="form-group row">
		    			<label class="col-form-label ml-3">{{ __("Receipt no")}}: </label>
		    			<div class="col-md-6">
		    				<input type="text" class="form-control" name="order_receive_no" id="order_receive_no" >
		    			</div>
		    		</div>
		    	</div>
		    	<div class="col-md-6">
		        <div class="form-group row">
		          <label class="col-form-label col-md-4 text-right">{{ __("Date") }}:</label>
		          <div class="input-group date col-md-8 px-0">
		            <div class="input-group-prepend">
		              <i class="fas fa-calendar-alt input-group-text"></i>
		            </div>
		            <input class="form-control" id="datepicker" type="text" name="receive_date" readonly="">
		          </div>
		          <div class="col-md-4"></div>
		          <label id="datepicker-error" class="error" for="datepicker"></label>
		        </div>
		      </div>
		    </div>
		    <div class="row">
		    	<div class="table-responsive">
            <table class="table table-bordered">
              <thead>
              	<tr class="tbl_header_color dynamicRows">
              		<th width="25%" class="text-center">{{ $orderInfo->invoice_type == "hours" ? __("Service") : __("Item") }}</th>
                  <th width="12%" class="text-center">{{ __("Ordered") }}</th>
                  <th width="12%" class="text-center">{{ __('Received') }}</th>
                  <th width="12%" class="text-center">{{ __('Remaining') }}</th>
                  <th width="12%" class="text-center">{{ __('Receive') }}</th>
                  <th width="10%" class="text-center">{{ __('Action') }}</th>
              	</tr>
              </thead>
              <tbody>
              	@if ( count($orderInfo->purchaseOrderDetails) > 0 )
                  @foreach ($orderInfo->purchaseOrderDetails as $k=>$result )
                    @if($result->quantity_ordered > $result->quantity_received)
                    	<tr class="tblRows" >
                    		<input type="hidden" name="item_id[]" value="{{ $result->item_id }}">
                        <input type="hidden" name="unit_price[]" value="{{ $result->unit_price }}">
                        <input type="hidden" name="purchase_order_detail_id[]" value="{{ $result->id }}">
                        <td class="text-center">
                          {{ $result->item_name }}
                          <input type="hidden" name="item_name[]" value="{{ $result->item_name }}">
                        </td>

                        <td class="text-center" >
                          {{ formatCurrencyAmount($result->quantity_ordered) }}
                        </td>

                        <td class="text-center" >
                          {{ formatCurrencyAmount($result->quantity_received) }}
                        </td>
                        
                        <td class="text-center" >
                          {{ formatCurrencyAmount($result->quantity_ordered - $result->quantity_received) }}
                        </td>
                        <td class="rows">
                          <input class="form-control text-center positive-float-number inputQty" name="quantity[]" value="{{ formatCurrencyAmount($result->quantity_ordered - $result->quantity_received) }}" type="text" data-max="{{ $result->quantity_ordered - $result->quantity_received }}" data-id="{{ $result->id }}" id="rowId-{{ $result->id }}">
                          <span id="error_{{ $result->id }}" class="f-11 color_red font-bold spanClass"></span>
                        </td>
                        <td class="text-center">
                          <button type="button" class="btn btn-xs btn-danger delete_item">
                           <i class="feather icon-trash-2"></i>
                          </button>
                        </td>
                      </tr>
                      @endif
                    @endforeach
                  @endif
              </tbody>
            </table>
            <div class="col-md-12">
              <button type="submit" class="btn btn-primary custom-btn-small btn-flat float-right" id="btnSubmit">{{ __("Submit") }}</button>
              <a href="{{ url('purchase/view-purchase-details/'.$orderInfo->id) }}" class="btn btn-danger custom-btn-small float-right">{{ __('Cancel') }}</a>
            </div>
          </div>
		    </div>
	    </div>
	  </div>
	</form>
</div>
@endsection

@section('js')
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/purchases.min.js') }}"></script>
@endsection