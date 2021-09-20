@extends('layouts.customer_panel')
@section('css')
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
  <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-select/dist/css/bootstrap-select.min.css') }}">

  @yield('listCSS')

@endsection

@section('content')
  <!-- Main content -->
<div class="col-sm-12" id="customer-panel-list-layout-container">
  <div class="card">
    <div class="card-header">
      @yield('list-title')
      <div class="card-header-right d-inline-block">
        @yield('list-add-button')
        
      </div>
    </div>
    <div class="card-body p-0">
      <div class="col-sm-12">
        @yield('list-form-content')
      </div>
      <div class="card-block pt-2">
        <div class="col-sm-12">
          <div class="table-responsive">
            {!! $dataTable->table(['class' => 'table table-striped table-hover dt-responsive', 'width' => '100%', 'cellspacing' => '0']) !!}
          </div>  
        </div>
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
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
        <button type="button" id="theModalSubmitBtn" data-task="" class="btn btn-primary">{{ __('Submit') }}</button>
        <span class="ajax-loading"></span>
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>

<!-- list Js -->  
@yield('list-js')

{!! $dataTable->scripts() !!}



@endsection