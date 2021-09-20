@extends('layouts.app')
@section('css')
{{-- DataTable --}}
<link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
{{-- Select2  --}}
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
<!-- Main content -->
<div class="col-sm-12" id="incExpCategory-settings-container">
  <div class="row">
    <div class="col-md-3">
      @include('layouts.includes.sub_menu')
    </div>
    <div class="col-md-9">
      <div class="card card-info">
        <div class="card-header">
          <h5 id="headerTitle"><a href="{{ url('item-category') }}">{{ __('General Settings') }} </a> >> {{ __('Income/Expense Category') }}</h5>
          <div class="card-header-right" id="cardRightButton">
            @if(Helpers::has_permission(Auth::user()->id, 'add_income_expense_category'))
            <a href="javascript:void(0)" data-toggle="modal" data-target="#add-category" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Category') }}</a>
            @endif
          </div>
        </div>
        <div class="card-body">
          <div class="row p-l-15">
            <div class="table-responsive">
              <table id="dataTableBuilder" class="table table-bordered table-hover table-striped dt-responsive">
                <thead>
                  <tr>
                    <th>{{ __('Category') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th width="5%">{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($categoryList as $data)
                  <tr>
                    <td>{{ $data->name }}</td>
                    <td>{{ ucwords($data->category_type) }}</td>
                    <td>
                      @if(Helpers::has_permission(Auth::user()->id, 'edit_income_expense_category'))
                      <a title="{{ __('Edit') }}" href="javascript:void(0)" data-toggle="modal" data-target="#edit-category" class="btn btn-xs btn-primary edit_category" id="{{ $data->id }}"><span class="feather icon-edit"></span></a> &nbsp;
                      @endif


                      @if(Helpers::has_permission(Auth::user()->id, 'delete_income_expense_category'))
                      <form method="POST" action="{{ url('income-expense-category/delete/' . $data->id) }}" id="delete-iec-{{ $data->id }}" accept-charset="UTF-8" class="display_inline">
                        {!! csrf_field() !!}
                        <button title="{{ __('Delete') }}" class="btn btn-xs btn-danger" data-id="{{ $data->id }}" data-label="Delete" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ __('Delete Category') }}" data-message="{{ __('Are you sure you want to delete this Category?') }}">
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
  <div id="add-category" class="modal fade display_none" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('Add New') }}</h4>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        <div class="modal-body">
          <form action="{{ url('income-expense-category/save') }}" method="post" id="myform1" class="form-horizontal">
            {!! csrf_field() !!}
            <div class="form-group row">
              <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Category') }}</label>

              <div class="col-sm-6">
                <input type="text" placeholder="{{ __('Name') }}" class="form-control CatName" name="name">
                <label for="name" generated="true" class="error" id="catName_error"></label>
                <span class="nameError"></span>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Type') }}</label>
              <div class="col-sm-6">
                <select class="form-control js-example-basic-single-1 catType" name="type">
                  <option value="">{{ __('Select One') }}</option>
                  @foreach ($types as $key=>$data)
                  <option value="{{ $key }}" hidden="{{ $key }}">{{ $data }}</option>
                  @endforeach
                </select>
              </div>
              <div class=" offset-sm-3 pl-3">
                <label for="type" generated="true" class="error" id="catType_error"></label>
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

  <div id="edit-category" class="modal fade display_none" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('Edit Income Expense Category') }}</h4>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        <div class="modal-body">
          <form action="{{ url('income-expense-category/update') }}" method="post" id="editCat" class="form-horizontal">
            {!! csrf_field() !!}
            <input type="hidden" name="id" id="id">
            <input type="hidden" name="cat_Id" id="cat_Id">
            <div class="form-group row">
              <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Category') }}</label>

              <div class="col-sm-6">
                <input type="text" placeholder="Name" class="form-control name_error edit_catName" id="name" name="name">
                <label for="name" generated="true" class="error" id="edit_catName_error"></label>
                <span class="editnameError"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Type') }}</label>
              <div class="col-sm-6">
                <select class="form-control js-example-basic-single-2 edit_catType" name="type" id="type">
                  @foreach ($types as $key=>$data)
                  <option value="{{ $key }}">{{ $data }}</option>
                  @endforeach
                </select>
                <label for="type" generated="true" class="error"></label>
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
</div>
@include('layouts.includes.message_boxes')
@endsection

@section('js')
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/general-settings.min.js') }}"></script>
@endsection