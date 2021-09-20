@extends('layouts.app')

@section('content')
  <!-- Main content -->
<div class="col-sm-12" id="import-item-container">
  <div class="card Recent-Users">
    <div class="card-header">      
      <h5><a href="{{ url('itemimport') }}"> {{ __('Items')  }} </a> >> {{ __('Import Items')  }}</h5>
      <div class="card-header-right">
          
      </div>
    </div>
    <div class="card-body p-0">
      <div class="col-sm-12">
        <div class="card-block pt-2">
          <button class="btn btn-outline-primary custom-btn-small" id="fileRequest"><i class="fa fa-download"></i>{{ __('Download Sample')  }}</button>
          <hr>
          <p>{{ __('Your CSV data should be in the format below. The first line of your CSV file should be the column headers as in the table example. Also make sure that your file is UTF-8 to avoid unnecessary encoding problems.')  }}</p>
          <small class="text-c-red">{{ __('Duplicate email rows wont be imported') }}</small>
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>{{ __('Item ID') }}</th>
                  <th>{{ __('Item Name') }}</th>
                  <th>{{ __('Category Name') }}</th>
                  <th>{{ __('Unit') }}</th>
                  <th>{{ __('Tax Type') }}</th>
                  <th>{{ __('Purchase Price') }}</th>
                  <th>{{ __('Retail Price') }}</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>HP-430</td>
                  <td>HP-Pavilion</td>
                  <td>Electronics</td>
                  <td>PC</td>
                  <td>Normal</td>
                  <td>35000</td>
                  <td>45000</td>
                </tr>
              </tbody>
            </table>
          </div><br><br>
          
          <form action="{{ url('itemimportcsv') }}" method="post" id="myform1" class="form-horizontal" enctype="multipart/form-data">
          {!! csrf_field() !!}
            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label pt-1">{{ __('Choose CSV File')  }}</label>
                <div class="custom-file col-md-8">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" name="item_image" id="validatedCustomFile">
                    <label class="custom-file-label overflow_hidden" for="validatedCustomFile">{{ __('Upload csv...') }}</label>
                  </div>                    
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-2 control-label"></label>
              <div class="col-md-8" id='note_txt_1'>
                <span class="badge badge-danger">{{ __('Note') }} !</span> {{ __('Allowed File Extensions: csv') }}
              </div>
              <div class="col-md-8" id='note_txt_2'>                      
              </div>
            </div>
          
            <!-- /.box-body -->
            <div class="col-sm-8 px-0">
              <button class="btn btn-primary custom-btn-small" type="submit" id="submit">{{  __('Submit')  }}</button>   
              <a href="{{ url('item') }}" class="btn btn-danger custom-btn-small">{{  __('Cancel')  }}</a>
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
<script src="{{ asset('public/dist/js/custom/item.min.js') }}"></script>
@endsection
