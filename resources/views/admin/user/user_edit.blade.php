@extends('layouts.app')


@section('content')

    <!-- Main content -->
    <section class="content" id="user-edit-container">

      <div class="row">
        <div class="col-md-3">
          @include('layouts.includes.company_menu')
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-info">
             <div class="box-header with-border">
              <h3 class="box-title">{{ __('User Edit') }}</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action='{{ url("update-user/$userData->id") }}' method="post" id="myform1" class="form-horizontal">
            {!! csrf_field() !!}
              <div class="box-body">
                
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">{{ __('Full Name') }}</label>

                  <div class="col-sm-10">
                    <input type="text" value="{{$userData->full_name}}" class="form-control valdation_check" id="fname" name="full_name">
                    <span id="val_fname" class="color_red"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">{{ __('Email') }}</label>

                  <div class="col-sm-10">
                    <input type="email" value="{{$userData->email}}" class="form-control valdation_check" id="em" name="email" readonly>
                    <span id="val_em" class="color_red"></span>
                  </div>
                </div>
                
                
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">{{ __('Phone') }}</label>

                  <div class="col-sm-10">
                    <input type="text" value="{{$userData->phone}}" class="form-control valdation_check" id="name" name="phone">
                    <span id="val_name" class="color_red"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">{{ __('Role') }}</label>

                  <div class="col-sm-10">
                    <select class="form-control" name="role_id" required>
                    <option value="">{{ __('Select One') }}</option>
                    @foreach ($roleData as $data)
                      <option value="{{$data->id}}" <?=isset($data->id) && $data->id == $userData->role_id ? 'selected':""?> >{{$data->name}}</option>
                    @endforeach
                    </select>
                  </div>
                </div>
            
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <a href="{{ url('users') }}" class="btn btn-default">{{ __('Cancel') }}</a>
                <button class="btn btn-info pull-right" type="submit">{{ __('Update') }}</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
@endsection

@section('js')
    <script src="{{ asset('public/dist/js/custom/user.min.js') }}"></script>
@endsection