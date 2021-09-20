@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css')}}">
{{-- Date Range picker --}}
<link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">


@yield('projectCSS')

@endsection

@section('content')
<!-- Main content -->
<div class="col-sm-12">
  <div class="card">
    <div class="card-header">
      <h5>@include('admin.project.project_title')</h5>
      <div class="card-header-right">
        @yield('add-title')
        
      </div>
    </div>
    <div class="card-body p-0">
      @include('layouts.includes.project_navbar')
      <div class="card-block">
        @yield('projectContent')
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

@endsection
@section('js')
{{-- Validate js --}}
<script src="{{ asset('public/dist/js/jquery.validate.min.js')}}"></script>
{{-- Select2 js --}}
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js')}}"></script>
{{-- Classic editor --}}
<script src="{{ asset('public/datta-able/plugins/ckeditor/js/ckeditor.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/project-main.min.js') }}"></script>

@yield('projectJS')
@endsection