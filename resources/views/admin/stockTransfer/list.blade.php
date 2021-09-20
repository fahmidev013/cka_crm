@extends('layouts.list-layout')

@section('listCSS')
@endsection

@section('list-title')
  <h5>{{ __('Stock Move List') }}</h5>
@endsection

@if(Helpers::has_permission(Auth::user()->id, 'add_stock_transfer'))
  @section('list-add-button')
    <a href="{{ url('stock_transfer/create') }}" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Transfer') }}</a>
  @endsection
@endif

@section('list-form-content')
    <!-- Main content -->
  <form class="form-horizontal" action="{{ url('stock_transfer/list') }}" method="GET" id='orderListFilter'>
    <input class="form-control" id="startfrom" type="hidden" name="from" value="<?= isset($from) ? $from : '' ?>">
    <input class="form-control" id="endto" type="hidden" name="to" value="<?= isset($to) ? $to : '' ?>">
      <div class="col-md-12 col-sm-12 col-xs-12 pl-4">
      @php 
        use App\Model\Location;
      @endphp
        <div class="row mt-3">
          <div class="ticket-filter col-xl-4 col-md-6 col-sm-6 col-xs-12 mb-2">
            <div class="input-group">
              <button type="button" class="form-control" id="daterange-btn">
                <span class="float-left">
                  <i class="fa fa-calendar"></i>
                  {{ __('Date Range') }}
                </span>
                <i class="fa fa-caret-down float-right pt-1"></i>
              </button>
            </div>
          </div>
          <div class="ticket-filter col-xl-3 col-md-3 col-sm-3 col-xs-12 mb-2">
            <select class="form-control select2" name="source" id="source">
              <option value="">{{ __('All Source') }}</option>
              @if(!empty($sourceList))
                @foreach($sourceList as $location)
                  <option value="{{$location->id}}" <?= ($location->id == $source) ? 'selected' : ''?>>{{$location->name}}</option>
                @endforeach
              @endif
            </select>
          </div>
          
          <div class="ticket-filter col-xl-3 col-md-2 col-sm-2 col-xs-12 mb-2">
            <select class="form-control select2" name="destination" id="destination">
              <option value="">{{ __('All Destination') }}</option>
              @if( (! empty($destination) && $destination != 'all') || (! empty($source) && $source != 'all') )
                @foreach($destinationList as $location)
                  <option value="{{$location->id}}" <?= ($location->id == $destination) ? 'selected' : ''?>>{{$location->name}}</option>
                @endforeach
              @else
                @foreach($sourceList as $location)
                  <option value="{{$location->id}}" <?= ($location->id == $source) ? 'selected' : ''?>>{{$location->name}}</option>
                @endforeach
              @endif
            </select>
          </div>
          <div class="col-xl-1 col-md-1 col-sm-1 col-xs-12 p-md-0">
            <button type="submit" name="btn" title="{{ __('Click to filte') }}r" class="btn btn-primary custom-btn-small push-sm-right mt-0 mr-0">{{ __('Go') }}</button>
          </div>
        </div>
      </div>
    </form>
@endsection


@section('list-js')
<script src="{{ asset('public/dist/js/custom/stock-transfer.min.js') }}"></script>
@endsection