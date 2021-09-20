@extends('admin.project.main')

@section('projectCSS')
<link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
@endsection

@section('projectContent')
<div id="project-timesheet-container">
  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="row mt-3">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-4 mb-2">
        <div class="card project-task status-border">
          <div class="card-block">
            <div class="row  align-items-center">
              <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
                  <span class="f-16 color_03a9f4"><i class="fas fa-clock m-r-10 f-4"></i>{{ __('Total Time Spent') }}</span>
              </div>
              <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-xs-12 text-right">
                <div class="row card-active">
                  <div class="col-md-4 col-6">
                    <span class="text-c-blue f-20 @if($hours == 0) {{'text-muted'}} @endif" ><strong>{{ $hours }}</strong></span>
                    <span class="text-muted">Hours</span>
                  </div>
                  <div class="col-md-4 col-6">
                    <span class="text-c-blue f-20 @if($minutes == 0) {{'text-muted'}} @endif" ><strong>{{ $minutes }}</strong></span>
                    <span class="text-muted">Minutes</span>
                  </div>
                  <div class="col-md-4 col-6">
                    <span class="text-c-blue f-20 @if($seconds == 0) {{'text-muted'}} @endif" ><strong>{{ $seconds }}</strong></span>
                    <span class="text-muted">Seconds</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="m-t-10 col-sm-12">
  	<div class="table-responsive">
  	  {!! $dataTable->table(['class' => 'table table-striped table-hover dt-responsive project-timesheet-styles', 'width' => '100%', 'cellspacing' => '0']) !!}
  	</div>
  </div>
</div>
@endsection

@section('projectJS')
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
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
