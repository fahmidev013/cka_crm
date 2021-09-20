@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content')
<div id="purchase-recieve-details-container">
  <div class="col-sm-12" id="card-with-header-button">
    <div class="card">
      <div class="card-header">
        <h5><a href="{{url('purchase_receive/list')}}">{{__('Purchase Receive') }}</a> >> #{{ sprintf("%04d", $receivedData->id) }}</h5>
        <div class="card-header-right">
          @if(Helpers::has_permission(Auth::user()->id, 'add_quotation'))
            <a href="{{ url('purchase/add') }}" class="btn btn-outline-primary custom-btn-small"><span class="feather icon-plus"> &nbsp;</span>{{ __('Purchase Order')  }}</a>
          @endif
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header">
        <div class="btn-group float-right">
          <a target="_blank" href="{{URL::to('/')}}/purchase/receive/print/{{$receivedData->id}}?type=print" title="Print" class="btn custom-btn-small btn-outline-secondary">{{ __('Print')  }}</a>
          <a target="_blank" href="{{URL::to('/')}}/purchase/receive/pdf/{{$receivedData->id}}" title="PDF" class="btn custom-btn-small btn-outline-secondary">{{ __('PDF')  }}</a>
          @if(Helpers::has_permission(Auth::user()->id, 'edit_purchase_receive'))
            <a title="Edit" class="btn custom-btn-small btn-outline-secondary" data-toggle="modal" data-target="#editModal">{{ __('Edit') }}</a>
          @endif
          @if(Helpers::has_permission(Auth::user()->id, 'delete_purchase_receive'))
            <form method="POST" id="delete-purchase-receive" accept-charset="UTF-8" class="display_inline" action='{{ url("purchase/receive/delete/$receivedData->id")}}'>
              {!! csrf_field() !!}
              <input type="hidden" name="receive_id" value="{{ $receivedData->id }}">
              <input type="hidden" name="order_no" value="{{ $receivedData->purchase_order_id }}">
              <button class="btn custom-btn-small btn-outline-danger" title="{{ __('Delete') }}" type="button" data-id="{{ $receivedData->id }}" data-toggle="modal" data-target="#theModal" data-label="Delete" data-title="{{ __('Delete purchase receive') }}" data-message="{{ __('Are you sure to delete this purchase receive?') }}">
                 {{ __('Delete')  }}
              </button>
            </form>
          @endif
        </div>
      </div>
      <div class="card-body">
        <div class="m-t-10">
          <div class="row m-t-10 ml-2">
            <div class="col-md-4 m-b-15">
              <strong class="text-black">{{ $company_name }}</strong><br>
              <strong>{{ $company_street }}</strong><br>
              <strong>{{ $company_city }}, {{ $company_state }}</strong><br>
              <strong>{{ $company_country_name }}, {{ $company_zipCode }}</strong><br>
            </div>

            <div class="col-md-4 m-b-15">
              <strong class="text-black">{{isset($receivedData->supplier->name) ? $receivedData->supplier->name : ''}}</strong><br>
              <strong>{{ isset($receivedData->supplier->street) ? $receivedData->supplier->street : ''}}</strong><br>
              <strong>{{ isset($receivedData->supplier->city) ? $receivedData->supplier->city : ''}}{{ isset($receivedData->supplier->state) ? ', '.$receivedData->supplier->state : ''}}</strong><br>
              <strong>{{ isset($receivedData->supplier->country->name) ? $receivedData->supplier->country->name : '' }}{{ isset($receivedData->supplier->zipcode) ? ', '.$receivedData->supplier->zipcode : '' }}</strong><br>
            </div>
            <div class="col-md-4 m-b-15">
              <strong>{{ __('Purchase No')  }} #<a href="{{url('purchase/view-purchase-details/'.$receivedData->purchaseOrder->id)}}">{{ $receivedData->reference }}</a></strong><br>
              <strong>{{ __('Location') }} : {{ isset($receivedData->location->name) ? $receivedData->location->name : ''}}</strong><br>
              <strong>{{ __('Date') }} : {{formatDate($receivedData->receive_date)}}</strong><br>
            </div>
          </div>
          <div class="row m-t-10">
            <div class="col-md-12">
              <div class="table-responsive">
                <table class="table table-bordered" id="salesInvoice">
                  <tbody>
                    <tr class="tbl_header_color dynamicRows">
                      <th  class="text-center">{{ __('SL No') }}</th>
                      <th  class="text-center">{{ isset($receivedData->purchaseOrder->invoice_type) && $receivedData->purchaseOrder->invoice_type == 'hours' ? __('Service') : __('Item') }}</th>
                      <th  class="text-center">{{ isset($receivedData->purchaseOrder->invoice_type) && $receivedData->purchaseOrder->invoice_type == 'hours' ? __('Hours') : __('Quantity') }}</th>
                    </tr>
                    @if ( count($receivedData->receivedOrderDetails) > 0 )
                      @foreach ($receivedData->receivedOrderDetails as $key=>$result)
                        <tr>
                          <td class="text-center">{{ ++$key }}</td>
                          <td class="text-center">{{ $result->item_name }}</td>
                          <td class="text-center">{{ formatCurrencyAmount($result->quantity) }}</td>
                        </tr>
                      @endforeach
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          @if(!empty($receivedData->order_receive_no))
            <div class="row m-t-10">
              <div class="col-md-12">
                <strong> {{ __('Note') }}: </strong>{{ $receivedData->order_receive_no }}
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="theModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="theModalLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary custom-btn-small" data-dismiss="modal">{{ __('Close') }}</button>
          <button type="button" id="theModalSubmitBtn" data-task="" class="btn btn-danger custom-btn-small">{{ __('Submit') }}</button>
          <span class="ajax-loading"></span>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="editModal" tabindex="-1" dialog="dialog">
    <div class="modal-dialog" role="document">
      <form method="POST" id="editReceive" action="{{url('purchase/receive/update-date')}}">
        {{csrf_field()}}
        <input type="hidden" name="id" id="receive-id" value="{{$receivedData->id}}">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">{{ __('Edit receive') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <label for="date" class="col-md-3 col-form-label">{{ __('Receive Date') }}:</label>
              <div class="col-md-8">
                <input type="text" class="form-control" name="date" id="date" value="{{formatDate($receivedData->receive_date)}}">
                <div class="text-danger" id="error-msg"></div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary custom-btn-small" data-dismiss="modal">{{ __('Close') }}</button>
            <button type="submit" class="btn btn-primary custom-btn-small">{{ __('Update') }}</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('js')
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
<script type="text/javascript">
  'use strict';
  var editDate = "{{ $receivedData->receive_date }}";
</script>
<script src="{{ asset('public/dist/js/custom/sales-purchase.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/purchases.min.js') }}"></script>
@endsection