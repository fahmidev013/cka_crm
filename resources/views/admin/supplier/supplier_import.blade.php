@extends('layouts.app')
@section('css')
@endsection

@section('content')

<!-- Main content -->
<div class="col-sm-12" id="supplier-import-container">
  <div class="card Recent-Users">
    <div class="card-header">
      <h5><a href="{{url('supplier')}}">{{ __('Supplier') }}</a> >> {{ __('Import Suppliers') }}</h5>
      <div class="card-header-right">

      </div>
    </div>
    <div class="card-body p-0">
      <div class="col-sm-12">
        <div class="card-block pt-2">
          <button class="btn btn-outline-primary custom-btn-small" id="fileRequest"><i class="fa fa-download"></i>{{ __('Download Sample') }}</button>
          <hr>
          <p>{{ __('Your CSV data should be in the format below. The first line of your CSV file should be the column headers as in the table example. Also make sure that your file is UTF-8 to avoid unnecessary encoding problems.') }}</p>
          <span class="badge badge-info mb-10">{{ __('Note') }}</span> <small class="text-info">{{ __('Duplicate email rows wont be imported') }}</small>

          <div class="table-responsive">
            <table class="table table-bordered m-md-0">
              <thead>
                <tr>
                  <th>Name<span class="color_ff2d42">*</span></th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Tax Id</th>
                  <th>Street</th>
                  <th>City</th>
                  <th>State</th>
                  <th>Zipcode</th>
                  <th>Country</th>
                  <th>Currency<span class="color_ff2d42">*</span></th>
                  <th>Status<span class="color_ff2d42">*</span></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>John De</td>
                  <td>example@exmample.com</td>
                  <td>1235678</td>
                  <td>134234</td>
                  <td>Washingto</td>
                  <td>Washingto</td>
                  <td>WA 123</td>
                  <td>12345</td>
                  <td>United States</td>
                  <td>USD</td>
                  <td>Active</td>
                </tr>
              </tbody>
            </table>
          </div>
          <span class="badge badge-info mt-3">{{ __('Note') }}</span> <small class="text-info">{{ __('Required field is mendatory') }}</small>
          <br><br>

          <form action="{{ url('supplierimportcsv') }}" method="post" id="myform1" class="form-horizontal" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label pt-1">{{ __('Choose CSV File') }}
                <span class="text-danger">*</span>
              </label>
                <div class="custom-file col-md-8">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" name="csv_file" id="validatedCustomFile">
                    <label class="custom-file-label overflow_hidden" for="validatedCustomFile">{{ __('Upload csv...') }}</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-2 control-label"></label>
              <div class="col-md-8" id='note_txt_1'>
                <span class="badge badge-info note-style">{{ __('Note') }}!</span> <small class="text-info">{{ __('Allowed File Extensions: csv') }}</small>
              </div>
              <div class="col-md-8" id='note_txt_2'>
              </div>
            </div>

            <!-- /.box-body -->
            <div class="col-sm-8 px-0">
              <button class="btn btn-primary custom-btn-small" type="submit" id="submit">{{ __('Submit') }}</button>
              <a href="{{ url('supplier') }}" class="btn btn-danger custom-btn-small">{{ __('Cancel') }}</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
  <script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('public/dist/js/custom/supplier.min.js') }}"></script>
@endsection
