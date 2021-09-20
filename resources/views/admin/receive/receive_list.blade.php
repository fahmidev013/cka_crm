@extends('layouts.list-layout')

@section('list-title')
  <h5>{{ __('Purchase Receive')  }}</h5>
@endsection

@section('list-form-content')
<div id="purchase-receive-list-container">
  <form class="form-horizontal" action="{{ url('purchase_receive/list') }}" method="GET" id='salesHistoryReport'>
    <input class="form-control" id="startfrom" type="hidden" name="from" value="<?= isset($from) ? $from : '' ?>">
    <input class="form-control" id="endto" type="hidden" name="to" value="<?= isset($to) ? $to : '' ?>">
    <div class="col-md-12 col-sm-12 col-xs-12 pl-4">
    <div class="row mt-3">
      <div class="col-xl-4 col-lg-5 col-md-6 col-sm-6 col-xs-12 mb-2">
        <div class="input-group">
          <button type="button" class="form-control" id="daterange-btn">
            <span class="float-left">
              <i class="fa fa-calendar"></i>
              {{ __('Pick a date range') }}
            </span>
            <i class="fa fa-caret-down float-right pt-1"></i>
          </button>
        </div>
      </div>
      <div class="col-xl-2 col-lg-2 col-md-4 col-sm-4 col-xs-12 mb-2">
        <select class="form-control select2" name="supplier" id="supplier">
          <option value="">{{ __('All suppliers') }}</option>
          @foreach($suppliers as $data)
          <option value="{{$data->id}}" <?= ($data->id == $supplier) ? 'selected' : ''?>>{{ $data->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-1 col-sm-1 col-xs-12 p-md-0">
        <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small mt-0 mr-0">{{ __('Go') }}</button>
      </div>
    </div>
  </div>  
</form>

<div class="modal fade" id="editModal" tabindex="-1" dialog="dialog">
  <div class="modal-dialog" role="document">
    <form method="POST" id="editReceive" action="{{url('purchase/receive/update-date')}}">
      {{csrf_field()}}
      <input type="hidden" name="id" id="receive-id">
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
              <input type="text" class="form-control" name="date" id="date">
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
@section('list-js')
<script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/purchases.min.js') }}"></script>
@endsection