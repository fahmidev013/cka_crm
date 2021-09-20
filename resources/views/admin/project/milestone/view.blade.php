@extends('layouts.app')

@section('css')
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content')
<div class="col-sm-12">
    <div class="card">
    <div class="card-header">
      <h5>{{ __('Milestone Details') }} >> {{ $milestone->name }}</h5>
      <div class="card-header-right">
          @if(Helpers::has_permission(Auth::user()->id, 'add_milestone'))
            <a href="{{ url('project/milestone/add/'.$project->id) }}" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('Add Milestone')  }}</a>
          @endif
      </div>
    </div>
    @include('layouts.includes.project_navbar')
    <div class="card-body table-border-style" >
      <div class="form-tabs">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active text-uppercase" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __('Milestone Info')  }}</a>
          </li>
        </ul>
      </div>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
          <div class="col-sm-12 pl-0 pb-2">
            <span class="float-left"><i class="feather icon-clock"></i>&nbsp{{ __('Due Date')  }} : {{ $milestone->due_date }}</span> <br class="mb-1">
            <b>{{ __('Description')  }} :</b>  
            <p class="mb-0 pl-2">{!! $milestone->description !!}</p>
          </div>
          <div class="col-sm-12 pl-0">
            <div class="table-responsive">
              {!! $dataTable->table(['class' => 'table table-bordered table-hover dt-responsive', 'width' => '100%', 'cellspacing' => '0']) !!}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
  
  @include('admin.task.details')
  @include('layouts.includes.message_boxes')
@endsection

@section('js')
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
    {!! $dataTable->scripts() !!}
    @include('admin.task.details_script')
@endsection