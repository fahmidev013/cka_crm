
@extends('admin.project.main')

@section('projectCSS')
<link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
  <link rel="stylesheet" type="text/css" href="{{asset('public/dist/css/project.min.css')}}">
@endsection

@section('add-title')
  @if(Helpers::has_permission(Auth::user()->id, 'add_milestone'))
    <a href="{{ url('project/milestone/add/'.$project->id) }}" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Milestone')  }}</a>
  @endif
@endsection

@section('projectContent')
<div id="project-milestone-container">
  <div class="m-t-10 col-sm-12">
    <div class="tab-pane show">
      <div class="table-responsive">
        {!! $dataTable->table(['class' => 'table table-striped table-hover dt-responsive', 'width' => '100%', 'cellspacing' => '0']) !!}
      </div>
    </div>
  </div>
</div>
@endsection

@section('projectJS')
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>

<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>

{!! $dataTable->scripts() !!}
<script>
  'use strict';
  var projectId = "{{ $project->id }}";
  var oldMembers = "";
  var from = "";
  var to = "";
</script>
<script src="{{ asset('public/dist/js/custom/project.min.js') }}"></script>
@endsection