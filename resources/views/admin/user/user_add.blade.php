@extends('layouts.app')
@section('css')
{{-- Select2  --}}
   <!--custom css-->
  <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">

@endsection

@section('content')
  <!-- Main content -->
  <div class="col-sm-12" id="user-add-container">
    <div class="card">
      <div class="card-header">
        <h5> <a href="{{url('create-user')}}">{{ __('User') }}</a> >>{{ __('Create User') }}</h5>
        <div class="card-header-right">
            
        </div>
      </div>
      <div class="card-block table-border-style">
        <div class="row form-tabs">
          <form action="{{ url('save-user') }}" method="post" id="userAdd" class="form-horizontal col-sm-12" enctype="multipart/form-data">
            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item">
                  <a class="nav-link active text-uppercase" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __('User Information') }}</a>
              </li>
            </ul>
            <div class="col-sm-12 tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="row">
                  <div class="col-sm-9">
                    <div class="form-group row">
                      <label for="first_name" class="col-sm-2 control-label require">{{ __('First Name') }}
                      </label>
                      <div class="col-sm-10">
                          <input type="text" placeholder="{{ __('First Name') }}" class="form-control" id="fname" name="first_name">
                      </div>                      
                    </div>
                    <div class="form-group row">
                      <label for="last_name" class="col-sm-2 control-label require">{{ __('Last Name') }}
                      </label>
                      <div class="col-sm-10">
                          <input type="text" placeholder="{{ __('Last Name') }}" class="form-control" id="lname" name="last_name">
                      </div>                      
                    </div>
                    <div class="form-group row">
                      <label for="email" class="col-sm-2 control-label require">{{ __('Email') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="email" name="email" value="{{old('email')}}" placeholder="{{__('Email')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                      <label for="password" class="col-sm-2 control-label require">{{ __('Password') }}</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="password" name="password"  placeholder="{{ __('Password') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                      <label for="phone" class="col-sm-2 control-label">{{ __('Phone') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="phone" name="phone" value="{{old('phone')}}" placeholder="{{ __('Phone') }}">
                            <span id="val_name" class="color_red"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                      <label for="Role" class="col-sm-2 control-label require">{{ __('Role') }}</label>
                        <div class="col-sm-10">
                            <select class="form-control js-example-basic-single" name="role_id" id="role_id">
                              <option value="">{{ __('Select One') }}</option>
                              @foreach ($roleData as $data)
                                <option value="{{$data->id}}" >{{$data->display_name}}</option>
                              @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2"></div>
                        <div class="ml-3">
                          <label for="role_id" class="error display_none" id="role_id-error">{{ __('This field is required.') }}</label>
                        </div>
                    </div>

                    <div class="form-group row">
                      <label for="Status" class="col-sm-2 control-label require">{{ __('Status') }}</label>
                        <div class="col-sm-10">
                            <select class="form-control js-example-basic-single" name="status_id" id="status_id">
                              <option value="">{{ __('Select One') }}</option>
                              <option value="1" >{{ __('Active') }}</option>
                              <option value="0" >{{ __('Inactive') }}</option>
                            </select>
                        </div>
                        <div class="col-sm-2"></div>
                        <div class="ml-3">
                          <label for="status_id" class="error display_none" id="status_id-error">{{ __('This field is required.') }}</label>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 control-label">{{ __('Attachment') }}</label>
                      <div class="col-sm-10">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" name="attachment" id="validatedCustomFile">
                          <label class="custom-file-label overflow_hidden" for="validatedCustomFile">{{ __('Upload file...') }}</label>
                        </div>                    
                      </div>
                    </div>

                    <div class="form-group row" id="divNote">
                      <label class="col-sm-2 control-label"></label>
                      <div class="col-sm-10" id='note_txt_1'>
                        <span class="badge badge-danger">{{ __('Note') }}!</span> {{ __('Allowed File Extensions: jpg, png, gif, bmp') }}
                      </div>
                      <div class="col-md-9" id='note_txt_2'>                      
                      </div>
                    </div>

                    <div class="row">
                      <label class="col-sm-6 control-label" for="department">{{ __('Department') }} </label>
                    </div>  
                    <div class="row ml-16">
                      <div class="col-sm-12 offset-sm-3">
                       @if($departments)
                          @foreach($departments as $department)
                            <div class="col-sm-5 d-inline-block">
                              <div class="form-group mb-0">
                                <div class="checkbox checkbox-success d-inline">
                                    <input type="checkbox" name="departments[]" id="checkbox-s-{{ $department->id }}" value="{{ $department->id }}">
                                    <label for="checkbox-s-{{ $department->id }}" class="cr">{{ $department->name }}</label>
                                </div>
                              </div>
                            </div> 
                          @endforeach
                        @endif      
                      </div>
                    </div>
                    <div class="form-group row mt-3">
                      <label class="col-sm-2 control-label"></label>
                      <div class="col-sm-10">
                        <div class="checkbox checkbox-primary checkbox-fill d-inline">
                          <input type="checkbox" class="form-control" name="sendMail" id="checkbox-p-fill-1">
                          <label for="checkbox-p-fill-1" class="cr">{{ __('Send Email to the Team Member') }}</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-sm-10 px-0 m-l-5">
                <button class="btn btn-primary custom-btn-small" type="submit" id="btnSubmit"><i class="comment_spinner spinner fa fa-spinner fa-spin custom-btn-small display_none"></i><span id="spinnerText">{{ __('Submit') }}</span></button>   
                <a href="{{ url('users') }}" class="btn btn-danger custom-btn-small">{{ __('Cancel') }}</a>
              </div>
            </div>            
          </div>
        </div>            
      </form>
    </div>
  </div>

@endsection

@section('js')
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/user.min.js') }}"></script>
@endsection