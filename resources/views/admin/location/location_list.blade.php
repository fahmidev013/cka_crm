@extends('layouts.app')

@section('css')
{{-- Data table --}}
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
@endsection
@section('content')

<!-- Main content -->
  <div class="col-sm-12" id="location-settings-container">
    <div class="row">
      <div class="col-sm-3">
       @include('layouts.includes.company_menu')
      </div>
      <div class="col-sm-9">
        <div class="card card-info">
          <div class="card-header">
            <h5><a href="{{ url('company/setting') }}">{{ __('Company Setting')  }} </a> >> {{ __('Locations')  }}</h5>
            <div class="card-header-right">
              @if(Helpers::has_permission(Auth::user()->id, 'add_role'))
                <a href="{{ url('create-location') }}" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Location')  }}</a>
              @endif
              
            </div>
          </div>
          <div class="card-body">
            <div class="row p-l-15">
              <div class="table-responsive">
                <table id="dataTableBuilder" class="table table-bordered table-striped table-hover dt-responsive" width='100%' cellspacing='0'>
                  <thead>
                    <tr>
                      <th class="location_name">{{ __('Location Name') }}</th>
                      <th>{{ __('Delivery Address') }}</th>
                      <th class="location_name">{{ __('Default Location')  }}</th>
                      <th>{{ __('Phone') }}</th>
                      <th>{{ __('Status') }}</th>
                      <th>{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($locationData as $data)
                      <tr>
                        <td>
                          <a class="no-wrap" href="{{ url("edit-location/$data->id") }}">                
                              {{ sanitize_output(ucwords($data->name)) }}
                          </a>
                        </td>
                        <td>
                            {{ sanitize_output(ucwords($data->delivery_address)) }}
                        </td>
                        
                        <td>
                            {{ $data->is_default == 1 ? __('Yes'): __('No') }}
                        </td>
                        
                        <td>
                            {{ sanitize_output(ucwords($data->phone)) }}
                        </td>

                        <td>
                           <?php 
                              $color = $data->is_active == 1 ? 'active_color' : 'inactive_color';
                           ?>
                          <span class="badge f-12 active_inactive_checking {{ $color }}" >{{ $data->is_active == 1 ? __('Active') : __('Inactive') }}</span>
                        </td>
                        <td>
                        @if(Helpers::has_permission(Auth::user()->id, 'edit_location'))
                            <a title="{{ __('Edit') }}" class="btn btn-xs btn-primary" href='{{ url("edit-location/$data->id") }}'><span class="feather icon-edit"></span></a> &nbsp;
                        @endif
                        @if(Helpers::has_permission(Auth::user()->id, 'delete_location'))
                             @if($data->is_default == 0)
                              <form method="POST" action='{{ url("delete-location/$data->id") }}' accept-charset="UTF-8" class="display_inline" id="delete-location-{{$data->id}}">
                                  {!! csrf_field() !!}
                                  <button title="{{ __('Delete') }}" data-id="{{ $data->id }}" class="btn btn-xs btn-danger" type="button" data-label = "{{ __('Delete') }}" data-toggle="modal" data-target="#confirmDelete" data-title="{{ __('Delete Location')  }}" data-message="{{ __('Are you sure you want to delete this Location?') }}">
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
            <button type="button" id="confirmDeleteSubmitBtn" data-task="" class="btn btn-primary custom-btn-small btn-danger">{{ __('Submit') }}</button>
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