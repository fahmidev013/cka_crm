@extends('admin.project.main')

@section('projectContent')
<div id="project-task-edit-container">
  <form class="form-horizontal" action="{{url('project/task/update')}}" method="post" id="project_task_form">
    <input type="hidden" name="project_id_db" id="project_id_db" value="{{ $project->id }}">
    <div class="form-tabs">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active text-uppercase" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __('Edit Task') }}</a>
        </li>
        <li class="nav-item"></li>
      </ul>
    </div>
    <div class="tab-content" id="myTabContent">
      {{csrf_field()}}
      <input type="hidden" name="task_id" id="task_id" value="{{ $task->id }}">
      <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
      <div class="row">
        <div class="col-sm-10">
          <div class="form-group row">
            <label class="col-sm-2 col-form-label require">{{ __('Subject')  }}</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="task_name" value="{{ $task->name }}">
            </div>
          </div>

          <div class="form-group row flexContainer" id="milestone_div">
            <label class="col-sm-2 col-form-label">{{ __('Milestone')  }}</label>
            <div class="col-sm-8">
              <select name="milestone_id" id="milestone" class="form-control w-100">
                <option value="">{{ __('Select One') }}</option>
                @foreach($milestones as $data)
                  <option <?= $data->id == $task->milestone_id ? 'selected' : '' ?> value="{{$data->id}}">{{ $data->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group row display_none" id="hourly_rate_div">
            <label class="col-sm-2 col-form-label">{{ __('Hourly Rate')  }}</label>
            <div class="col-sm-8">
              <input type="number" name="hourly_rate" class="form-control" value="{{ $task->hourly_rate }}">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-2 col-form-label require">{{ __('Start Date')  }}</label>
            <div class="col-sm-3">
              <input type="text" id="startDate" name="start_date" class="form-control" value="{{ formatDate($task->start_date) }}">
            </div>
            <label class="col-sm-2 col-form-label">{{ __('Due Date')  }}</label>
            <div class="col-sm-3">
              <input type="text" id="dueDate" name="due_date" class="form-control" value="{{ $task->due_date ? formatDate($task->due_date) : '' }}">
              <label for="due_date" generated="true" class="error" id="due_date-error"></label>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-2 col-form-label">{{ __('Related To') }}</label>
            <div class="col-sm-3">
              @if($task->related_to_type == '1')
              <input type="text" class="py-1" disabled="true" value="{{ __('Project') }}">
              <input type="hidden" name="related_to" id="related_to" value="{{$task->related_to_type}}">
              @endif
            </div>

           <label class="col-sm-2 col-form-label ">{{ __('Priority')  }}</label>
            <div class="col-sm-3">
              <select class="form-control select2 w-100" name="priority_id" id="priority">
                @foreach($priorities as $data)
                  <option <?= $data->id == $task->priority_id ? 'selected' : '' ?> value="{{$data->id}}">{{$data->name}}</option>
                @endforeach
              </select>
             </div>
          </div>

         <div class="form-group row">
            <label class="col-sm-2 col-form-label">{{ __('Project') }}</label>
              <div class="col-sm-8">
                <input type="text" class="py-1 w-100" disabled="true" value="{{ $project->name }}">
                <input type="hidden" name="project_id" id="project" value="{{ $project->id }}">
              </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-2 control-label">{{ __('Assignee') }}</label>
            <div class="col-sm-6">
              <select class="form-control w-100" name="assignee[]" id="assignee" multiple>
                @if(!empty($assignees))
                  @foreach($assignees as $key=>$user)
                   <option value="{{ $user->id }}" {{ in_array($user->id, $task_assignee)?'selected':''}}>{{ $user->full_name }}</option>
                  @endforeach
                @endif
              </select>
            </div>
            <div class="col-sm-4">
              <a id="assign_me">{{ __('Assign me') }}</a>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-2 col-form-label">{{ __('Tags') }}</label>
            <div class="col-sm-8">
              <select class="js-example-responsive" multiple="multiple" id="tags" name="tags[]">
                @foreach ($tags as $value)
                  <option selected="selected" value="{{ $value->tag->name }}">{{ $value->tag->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <!-- Checklist  starts-->
          <div class="form-group row">
            <label class="col-sm-2 control-label">{{ __('Checklist') }}</label>
            <div class="col-sm-8">
              <div id="checkListDiv">
                <div class="flexContainer">
                  <input type="text" id="myInput" class="form-control">
                  <span id="checklistAddBtn" class="checklistAddBtn">
                      <i class="fa fa-plus-square"></i>
                  </span>
                </div>
              </div>
              <div id="listHolderDiv" name="listHolderDiv">
                <ul id="myUL" name="myUL">
                  @foreach ($checklist_items as $checklist_item)
                    <li class='checkbox checkbox-primary'>
                      <input type='checkbox' name='todo-item-done' class='todo-item-done' id="checklist_status_{{$checklist_item->id}}" value='{{$checklist_item->title}}' {{ $checklist_item->is_checked == 1 ? "checked" : "" }} />
                      <label for="checklist_status_{{$checklist_item->id}}" class="cr f-12">
                      <span class="checklist_label {{ $checklist_item->is_checked == 1 ? __('strike') : '' }}  cursor_text" data-id="{{$checklist_item->id}}" title="{{ __('Click to edit') }}">{{$checklist_item->title}}</span></label>
                      <span class='todo-item-delete text-c-red f-18' id='{{$checklist_item->id}}'><i class='feather icon-trash-2'></i></span>
                    </li>
                  @endforeach
                </ul>
                <div id="checklistCollector" name="checklistCollector">
                </div>
              </div>
            </div>
          </div>
          <!-- Checklist  Ends-->
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">{{ __('Status') }}</label>
            <div class="col-sm-8">
              <select id="status" name="status_id" class="form-control select2">
                @foreach($task_statuses as $data)
                  <option <?= $data->id == $task->task_status_id ? 'selected': '' ?> value="{{ $data->id  }}">{{ $data->name }}</option>>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-2 col-form-label require">{{ __('Task Details') }}</label>
            <div class="col-sm-8">
              <textarea name="task_details" class="text-editor form-control" id="task_details">{{ $task->description }}</textarea>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm-8 m-t-15">
              <button type="button" class="btn btn-primary custom-btn-small" id="btnSubmit">{{ __('Update') }}</button>
              <a href="{{ url('project/tasks') . '/' . $project->id }}" class="btn btn-danger custom-btn-small">{{ __('Cancel')  }}</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

@section('projectJS')
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
<!-- daterangespicker -->
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script type="text/javascript">
  'use strict';
  var loggedUserId = '{{ Auth::user()->id }}';
  var projectChargeType = '{{ $project->charge_type }}';
</script>
<script src="{{ asset('public/dist/js/custom/project.min.js') }}"></script>
@endsection
