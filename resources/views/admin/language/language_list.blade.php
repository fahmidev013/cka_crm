@extends('layouts.app')
@section('css')
  {{-- Data table --}}
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
  {{-- Select2  --}}
  <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
  
@endsection
@section('content')

  <!-- Main content -->
<div id="language-settings-container">
  <div class="col-sm-12">
    <div class="row">
      <div class="col-md-3">
        @include('layouts.includes.sub_menu')
      </div>
      <div class="col-md-9">
        <div class="card card-info">
          <div class="card-header">
            <h5><a href="{{ url('item-category') }}">{{ __('General Settings')}}</a> >> {{ __('Languages') }}</h5>
            <div class="card-header-right">
              @if(Helpers::has_permission(Auth::user()->id, 'add_language'))
                <a href="javascript:void(0)" data-toggle="modal" data-target="#add-language" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Language') }}</a>
              @endif
              
            </div>
          </div>
          <div class="card-body">
            <div class="row p-l-15">
              <div class="table-responsive">
                <table id="dataTableBuilder" class="table table-bordered table-hover table-striped dt-responsive">
                  <thead>
                    <tr>
                      <th>{{ __('Language Name') }}</th>
                      <th>{{ __('Short Name') }}</th>
                      <th>{{ __('Flag') }}</th>
                      <th>{{ __('Status') }}</th>
                      <th width="5%">{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($languageList as $language)
                    <tr>
                      <td>{{ $language->name }}</td>
                      <td>{{ $language->short_name }}</td>
                      <td>
                          <img src='{{ url("public/datta-able/fonts/flag/flags/4x3/" . getSVGFlag($language->short_name) . ".svg") }}' width="32">
                      </td>
                      <td>
                        <?php 
                          $color = $language->status == 'Active' ? 'active_color' : 'inactive_color';
                         ?>
                        <span class="badge f-12 active_inactive_checking {{ $color }}">{{ $language->status }}</span>
                      </td>
                      <td>

                      @if(Helpers::has_permission(Auth::user()->id, 'edit_language'))
                              <a title="{{ __('Translate language') }}" href="{{ url('languages/translation') . '/' . $language->id }}"  class="btn btn-xs btn-secondary"><span class="fas fa-language"></span></a> &nbsp;
                      @endif

                      @if(Helpers::has_permission(Auth::user()->id, 'edit_language'))
                              <a title="{{ __('Edit language') }}" href="javascript:void(0)"  class="btn btn-xs btn-primary edit_language" data-toggle="modal" data-target="#edit_language" id="{{ $language->id }}" ><span class="feather icon-edit"></span></a> &nbsp;
                      @endif

                      @if(Helpers::has_permission(Auth::user()->id, 'delete_language'))
                        @if($language->short_name != 'en' && $language->is_default != 1)
                          <form method="POST" action="{{ url('delete-language/' . $language->id) }}" accept-charset="UTF-8" id="delete-language-{{ $language->id }}" class="display_inline">
                              {!! csrf_field() !!}
                              <input type="hidden" name="flag" value="{{ $language->flag }}">
                              <input type="hidden" name="id" value="{{ $language->id }}">
                              <button title="{{ __('Delete') }}"  class="btn btn-xs btn-danger" data-id="{{ $language->id }}" type="button" data-toggle="modal" data-target="#confirmDelete" data-label = "Delete" data-title="{{ __('Delete Language') }}" data-message="{{ __('Are you sure to delete this language?') }}">
                                <i class="feather icon-trash-2"></i>
                              </button>
                          </form>
                        @endif
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
              <button type="button" id="confirmDeleteSubmitBtn" data-task="" class="btn custom-btn-small btn-danger">{{ __('Confirm') }}            
            <span class="ajax-loading"></span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="add-language" class="modal fade display_none" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('Add New') }}</h4>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        <div class="modal-body">
          <form action="{{ url('save-language') }}" method="post" id="addLanguage" class="form-horizontal" enctype="multipart/form-data">
              {!! csrf_field() !!}

            <div class="form-group row">
              <label class="col-sm-4 control-label require" for="language_name">{{ __('Language Name') }}</label>
              <div class="col-sm-7">
                <select class="form-control js-example-basic-single-2 commonClass" id="language_name" name="language_name">
                  <option value="">{{ __('Select One') }}</option>
                  @foreach ($languageShortName as $key => $value) 
                    <option value="{{ $key }}">{{ $value }}</option>
                  @endforeach
                </select>
              </div>
              <div class=" offset-sm-4 pl-3">
                <label for="language_name" generated="true" class="error" id="language_name_error"></label>
              </div>
              </div>

            <div class="form-group row">
              <label class="col-sm-4 control-label require" for="status">{{ __('Status') }}</label>
              <div class="col-sm-7">
                <select class="form-control js-example-basic-single-2 commonClass" id="status" name="status">
                  <option value="">{{ __('Select One') }}</option>
                  <option value="Active">{{ __('Active') }}</option>
                  <option value="Inactive">{{ __('Inactive') }}</option>
                </select>
              </div>
              <div class=" offset-sm-4 pl-3">
                <label for="status" generated="true" class="error" id="status_error"></label> 
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-4 control-label require" for="direction">{{ __('Website Direction') }}</label>
              <div class="col-sm-7">
                <select class="form-control js-example-basic-single-2 commonClass" id="direction" name="direction">
                  <option value="">{{ __('Select One') }}</option>
                  <option value="ltr">{{ __('Left to Right') }}</option>
                  <option value="rtl">{{ __('Right to Left') }}</option>
                </select>
              </div>
               <div class=" offset-sm-4 pl-3">
                <label for="direction" generated="true" class="error" id="direction_error"></label>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-4 control-label" for="default">{{ __('Is Default') }}</label>
              <div class="col-sm-0 switch switch-primary">
                  <input class="switch switch-primary minimal" type="checkbox" id="default" name="default">
                  <label for="default" class="cr ml-3 swicth-pos"></label>
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

  <div id="edit_language" class="modal fade display_none" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('Edit Language') }}</h4>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        <div class="modal-body">
          <form action="{{ url('update-language') }}" method="post" id="editLanguage" class="form-horizontal" enctype="multipart/form-data">
              {!! csrf_field() !!}

            <div class="form-group row">
              <label class="col-sm-4 control-label require" for="edit_status">{{ __('Status') }}</label>
              <div class="col-sm-7">
                <div class="row">
                  <div class="col-md-12">
                    <select class="form-control js-example-basic-single-1 editCommonclass" id="edit_status" name="edit_status">
                      <option value="">{{ __('Select One') }}</option>
                      <option value="Active">{{ __('Active') }}</option>
                      <option value="Inactive">{{ __('Inactive') }}</option>
                    </select>
                  </div> 
                  <div class="col-md-12">
                    <label for="edit_status" generated="true" class="error" id="edit_status_error"></label>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-4 control-label require" for="edit_direction">{{ __('Website Direction') }}</label>
              <div class="col-sm-7">
                <div class="row">
                  <div class="col-md-12">
                    <select class="form-control js-example-basic-single-1 editCommonclass" id="edit_direction" name="edit_direction">
                      <option value="">{{ __('Select One') }}</option>
                      <option value="ltr">{{ __('Left to Right') }}</option>
                      <option value="rtl">{{ __('Right to Left') }}</option>
                    </select>
                  </div>
                  <div class="col-md-12">
                    <label for="edit_direction" generated="true" class="error" id="edit_direction_error"></label>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-4 control-label require" for="edit_flag">{{ __('Flag') }}</label>
              <div class="col-sm-7">        
                <img id="editImg" src='#' alt="" class="img-responsive img-thumbnail" height="64" width="64"/> 
              </div>
            </div>

             <div class="form-group row">
              <label class="col-sm-4 control-label" for="edit_default">{{ __('Is Default') }}</label>
              <div class="col-sm-0 switch switch-primary">
                  <input class="switch switch-primary minimal" type="checkbox" id="edit_default" name="edit_default">
                  <label for="edit_default" class="cr ml-3 swicth-pos"></label>
              </div>
            </div>

            <input type="hidden" name="language_id" id="language_id">
            @if(Helpers::has_permission(Auth::user()->id, 'edit_language'))
            <div class="form-group row">
              <label for="btn_save" class="col-sm-3 control-label"></label>
              <div class="col-sm-12">
                  <button type="submit" class="btn btn-primary custom-btn-small float-right">{{ __('Submit') }}</button>
                  <button type="button" class="btn btn-secondary custom-btn-small float-right" data-dismiss="modal">{{ __('Close') }}</button>
              </div>
            </div>
            @endif

          </form>
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
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/general-settings.min.js') }}"></script>
@endsection