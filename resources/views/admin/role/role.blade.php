@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
@endsection
@section('content')
<!-- Main content -->
  <div class="col-sm-12" id="role-settings-container">
    <div class="row">
      <div class="col-sm-3">
       @include('layouts.includes.company_menu')
      </div>
      <div class="col-sm-9">
        <div class="card card-info">
          <div class="card-header">
            <h5><a href="{{ url('company/setting') }}">{{ __('Company Settings')  }} </a> >> {{ __('User Roles') }}</h5>
            <div class="card-header-right" id="cardRightButton">
              @if(Helpers::has_permission(Auth::user()->id, 'add_role'))
                <a href="{{ url('role/create') }}" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Role') }}</a>
              @endif
              
            </div>
          </div>
          <div class="card-body">
            <div class="row p-l-15">
              <div class="table-responsive">
                <table id="dataTableBuilder" class="table table-bordered table-hover table-striped dt-responsive decriptionLarge decriptionLarge1">
                  <thead>
                    <tr>
                      <th>{{ __('Name') }}</th>
                      <th>{{ __('Display Name') }}</th>
                      <th>{{ __('Description') }}</th>
                      <th width="5%">{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($roleData as $data)
                      <tr>
                        <td><a href="{{ ($data->id != 1) ? url("role/edit/$data->id") :'#'}}">{{ $data->name }}</a></td>

                        <td>{{ $data->display_name }}</td>
                        <td>{{ $data->description }}</td>

                        <td>
                         @if ($data->id != 1)
                            @if(Helpers::has_permission(Auth::user()->id, 'edit_role'))
                              <a title="{{ __('Edit') }}" class="btn btn-xs btn-primary" href='{{ url("role/edit/$data->id") }}'><span class="feather icon-edit"></span></a> &nbsp;
                            @endif
                            @if(Helpers::has_permission(Auth::user()->id, 'delete_role'))
                              <form method="POST" action="{{ url('role/delete') }}" accept-charset="UTF-8" class="display_inline" id="delete-role-{{$data->id}}">
                                {!! csrf_field() !!}
                                <input type="hidden" name="id" value="{{$data->id}}">
                                <button title="{{ __('Delete') }}" data-id="{{ $data->id }}" class="btn btn-xs btn-danger" type="button" data-label = "Delete" data-toggle="modal" data-target="#confirmDelete" data-title="{{ __('Delete Role') }}" data-message="{{ __('Are you sure you want to delete this role? If you do this then this role will be also deleted from associated team members if any.') }}">
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
            <button type="button" id="confirmDeleteSubmitBtn" data-task="" class="btn btn-danger custom-btn-small">{{ __('Submit') }}</button>
            <span class="ajax-loading"></span>
          </div>
        </div>
      </div>
    </div>
  </div>


@endsection

@section('js')
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/settings.min.js') }}"></script>
@endsection