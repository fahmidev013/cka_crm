@extends('admin.project.main')

@section('projectCSS')
<link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
  <link rel="stylesheet" type="text/css" href="{{asset('public/dist/css/project-files-list.min.css')}}">
@endsection

@section('projectContent')
  <div class="m-t-10 col-sm-12" id="project-files-container">
      <form enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="project_id" id="project_id" value="{{ $project->id }}">
        <div class="form-group row mt-4 mb-2">
          <div class="col-md-12">
            <div class="dropzone-attachments" id="reply-attachment">
              <div class="event-attachments">
                <div class="add-attachments"><i class="fa fa-plus"></i></div>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-sm-6" id="uploader-text"></div>
        </div>

        <div class="form-group row">
          <div class="col-md-8">
              <span class="badge badge-danger">{{ __('Note')  }}!</span> {{ __('Allowed File Extensions: jpg, png, gif, docx, xls, xlsx, csv and pdf')  }}
          </div>
        </div>        
      </form>
    <div class="m-t-20">
      <div class="table-responsive">
        {!! $dataTable->table(['class' => 'table table-striped table-hover dt-responsive', 'width' => '100%', 'cellspacing' => '0']) !!}
      </div>
    </div>
  </div>

@endsection

@section('projectJS')
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
{!! $dataTable->scripts() !!}
<script src="{{ asset('public/dist/plugins/dropzone/dropzone.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>
<script type="text/javascript">
'use strict';
var projectId = "{{ $project->id }}"
</script>
<script src="{{ asset('public/dist/js/custom/project.min.js') }}"></script>
@endsection