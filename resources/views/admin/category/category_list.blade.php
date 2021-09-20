@extends('layouts.app')
@section('css')
{{-- DataTable --}}
<link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
{{-- Select2  --}}
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
<!-- Main content -->
<div class="col-sm-12" id="itemCategory-settings-container">
  <div class="row">
    <div class="col-md-3">
      @include('layouts.includes.sub_menu')
    </div>
    <div class="col-md-9">
      <div class="card card-info">
        <div class="card-header">
          <h5><a href="{{ url('item-category') }}">{{ __('General Settings') }}</a> >> {{ __('Item Categories') }}</h5>
          <div class="card-header-right">

          </div>
        </div>
        <div class="card-body">
          <div class="row p-l-20">
            <a href="{{ URL::to('categoryimport') }}" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-upload"> &nbsp;</span>{{ __('Import Category') }}</a>
            <a href="javascript:void(0)" data-toggle="modal" data-target="#add-category" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Category') }}</a>
          </div>
          <hr>
          <div class="row p-l-15">
            <div class="table-responsive">
              <table id="dataTableBuilder" class="table table-bordered table-hover table-striped dt-responsive">
                <thead>
                  <tr>
                    <th>{{ __('Category') }}</th>
                    <th>{{ __('Unit') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th width="5%">{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($categoryData as $data)
                  <tr>
                    <td>{{ $data->name }}</td>
                    <td>{{ !empty($data->itemUnit->name) ? $data->itemUnit->name  : "" }}</td>
                    <td>
                      <?php $color = $data->is_active == 1 ? 'active_color' : 'inactive_color'; ?>
                      <span class="badge f-12 active_inactive_checking {{ $color }}">{{ $data->is_active == 1 ?  __('Active') : __('Inactive') }}</span>
                    </td>
                    <td>
                      @if(Helpers::has_permission(Auth::user()->id, 'edit_item_category') )
                      <a title="{{ __('Edit') }}" href="javascript:void(0)" data-toggle="modal" data-target="#edit-category" class="btn btn-xs btn-outline-primary edit_category" id="{{ $data->id }}"><span class="feather icon-edit"></span></a> &nbsp;
                      @endif

                      @if(Helpers::has_permission(Auth::user()->id, 'delete_item_category') && !in_array($data->id, [1]))
                      <form method="POST" action="{{ url('delete-category') }}/{{ $data->id }}" accept-charset="UTF-8" id="delete-category-{{ $data->id }}" class="display_inline">
                        {!! csrf_field() !!}
                        <button title="{{ __('Delete') }}" data-id="{{ $data->id }}" class="btn btn-xs btn-outline-danger" type="button" data-label="Delete" data-toggle="modal" data-target="#confirmDelete" data-title="{{ __('Delete Category') }}" data-message="{{ __('Are you sure you want to delete this Category?') }}">
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
          <button type="button" class="btn custom-btn-small btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
          <button type="button" id="confirmDeleteSubmitBtn" data-task="" class="btn custom-btn-small btn-danger">{{ __('Confirm') }}</button>
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
          <form action="{{ url('save-category') }}" method="post" id="myform1" class="form-horizontal">
            {!! csrf_field() !!}
            <div class="form-group row">
              <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Category') }}</label>
              <div class="col-sm-6">
                <input type="text" placeholder="{{ __('Name') }}" class="form-control" name="description">
                <label for="description" generated="true" class="error" id="description-error"></label>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Unit') }}</label>
              <div class="col-sm-6">
                <select class="form-control js-example-basic-single add_unit" name="dflt_units">
                  <option value="">{{ __('Select One') }}</option>
                  @foreach($unitData as $data)
                  <option value="{{ $data->id }}">{{ $data->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class=" offset-sm-3 pl-3">
                <label for="dflt_units" generated="true" class="error" id="dflt_units-error"></label>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Status') }}</label>
              <div class="col-sm-6">
                <select class="form-control js-example-basic-single" name="status" id="status">
                  <option value="1">{{ __('Active') }}</option>
                  <option value="0">{{ __('Inactive') }}</option>
                </select>
              </div>
            </div>

            @include('layouts.includes.modal_footer_button')
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
          <h4 class="modal-title">{{ __('Edit Category') }}</h4>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        <div class="modal-body">
          <form action="{{ url('update-category') }}" method="post" id="editCat" class="form-horizontal">
            {!! csrf_field() !!}
            <div class="form-group row">
              <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Category') }}</label>
              <div class="col-sm-6">
                <input type="text" placeholder="Name" class="form-control nameErr edit_name_error" id="name" name="description">
                <label for="description" generated="true" class="error" id="edit_description-error"></label>
                <span class="editnameError"></span>
              </div>

            </div>
            <div class="form-group row">
              <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Unit') }}</label>
              <div class="col-sm-6">
                <select class="form-control js-example-basic-single-2 edit_unit" name="dflt_units" id="dflt_units">
                  <option value="">{{ __('Select One') }}</option>
                  @foreach ($unitData as $data)
                  <option value="{{ $data->id }}">{{ $data->name }}</option>
                  @endforeach
                </select>
                <label for="dflt_units" generated="true" class="error" id="edit_dflt_units-error"></label>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Status') }}</label>
              <div class="col-sm-6">
                <select class="form-control js-example-basic-single-2" name="status" id="edit_status">
                  <option value="1">{{ __('Active') }}</option>
                  <option value="0">{{ __('Inactive') }}</option>
                </select>
              </div>
            </div>

            <input type="hidden" name="cat_id" id="cat_id">
            <input type="hidden" name="catId" id="catId">
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

@endsection

@section('js')
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/general-settings.min.js') }}"></script>
@endsection