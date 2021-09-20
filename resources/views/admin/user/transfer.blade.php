@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
<!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
<!-- Main content -->
<div class="col-sm-12" id="user-transfer-container">
  <div class="card">
    <div class="card-header">
      <h5><a href="{{ url('users') }}">{{ __('Transfers') }}</a> >> {{ __('Transfer List') }} >> {{$user->full_name}} </h5>
      <div class="card-header-right">
        
      </div>
    </div>
    <div class="card-body p-0" id="no_shadow_on_card">
      @include('layouts.includes.user_menu')
    </div>

    <div class="col-sm-12">
      <form class="form-horizontal" action='{{ url("user/user-transfer-list/$user_id") }}' method="GET" id='salesHistoryReport'>
        <input class="form-control" id="startfrom" type="hidden" name="from" value="<?= isset($from) ? $from : '' ?>">
        <input class="form-control" id="endto" type="hidden" name="to" value="<?= isset($to) ? $to : '' ?>">
        <div class="col-md-12 col-sm-12 col-xs-12 m-l-10">
          <div class="row mt-3 mb-2">
            <div class="col-md-4 col-sm-4 col-xs-12 mb-2">
              <div class="input-group">
                <button type="button" class="form-control" id="daterange-btn">
                  <span class="float-left">
                    <i class="fa fa-calendar"></i>  {{ __("Date Range") }}
                  </span>
                  <i class="fa fa-caret-down float-right pt-1"></i>
                </button>
              </div>
            </div> 

            <div class="col-md-4 col-sm-3 col-xs-11 mb-2">
              <select class="form-control valdation_select" name="source" id="source" >
                <option value="">{{ __('Source') }}</option>
                @foreach($locationList as $locationItem)
                  <option value="{{ $locationItem->id }}" {{ ($locationItem->id == $source) ? 'selected' : '' }}>{{ $locationItem->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-11 mb-2">
              <select class="form-control valdation_select" name="destination" id="destination" >
                <option value="">{{ __('Destination') }}</option>
                  @foreach($locationList as $locationItem)
                    <option value="{{ $locationItem->id }}" {{ ($locationItem->id == $destination) ? 'selected' : '' }}>{{ $locationItem->name }}</option>
                  @endforeach
              </select>
            </div>
            <div class="col-md-1 col-sm-1 col-xs-12 p-md-0">
              <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small push-sm-right mt-0 mr-0">{{ __('Go') }}</button>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="card-block">
      <div class="col-sm-12">
        <div class="table-responsive">
          {!! $dataTable->table(['class' => 'table table-striped table-hover dt-responsive customer-transfer', 'width' => '100%', 'cellspacing' => '0']) !!}
        </div>
      </div>
    </div>

  </div>
  <!-- Delete modal -->
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
</div>
@include('layouts.includes.message_boxes')
@endsection
@section('js')
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
{{-- Moment js --}}
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
<!-- date-range-picker -->
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
{{-- Select2 --}}
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
{!! $dataTable->scripts() !!}
<script type="text/javascript">
  "use strict";
  var startDate   = "{!! isset($from) ? $from : 'undefined' !!}";
  var endDate     = "{!! isset($to) ? $to : 'undefined' !!}";
  var sessionDate = '{!! $date_format_type !!}';
</script>
<script src="{{ asset('public/dist/js/custom/user.min.js') }}"></script>
@endsection