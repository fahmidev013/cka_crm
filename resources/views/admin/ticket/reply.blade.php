@extends('layouts.app')
@section('css')
  <link rel="stylesheet" type="text/css" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/jQueryUI/jquery-ui.min.css') }}" type="text/css"/>
  <link rel="stylesheet" type="text/css" href="{{asset('public/dist/css/custom-badges.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('public/dist/css/invoice-style.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('public/dist/css/ticket-reply.min.css')}}">
@endsection

@section('content')
  <div class="col-sm-12" id="reply-ticket-container">
    <div class="card">
      <div class="card-header">
        <h5 class="top-bar-title font-weight-bold">{{ __('Ticket No') }}: #{{ $ticketDetails->id }}</h5>
        <span class="cbadge cbadge-outlined f-14 mb-1 border-B0BEC5" style="color: {{ $ticketDetails->ticketStatus->color }}" id="status_label">{{$ticketDetails->ticketStatus->name}}</span>
        <div class="card-header-right">
          <button class="btn btn-primary custom-btn-small" data-toggle="modal" data-target="#modal-note">{{ __('Note') }}</button>
        </div>
      </div>
      <div class="card-block">
        <div class="row">
          <p class="font-weight-bold p-l-20 p-b-0 mb-2 f-16">{{ __('Subject') }}: <span class="font-weight-normal">{{$ticketDetails->subject}}</span></p>
        </div>
           @if(isset($ticketDetails->project) && !empty($ticketDetails->project->name))
           <div class="row">
               <p class="font-weight-bold p-l-20 p-b-0 mb-2 f-16">{{ __('Project') }}: <a class="project-url" href="{{url('project/details/' . $ticketDetails->project_id)}}">{{ $ticketDetails->project->name }}</a></p>
           </div>
           @endif
           @if(isset($ticketDetails->tasks) && !empty($ticketDetails->tasks->name))
           <div class="row">
               <p class="font-weight-bold p-l-20 p-b-0 mb-2 f-16">{{ __('Task') }}: <a href="{{url('task/v/' . $ticketDetails->tasks->id)}}">{{ $ticketDetails->tasks->name }}</a></p>
           </div>
           @endif
         <div class="row">
           <div class="col-md-9 col-sm-12 m-t-5">
               <?php
               if ($ticketDetails->priority->name == 'High') {
                   $color = '#f2d2d2';
               } else if ($ticketDetails->priority->name == 'Low') {
                   $color = '#e1e0e0';
               } else if ($ticketDetails->priority->name == 'Medium') {
                   $color = '#fae39f';
               }
               ?>
               <div id="removeLiPriority" class="btn-group">
                   <button type="button" class="cbadge cbadge-default cbadge-outlined f-14 mb-2 dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="priority">
                       {{ __('Priority')}} :  <span class="badge priority-style priority-color" style="background-color: {{ $color }}">{{ $ticketDetails->priority->name }}</span>
                   </button>
                   <ul class="dropdown-menu scrollable-menu status task-priority-name w-150p" role="menu">
                       @foreach($priority as $key => $value)
                           <li class="properties"><a class="ticket_priority_change f-14 color_black" ticket_id="{{ $ticketDetails->id }}" data-id="{{ $value->id }}" data-value="{{ $value->name}} ">{{ $value->name }}</a></li>
                       @endforeach
                   </ul>
               </div>
               <div id="removeLiProject" class="btn-group">
               <button type="button" class="cbadge cbadge-default cbadge-outlined f-14 mb-2 dropdown-toggle" data-toggle="dropdown"  data-flip="false" aria-haspopup="false" aria-expanded="false" id="priority">
                   {{ __('Project')}} :  <span class="badge priority-style project-name project-name-color" >{{ $ticketDetails->project->name }}</span>
               </button>
               <ul class="dropdown-menu dropdown-menu-right status scroable-dropdown  task-priority-name w-150p" role="menu">
                   @foreach($project as $key => $value)
                       <li class="properties"><a class="project-name-change f-14 color_black" ticket_id="{{ $ticketDetails->id }}" data-id="{{ $value->id }}" data-value="{{ $value->name}} ">{{ $value->name }}</a></li>
                   @endforeach
               </ul>
           </div>

             <p class="cbadge cbadge-default department-padding cbadge-outlined f-14 mb-2">{{ __('Department') }}:
             <span class="departmentText"> {{ $ticketDetails->department->name }}</span></p>
            <?php
             if ($ticketDetails->priority == 'High') {
               $color = '#099909';
             } else {
               $color = '#367fa9';
             }
             ?>
               <div id="removeLi" class="btn-group">
                 <button style="color:{{ $ticketDetails->ticketStatus->color }} !important" type="button" class="badge badgePadding cbadge-default cbadge-outlined text-white f-12 dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="ticket-status">
                 {{ $ticketDetails->ticketStatus->name }}&nbsp;<span class="caret"></span>
                 </button>
                 <ul class="dropdown-menu scrollable-menu status task-priority-name w-150p" role="menu">
                 @foreach($ticketStatus as $key => $value)
                   <li class="properties"><a class="status_change f-14 color_black" ticket_id="{{ $ticketDetails->id }}" data-id="{{ $value->id }}" data-value="{{ $value->name}} ">{{ $value->name }}</a></li>
                 @endforeach
                 </ul>
               </div>&nbsp
           </div>
           <div class="col-md-3">
             <select id="assignee" class="form-control select2">
               <option value=''>{{ __('No Assignee') }}</option>
              @foreach($assignee as $data)
                 <option <?= !empty($ticketDetails->assignedMember->id) &&  $data->id == $ticketDetails->assignedMember->id  ? 'selected' :  ''   ?> value="{{$data->id}}">{{ $data->full_name }}</option>
               @endforeach
             </select>
           </div>
         </div>
       </div>
       <hr class="m-t-0">
       <div class="card-block">
         <form class="form-horizontal" id="reply_form" action="{{ url('ticket/replyStore') }}" method="post" enctype="multipart/form-data">
           {{csrf_field()}}
           <input type="hidden" name="ticket_id" value="{{ $ticketDetails->id }}">
           <input type="hidden" name="user_id" value="{{ $ticketDetails->user_id }}">
           <input type="hidden" id="customer_id" name="customer_id" value="{{ $ticketDetails->customer_id }}">
           <input type="hidden" name="name" value="{{ $ticketDetails->name }}">
           <input type="hidden" name="email" value="{{ $ticketDetails->email }}">
           <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
           <div class="row">
             <div class="col-md-12 col-sm-12">
                   <div class="form-group row">
                       <div class="col-md-1 col-sm-1"></div>
                       <div class="col-md-5 col-sm-5">
                           <input class="form-control auto col-md-12" placeholder="{{ __('Canned Message Title') }}" id="cannedMsg_search">
                           <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="no_div_msg" tabindex="0">
                               <li>{{ __('No record found') }} </li>
                           </ul>
                       </div>
                       <div class="col-md-1 col-sm-1"></div>
                       <div class="col-md-5 col-sm-5">
                           <input class="form-control auto col-md-12" placeholder="{{ __('Canned Link Title') }}" id="cannedLink_search">
                           <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="no_div_link" tabindex="0">
                               <li>{{ __('No record found') }} </li>
                           </ul>
                       </div>
                   </div>
               </div>
             <div class="col-md-12">
               <div class="form-group row">
                 <div class="col-sm-1">
                   <label class="control-label require">{{ __('Reply') }}</label>
                 </div>
                 <div class="col-sm-11">
                   <textarea class="ticket_message form-control" name="message" id="ticket_messages">{{ old('message') }}</textarea>
                   <label id="ticket_messages-error" class="error" for="ticket_messages"></label>
                     @if(Helpers::has_permission(Auth::user()->id, 'add_canned_message'))
                     <span id="canned_msg" class="btn btn-primary custom-btn-small f-12 my-3" data-toggle="modal" data-type="canned_message" data-target="#modal-canned">{{ __('Save Canned Message') }}</span>
                     @endif
                     @if(Helpers::has_permission(Auth::user()->id, 'add_canned_link'))
                     <span id="canned_link" class="btn btn-primary custom-btn-small f-12 my-3" data-type="canned_link">{{ __('Save Canned Link') }}</span>
                     @endif
                 </div>
               </div>
               <div class="form-group row">
                 <div class="col-sm-1">
                   <label class="control-label">{{ __('Status') }}</label>
                 </div>
                 <div class="col-sm-3">
                   <select name="status_id" class="form-control select2 reply-select2">
                     @foreach($ticketStatuses as $data)
                       <option <?= $data->id == 5 ? 'selected' : '';?> value="{{ $data->id }}">{{ $data->name }}</option>
                     @endforeach
                   </select>
                 </div>
                 <div class="col-sm-1 ">
                   <label class="col-form-label pr-0 ml-1">{{ __('File') }}</label>
                 </div>
                 <div class="col-sm-7">
                   <div class="dropzone-attachments" id="reply-attachment">
                     <div class="event-attachments">
                       <div class="add-attachments"><i class="fa fa-plus"></i></div>
                     </div>
                   </div>
                   <div class="form-group row">
                   <div class="ml-3" id="uploader-text"></div>
                 </div>
                   <span class="badge badge-danger m-t-5">{{ __('Note') }}!</span> {{ __('Allowed File Extensions: jpg, png, gif, docx, xls, xlsx, csv and pdf') }}
                 </div>
               </div>
             </div>
             <div class="col-md-12 m-t-5" id='reply-btn'>
               <button type="submit" class="btn btn-primary custom-btn-small btn-reply mr-0 float-right" id="btnSubmit"><i class="comment_spinner spinner fa fa-spinner fa-spin custom-btn-small display_none"></i><span id="spinnerText">{{ __('Reply') }} </span></button>
             </div>
           </div>
         </form>
       </div>
     </div>
   @if($ticketReplies->count() > 0)
     <div class="card shadow-none">
       <div class="card-header">
         <h5 class="card-header-text">{{ __('Message') }}</h5>
       </div>
       <div class="bg_f4f7fa">
         @if($ticketReplies->count() > 0)
           @foreach($ticketReplies as $data)
             @if($data->user_id == 0 || is_null($data->user_id))
               <!-- Customer Reply-->
               @if(!empty($data->customer))
               <div class="card customer-card">
                 <div class="card-body">
                   <div class="row">
                     <div class="col-sm-2 px-2">
                       <h6 class="media-heading txt-primary text-left"><a href="{{url('customer/edit/'.$data->customer_id)}}">{{ $data->customer->name }}</a></h6>
                       <a href="{{url('customer/edit/'.$data->customer_id)}}" class="float-left m-l-20">
                           <img class="rounded-circle" width="45" height="45" alt=" " src='{{ url("public/dist/img/customer-avatar.jpg") }}'>
                       </a>
                     </div>
                     <div class="col-sm-10">
                       {!! $data->message !!}
                     </div>
                   </div>
                 </div>
                 <div class="card-footer mx-2 p-2">
                   <span><i class="feather icon-clock"></i>&nbsp{{ timeZoneformatDate($data->date) }} &nbsp{{ timeZonegetTime($data->date) }}</span>
                   @if(! empty($replyFiles))
                     @foreach($replyFiles as $k => $val)
                       @if(count($val) > 0 && $k == $data->id)
                        &nbsp | &nbsp
                         @foreach($val as $key => $file)
                           @php
                             $str   = explode(".", $file->file_name);
                             $file->extension = $str[count($str)-1];
                             $url   = url('public/dist') . '/js/html5lightbox/no_preview.png?v'. $file->id;
                             $extra = '';
                             $div   = '';
                             if (in_array($file->extension, array('jpg', 'png', 'jpeg', 'gif', 'pdf', 'flv', 'webm', 'mp4', 'ogv', 'swf', 'm4v', 'ogg'))) {
                               $url = url($filePath) .'/'. $file->file_name;
                             } elseif (in_array($file->extension, array('csv', 'xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx', 'txt'))) {
                               $url   = '#pdiv-'. $file->id;
                               $extra = 'data-width=900 data-height=600';
                               $div = '<div id="pdiv-'. $file->id .'" class="display_none">
                                         <div class="lightboxcontainer">
                                           <iframe width="100%" height="100%" src="//docs.google.com/gview?url='. url($filePath) .'/'. $file->file_name .'&embedded=true" frameborder="0" allowfullscreen></iframe>
                                           <div class="clear_both"></div>
                                         </div>
                                       </div>';
                             }
                             if (strlen($file->original_file_name) > 10) {
                                 $file_full_name = substr_replace($file->original_file_name, "...", 10);
                             } else {
                                 $file_full_name = $file->original_file_name;
                             }
                          @endphp
                          <div data-placement="top" data-toggle="tooltip" title="{{ $file->original_file_name }}" class="file"> <a  <?= $extra ?> href="{{ $url }}" class="m-r-10 f-15 text-muted html5lightbox color_8e8e8e" data-group="{{ $file->object_type.'-'.$file->object_id }}" title="{{ $file->original_file_name }}" data-attachment="<?= $file->id; ?>" data-original-title="{{ $file->original_file_name }}" download="{{ $file->file_name }}"><i class="{{getFileIcon($file->file_name)}} file-name"></i>{{ $file_full_name }}</a></div>
                        <?= $div ?>
                        @endforeach
                      @endif
                    @endforeach
                  @endif
                </div>
              </div>
              @endif
            @else
            <!-- Admin Reply-->
            @if(!empty($data->user))
              <div class="card admin-card">
                <div class="card-body mx-0">
                  <div class="row d-flex flex-xl-row-reverse flex-md-row-reverse flex-sm-row-reverse">
                    <div class="col-sm-2 px-2">
                      <h6 class="media-heading txt-primary text-right"><a href="{{url('user/team-member-profile/'.$data->user_id)}}">{{ $data->user->full_name }}</a></h6>
                      <a href="{{url('user/team-member-profile/'.$data->user_id)}}" class="float-right mr-4">
                        <img class="rounded-circle" width="45" height="45" alt=" " src='{{ getUserProfilePicture($data->user_id) }}'>
                      </a>
                    </div>
                    <div class="col-sm-10 px-2">
                      {!! $data->message !!}
                    </div>
                  </div>
                </div>
                <div class="card-footer mx-2 p-2">
                  <span><i class="feather icon-clock"></i>&nbsp{{ timeZoneformatDate($data->date) }} &nbsp{{ timeZonegetTime($data->date) }}</span>

                  @if(Auth::user()->id == $data->user_id)
                    @if (Helpers::has_permission(Auth::user()->id, 'edit_ticket_reply'))
                  &nbsp | &nbsp
                    <span class="btn btn-xs btn-secondary edit-btn" data-toggle="modal" data-type="admin_replay" data-id="{{$data->id}}" data-message="{{$data->message}}" data-target="#modal-default"><i class="feather icon-edit  f-12"></i></span>
                    @endif
                        <span class="btn btn-xs btn-secondary canned-btn" data-toggle="modal" data-type="admin_replay" data-id="{{$data->id}}" data-message="{{$data->message}}" data-target="#modal-canned"><i class="feather icon-message-circle  f-12"></i></span>
                  @endif
                  @if(!empty($replyFiles))
                    @foreach($replyFiles as $k => $val)
                      @if(count($val) > 0 && $k == $data->id)
                      &nbsp | &nbsp
                        @foreach($val as $key => $file)
                          @php
                            $str   = explode(".", $file->file_name);
                            $file->extension = $str[count($str)-1];
                            $url   = url('public/dist'). '/js/html5lightbox/no_preview.png?v'. $file->id;
                            $extra = '';
                            $div   = '';
                            if (in_array($file->extension, array('jpg', 'png', 'jpeg', 'gif', 'pdf', 'flv', 'webm', 'mp4', 'ogv', 'swf', 'm4v', 'ogg'))) {
                              $url = url($filePath) .'/'. $file->file_name;
                            } elseif (in_array($file->extension, array('csv', 'xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx', 'txt'))) {
                              $url   = '#pdiv-'. $file->id;
                              $extra = 'data-width=900 data-height=600';
                              $div =  '<div id="pdiv-'. $file->id .'" class="display_none">
                                        <div class="lightboxcontainer">
                                          <iframe width="100%" height="100%" src="//docs.google.com/gview?url='. url($filePath) .'/'. $file->file_name .'&embedded=true" frameborder="0" allowfullscreen></iframe>
                                          <div class="clear_both"></div>
                                        </div>
                                      </div>';
                            }
                            if (strlen($file->original_file_name) > 10) {
                                $file_full_name = substr_replace($file->original_file_name, "...", 10);
                            } else {
                                $file_full_name = $file->original_file_name;
                            }
                          @endphp

                          <div data-placement="top" data-toggle="tooltip" title="{{ $file->original_file_name }}" class="file"> <a  <?= $extra ?> href="{{ $url }}" class="m-r-10 f-15 html5lightbox color_8e8e8e" data-group="{{ $file->object_type.'-'.$file->object_id }}" title="{{ $file->original_file_name }}" data-attachment="<?= $file->id; ?>" data-original-title="{{ $file->original_file_name }}" download="{{ $file->file_name }}"><i class="{{getFileIcon($file->file_name)}} file-name"></i>{{ $file_full_name }}</a></div>
                        <?= $div ?>
                        @endforeach
                      @endif
                    @endforeach
                  @endif
                </div>
              </div>
            @endif
            @endif
          @endforeach
        @endif
      </div>
    </div>
  @endif
