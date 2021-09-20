@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content" id="change-password-container">
      <!-- Default box -->
        <div class="row">
          <div class="col-md-offset-2 col-md-8">
            <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">{{ __('Change Password') }}</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action='{{ url("change-password") }}' method="post" id="myform1" class="form-horizontal">
            {!! csrf_field() !!}
              <div class="box-body">
                
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ __('Old password') }}</label>

                  <div class="col-sm-7">
                    <input type="password" class="form-control valdation_check"  name="old_pass" id="name">
                    <span id="val_name" class="color_red"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ __('New Password') }}</label>

                  <div class="col-sm-7">
                    <input type="password" class="form-control valdation_check" id="n_pass" name="new_pass">
                    <span id="val_n_pass" class="color_red"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ __('Confirm Password') }}</label>

                  <div class="col-sm-7">
                    <input type="password" class="form-control valdation_check" id="r_pass" name="r_pass">
                    <span id="val_r_pass" class="color_red"></span>
                  </div>
                </div>
                
            
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                
                <button class="btn btn-primary btn-flat pull-right" type="submit">{{ __('Submit') }}</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
          </div>
        </div>
    </section>
@endsection

@section('js')
    <script type="{{ asset('public/dist/js/custom/user.min.js') }}"></script>
@endsection