@extends('layouts.list-layout')
@section('list-title')
  <h5><a href="{{ url('transfer/list') }}">{{ __('Transfer') }}</a></h5>
@endsection

@section('list-add-button')
  @if(Helpers::has_permission(Auth::user()->id, 'add_balance_transfer'))
    <a href="{{ url('transfer/create') }}" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Transfer') }}</a>
  @endif
@endsection

@section('list-form-content')
  <form class="form-horizontal" action="{{ url('transfer/list') }}" method="GET" id='orderListFilter'>
    <input class="form-control" id="startfrom" type="hidden" name="from" value="<?= isset($from) ? $from : '' ?>">
    <input class="form-control" id="endto" type="hidden" name="to" value="<?= isset($to) ? $to : '' ?>"> 
    <div class="col-md-12 col-sm-12 col-xs-12 m-l-10">
      <div class="row mt-3"> 
          <div class="col-xl-4 col-md-5 col-sm-6 col-xs-12 mb-2 ">
          <div class="input-group">
            <button type="button" class="form-control" id="daterange-btn">
              <span class="float-left">
                <i class="fa fa-calendar"></i>
                <span>{{ __('Date Range') }}</span>
              </span>
              <i class="fa fa-caret-down float-right pt-1"></i>
            </button>
          </div>
        </div>
        <div class="col-md-3 col-sm-2 col-xs-12 mb-1">
          <div class="form-group">
            <select class="form-control select2" name="from_bank_id" id="from_bank_id">
              <option value="">{{ __('From Bank Account') }}</option>
              @foreach($bankAccounts as $data)
               <option value="{{$data->id}}" <?=  $data->id == $from_bank_id ? 'selected' : '' ?> >{{ $data->name }} ({{ $data->currency['name'] }})</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="col-md-2 col-sm-2 col-xs-12 mb-1">
          <div class="form-group">
            <select class="form-control select2" name="to_bank_id" id="to_bank_id">
              <option value="">{{ __('To Bank Account') }}</option>
              @foreach($bankAccounts as $data)
              <option value="{{$data->id}}" <?=  $data->id == $to_bank_id ? 'selected' : '' ?>>{{ $data->name }} ({{ $data->currency['name'] }})</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-1 col-sm-1 col-xs-12">
          <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small push-sm-right mt-0 mr-0">{{ __('Go') }}</button>
        </div>
      </div>
    </div>
  </form>
@endsection

@section('list-js')
<script src="{{ asset('public/dist/js/custom/bank.min.js') }}"></script>
@endsection