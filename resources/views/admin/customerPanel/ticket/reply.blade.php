@extends('layouts.customer_panel')
@section('css')
{{-- Select2  --}}
  <link rel="stylesheet" type="text/css" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/jQueryUI/jquery-ui.min.css') }}" type="text/css"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/css/custom-badges.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/css/invoice-style.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/css/customer-panel.min.css') }}">
@endsection
@section('content')
  <div class="col-sm-12" id="cus-panel-ticket-reply-container">
    <div class="card">
      <div class="card-header">
        <h5 class="top-bar-title font-weight-bold p-0">{{ __('Ticket No') }}: #{{ $ticketDetails->id }}</h5>
        <span class="cbadge cbadge-outlined f-14 mb-1 border-B0BEC5" style="color: {{ $ticketDetails->ticketStatus->color }}" id="status_label">{{ $ticketDetails->ticketStatus->name }}</span>
      </div>
      <div class="card-block">
        <div class="row">
          <p class="font-weight-bold p-l-20 p-b-0 mb-2 f-16">{{ __('Subject') }}:</p>
            <span class="f-16">
              &nbsp;{{ $ticketDetails->subject }}
            </span>

        </div>
        <div class="row">
          <div class="col-md-12 col-sm-12 m-t-5">
            <p class="cbadge cbadge-default cbadge-outlined ml-sm-0 mb-1">{{ __('Department') }}:
            <span class="departmentText"> {{ $ticketDetails->department->name }}</span></p>
            <?php
            if ($ticketDetails->priority == 'High') {
              $color = '#099909';
            } else {
              $color = '#367fa9';
            }
            ?>
            <p class="cbadge cbadge-default cbadge-outlined mb-1">{{ __('Priority') }}:
              <span style="color: {{ $color }}"> {{ $ticketDetails->priority->name }}</span></p>
            @if(!empty($ticketDetails->assigned_member_id))
              <span class="cbadge cbadge-outlined cbadge-default ml-sm-0 mb-1 bolder-info">{{ __('Assignee')  }}: {{ $assignee->full_name }}</span>
            @endif
          </div>
        </div>
      </div>
      <hr class="m-t-0">
      <div class="card-block">
        <form class="form-horizontal" action="{{ url('customer-ticket/replyStore') }}" method="post" enctype="multipart/form-data" id="reply_form">
          <input type="hidden" name="ticket_id" value="{{ $ticketDetails->id }}">
          <input type="hidden" name="user_id" value="{{ $ticketDetails->user_id }}">
          <input type="hidden" name="customer_id" value="{{ $ticketDetails->customer_id }}">
          <input type="hidden" name="name" value="{{ $ticketDetails->name }}">
          <input type="hidden" name="email" value="{{ $ticketDetails->email }}">
          <input type="hidden" name="department_id" value="{{ $ticketDetails->department_id}}">
          <input type="hidden" name="status_id" value="1">
          <input type="hidden" name="assigned_member_id" value="{{ $ticketDetails->assigned_member_id }}">
          <input type="hidden" value="{{ csrf_token() }}" name="_token" id="token">
          <div class="row">
              <div class="col-md-12">
                  <div class="form-group row">
                      <div class="col-sm-1"></div>
                      <div class="col-sm-5">
                          <input class="form-control auto col-md-12" placeholder="{{ __('Canned Message') }}" id="cannedMsg_search">
                          <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="no_div_msg" tabindex="0">
                              <li>{{ __('No record found') }} </li>
                          </ul>
                      </div>
                      <div class="col-sm-1"></div>
                      <div class="col-sm-5">
                          <input class="form-control auto col-md-12" placeholder="{{ __('Canned Links') }}" id="cannedLink_search">
                          <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="no_div_link" tabindex="0">
                              <li>{{ __('No record found') }} </li>
                          </ul>
                      </div>
                  </div>
              </div>
            <div class="col-md-12">
              <div class="form-group row">
                <div class="col-sm-1">
                  <label class="control-label require">{{ __('Reply')  }}</label>
                </div>
                <div class="col-sm-11">
                  <textarea class="ticket_message form-control" name="message" placeholder="{{ __('Reply')  }}" id="ticket_messages" cols="30" rows="10">{{ old('message') }}</textarea>
                  <label id="ticket_messages-error" class="error" for="ticket_messages"></label>
                </div>
              </div>
              <div class="form-group row">
              <label class="col-sm-1 col-form-label">{{ __('File')  }}</label>
              <div class="col-md-11">
                <div class="dropzone-attachments" id="reply-attachment">
                  <div class="event-attachments">
                    <div class="add-attachments"><i class="fa fa-plus"></i></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-1"></div>
                <div class="ml-3" id="uploader-text"></div>
              </div>

            <div class="form-group row">
                <label class="col-sm-1 control-label"></label>
                <div class="col-sm-8">
                    <span class="badge badge-danger">{{ __('Note')  }}!</span> {{ __('Allowed File Extensions: jpg, png, gif, docx, xls, xlsx, csv and pdf')  }}
                  </div>
            </div>
              <div class="form-group row">
                <div class="col-md-12 m-t-2">
                  <button type="submit" class="btn btn-primary custom-btn-small btn-reply mr-0 float-right" id="btnSubmit"><i class="comment_spinner spinner fa fa-spinner fa-spin custom-btn-small display_none"></i><span id="spinnerText">{{ __('Reply') }} </span></button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    @if($ticketReplies->count() > 0)
    <div class="card shadow-none">
      <div class="card-header">
        <h5 class="card-header-text">{{ __('Messages')  }}</h5>
      </div>
      <div class="card-block">
        @if($ticketReplies->count() > 0)
          @foreach($ticketReplies as $data)
            @if(!empty($data->customer_id))
              <!-- Customer Reply-->
              <div class="card customer-card">
                <div class="card-body mx-0">
                  <div class="row">
                    <div class="col-sm-2 px-2">
                      <h6 class="media-heading txt-primary text-left">{{ $data->customer->name }}</h6>
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
                  @if(!empty($replyFiles))
                    @foreach($replyFiles as $k => $val)
                      @if(count($val) > 0 && $k == $data->id)
                       &nbsp | &nbsp
                        @foreach($val as $key => $file)
                          @php
                            $str   = explode(".", $file->file_name);
                            $file->extension = $str[count($str)-1];
                            $url   = url('public/dist'). '/js/html5lightbox/no_preview.png?v' . $file->id;
                            $extra = '';
                            $div   = '';
                            if (in_array($file->extension, array('jpg', 'png', 'jpeg', 'gif', 'pdf', 'flv', 'webm', 'mp4', 'ogv', 'swf', 'm4v', 'ogg'))) {
                              $url = url($filePath) . '/' . $file->file_name;
                            } elseif (in_array($file->extension, array('csv', 'xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx', 'txt'))) {
                              $url   = '#pdiv-' . $file->id;
                              $extra = 'data-width=900 data-height=600';
                              $div = '<div id="pdiv-' . $file->id . '" class="display_none">
                                        <div class="lightboxcontainer">
                                          <iframe width="100%" height="100%" src="//docs.google.com/gview?url=' . url($filePath) . '/' . $file->file_name . '&embedded=true" frameborder="0" allowfullscreen></iframe>
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
                          <div data-placement="top" data-toggle="tooltip" title="{{ $file->original_file_name }}" class="file"> <a  <?= $extra ?> href="{{ $url }}" class="m-r-10 f-15 text-muted html5lightbox" data-group="{{ $file->object_type . '-' . $file->object_id }}" title="{{ $file->original_file_name }}" data-attachment="<?= $file->id; ?>" data-original-title="{{ $file->original_file_name }}" download="{{ $file->file_name }} color_8e8e8e"><i class="{{ getFileIcon($file->file_name) }} file-name"></i>{{ $file_full_name }}</a></div>
                        <?= $div ?>
                        @endforeach
                      @endif
                    @endforeach
                  @endif
                </div>
              </div><br>
            @else
            <!-- Admin Reply-->
                @if(!empty($data->user))
                   <div class="card admin-card">
                    <div class="card-body mx-0">
                      <div class="row d-flex flex-xl-row-reverse flex-md-row-reverse flex-sm-row-reverse">
                        <div class="col-sm-2 px-2">
                          <h6 class="media-heading txt-primary text-right">{{ $data->user->full_name }}</h6>

                            <img class="rounded-circle float-right mr-4" width="45" height="45" alt=" " src='{{ getUserProfilePicture($data->user->id) }}'>

                        </div>
                        <div class="col-sm-10 px-2">
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
                                $url   = url('public/dist') . '/js/html5lightbox/no_preview.png?v' . $file->id;
                                $extra = '';
                                $div   = '';
                                if (in_array($file->extension, array('jpg', 'png', 'jpeg', 'gif', 'pdf', 'flv', 'webm', 'mp4', 'ogv', 'swf', 'm4v', 'ogg'))) {
                                  $url = url($filePath) . '/' . $file->file_name;
                                } elseif (in_array($file->extension, array('csv', 'xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx', 'txt'))) {
                                  $url   = '#pdiv-' . $file->id;
                                  $extra = 'data-width=900 data-height=600';
                                  $div = '<div id="pdiv-' . $file->id . '" class="display_none">
                                            <div class="lightboxcontainer">
                                              <iframe width="100%" height="100%" src="//docs.google.com/gview?url=' . url($filePath) . '/' . $file->file_name . '&embedded=true" frameborder="0" allowfullscreen></iframe>
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
                              <div data-placement="top" data-toggle="tooltip" title="{{ $file->original_file_name }}" class="file"> <a  <?= $extra ?> href="{{ $url }}" class="m-r-10 f-15 text-muted html5lightbox" data-group="{{ $file->object_type . '-' . $file->object_id }}" title="{{ $file->original_file_name }}" data-attachment="<?= $file->id; ?>" data-original-title="{{ $file->original_file_name }}" download="{{ $file->file_name }} color_8e8e8e"><i class="{{ getFileIcon($file->file_name) }} file-name"></i>{{ $file_full_name }}</a></div>
                            <?= $div ?>
                            @endforeach
                          @endif
                        @endforeach
                      @endif
                    </div>
                  </div>
                  <br>
                @endif
            @endif
          @endforeach
        @endif
      </div>
    </div>
  @endif

  </div>

@include('layouts.includes.message_boxes')
@endsection

@section('js')
<script src="{{ asset('public/dist/js/html5lightbox/html5lightbox.js?v=1.0') }}"></script>
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/jQueryUI/jquery-ui.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/ckeditor/js/ckeditor.js') }}"></script>
<script src="{{ asset('public/dist/plugins/dropzone/dropzone.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/canned.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/customerpanel.min.js') }}"></script>
@endsection
