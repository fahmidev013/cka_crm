@extends('layouts.list-layout')

@section('listCSS')
  <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
  
@endsection

@section('list-title')
  <h5><a href='{{url("item")}}'> {{ __('Items')  }} </a></h5>
@endsection


@section('list-form-content')
  @php 
    $from = '';
    $to = '';
  @endphp

  @if(Helpers::has_permission(Auth::user()->id, 'add_item'))
    <div class="card-block">
      @if(Helpers::has_permission(Auth::user()->id, 'import_items'))
        <a href="{{ URL::to('itemimport') }}" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-upload"> &nbsp;</span>{{ __('Import Items')  }}</a>
      @endif

        <a href="{{ url('create-item') }}" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Items')  }}</a>
    </div>
  @endif
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
          <button type="button" id="confirmDeleteSubmitBtn" data-task="" class="btn btn-danger custom-btn-small delete-task-btn">{{ __('Submit') }}</button>
          <span class="ajax-loading"></span>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('list-js')
<script src="{{ asset('public/dist/js/custom/item.min.js') }}"></script>
@endsection