</div>


  {{-- Modal Start--}}
  <div class="modal fade" id="modal-default">
    <div class="modal-dialog">
      <form  method="POST" action="{{ url('update/admin_reply') }}" id="replyModal">
        {{ csrf_field() }}
        <input type="hidden" name="id" id="reply_id">
        <input type="hidden" name="in_type" id="update_type">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">{{ __('Update Reply') }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <textarea class="form-control" name="message" id="reply-modal"></textarea>
              <label id="reply-modal-error" class="error" for="reply-modal"></label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary custom-btn-small">{{ __('Update') }}</button>
            <button type="button" class="btn btn-danger custom-btn-small" data-dismiss="modal">{{ __('Cancel') }}</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  {{-- Note Modal Start--}}
  <div class="modal fade" id="modal-note">
    <div class="modal-dialog">
      <form  method="POST" action="{{ url('ticket/save-note') }}" id="noteForm">
        {{ csrf_field() }}
        <input type="hidden" name="note_id" value="{{ $note->id ?? null }}">
        <input type="hidden" name="ticket_id" value="{{ $ticketDetails->id }}">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">{{ __('Note') }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <textarea  id="txtAreaNote" name="message" data-message="{{ $note->content ?? '' }}" class="form-control">{{ $note->content ?? '' }}</textarea>
              <label id="txtAreaNote-error" class="error" for="txtAreaNote"></label>
              <p class="f-12 pt-4px"> @isset($note->user->full_name) {{ __('Added by') }}: <b>{{ $note->user->full_name }}</b> @endisset @isset($note->created_at) <span class="float-right"><i class="feather icon-clock  f-12"></i> <b>{{ $note->created_at }}</b></span> @endisset</p>
              <label id="reply-modal-error" class="error" for="reply-modal"></label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary custom-btn-small">{{ __('Save') }}</button>
            <button type="button" class="btn btn-danger custom-btn-small" data-dismiss="modal">{{ __('Cancel') }}</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  {{-- Canned Message Modal Start--}}
  <div class="modal fade" id="modal-canned"  role="dialog">
      <div class="modal-dialog">
          <form  method="POST" action="" id="cannedForm">
              {{ csrf_field() }}
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title">{{ __('Canned Message') }}</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="modal-body">
                      <div class="form-group">
                          <input  type="text" name="title" id="title" class="form-control" placeholder="{{ __('Provide Title') }}">
                          <label id="title-error" class="error" for="title"></label>
                      </div>
                      <div class="form-group">
                          <textarea  id="txtAreaCanned" name="canned_message" class="ticket_message form-control"></textarea>
                          <label id="txtAreaCanned-error" class="error" for="txtAreaCanned"></label>
                          <label id="canned-modal-error" class="error" for="reply-modal"></label>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-primary custom-btn-small">{{ __('Save') }}</button>
                      <button type="button" class="btn btn-danger custom-btn-small" data-dismiss="modal">{{ __('Cancel') }}</button>
                  </div>
              </div>
          </form>
      </div>
  </div>

  {{-- Canned Link Modal Start--}}
  <div class="modal fade" id="modal-link"  role="dialog">
      <div class="modal-dialog modal-lg">
          <form  method="POST" action="" id="linkForm">
              {{ csrf_field() }}
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title">{{ __('Canned Link') }}</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="modal-body">
                      <div class="card-body">
                          <div class="col-md-12 col-sm-12" id="custom_link">
                          </div>
                      </div>
                  </div>
                  <input type="hidden" name="type" id="type" value="reply">
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-primary custom-btn-small">{{ __('Save') }}</button>
                      <button type="button" class="btn btn-danger custom-btn-small" data-dismiss="modal">{{ __('Cancel') }}</button>
                  </div>
              </div>
          </form>
      </div>
  </div>

  <div id="all_status" class="display_none"> </div>

  {{-- Loading --}}
  <div id="wait">
    <img src='{{url("public/dist/img/loader/loader.gif")}}' width="64" height="64" /><br>Loading..
  </div>

  @include('layouts.includes.message_boxes')

@endsection

@section('js')
  <script type="text/javascript" src="{{ asset('public/dist/js/html5lightbox/html5lightbox.js?v=1.0') }}"></script>
  <script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
  <script src="{{ asset('public/dist/plugins/jQueryUI/jquery-ui.min.js') }}"></script>
  <script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('public/datta-able/plugins/ckeditor/js/ckeditor.js') }}"></script>
  <script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>
  <script src="{{ asset('public/dist/plugins/dropzone/dropzone.min.js') }}"></script>
  {!! translateValidationMessages() !!}
  <script type="text/javascript">
    'use strict';
    let editor;
    var ticket_id = "{{ $ticketDetails->id }}";
  </script>
  <script src="{{ asset('public/dist/js/custom/status_priority.min.js') }}"></script>
  <script src="{{ asset('public/dist/js/custom/canned.min.js') }}"></script>
  <script src="{{ asset('public/dist/js/custom/ticket.min.js') }}"></script>
@endsection
