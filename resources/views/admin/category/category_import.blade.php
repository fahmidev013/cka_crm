@extends('layouts.app')
@section('css')
{{-- DataTable --}}
<link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
@endsection

@section('content')
<!-- Main content -->
<div class="col-sm-12" id="categoryImport-settings-container">
  <div class="row">
    <div class="col-md-3">
      @include('layouts.includes.sub_menu')
    </div>
    <div class="col-md-9">
      <div class="card card-info">
        <div class="card-header">
          <h5><a href="{{ url('item-category') }}">{{ __('General Settings') }} </a> >> {{ __('Import Categories') }}</h5>
          <div class="card-header-right" id="cardRightButton">
            <button class="btn btn-outline-primary" id="fileRequest"><i class="feather icon-download"></i>{{ __('Download Sample') }}</button>

          </div>
        </div>
        <div class="card-body">
          <div class="row p-l-15">
            <p>{{ __('Your CSV data should be in the format below. The first line of your CSV file should be the column headers as in the table example. Also make sure that your file is UTF-8 to avoid unnecessary encoding problems.') }}</p>
            <span class="badge badge-info mb-10">{{ __('Note') }}</span> <small class="text-info">&nbsp;{{ __('Duplicate categoty rows will not be imported AND Abbr should be matched with Abbr of Units.') }} </small>
          </div>
          <div class="row p-l-15">
            <div class="table-responsive">
              <table id="dataTableBuilder" class="table table-bordered table-hover table-striped dt-responsive">
                <thead>
                  <tr>
                    <th>{{ __('Category') }}<span class="color_ff2d42">*</span></th>
                    <th>{{ __('Abbr') }}<span class="color_ff2d42">*</span></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ __('Electronic Device') }}</td>
                    <td>{{ __('Pc') }}</td>
                  </tr>
                  <tr>
                    <td>{{ __('Rice') }}</td>
                    <td>{{ __('Kg') }}</td>
                  </tr>
                </tbody>
              </table>
              <span class="badge badge-info">{{ __('Note') }}</span> <small class="text-info">{{ __('Required field is mendatory') }}</small>
            </div>
            <br /><br />
          </div>
          <form action="{{ url('categoryimportcsv') }}" method="post" id="myform1" class="form-horizontal" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label item_category_padding">{{ __('Choose CSV File') }}</label>
                <div class="custom-file col-md-8">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" name="item_image" id="validatedCustomFile">
                    <label class="custom-file-label overflow_hidden" for="validatedCustomFile">{{ __('Upload csv...') }}</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-2 control-label note"></label>
              <div class="col-md-8" id='note_txt_1'>
                <span class="badge badge-info">{{ __('Note') }}</span> <small class="text-info">{{ __('Allowed File Extensions: csv') }}</small>
              </div>
              <div class="col-md-8" id='note_txt_2'>
              </div>
            </div>
        </div>
        <div class="card-footer form-group row">
          <div class="col-sm-8">
            <a href="{{ url('item-category') }}" class="btn btn-danger custom-btn-small">{{ __('Cancel') }}</a>
            <button type="submit" class="btn btn-primary custom-btn-small float-left">{{ __('Submit') }}</button>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/general-settings.min.js') }}"></script>
@endsection