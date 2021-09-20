@extends('layouts.app')
@section('css')
{{-- Select2  --}}
  <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
  <!-- datattable responsive -->
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
  <!-- daterange picker -->
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection
@section('content')
  <!-- Main content -->
  <div class="col-sm-12" id="user-purch-payment-container">
    <div class="card">
      <div class="card-header">
        <h5><a href="{{ url('users') }}">{{ __('Users') }}</a> >> {{ __('Purchase Payment') }} >> {{ $user->full_name }}</h5>
        <div class="card-header-right">

        </div>
      </div>
      <div class="card-body p-0" id="no_shadow_on_card">
        @include('layouts.includes.user_menu')
      </div>
      <div class="col-sm-12">
        <form class="form-horizontal" action='{{ url("user/user-purchase-payment-list/$user_id") }}' method="GET" id='salesHistoryReport'>
          <input class="form-control" id="startfrom" type="hidden" name="from">
          <input class="form-control" id="endto" type="hidden" name="to">
          <input type="hidden" name="customer" id="customer" value="{{ $user_id }}">
          <div class="col-md-12 col-sm-12 col-xs-12 m-l-10">
            <div class="row mt-3 mb-2">
              <div class="col-md-4 col-sm-4 col-xs-12 mb-2">
                <div class="input-group">
                  <button type="button" class="form-control" id="daterange-btn">
                    <span class="float-left">
                      <i class="fa fa-calendar"></i>  {{ __('Date Range') }}
                    </span>
                    <i class="fa fa-caret-down float-right pt-1"></i>
                  </button>
                </div>
              </div>
              <div class="col-md-3 col-sm-3 col-xs-11 mb-2">
                <select class="form-control valdation_select select2" name="supplier" id="supplier" required>
                  <option value="all" <?= ($supplier == 'all') ? 'selected' : ''?>>{{ __('All Supplier') }}</option>
                  @foreach($suppliers as $data)
                    <option value="{{ $data->id }}" <?= ($data->id == $supplier) ? 'selected' : ''?>>{{ $data->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-2 col-sm-2 col-xs-11 mb-2">
          <div class="input-group">
            <select class="form-control select2" name="method" id="method">
              <option value="">{{ __('All methods') }}</option>
              @if(!empty($methodList))
                @foreach($methodList as $methodItem)
                  <option value="{{ $methodItem->id }}" <?= ($methodItem->id == $method) ? 'selected' : ''?>>{{ $methodItem->name }}</option>
                @endforeach
              @endif
            </select>
          </div>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-11 mb-2">
          <select class="form-control select2" name="currency" id="currency">
            <option value="">{{ __('All currencies') }}</option>
            @if(!empty($currencyList))
              @foreach($currencyList as $currencyData)
                <option value="{{ $currencyData->id }}" <?= ($currencyData->id == $currency) ? 'selected' : ''?>>{{ $currencyData->name }}</option>
              @endforeach
            @endif
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
          <div class="table-responsive" id="purchase_payment">
            {!! $dataTable->table(['class' => 'table table-striped table-hover dt-responsive purch', 'width' => '100%', 'cellspacing' => '0']) !!}
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="confirmDeleteLabel"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary custom-btn-small" data-dismiss="modal">{{ __('Close') }}</button>
            <button type="button" id="confirmDeleteSubmitBtn" data-task="" class="btn btn-danger custom-btn-small">{{ __('Submit') }}</button>
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
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
{!! $dataTable->scripts() !!}
<script type="text/javascript">
  "use strict";
  var startDate     = "{!! isset($from) ? $from : 'undefined' !!}";
  var endDate       = "{!! isset($to) ? $to : 'undefined' !!}";
  var sessionDate   = '{!! $date_format_type !!}';
  var team_memberId = '{{ $user->id }}';
</script>
<script src="{{ asset('public/dist/js/custom/user.min.js') }}"></script>
@endsection
