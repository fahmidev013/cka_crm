@extends('layouts.app')
@section('css')
  {{-- Data table --}}
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
@endsection

@section('content')
<!-- Main content -->  
  <div class="col-sm-12" id="bank-account-type-container">
    <div class="row">
      <div class="col-sm-3">
        @include('layouts.includes.finance_menu')
      </div>
      <div class="col-sm-9">
        <div class="card card-info">
          <div class="card-header">
            <h5>
              <a href="{{ url('tax') }}">{{ __('Finance') }}</a> >> {{ __('Account Type') }}
            </h5>
            <div class="card-header-right">
               @if(Helpers::has_permission(Auth::user()->id, 'add_tax'))
                <a href="javascript:void(0)" data-toggle="modal" data-target="#add-acc-type" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Account Type') }}</a>
              @endif
              
            </div>
          </div>
          <div class="card-body">
            <div class="row p-l-15">
              <div class="table-responsive">
                <table id="dataTableBuilder" class="table table-bordered table-hover table-striped dt-responsive bank-account-type">
                  <thead>
                    <tr>
                      <th>{{ __('Name') }}</th>
                      <th width="10%">{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($typeNames as $typeName)
                      <tr>
                        <td>{{$typeName->name}}</td>
                        <td>
                          @if(Helpers::has_permission(Auth::user()->id, 'edit_account_type'))
                            <a title="{{ __('Edit') }}" href="javascript:void(0)" class="btn btn-xs btn-primary edit-acc-type" data-toggle="modal" data-target="#edit-acc-type" id="{{ $typeName->id }}"><span class="feather icon-edit"></span></a> &nbsp;
                          @endif

                          @if(Helpers::has_permission(Auth::user()->id, 'delete_account_type'))
                            <form method="POST" action="{{ url('delete-account-type') }}" id="delete-accType-{{ $typeName->id }}" accept-charset="UTF-8" class="display_inline">
                              {!! csrf_field() !!}
                              <input type="hidden" name="acc_type_id" value="{{ $typeName->id }}">
                              <button title="{{ __('Delete') }}" class="btn btn-xs btn-danger" data-id="{{ $typeName->id }}" data-label="Delete" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ __('Delete Account Type') }}" data-message="{{ __('Are you sure you want to delete this Account Type?') }}">
                                  <i class="feather icon-trash-2"></i> 
                              </button>
                            </form>
                          @endif
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
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
  
  <div id="add-acc-type" class="modal fade display_none" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('Add New') }}</h4>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" action='{{ url("save-acc-type") }}' method="post" id="addAccType">
            <input type="hidden" value="{{ csrf_token() }}" name="_token" id="token_save">
            <div class="form-group row">
              <label class="col-sm-3 control-label require" for="name">{{ __('Name') }}</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" placeholder="{{ __('Name') }}" name="name">
              </div>
            </div>
            <div class="form-group row">
              <label for="btn_save" class="col-sm-3 control-label"></label>
              <div class="col-sm-12">
                <button type="submit" class="btn btn-primary custom-btn-small float-right">{{ __('Submit') }}</button>
                <button type="button" class="btn btn-secondary custom-btn-small float-right" data-dismiss="modal">{{ __('Close') }}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div id="edit-acc-type" class="modal fade display_none" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('Edit Account Type') }}</h4>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" action='{{ url("update-acc-type") }}' method="post" id="editAccType">
            <input type="hidden" value="{{ csrf_token() }}" name="_token" id="token">
            <input type="hidden" name="type_id" id="type_id">
            <div class="form-group row">
              <label class="col-sm-3 control-label require" for="name">{{ __('Name') }}</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" placeholder="{{ __('Name') }}" name="name" id="type_name">
              </div>
            </div>
            <div class="form-group row">
              <label for="btn_save" class="col-sm-3 control-label"></label>
              <div class="col-sm-12">
                <button type="submit" class="btn btn-primary custom-btn-small float-right">{{ __('Submit') }}</button>
                <button type="button" class="btn btn-secondary custom-btn-small float-right" data-dismiss="modal">{{ __('Close') }}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection


@section('js')
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/bank.min.js') }}"></script>
@endsection