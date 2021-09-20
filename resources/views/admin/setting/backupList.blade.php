@extends('layouts.app')
@section('css')
  {{-- DataTable --}}
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
@endsection

@section('content')
<!-- Main content -->
  <div class="col-sm-12" id="dbBackup-settings-container">
    <div class="row">
      <div class="col-md-3">
        @include('layouts.includes.sub_menu')
      </div>
      <div class="col-md-9">
        <div class="card card-info">
          <div class="card-header">
            <h5><a href="{{ url('item-category') }}">{{ __('General Settings') }} </a> >> {{ __('Database Backups') }}</h5>
            <div class="card-header-right">
                @if(Helpers::has_permission(Auth::user()->id, 'add_db_backup'))
                  <a href="{{url('back-up')}}" class="btn btn-outline-primary custom-btn-small" id="backupid"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Backup') }}</a>
                @endif
              
            </div>
          </div>
          <div class="card-body">
            <div class="row p-l-15">
              <div class="table-responsive">
                <table id="dataTableBuilder" class="table table-bordered table-hover table-striped dt-responsive">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>{{ __('Name') }}</th>
                      <th>{{ __('Date') }}</th>
                      <th width="5%">{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $i=0;
                    @endphp
                    @foreach ($backupData as $data)
                      <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $data->name }}</td>
                        <td>{{ timeZoneformatDate($data->created_at) }} &nbsp; {{ timeZonegetTime($data->created_at) }}</td>
                        <td>
                    
                      @if(Helpers::has_permission(Auth::user()->id, 'edit_db_backup'))
                            <a  title="Download" href="{{URL::to('/storage/laravel-backups/'.$data->name)}}"  download="{{$data->name}}" class="btn btn-xs btn-info edit_unit" id="{{$data->id}}" ><span class="feather icon-download"></span></a> &nbsp;
                      @endif
                   
                      @if(Helpers::has_permission(Auth::user()->id, 'add_db_backup'))
                       <form method="POST" action="{{ url('backup/delete/'.$data->id) }}" id="delete-backup-{{$data->id}}" accept-charset="UTF-8" class="display_inline">
                          {!! csrf_field() !!}
                          <button class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id="{{$data->id}}" data-label="Delete" data-target="#confirmDelete" data-title="{{ __('Backup Delete') }}" data-message="{{ __('Are you sure to delete this backup?') }}">
                             <i class="feather icon-trash-2" aria-hidden="true"></i>
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
  </div>
@endsection

@section('js')
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/general-settings.min.js') }}"></script>
@endsection