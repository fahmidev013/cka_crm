@extends('layouts.app')
@section('css')
{{-- Select2  --}}
  <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{asset('public/dist/plugins/lightbox/css/lightbox.min.css')}}">
@endsection
@section('content')
<div class="col-sm-12" id="team-member-profile-container">
  <div class="card">
    <div class="card-header">
      <h5><a href="{{ url('users') }}">{{ __('Users') }}</a> >> {{$userData->full_name}} >> {{ __('Profile') }}</h5>
      <div class="card-header-right">
        
      </div>
    </div>
    <div class="card-body p-0" id="no_shadow_on_card">
      @include('layouts.includes.user_menu')
      <div class="col-sm-12 m-t-20 form-tabs">
        <ul class="nav nav-tabs " id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active text-uppercase" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __('User Information') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-uppercase" id="profile-tab" data-toggle="tab" data-rel="{{$userData->id}}" href="#profile" role="tab" aria-controls="profile" aria-selected="false">{{ __('Update Password') }}</a>
          </li>
        </ul>
        <div class="col-sm-12 tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <form action='{{ url("user/team-member-update/$userData->id") }}' method="post" class="form-horizontal" id="userAdd" enctype="multipart/form-data">
              <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
              <input type="hidden" value="{{$userData->id}}" id="user-id" name="user_no">
              <div class="row">
                <div class="col-sm-8">
                  <div class="form-group row">
                    <label for="first_name" class="col-sm-2 col-form-label require pr-0">{{ __('First Name') }}
                      </label>
                      <div class="col-sm-10">
                          <input type="text" placeholder="{{ __('First Name') }}" class="form-control" id="fname" name="first_name" value="{{$userData->first_name}}">
                          <span id="val_fname" class="color_red"></span>
                      </div>
                  </div>
                  <div class="form-group row">
                    <label for="first_name" class="col-sm-2 col-form-label require pr-0">{{ __('Last Name') }}
                      </label>
                      <div class="col-sm-10">
                          <input type="text" placeholder="{{ __('Last Name') }}" class="form-control" id="lname" name="last_name" value="{{$userData->last_name}}">
                          <span id="val_fname" class="color_red"></span>
                      </div>
                  </div>
                  <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label require">{{ __('Email') }}</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="email" name="email" value="{{ old('email') ? old('email') : $userData->email }}" placeholder="{{ __('Email') }}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="phone" class="col-sm-2 col-form-label">{{ __('Phone') }}</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="phone" name="phone" value="{{$userData->phone}}" placeholder="{{ __('Phone') }}">
                      <span id="val_phone" class="color_red"></span>
                    </div>
                  </div>
                  @if(Helpers::has_permission(Auth::user()->id, 'edit_role'))
                  <div class="form-group row">
                    <label for="Role" class="col-sm-2 col-form-label require">{{ __('Role') }}</label>
                      <div class="col-sm-10">
                          <select class="form-control valdation_select select2" name="role_id" id="role_id">
                          <option value="">{{ __('Select One') }}</option>
                          @foreach ($roleData as $data)
                            <option value="{{ $data->id }}" <?=isset($data->id) && $data->id == $userData->role_id ? 'selected':""?> >{{$data->display_name}}</option>
                          @endforeach
                          </select>
                          <label for="role_id" class="error display_none" id="role_id-error">{{ __('This field is required.') }}</label>
                      </div>
                  </div>
                   <div class="form-group row">
                    <label for="Status" class="col-sm-2 col-form-label require">{{ __('Status') }}</label>
                      <div class="col-sm-10">
                          <select class="form-control valdation_select select2" name="status_id" id="status_id">
                            <option value="">{{ __('Select One') }}</option>
                            <option value="1" <?=isset($userData->is_active) && $userData->is_active == '1' ? 'selected':""?> >{{ __('Active') }}</option>
                            <option value="0" <?=isset($userData->is_active) && $userData->is_active == '0' ? 'selected':""?>>{{ __('Inactive') }}</option>
                          </select>
                          <label for="status_id" class="error display_none" id="status_id-error">{{ __('This field is required.') }}</label>
                      </div>
                  </div>
                  @endif
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
                  </div>

                  @if(!empty(getUserProfilePicture($userData->id, 0)))
                  <div class="col-sm-3">
                    <div class="form-group">
                      <div class="col-sm-9">
                        <div class="fixSize">
                          <a class="cursor_pointer" href='{{ getUserProfilePicture($userData->id, 0) }}'  data-lightbox="image-1"> <img class="profile-user-img img-responsive fixSize" src='{{ getUserProfilePicture($userData->id, 0) }}' alt="" class="img-thumbnail attachment-styles"></a>
                        </div>
                      </div>
                    </div>
                  </div>
                  @endif


              </div>
                <div class="row">
                  <label class="col-sm-3 control-label" for="department">{{ __('Department') }} </label>
                 
                <div class="row ml-18" id="department-parent-div">
                  <div class="col-sm-9 offset-sm-3">
                   @if($departments)
                        @foreach($departments as $department)
                          <div class="col-sm-4 d-inline-block" id="department-div">                         
                            <div class="form-group mb-0">
                              <div class="checkbox checkbox-success d-inline">
                                  <input <?= in_array( $department->id, $userDept) ? 'checked' : ' ' ?> type="checkbox" name="departments[]" value="{{ $department->id }}" id="checkbox-s-{{ $department->id }}">
                                  <label for="checkbox-s-{{ $department->id }}" class="cr">{{ $department->name }}</label>
                              </div>
                            </div>
                          </div> 
                        @endforeach
                      @endif      
                  </div>
                </div>
              </div> 
              <div class="col-sm-10 px-0 m-l-5">
                <button class="btn btn-primary custom-btn-small" type="submit" id="btnSubmit">{{ __('Update') }}</button>   
                <a href="{{ url('users') }}" class="btn btn-info custom-btn-small">{{ __('Cancel') }}</a>
              </div>
            </form>
          </div>

          <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="row">
              <div class="col-sm-12">
                <form action='{{url("change-member-password/$user_id")}}' class="form-horizontal" id="password-form" method="POST">
                  <input type="hidden" value="{{csrf_token()}}" name="_token" id="token2">
                  <input type="hidden" value="{{$userData->id}}" name="customer_id">

                  <div class="form-group row">
                    <label for="password" class="col-sm-2 text-left col-form-label">{{ __('Password') }}
                      <span class="color_red">*</span>
                    </label>                    
                    <div class="col-sm-6">
                      <input type="password" class="form-control" name="new_pass" id="password">
                      <span id="new_pass_error" class="color_red"></span>
                    </div>
                  </div>

                  <div class="form-group row mb-1">
                    <label for="password" class="col-sm-2 text-left col-form-label">{{ __('Confirm Password') }}<span class="color_red">*</span>
                    </label>
                    <div class="col-sm-6">
                      <input type="password" class="form-control" name="con_new_pass" id="password_confirmation">
                      <span id="con_new_pass_error" class="color_red"></span>
                    <br>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-10">
                      <div class="checkbox checkbox-primary checkbox-fill d-inline">
                        <input type="checkbox" class="form-control" name="sendMail" id="checkbox-p-fill-1">
                        <label for="checkbox-p-fill-1" class="cr">{{ __('Send Email to the Team Member') }}</label>
                      </div>
                    </div>
                  </div>
                 @if(Helpers::has_permission(Auth::user()->id, 'edit_customer'))
                  <div class="col-sm-10 px-0 m-l-5">
                    <button class="btn btn-primary custom-btn-small" type="submit" id="btnSubmit1">{{ __('Submit') }}</button>
                  </div>
                  @endif
                </form>
              </div>
            </div>
          </div> 
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
<script src="{{asset('public/dist/plugins/lightbox/js/lightbox.min.js')}}"></script>
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>

{!! translateValidationMessages() !!}
<script type="text/javascript">
  "use strict";
  var user_id = '{{ $userData->id }}';
</script>
<script src="{{ asset('public/dist/js/custom/user.min.js') }}"></script>
@endsection