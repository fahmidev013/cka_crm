@extends('admin.project.main')

@section('projectCSS')
{{-- Date Range picker --}}
<link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('public/dist/css/invoice-style.min.css')}}">
@endsection

@section('projectContent')
<!-- Main content -->
<div id="project-task-add-container">
  <form class="form-horizontal" action="{{url('project/task/store')}}" method="post" id="project_task_form"  enctype="multipart/form-data">
    {{csrf_field()}}
    <input type="hidden" name="project_id_db" id="project_id_db" value="{{ $project->id }}">
    <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
    <div class="form-tabs">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active text-uppercase" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __('Add Task')  }}</a>
        </li>
      </ul>
    </div>
    <div class="tab-content" id="myTabContent">
      <div class="row">
        <div class="col-md-10 col-sm-8">
          <div class="form-group row">
            <label class="col-sm-2 col-form-label require">{{ __('Subject')  }}</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="task_name" value="{{ old('task_name') }}">
            </div>
          </div>

          <div class="form-group row display_none" id="hourly_rate_div">
            <label class="col-sm-2 col-form-label require">{{ __('Hourly Rate')  }}</label>
            <div class="col-sm-8">
              <input type="text" name="hourly_rate" id="hourly_rate" class="form-control positive-float-number" value="{{ old('hourly_rate') }}">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-2 col-form-label require">{{ __('Start Date')  }}</label>
            <div class="col-sm-3">
              <input type="text" id="startDate" name="start_date" class="form-control" value="{{ old('startDate') }}">
            </div>
            <label class="col-sm-2 col-form-label">{{ __('Due Date')  }}</label>
            <div class="col-sm-3">
              <input type="text" id="dueDate" name="due_date" class="form-control" value="{{ old('due_date') }}">
              <label for="due_date" generated="true" class="error" id="due_date-error"></label>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-2 col-form-label">{{ __('Related To') }}</label>
            <div class="col-sm-3">
              <input type="text" class="py-1 w-100" disabled="true" value="{{ __('Project') }}">
              <input type="hidden" name="related_to" id="related_to" value="1">
            </div>
            <label class="col-sm-2 col-form-label">{{ __('Priority')  }}</label>
            <div class="col-sm-3">
              <select class="col-form-label select2" name="priority_id" id="priority">
                @foreach($priorities as $data)
                  <option value="{{ $data->id }}">{{ $data->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-2 col-form-label require">{{ __('Project') }}</label>
              <div class="col-sm-8">
                <input type="text" class="py-1 w-100" disabled="true" value="{{ $project->name }}">
                <input type="hidden" name="project_id" id="project" value="{{ $project->id }}">
              </div>
          </div>
           
          <div class="form-group row" id="milestone_div">
            <label class="col-sm-2 col-form-label">{{ __('Milestone')  }}</label>
            <div class="col-sm-8">
              <select id="milestone" name="milestone_id" class="form-control select2 w-100">
                <option value=""></option>
                @foreach($milestones as $data)
                  <option value="{{$data->id}}">{{ $data->name }}</option>
                @endforeach
              </select>
            </div>
          </div>


          <div class="form-group row" id="assignee_div">
            <label class="col-sm-2 control-label">{{ __('Assignee') }}</label>
            <div class="col-sm-6">
                <select class="form-control select w-100" name="assignee[]" id="assignee" multiple>
                  <?php $allAssignee = []; ?>
                  @if(!empty($assignees))
                    @foreach($assignees as $key=>$user)
                        <?php 
                          $allAssignee[] = $user->id;
                        ?>
                        <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                    @endforeach
                  @endif
                </select>
            </div>
            @if (in_array(Auth::user()->id, $allAssignee))
            <div class="col-sm-4">
                <a id="assign_me">{{ __('Assign me') }}</a>
            </div>
            @endif
          </div>
          
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">{{ __('Tags') }}</label>
            <div class="col-sm-8">
              <select class="js-example-responsive" multiple="multiple" id="tags" name="tags[]"></select>
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
                    </ul>
                    <div id="checklistCollector" name="checklistCollector">    
                    </div>
                </div>
            </div>
          </div>
          <!-- Checklist  Ends-->

         <div class="form-group row">
            <label class="col-sm-2 control-label require">{{ __('Task Details') }}</label>
            <div class="col-sm-8">
              <textarea name="task_details" class="text-editor form-control" id="task_details"></textarea>
            </div>
          </div>
          <div class="form-group row mt-4 mb-2">
            <label class="col-sm-2 control-label">{{ __('File') }}</label>
            <div class="col-sm-8">
              <div class="dropzone-attachments" id="reply-attachment">
                <div class="event-attachments">
                  <div class="add-attachments"><i class="fa fa-plus"></i></div>
                </div>
              </div>
            </div>
         </div>
         <div class="form-group row">
            <div class="col-sm-2"></div>
            <div class="ml-3" id="uploader-text"></div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 control-label"></label>
            <div class="col-sm-8">
                <span class="badge badge-danger">{{ __('Note')  }}!</span> {{ __('Allowed File Extensions: jpg, png, gif, docx, xls, xlsx, csv and pdf')  }}
            </div>
        </div>    
        </div>
      </div>
      <div class="col-sm-8 px-0 m-t-10">
        <button class="btn btn-primary custom-btn-small" type="submit" id="btnSubmit">{{ __('Submit')  }}</button>
        <a href="{{ url('project/tasks').'/'.$project->id }}" class="btn btn-danger custom-btn-small">{{ __('Cancel')  }}</a>
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
<script src="{{ asset('public/dist/plugins/dropzone/dropzone.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script type="text/javascript">
  'use strict';
  var loggedUserId = '{{ Auth::user()->id }}'; 
  var projectChargeType = '{{ $project->charge_type }}';
</script>
<script src="{{ asset('public/dist/js/custom/project.min.js') }}"></script>
@endsection