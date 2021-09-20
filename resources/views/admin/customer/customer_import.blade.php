@extends('layouts.app')
@section('css')
@endsection

@section('content')
<!-- Main content -->
<div class="col-sm-12" id="import-customer-container">
  <div class="card Recent-Users">
    <div class="card-header">      
      <h5><a href="{{ url('customer/list') }}">{{ __('Customers') }}</a> >> {{ __('Import Customer') }}</h5>
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
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th class="star-sign">{{ __('First Name') }}<span class="color_ff2d42">*</span></th>
                  <th class="star-sign">{{ __('Last Name') }}<span class="color_ff2d42">*</span></th>
                  <th>{{ __('Email') }}</th>
                  <th>{{ __('Phone') }}</th>
                  <th>{{ __('Tax Id') }}</th>
                  <th>{{ __('Billing Street') }}</th>
                  <th>{{ __('Billing City') }}</th>
                  <th>{{ __('Billing State') }}</th>
                  <th>{{ __('Billing Zipcode') }}</th>
                  <th>{{ __('Billing Country') }}</th>
                  
                  <th>{{ __('Shipping Street') }}</th>
                  <th>{{ __('Shipping City') }}</th>
                  <th>{{ __('Shipping State') }}</th>
                  <th>{{ __('Shipping Zipcode') }}</th>
                  <th>{{ __('Shipping Country') }}</th>
                  <th class="star-sign">{{ __('Currency') }}<span class="color_ff2d42">*</span></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Michel </td>
                  <td>Anam</td>
                  <td>anam@test.com</td>
                  <td>0136664981</td>
                  <td>4523</td>
                  
                  <td>23 Wagton</td>
                  <td>Washington</td>
                  <td>Washington DC</td>
                  <td>21252</td>
                  <td>United States</td>

                  <td>2430 LA</td>
                  <td>Los Angels Demo</td>
                  <td>Los Angels</td>
                  <td>1234</td> 
                  <td>United States</td>
                  <td>USD</td>
                </tr>
              </tbody>
            </table>
          </div>
          <span class="badge badge-info mt-3">{{ __('Note') }}</span> <small class="text-info">{{ __('Required field is mendatory') }}</small>

          <br><br>
          
          <form action="{{ url('customerimportcsv') }}" method="post" id="myform1" class="form-horizontal" enctype="multipart/form-data">
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
                <label class="col-md-2 control-label note"></label>
                <div class="col-md-8" id='note_txt_1'>
                  <span class="badge badge-info note-style">{{ __('Note') }}</span> <small class="text-info">{{ __('Allowed File Extensions: csv') }}</small>
                </div>
                <div class="col-md-8" id='note_txt_2'>                      
                </div>
              </div>
          
            <!-- /.box-body -->
            <div class="col-sm-8 px-0 mt-3">
              <button class="btn btn-primary custom-btn-small" type="submit" id="submit">{{ __('Submit') }}</button>   
              <a href="{{ url('customer/list') }}" class="btn btn-danger custom-btn-small">{{ __('Cancel') }}</a>
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
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/customer.min.js') }}"></script>
@endsection
