@extends('layouts.list-layout')

@section('list-title')
  <h5>{{ __('Stock Adjustment') }}</h5>
@endsection

@section('list-add-button')
  <a href="{{ url('adjustment/create') }}" class="btn btn-outline-primary custom-btn-small"><span class="feather icon-plus"> &nbsp;</span>{{ __('New Adjustment') }}</a>
@endsection

@section('list-form-content')
    <!-- Main content -->
  <div id="adjustment-list-container">
    <form class="form-horizontal" action="{{ url('adjustment/list') }}" method="GET" id='orderListFilter'>
      <input class="form-control" id="startfrom" type="hidden" name="from" value="<?= isset($from) ? $from : '' ?>">
      <input class="form-control" id="endto" type="hidden" name="to" value="<?= isset($to) ? $to : '' ?>">
        <div class="col-md-12 col-sm-12 col-xs-12 pl-4">
          <div class="row mt-3">
            <div class="col-xl-4 col-md-6 col-sm-6 col-xs-12 mb-2">
              <div class="input-group">           
                <button type="button" class="form-control" id="daterange-btn">
                  <span class="float-left">
                    <i class="fa fa-calendar"></i> {{ __('Date range picker') }}
                  </span>
                  <i class="fa fa-caret-down float-right pt-1"></i>
                </button> 
              </div>           
            </div>

            <div class="col-xl-3 col-md-3 col-sm-3 col-xs-12 mb-2">
              <select class="form-control select2" name="trans_type" id="trans_type">
                <option value="">{{ __('All Types') }}</option>
                <option value="{{ 'STOCKIN' }}" <?= ('STOCKIN' == $trans_type ? 'selected' : '') ?>>{{ __('Stock In') }}</option>
                <option value="{{ 'STOCKOUT' }}" <?= ('STOCKOUT' == $trans_type ? 'selected' : '')?>>{{ __('Stock Out') }}</option>
              </select>
            </div>

            <div class="col-xl-3 col-md-2 col-sm-2 col-xs-12 mb-2">
              <select class="form-control select2" name="destination" id="destination">
                <option value="">{{ __('All Location') }}</option>
                @if(!empty($locationList))
                  @foreach($locationList as $location)
                    <option value="{{ $location->id }}" <?= ($location->id == $destination) ? 'selected' : ''?>>{{ $location->name }}</option>
                  @endforeach
                @endif
              </select>
            </div>

            <div class="col-xl-1 col-md-1 col-sm-1 col-xs-12 p-md-0">
              <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small push-sm-right mt-0 mr-0">{{ __('Go') }}</button>
            </div>
          </div>
        </div>
    </form>
  </div>
@endsection
@section('list-js')
<script src="{{ asset('public/dist/js/custom/adjustment.min.js') }}"></script>
@endsection