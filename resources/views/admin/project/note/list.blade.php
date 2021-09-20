@extends('layouts.app')
@section('css')
{{-- select2 css --}}
<link rel="stylesheet" type="text/css" href="{{asset('public/dist/css/project.min.css')}}">
@endsection
@section('content')
      <!-- Main content -->
<div class="col-sm-12">
  <div class="card mb-2">
    <div class="card-header">
        <h5>
          <div class="top-bar-title padding-bottom">{{ $project->name }} &nbsp;<span class="color_84c529 f-16">{{ $project->status_name }}</span>
          </div>
        </h5>
        <div class="card-header-right">
          
        </div>
    </div>
    <div class="card-body p-0">
      @include('layouts.includes.project_navbar')
    </div>
    <div class="card-block m-t-10" id="project-note-container">
      <form method="POST" action="{{ url('project/note/store') }}" id="addNote">
        {{csrf_field()}}
        <input type="hidden" name="id" value="{{ $project->id }}">
        <div class="box-body">
          <div class="form-group row">
            <label class="col-sm-2 col-form-label require">{{ __('Subject')  }}</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="subject">
            </div>
          </div>
          <div class="form-group row">
            <label for="note" class="col-sm-2 control-label require">{{ __('Note')  }}</label>
            <div class="col-sm-9">
              <textarea name="content" class="form-control text-editor" id="note_add_messages"></textarea>
              <label id="note_add_messages-error" class="error" for="note_add_messages"></label>
            </div>
          </div>
        </div>
        <div class="col-sm-8 px-0">
            <button class="btn btn-primary custom-btn-small" type="submit"  id="note_submit">{{ __('Submit')  }}</button>   
          </div>
      </form>
    </div>
  </div>
  @if(count($notes) > 0 )
  <div class="card">
    <div class="card-header">
      <h5 class="card-header-text">{{ __('Notes')  }}</h5>
      <div class="card-header-right">
        
      </div>
    </div>
    <div class="card-block task-comment">
      <ul class="media-list p-0">
        @foreach($notes as $note)
          <li class="media">
            <div class="media-left mr-3">
              <a href="#!">
                <img class="img-fluid rounded-circle" width="45" alt=" " src='{{ getUserProfilePicture($note->user_id) }}'>
              </a>
            </div>
            <div class="media-body">
              @if($note->user_id == Auth::user()->id)
                @if(Helpers::has_permission(Auth::user()->id, 'delete_project_note'))
                  <form method="post" action="{{ url('project/note/delete') }}" class="display_inline"  id="delete-item-{{$note->id}}">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $note->id }}">
                    <button title="{{ __('Delete') }}" class="btn btn-xs btn-danger float-right ml-3" type="button" data-toggle="modal" data-id="{{ $note->id }}" data-target="#theModal" data-label="Delete" data-title="{{ __('Delete Note')  }}" data-message="{{ __('Are you sure to delete this note?')  }}">
                              <i class="feather icon-trash-2"></i> </button>
                  </form>
                @endif

                @if(Helpers::has_permission(Auth::user()->id, 'edit_project_note'))
                  <span class="btn btn-xs btn-primary edit-btn float-right" data-id="{{$note->id}}" data-message="{{$note->subject}}" data-toggle="modal" data-target="#modal-default"><i class="feather icon-edit"></i></span>
                @endif
              @endif
              <h6 class="media-heading">
                <a class="text-muted f-w-700" href="{{url('user/team-member-profile/'.$note->user_id)}}">{{ $note->full_name }}</a>
                <span class="f-12 text-muted m-l-5">
                  <i class="feather icon-clock"></i>
                   {{ timeZoneformatDate($note->created_at) }} - {{ date_format(date_create($note->created_at), 'g:i A') }}
                 </span></h6>
              <div class="text-justify"> {{ __('Subject') }}: <strong class="f-w-700"> {!! $note->subject !!} </strong></div>
              <div class="m-t-5 text-justify">{!! $note->content !!}</div>
            </div>
          </li>
        @endforeach
      </ul>
    </div>
  </div>
@endif
</div>

{{-- Modal Start--}}
<div class="modal fade" id="modal-default">
  <div class="modal-dialog">
    <form  method="POST" action="{{ url('project/note/update') }}" id="update-note">
      {{ csrf_field() }}
      <input type="hidden" name="id" id="id">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('Edit Note')  }} # <span id="note-id"></span></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <input class="col-sm-12" type="text" name="subject" id="note_subject_edit" placeholder="Subject">
          </div>
          <div class="form-group">
            <textarea class="col-sm-12" id="note_content_edit" name="content">{{__('Note') }}</textarea>
            <label id="note_content_edit-error" class="error" for="note_content_edit"></label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary custom-btn-small">{{ __('Update')  }}</button>
          <button type="button" class="btn btn-danger custom-btn-small" data-dismiss="modal">{{ __('Cancel')  }}</button>
        </div>
      </div>
    </form>
  </div>
</div>
<div class="modal fade" id="theModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="theModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary custom-btn-small" data-dismiss="modal">{{ __('Close') }}</button>
        <button type="button" id="theModalSubmitBtn" data-task="" class="btn btn-danger custom-btn-small">{{ __('Submit') }}</button>
        <span class="ajax-loading"></span>
      </div>
    </div>
  </div>
</div>
<!-- /.Modal End -->

<!--all member show in popover-->
<div id="all_member" class="display_none"> </div>

{{-- Loading --}}
<div id="wait">
  <img src='{{url("public/dist/img/loader/loader.gif")}}' width="64" height="64" /><br>{{ __('Loading..') }}
</div>
@include('layouts.includes.message_boxes')
@endsection
@section('js')
{{-- Validate js --}}
<script src="{{ asset('public/dist/js/jquery.validate.min.js')}}"></script>
{{-- Classic editor --}}
<script src="{{ asset('public/datta-able/plugins/ckeditor/js/ckeditor.js') }}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/project.min.js') }}"></script>
@endsection