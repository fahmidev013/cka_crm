@extends('admin.project.main')

@section('projectContent')  
<div id="milestone-edit-container">
  <form class="form-horizontal" action="{{url('project/milestones/update')}}"  id="milestone_form" method="post">
    {{csrf_field()}}
    <input type="hidden" name="milestone_id" value="{{ $milestone->id }}">
    <input type="hidden" name="project_id" value="{{ $milestone->project_id }}">
    <div class="form-tabs">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active text-uppercase" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __('Edit Milestone')  }}</a>
        </li>
        <li class="nav-item"></li>
      </ul>
    </div>
    <div class="tab-content" id="myTabContent">
      <div class="row">
        <div class="col-md-12">
          <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label require">{{ __('Name')  }}</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="name" value="{{ $milestone->name }}">
            </div>
          </div>
          <div class="form-group row">
            <label for="due_date" class="col-sm-2 col-form-label require">{{ __('Due Date')  }}</label>
            <div class="col-sm-8">
              <input type="text" id="dueDate" name="due_date" class="form-control" value="{{ formatDate($milestone->due_date) }}">
            </div>
          </div>

          <div class="form-group row">
            <label for="description" class="col-sm-2 col-form-label">{{ __('Description')  }}</label>
            <div class="col-sm-8">
              <textarea name="description" id="description" class="form-control text-editor">{!! $milestone->description  !!}</textarea>
            </div>
          </div>
          
          <div class="col-sm-8 px-0 m-t-10">
            <button class="btn btn-primary custom-btn-small" type="submit" id="btnSubmit">{{ __('Submit')  }}</button>
            <a href="{{ url('project/milestones').'/'.$project->id }}" class="btn btn-danger custom-btn-small">{{ __('Cancel')  }}</a>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

@section('projectJS')
<script src="{{ asset('public/dist/js/moment.min.js')}}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
{{-- Validate js --}}
<script src="{{ asset('public/dist/js/jquery.validate.min.js')}}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/project.min.js') }}"></script>
@endsection