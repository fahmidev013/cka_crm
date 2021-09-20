@extends('layouts.app')
@section('css')
{{-- Select2  --}}
  <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
  <!-- datattable responsive -->
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
  <!-- daterange picker -->
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
@endsection
@section('content')
  <!-- Main content -->
  <div class="col-sm-12" id="user-project-container">
    <div class="card">
      <div class="card-header">
        <h5><a href="{{ url('users') }}">{{ __('Users') }}</a>  >> {{ __('Projects') }} >> {{$user->full_name}}</h5>
        <div class="card-header-right">
          
        </div>
      </div>
      <div class="card-body p-0" id="no_shadow_on_card">
        @include('layouts.includes.user_menu')
      </div>
      <div class="col-sm-12">
        <form class="form-horizontal" action="{{ url('user/project-list/'.$user_id) }}" method="GET" id='orderListFilter'>
          <input class="form-control" id="startfrom" type="hidden" name="from" value="<?= isset($from) ? $from : '' ?>">
          <input class="form-control" id="endto" type="hidden" name="to" value="<?= isset($to) ? $to : '' ?>">
          <input type="hidden" name="customer" id="user" value="{{$user_id}}">
          <div class="col-md-12 col-sm-12 col-xs-12 m-l-10">
            <div class="row mt-3 mb-2">
              <div class="col-md-12 col-xl-4 col-lg-3 col-sm-12 col-xs-12 mb-2">
                <div class="input-group">
                  <button type="button" class="form-control" id="daterange-btn">
                    <span class="float-left">
                      <i class="fa fa-calendar"></i>  {{ __('Date Range') }}
                    </span>
                    <i class="fa fa-caret-down float-right pt-1"></i>
                  </button>
                </div>
              </div>
              <div class="col-md-12 col-xl-4 col-lg-3 col-sm-12 col-xs-12 mb-2">
              <select class="form-control select2" name="status" id="status">
                <option value="">{{ __('All Status') }}</option>
                @foreach($status as $data)
                  <option value="{{$data->id}}" "{{$data->id == $allstatus ? ' selected="selected"' : ''}}">{{$data->name}}</option>
                @endforeach
              </select>
          </div>
          <div class="col-md-12 col-xl-3 col-lg-3 col-sm-12 col-xs-12 mb-1">
              <select class="form-control selectpicker" name="project_type[]" id="project_type" multiple>
                <option value="customer" {{ in_array('customer', $project_type) ? 'selected="selected"' : ''}}>Customer</option>
                <option value="product" {{ in_array('product', $project_type) ? 'selected="selected"' : ''}}>Product</option>
                <option value="in_house" {{ in_array('in_house', $project_type) ? 'selected="selected"' : ''}}>In house</option>
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
            {!! $dataTable->table(['class' => 'table table-striped table-hover dt-responsive', 'width' => '100%', 'cellspacing' => '0']) !!}
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
            <button type="button" class="btn btn-secondary custom-btn-small " data-dismiss="modal">{{ __('Close') }}</button>
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
<script src="{{ asset('public/dist/plugins/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>

{!! $dataTable->scripts() !!}

<script type="text/javascript">
  "use strict";
  var startDate   = "{!! isset($from) ? $from : 'undefined' !!}";
  var endDate     = "{!! isset($to) ? $to : 'undefined' !!}";
  var sessionDate = '{!! $date_format_type !!}';
  var userId = '{{ $user->id }}';
</script>
<script src="{{ asset('public/dist/js/custom/user.min.js') }}"></script>
@endsection