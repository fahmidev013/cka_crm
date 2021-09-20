@extends('layouts.app')
@section('css')
  {{-- Data table --}}
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
@endsection
@section('content')
  <!-- Main content -->
  <div class="col-sm-12" id="translation-settings-container">
    <div class="row">
      <div class="col-md-3">
        @include('layouts.includes.sub_menu')
      </div>
      <div class="col-md-9">
        <div class="card card-info">
          <div class="card-header">
            <h5><a href="{{ url('item-category') }}">{{ __('General Settings')}}</a> >> {{ __('Language Translation') }}  >> {{ $language->name }}</h5>
          </div>
          <form class="form-horizontal" action="{{ url('languages/translation/save') }}" method="POST">
          @csrf
          <input type="hidden" name="id" value="{{ $language->id }}">
          <div class="card-body">
            <div class="row p-l-15">
              <div class="table-responsive">
                <table id="dataTableBuilder" class="table table-bordered table-hover table-striped dt-responsive translation-table" width="100%">
                  <thead class="table table-bordered dt-responsive" width="100%">
                    <tr>
                      <th>#</th>
                      <th>{{ __('Key') }}</th>
                      <th>{{ __('Value') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                  
                    @php 
                    $i = 1; 
                    @endphp
                    @foreach ($jsonData as $key => $value)
                    @if(is_array($value))
                      @foreach($value as $k => $v)
                    <tr>
                      <td>{{ $i++ }}</td>
                      <td width="50%">
                        <b>{{ $k }}</b>
                      </td>
                      <td width="50%">
                        <input type="text" class="form-control w-100" name="key[{{ $key }}][{{ $k }}]" value="{{ $v }}">
                      </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                      <td>{{ $i++ }}</td>
                      <td width="50%">
                        <b>{{ $key }}</b>
                      </td>
                      <td width="50%">
                        <input type="text" class="form-control w-100" name="key[{{ $key }}]" value="{{ $value }}">
                      </td>
                    </tr>
                    @endif
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            <div class="row p-l-15">
              <button type="submit" class="btn btn-primary custom-btn-small float-left">{{ __('Save') }}</button>
            </div>
          </div>
        </form>
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
@endsection

@section('js')
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/general-settings.min.js') }}"></script>
@endsection