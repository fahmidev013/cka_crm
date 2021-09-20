@extends('layouts.app')
@section('css')
  {{-- DataTable --}}
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
  {{-- Select2  --}}
  <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
 
@endsection

@section('content')
<!-- Main content -->
  <div class="col-sm-12" id="unit-settings-container">
    <div class="row">
      <div class="col-md-3">
        @include('layouts.includes.sub_menu')
      </div>
      <div class="col-md-9">
        <div class="card card-info">
          <div class="card-header">
            <h5><a href="{{ url('item-category') }}">{{ __('General Settings') }} </a> >> {{ __('Units') }}</h5>
            <div class="card-header-right">
              @if(Helpers::has_permission(Auth::user()->id, 'add_unit'))
                <a href="javascript:void(0)" data-toggle="modal" data-target="#add-unit" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Unit') }}</a>
              @endif
              
            </div>
          </div>
          <div class="card-body">
            <div class="row p-l-15">
              <div class="table-responsive">
                <table id="dataTableBuilder" class="table table-bordered table-hover table-striped dt-responsive">
                  <thead>
                    <tr>
                      <th>{{ __('Unit Name') }}</th>
                      <th>{{ __('Unit Abbr') }}</th>
                      <th width="5%">{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($unitData as $data)
                    <tr>
                      <td>{{ $data->name }}</td>
                      <td>{{ $data->abbreviation }}</td>
                      <td>
                  
                      @if(Helpers::has_permission(Auth::user()->id, 'edit_unit'))
                              <a title="{{ __('Edit') }}" href="javascript:void(0)" data-toggle="modal" data-target="#edit-unit"  class="btn btn-xs btn-primary edit_unit" id="{{$data->id}}" ><span class="feather icon-edit"></span></a> &nbsp;
                      @endif
                  
                      @if(Helpers::has_permission(Auth::user()->id, 'delete_unit'))
                        @if(!in_array($data->id,[1]))   
                              <form method="POST" action="{{ url('delete-unit/'.$data->id) }}" id="delete-unit-{{$data->id}}" accept-charset="UTF-8" class="display_inline">
                                  {!! csrf_field() !!}
                                  <button title="{{ __('Delete') }}" class="btn btn-xs btn-danger" data-id="{{$data->id}}" type="button" data-toggle="modal" data-label = "Delete" data-target="#confirmDelete" data-title="{{ __('Delete Unit') }}" data-message="{{ __('Are you sure to delete this?') }}">
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
            <button type="button" class="btn btn-secondary custom-btn-small" data-dismiss="modal">{{ __('Close') }}</button>
            <button type="button" id="confirmDeleteSubmitBtn" data-task="" class="btn btn-danger custom-btn-small">{{ __('Submit') }}</button>
            <span class="ajax-loading"></span>
          </div>
        </div>
      </div>
    </div>
    <div id="add-unit" class="modal fade display_none" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">{{ __('Add New') }}</h4>
            <button type="button" class="close" data-dismiss="modal">×</button>
          </div>
          <div class="modal-body">
            <form action="{{ url('save-unit') }}" method="post" id="addUnit" class="form-horizontal">
                {!! csrf_field() !!}
              
              <div class="form-group row">
                <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Unit Name') }}</label>
  
                <div class="col-sm-6">
                  <input type="text" placeholder="{{ __('Name') }}" class="form-control" id="name" name="name">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Abbr') }}</label>
  
                <div class="col-sm-6">
                    <input type="text" placeholder="{{ __('Abbr') }}" class="form-control" id="abbr" name="abbr">
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
  
    <div id="edit-unit" class="modal fade display_none" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">{{ __('Update Unit') }}</h4>
            <button type="button" class="close" data-dismiss="modal">×</button>
          </div>
          <div class="modal-body">
            <form action="{{ url('update-unit') }}" method="post" id="editUnit" class="form-horizontal">
                {!! csrf_field() !!}
              
              <div class="form-group row">
                <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Unit Name') }}</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" placeholder="{{ __('Name') }}" id="unit_name" name="name">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 control-label require" placeholder="{{ __('Abbr') }}" for="inputEmail3">{{ __('Unit Abbr') }}</label>
  
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="unit_abbr" name="abbr">
                </div>
              </div>
              <input type="hidden" name="id" id="unit_id">
              <input type="hidden" name="unitId" id="unitId">
              
              @if(Helpers::has_permission(Auth::user()->id, 'edit_unit'))
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
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/general-settings.js') }}"></script>
@endsection