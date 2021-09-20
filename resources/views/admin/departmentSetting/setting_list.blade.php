@extends('layouts.app')
@section('css')
  {{-- Data table --}}
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
  {{-- Select2  --}}
  <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection
@section('content')
  <!-- Main content -->
  <div class="col-sm-12" id="department-settings-container">
    <div class="row">
      <div class="col-sm-3">
        @include('layouts.includes.company_menu')
      </div>
      <div class="col-sm-9">
        <div class="card card-info">
          <div class="card-header">
            <h5><a href="{{ url('company/setting') }}"> {{ __('Company Settings') }} </a> >> {{ __('Department') }}</h5>
            <div class="card-header-right">
              @if(Helpers::has_permission(Auth::user()->id, 'add_department'))
                <a href="javascript:void(0)" data-toggle="modal" data-target="#add-department" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Department') }}</a>
              @endif
            </div>
          </div>
          <div class="card-body">
            <div class="row p-l-15">
              <div class="row p-l-15">
                <a href="{{ URL::to('departmentdownload/csv') }}"><button class="btn btn-primary custom-btn-small"><span class="feather icon-download"> &nbsp;</span>{{ __('Download CSV') }}</button></a>
              </div>
              <div class="table-responsive pt-2">
                <table id="dataTableBuilder" class="table table-bordered table-hover table-striped dt-responsive departments">
                  <thead>
                    <tr>
                      <th>{{ __('Department') }}</th>
                      <th>{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($departmentData as $data)
                      <tr>
                        <td>{{ $data->name }}</td>
                        <td>
                          @if(Helpers::has_permission(Auth::user()->id, 'edit_department') )
                            <a title="{{ __('Edit') }}" href="javascript:void(0)" class="btn btn-xs btn-primary department_edit" data-toggle="modal" data-target="#department_edit" id="{{ $data->id }}"><span class="feather icon-edit"></span></a> &nbsp;
                          @endif
                          @if($data->id != '1')
                            @if(Helpers::has_permission(Auth::user()->id, 'delete_department'))
                            <form method="POST" action='{{ url("department/delete") }}' id="delete-dept-{{ $data->id }}" accept-charset="UTF-8" class="display_inline">
                                {!! csrf_field() !!}
                                <input type="hidden" name="id" value="{{ $data->id }}">
                                <button title="{{ __('Delete') }}" class="btn btn-xs btn-danger" data-id="{{ $data->id }}" data-label="Delete" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ __('Delete Department') }}" data-message="{{ __('Are you sure to delete this department?') }}">
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

        <div id="add-department" class="modal fade display_none" role="dialog">
          <div class="modal-dialog">
      
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">{{ __('Add New') }}</h4>
                <button type="button" class="close" data-dismiss="modal">×</button>
              </div>
              <div class="modal-body">
                <form action="{{ url('save-department') }}" method="post" id="myform1" class="form-horizontal">
                    {!! csrf_field() !!}
                  <div class="form-group row">
                    <label class="col-sm-4 control-label require" for="inputEmail3">{{ __('Department') }}</label>
                    <div class="col-sm-7">
                      <input type="text" placeholder="{{ __('Name') }}" class="form-control" name="name">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="btn_save" class="col-sm-4 control-label"></label>
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
      
        <div id="department_edit" class="modal fade display_none" role="dialog">
          <div class="modal-dialog">
      
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">{{ __('Edit Department') }}</h4>
                <button type="button" class="close" data-dismiss="modal">×</button>
              </div>
              <div class="modal-body">
                <form action="{{ url('update-department') }}" method="post" id="editDept" class="form-horizontal">
                  {!! csrf_field() !!}
                  <input type="hidden" name="depart_id" id="depart_id">
                  <div class="form-group row">
                    <label class="col-sm-4 control-label require" for="inputEmail3">{{ __('Department') }}</label>
      
                    <div class="col-sm-7">
                      <input type="text" placeholder="{{ __('Name') }}" class="form-control" id="name" name="name" >
                      <span id="val_name" class="color_red"></span>
                    </div>
                  </div>
      
                  <div class="form-group row">
                    <label for="btn_save" class="col-sm-4 control-label"></label>
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
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/settings.min.js') }}"></script>
@endsection