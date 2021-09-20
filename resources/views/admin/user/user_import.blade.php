@extends('layouts.app')
@section('css')
@endsection
@section('content')
  <!-- Main content -->
<div class="col-sm-12" id="user-import-container">
  <div class="card Recent-Users">
    <div class="card-header">      
      <h5><a href="{{ url('users') }}">{{ __('Team Member') }}</a> >> {{ __('Import Team Member') }}</h5>
      <div class="card-header-right">
          
      </div>
    </div>
    <div class="card-body p-0">
      <div class="col-sm-12">
        <div class="card-block pt-2">
          <button class="btn btn-outline-primary custom-btn-small" id="fileRequest"><i class="fa fa-download"></i>{{ __('Download Sample') }}</button>
          <hr>
          <p>{{ __('Your CSV data should be in the format below. The first line of your CSV file should be the column headers as in the table example. If an user does not have any role, admin role would be added by default. Also make sure that your file is UTF-8 to avoid unnecessary encoding problems.') }}</p>
          <span class="badge badge-info mb-10">{{ __('Note') }}</span> <small class="text-info">&nbsp;{{ __('Duplicate email rows and team members with unmatched roles would not be imported') }} </small>

          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>{{ __('First Name') }}<span class="text-danger">*</span></th>
                  <th>{{ __('Last Name') }}<span class="text-danger">*</span></th>
                  <th>{{ __('Email') }}<span class="text-danger">*</span></th>
                  <th>{{ __('Password') }}<span class="text-danger">*</span></th>
                  <th>{{ __('Phone') }}</th>
                  <th>{{ __('Role') }}<span class="text-danger">*</span></th>
                  <th>{{ __('Status') }}<span class="text-danger">*</span></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Michel</td>
                  <td>Anam</td>
                  <td>anam@test.com</td>
                  <td>123456789</td>
                  <td>01711223344</td>
                  <td>Admin</td>
                  <td>Active</td>
                </tr>
              </tbody>
            </table>
            <span class="badge badge-info">{{ __('Note') }}</span> <small class="text-info">{{ __('Required field is mendatory') }}</small>
          </div><br>
          
          <form action="{{ url('user-import-csv') }}" method="post" id="myform1" class="form-horizontal" enctype="multipart/form-data">
            {!! csrf_field() !!}
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label pt-1">{{ __('Choose CSV File') }}
                  <span class="text-danger">*</span>
                </label>
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
          
            <!-- /.box-body -->
            <div class="col-sm-8 px-0 mt-3">
              <button class="btn btn-primary custom-btn-small" type="submit" id="submit">{{ __('Submit') }}</button>   
              <a href="{{ url('users') }}" class="btn btn-danger custom-btn-small">{{ __('Cancel') }}</a>
            </div>
            <!-- /.box-footer -->
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<!-- notification Js -->
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/user.min.js') }}"></script>
@endsection
