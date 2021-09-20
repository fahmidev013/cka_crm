@extends('layouts.app')
@section('css')
{{-- Select2  --}}
  <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection
@section('content')
<!-- Main content -->
<div class="col-sm-12" id="locationEdit-settings-container">
  <div class="row">
    <div class="col-md-3">
      @include('layouts.includes.company_menu')
    </div>
    <div class="col-md-9">
      <div class="card card-info">
        <div class="card-header">
          <h5><a href="{{ url('company/setting') }}">{{ __('Company Setting')  }}</a> >> {{ __('Edit Location')  }}</h5>
          <div class="card-header-right">
            
          </div>
        </div>
        <div class="card-body">
          <div class="p-l-15">
            <form action='{{ url("update-location/$locationData->id") }}' method="post" id="myform1" class="form-horizontal">
              <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
              <div class="form-group row p-t-10">
                <label class="col-sm-3 control-label text-right" for="inputEmail3">
                  {{ __('Location Name')  }}
                  <span class="text-danger"> *</span>
                </label>
                <div class="col-sm-6">
                  <input type="text" placeholder="{{ __('Location Name')  }}" class="form-control valdation_check" id="name" name="location_name" value="{{$locationData->name}}">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 control-label text-right" for="inputEmail3">
                  {{ __('Location Code')  }}
                  <span class="text-danger"> *</span>
                </label>
                <div class="col-sm-6">
                  <input type="text" placeholder="{{ __('Location Code')  }}" class="form-control valdation_check" id="loc_code" location-data = "{{$locationData->id}}" name="loc_code" value="{{$locationData->code}}">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 control-label" for="inputEmail3">
                  {{ __('Delivery Address')  }}
                  <span class="text-danger"> *</span>
                </label>
                <div class="col-sm-6">
                  <input type="text" placeholder="{{ __('Delivery Address')  }}" id="address" class="form-control valdation_check" name="delivery_address" value="{{$locationData->delivery_address}}">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 control-label" for="inputEmail3">
                  {{ __('Default Location')  }}
                  <span class="text-danger"> *</span>
                </label>

                  <div class="col-sm-6">
                    <select class="form-control js-example-basic-single" name="default" id="nn">
                      <option value="1" <?=isset($locationData->is_default) && $locationData->is_default == 1 ? 'selected':""?> >{{ __('Yes') }}</option>
                      <option value="0"  <?=isset($locationData->is_default) && $locationData->is_default == 0 ? 'selected':""?> >{{ __('No') }}</option>
                    </select>
                  </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 control-label text-right" for="inputEmail3">
                  {{ __('Phone')  }}
                </label>
                <div class="col-sm-6">
                  <input type="text" placeholder="{{ __('Phone')  }}" class="form-control" name="phone" id="phone" value="{{$locationData->phone}}">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 control-label text-right" for="inputEmail3">
                  {{ __('Fax')  }}
                </label>
                <div class="col-sm-6">
                  <input type="text" placeholder="{{ __('Fax')  }}" class="form-control" name="fax" value="{{$locationData->fax}}">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 control-label text-right" for="inputEmail3">
                  {{ __('Email')  }}
                </label>
                <div class="col-sm-6">
                  <input type="text" placeholder="{{ __('Email')  }}" class="form-control" name="email" value="{{ old('email') ? old('email') : $locationData->email}}">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 control-label text-right" for="inputEmail3">
                  {{ __('Contact')  }}
                </label>
                <div class="col-sm-6">
                  <input type="text" placeholder="{{ __('Contact')  }}" class="form-control" name="contact" value="{{$locationData->contact}}">
                </div>
              </div>

                <div class="form-group row">
                <label class="col-sm-3 control-label text-right" for="inputEmail3">{{ __('Status') }}

                  <span class="text-danger"> *</span>
                </label>

                  <div class="col-sm-6">
                    <select class="form-control js-example-basic-single" name="status" id="status">
                      <option value="1" {{ isset($locationData->is_active) && $locationData->is_active == 1 ? 'selected':""}} >{{ __('Active') }}
                      </option>
                      <option value="0" {{ isset($locationData->is_active) && $locationData->is_active == 0 ? 'selected':""}} >{{ __('Inactive') }}
                      </option>
                    </select>
                  </div>
                </div>

              <div class="form-group row mt-1">
                <label for="btn_save" class="col-sm-0 ml-3 control-label"></label>
                  <button type="submit" class="btn btn-primary custom-btn-small float-left">{{ __('Submit')  }}</button>
                  <a href="{{ url('location') }}" class="btn btn-danger custom-btn-small float-left">{{ __('Cancel')  }}</a>
              </div>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>


@include('layouts.includes.message_boxes')
@endsection

@section('js')
  <script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
  <script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
  {!! translateValidationMessages() !!}
  <script src="{{ asset('public/dist/js/custom/settings.min.js') }}"></script>
@endsection
