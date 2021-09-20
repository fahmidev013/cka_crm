@extends('layouts.list-layout')

@section('listCSS')
  <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection

@section('list-title')
  <h5><a href="{{ url('deposit/list') }}">{{ __('Bank Account Deposits')  }}</a></h5>
@endsection

@section('list-add-button')
  @if(Helpers::has_permission(Auth::user()->id, 'add_deposit'))
    <a href="{{ url('deposit/add-deposit') }}" class="btn custom-btn-small btn-outline-primary"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Deposit')  }}</a>
  @endif
@endsection

@section('list-form-content')
  <form class="form-horizontal" action="{{ url('deposit/list') }}" method="GET" id='orderListFilter'>
    <input class="form-control" id="startfrom" type="hidden" name="from" value="<?= isset($from) ? $from : '' ?>">
    <input class="form-control" id="endto" type="hidden" name="to" value="<?= isset($to) ? $to : '' ?>">
    <div class="col-md-12 col-sm-12 col-xs-12 m-l-10">
      <div class="row mt-3"> 
        <div class="col-xl-4 col-md-6 col-sm-6 col-xs-12 mb-2">
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
        <div class="col-md-3 col-sm-4 col-xs-12 mb-1">
          <div class="form-group">
            <select class="form-control select2" name="account_no" id="account_no">
              <option value="all">{{ __('All Bank Accounts') }}</option>
              @foreach($bankAccounts as $data)
                <option value="{{$data->id}}" "{{$data->id == $account_no ? ' selected="selected"' : ''}}">{{ $data->name }} ({{ $data->currency_name }})</option>
              @endforeach
            </select>            
          </div>
        </div>
        <div class="col-md-1 col-sm-1 col-xs-12 p-md-0 mb-1">
          <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small push-sm-right mt-0 mr-0">{{ __('Go') }}</button>
        </div>
      </div>
    </div>
  </form>
@endsection
@section('list-js')
<script src="{{ asset('public/dist/js/custom/deposit.min.js') }}"></script>
@endsection