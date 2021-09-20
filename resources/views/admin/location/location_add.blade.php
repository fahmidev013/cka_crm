@extends('layouts.app')
@section('content')
  <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">


<!-- Main content -->
  <div class="col-sm-12" id="locationAdd-settings-container">
    <div class="row">
      <div class="col-sm-3">
        @include('layouts.includes.company_menu')
      </div>
      <div class="col-sm-9">
        <div class="card card-info">
          <div class="card-header">
            <h5><a href="{{ url('company/setting') }}">{{ __('Company Setting')  }}</a> >> {{ __('New Location')  }}</h5>
            <div class="card-header-right">
              
            </div>
          </div>
          <div class="card-body">
            <form action="{{ url('save-location') }}" method="post" id="myform1" class="form-horizontal">
                <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                <div class="form-group row p-t-10">
                  <label class="col-sm-3 control-label " for="inputEmail3">
                    {{ __('Location Name')  }}
                    <span class="text-danger"> *</span>
                  </label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ __('Location Name')  }}" class="form-control valdation_check" id="name" name="location_name">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label" for="inputEmail3">
                    {{ __('Location Code')  }}
                    <span class="text-danger"> *</span>
                  </label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ __('Location Code')  }}" class="form-control valdation_check" id="loc_code" name="loc_code">
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-3 control-label " for="inputEmail3">
                    {{ __('Delivery Address')  }}
                    <span class="text-danger"> *</span>
                  </label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ __('Delivery Address')  }}" id="address" class="form-control valdation_check" name="delivery_address">
                  </div>
                </div>

                <div class="form-group row">
                <label class="col-sm-3 control-label" for="inputEmail3">
                  {{ __('Default Location')  }}
                  <span class="text-danger"> *</span>
                </label>

                  <div class="col-sm-6">
                    <select class="form-control js-example-basic-single" name="default" id="default">
                      <option value="0">{{ __('No') }}</option>
                      <option value="1">{{ __('Yes') }}</option>
                    </select>
                  </div>
              </div>

                <div class="form-group row">
                  <label class="col-sm-3 control-label " for="inputEmail3">
                    {{ __('Phone')  }}
                  </label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ __('Phone')  }}" class="form-control" name="phone">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label " for="inputEmail3">
                    {{ __('Fax')  }}
                  </label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ __('Fax')  }}" class="form-control" name="fax">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label " for="inputEmail3">
                    {{ __('Email')  }}
                  </label>
                  <div class="col-sm-6">
                  <input type="text" placeholder="{{ __('Email')  }}" class="form-control" name="email" value="{{ old('email') }}">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label " for="inputEmail3">
                    {{ __('Contact')  }}
                  </label>
                  <div class="col-sm-6">
                    <input type="text" placeholder="{{ __('Contact')  }}" class="form-control" name="contact">
                  </div>
                </div>
                 <div class="form-group row">
                <label class="col-sm-3 control-label" for="inputEmail3">{{ __('Status') }}
                  <span class="text-danger"> *</span>
                </label>

                  <div class="col-sm-6">
                    <select class="form-control js-example-basic-single" name="status" id="status">
                      <option value="1">{{ __('Active') }}</option>
                      <option value="0">{{ __('Inactive') }}</option>
                    </select>
                  </div>
              </div>
              
              <div class="form-group row mt-1">
                <label for="btn_save" class="col-sm-0 ml-3 control-label"></label>
                  <button type="submit" class="btn btn-primary custom-btn-small float-left">{{ __('Submit')  }}</button>
              </div>
               
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection

@section('js')
  <script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
  <script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
  {!! translateValidationMessages() !!}
  <script src="{{ asset('public/dist/js/custom/settings.min.js') }}"></script>
@endsection