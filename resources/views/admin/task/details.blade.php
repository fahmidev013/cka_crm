 <div class="modal fade" id="task-modal">
  <div class="modal-dialog modal-lg">
    <form  method="POST" action="" id="replyModal">
      {{ csrf_field() }}
      <input type="hidden" name="task_id" id="task_id">
      <input type="hidden" name="relatedToId" id="relatedToId">
      <input type="hidden" name="relatedToType" id="relatedToType">
      <input type="hidden" name="chargeType" id="chargeType">
      <input type="hidden" name="ratePerHour" id="ratePerHour">
      <div class="modal-content">
        <div class="modal-header task-modal-header">
          <h5 class="modal-title task-modal-title" id="subject"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body task-modal-body">
          <div class="row">
        		<div class="col-md-4">
              <div class="row ml-0 bottom-line f-13">
  	        		<a href="#" class="btn status_change custom-btn-small task-custom-btn-small" id="mark_status" autocomplete="off" data-loading-text=""  data-toggle="tooltip" title=""  data-id="">
  	            	<i class="feather icon-check mr-0"></i>
  	          	</a>
  	          	<a href="#" class="btn btn-default timesheet custom-btn-small task-custom-btn-small" data-toggle="tooltip" data-title="Timesheets">
  	          		<i class="feather icon-list"></i>
  	          	</a>
  	          	<div class="btn btn-success timer custom-btn-small task-custom-btn-small" id="timerDiv">
  	          		<i class="feather icon-clock"></i><span id="timer_text">{{ __('Start Timer') }}</span>
  	          	</div>
              </div>
              <div class="card task-card row ml-0 pr-0">
                <div class="card-body px-1">
                  <div class="bottom-line f-13 testingP">
                    <i class="feather icon-mail mx-2"></i> <label id="related_div" class="ml-2">{{ __('Related To') }} : <a target="_blank" href="" id="related_to"></a></label>
                  </div>
                  <div class="bottom-line f-13 recuring_div">
                    <i class="feather icon-hash mx-2"></i>{{ __('Task Type') }} : <label id="recuring"></label>
                  </div>
                  <div class="bottom-line f-13 row">
                    <div class="mb-2 ml-3 mr-1"><i class="feather icon-clock mx-2"></i>{{ __('Created') }} :</div>
                    <label id="created_at"></label>
                  </div>
                  <div class="bottom-line f-13">
                    <i class="feather icon-star mx-2"></i><label>{{ __('Status') }} : <a href="#" id="task_status" data-rel="" data-trigger="focus" class="popover-toggle" data-placement="bottom" title="Change Status"></a></label>
                  </div>
                  <div class="bottom-line f-13 row">
                    <i class="feather icon-calendar task-date-info-icon ml-4"></i>{{ __('Start Date') }} :
                    <input name="start_date" id="start_date" class="task-date-info datepicker" value="">
                  </div>

                  <div class="bottom-line f-13 row">
                    <i class="feather icon-calendar task-date-info-icon ml-4"></i>{{ __('Due Date') }} :
                    <input name="due_date" id="due_date" class="task-date-info datepicker" value="">
                    <span class="ml-4 display_none" id="due_date_error"></span>
                  </div>

                  <div class="bottom-line f-13">
                    <i class="feather icon-droplet mx-2"></i><label>{{ __('Priority') }} : <a href="#" id="priority" class="priorityText" data-rel="" data-trigger="focus" class="popover-toggle" data-placement="bottom" title="Change Priority"></a></label>
                  </div>
                  <div class="bottom-line f-13 hourly_rate_div">
                    <i class="fas fa-dollar-sign mx-2"></i><label>{{ __('Hourly Rate') }} : <span id="hourly_rate" class="hourly_rate_text"></span></label>
                  </div>
                  <div class="bottom-line f-13">
                    <i class="feather icon-watch mx-2"></i><label>{{ __('Logged Time') }} : <span id="individual_logged_time"></span></label>
                  </div>
                  <div class="mb-2 f-13">
                    <i class="feather icon-clock mx-2"></i><label>{{ __('Total Logged Time') }} : <span id="total_logged_time"></span></label>
                  </div>
                </div>
              </div>
              <div class="card task-card row ml-0">
                <div class="card-header">
                  <span class="f-w-400 color_000">
                    <i class="feather icon-users mr-1"></i>{{ __('Assignees') }}
                  </span>
                  <span class="float-right">
                    @if(Helpers::has_permission(Auth::user()->id, 'add_task_assignee'))
                      <span class="cursor_pointer;" id="assignee_list" data-toggle="modal" data-target="#assignee-modal" title="{{ __('Add Assignee') }}"><i class="feather icon-user-plus ml-2"></i></span>
                    @endif
                  </span>
                </div>
                <div class="card-body">
                  <div id="all_assignee" class="mb-2"></div>
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <div class="card task-card row mx-0">
                <div class="card-header">
                  <h6><i class="feather icon-tag"></i> {{ __('Tags') }}</h6>
                </div>
                <div class="card-body" id="tag_area">
                  <select class="js-example-responsive" multiple="multiple" id="tags"></select>
                </div>
              </div>
            	<div class="card  task-card row mx-0">
              	<div class="card-header">
                 <h6><i class="feather icon-file-text"></i> {{ __('Details') }}<i class="feather icon-edit float-right cursor-poiner text-primary task-text-primary" id="task-details-edit" title="{{ __('Click to edit') }}"></i></h6>

                </div>
                <div class="card-body ml-2">
                  <div id="task-details"></div>
                  <div id="task-details-write">
                    <textarea class=" form-control" id="task-details-textarea" rows="8"></textarea>
                  </div>
                </div>
              </div>
              <div class="card task-card row mx-0 mt-3">
                <div class="card-header">
                 <h6><i class="feather icon-list"></i> {{ __('Checklist') }}<i class="feather icon-plus-circle float-right cursor-poiner text-primary task-text-primary" id="add-checklist"></i></h6>
                </div>
                <div class="card-body col-sm-12">
                  <div class="table mx-2 mb-0" id="checklist_items">
                    <table class="table-responsive">
                      
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        
          <div class="card task-card row mx-0" id="filesDiv">
            <div class="card-header">
              <h6>{{ __('Files') }}</h6>
            </div>
            <div class="card-body">
              <div class="dropzone-attachments m-0">
                  <div class="event-attachments">
                    <div id="attachments"></div>
                    <div class="add-attachments"><i class="fas fa-plus"></i></div>
                  </div>
                </div>
              <div class="col-md-12" id="uploader-text"></div>

                <label class="control-label p-0"></label>
                <div class="col-md-8 mb-3">
                  <span class="badge badge-danger">{{ __('Note') }}!</span> {{ __('Allowed File Extensions: jpg, png, gif, docx, xls, xlsx, csv and pdf') }}
                </div>

              <div id="files"></div>
            </div>
          </div>

          <div class="card task-card row mx-0">
            <div class="card-header">
              <h6>{{ __('Comment') }}</h6>
            </div>
            <div class="card-body">
               <div class="row">
                <div class="col-md-12">
                  <div id="comment_wrapper">
                    <textarea class="text-editor form-control" rows="3" id="comment" placeholder="{{ __('Comment') }}"></textarea>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <button type="button" id="btn_comment" class="btn btn-primary custom-btn-small task-custom-btn-small float-right mt-10"><i class="comment_spinner spinner fa fa-spinner fa-spin display_none"></i> <span id="comment_text">{{ __('Add Comment') }} </span></button>
                </div>
              </div>
                        
              <!-- Comment Area -->
              <div id="comments_error" class="mt-10"></div>
              <div class="row m-t-30">
                <div class="col-md-12" id="comments_area"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="assignee-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header task-modal-header">
        <h5 class="modal-title task-modal-title">{{ __('Assignees') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      </div>
      <div class="modal-body task-modal-body">
        <div class="form-group row m-l-1">
          <label class="col-md-3 col-form-label">{{ __('Add assignee') }}</label>
          <div class="col-md-8">
            <select id="assignee_dropdown"></select>
          </div>
        </div>
        <hr>
        <div id="assignees_modal_assignee_list"></div>
      </div>
    </div>
  </div>
</div>

<div id="task-end-modal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header task-modal-header">
        <h5 class="modal-title task-modal-title">{{ __('End task timer') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      </div>
      <div class="modal-body task-modal-body pb-0">
        <div class="form-group row m-l-1">
          <div class="col-12">
            <textarea class="form-control" placeholder="{{ __('Task end note') }}" id="task-end-note"></textarea>
          </div>
          <div class="col-12">
            <span class="text-danger" id="task-note-error"></span>
          </div>
        </div>
      </div>
      <div class="modal-footer p-2">
        <button class="btn custom-btn-small task-custom-btn-small btn-danger" id="task-end-submit">{{ __('End Now') }}</button>
      </div>
    </div>
  </div>
</div>


<!--time sheet modal start-->
<!-- class modal and fade -->
<div id="DemoModal2" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">       
      <!-- modal header -->
      <div class="modal-header task-modal-header">
        <h5 class="modal-title task-modal-title">{{ __('Timer List') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      </div>
           
      <!-- modal body -->
      <div class="modal-body task-modal-body" id="DemoModal2-body">
        <div class="row">
          <div class="col-sm-12">
            <button class="btn btn-sm btn-outline-primary custom-btn-small float-right m-0 task_timer" id="add_custom_task_timer" data-toggle="collapse" data-target="#manual_time_add_form"><span class="fa fa-plus m-2"></span>{{ __('New Custom Time') }}</button>
          </div>
        </div>
        <form role="form" id="manual_time_add_form" class="collapse">
          {{ csrf_field() }}
          <div class="box">
            <div class="box-body">
              <div class="form-group row ml-2 mt-10">
                <label class="col-sm-2 control-label require">{{ __('Start time') }}</label>
                <div class="col-sm-6 p-0">
                  <div class="input-group date">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="feather icon-calendar"></i></span>
                    </div>
                    <input type="text" class="form-control float-right datetimepicker" autocomplete="off" name="start_time" id="start_time">
                  </div>
                  <label id="start_time-error" class="error display_inline_block" for="start_time"></label>
                </div>
              </div>

              <div class="form-group row ml-2">
                <label class="col-sm-2 control-label require">{{ __('End Time') }}</label>
                <div class="col-sm-6 p-0">
                  <div class="input-group date">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="feather icon-calendar"></i></span>
                    </div>
                    <input type="text" class="form-control float-right datetimepicker" autocomplete="off" name="end_time" id="end_time">
                  </div>
                  <label id="end_time-error" class="error display_inline_block" for="end_time"></label>
                </div>
              </div>

              <div class="form-group row ml-2">
                <label class="col-sm-2 control-label require">{{ __('Member') }}</label>
                <div class="col-sm-6 p-0">
                  <select class="form-control" id="time_sheet_assignee_list" name="assignee"></select>
                <label id="time_sheet_assignee_list-error" class="error" for="time_sheet_assignee_list">{{ __('This field is required.') }}</label>
                </div>
              </div>

              <div class="form-group row ml-2 mb-0">
                <label class="col-sm-2 control-label">{{ __('Note') }}</label>
                <div class="col-sm-6 p-0">
                  <textarea class="form-control" rows="3" placeholder="{{ __('Enter Note..') }}" name="note" id="note"></textarea>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <span id="timer_error"></span>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <div class="row">
                <div class="col-sm-8 ml-4 mb-1">
                  <button type="submit" class="btn btn-primary custom-btn-small task-custom-btn-small float-right m-0" id="task-timer-submitBtn">{{ __('Submit') }}</button>
                </div> 
              </div>
            </div>
          </div>
        </form>
             
        <div class="box-body no-padding m-t-10">
          <div class="table-responsive">
            <table class="table table-bordered" id="timer_table">
              <thead>
                <tr class="tbl_header_color">
                  <th width="20%" class="text-center">{{ __('Assignee') }}</th>
                  <th width="30%" class="text-center">{{ __('Start time') }}</th>
                  <th width="30%" class="text-center">{{ __('End Time') }}</th>
                  <th width="20%" class="text-center">{{ __('Time Spent') }}</th>
                  <th width="20%" class="text-center">{{ __('Action') }}</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
          <br>
        </div>
      </div>
    <!-- / .modal-content -->  
    </div>
  <!-- / .modal-dialog -->
  </div>
<!-- / .modal -->
</div>

<!--time sheet modal end-->
<!--lightbox-->
<script src="{{url('public/dist/plugins/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('public/dist/plugins/lightbox/js/lightbox.min.js')}}"></script>
<script src="{{asset('public/dist/js/custom/task-details.min.js')}}"></script>
