@extends('layouts.list-layout')
@section('css')
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
@endsection
@section('list-title')
  <h5> <a href="{{ url('users') }}">{{ __('Users') }}</a> </h5>
@endsection

@section('list-form-content')
  @php 
    $from='';
    $to = '';
  @endphp
  <div class="col-md-12 col-sm-12 col-xs-12 m-l-10 m-b-0" id="user-list-container">
    @if(Helpers::has_permission(Auth::user()->id, 'add_team_member'))
    <div class="buttonRelation mt-3">
      <a href="{{ URL::to('user-import') }}" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-upload"> &nbsp;</span>{{ __('Import Members') }}</a>

      <a href="{{ url('create-user') }}" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Member') }}</a>
    </div>
    @endif
    <div class="row mt-3">      
      <div class="col-md-4 col-sm-4 col-xs-12">
        <div class="card project-task theme-bg">
          <div class="card-block">
            <div class="row  align-items-center">
              <div class="col-9">
                <a href="{{ url('users?user=total') }}">
                  <h5 class="text-white"><i class="fas fa-users m-r-10 f-20"></i>{{ __('Total') }}</h5>
                </a>
              </div>
              <div class="col-3 text-right">
                <a href="{{ url('users?user=total') }}">
                  <h3 class="text-white" id="userCount">{{ $userCount }}</h3>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12">
        <div class="card project-task bg-c-blue">
          <div class="card-block">
            <div class="row  align-items-center">
              <div class="col-9">
                <a href="{{ url('users?user=active') }}">
                  <h5 class="text-white"><i class="fas fa-users m-r-10 f-20"></i>{{ __('Active') }}</h5>
                </a>
              </div>
              <div class="col-3 text-right">
                <a href="{{ url('users?user=active') }}">
                  <h3 class="text-white" id="userActive">{{ $userActive }}</h3>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12">
        <div class="card project-task theme-bg2">
          <div class="card-block">
            <div class="row  align-items-center">
              <div class="col-9">
              <a href="{{ url('users?user=inactive') }}">
                <h5 class="text-white"><i class="fas fa-users m-r-10 f-20"></i>{{ __('Inactive') }}</h5>
              </a>
              </div>
              <div class="col-3 text-right">
                <a href="{{ url('users?user=inactive') }}">
                  <h3 class="text-white" id="userInActive">{{ $userInActive }}</h3>
                </a>
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
  <div class="alert alert-danger display_none" role="alert" id="alert">
    {{ __('Something went wrong, please try again.') }}
  </div>

@endsection

@section('list-js')

<script src="{{ asset('public/dist/js/custom/user.min.js') }}"></script>
@endsection