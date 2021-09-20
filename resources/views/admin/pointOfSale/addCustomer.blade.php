<div class="modal-body-content" id="theModalBody">
{{-- Customer add modal --}}
<!-- [ Tabs ] start -->
<form action="{{ url('save-customer') }}" method="post" id="customerAdd" class="form-horizontal col-sm-12">
  <div class="row form-tabs">
    <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
    @if (!empty($type))
      <input type="hidden" value="{{ $type == 'pos' ? md5($type) : $type }}" name="type">
    @endif
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active text-uppercase" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __('Customer Information') }}</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-uppercase" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">{{ __('Shipping Address') }}</a>
      </li>
    </ul>
    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group row">
              <label for="first_name" class="col-sm-2 col-form-label">{{ __('First Name')  }}<span class="text-danger"> *</span></label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="first_name" name="first_name" value="{{old('first_name')}}" placeholder="{{ __('First Name')  }}">
              </div>
            </div>
            <div class="form-group row">
              <label for="last_name" class="col-sm-2 col-form-label">{{ __('Last Name')  }}<span class="text-danger"> *</span></label>
              <div class="col-sm-9">
                  <input type="text" class="form-control" id="last_name" name="last_name" value="{{old('last_name')}}" placeholder="{{ __('Last Name')  }}">
              </div>
            </div>
            <div class="form-group row">
              <label for="email" class="col-sm-2 col-form-label">{{ __('Email')  }}</label>
              <div class="col-sm-9">
                  <input type="email" class="form-control" id="email" name="email" placeholder="{{ __('Email')  }}" value="{{ old('email') }}">
                  <label id="email-error" class="error" for="email"></label>
              </div>
            </div>
            <div class="form-group row mt-2">
              <label for="currency" class="col-sm-2 col-form-label">{{ __('Currency')  }}<span class="text-danger"> *</span></label>
              <div class="col-sm-9">
                <select class="form-control select2-dropdown" id="currency" name="currency_id">
                  @foreach ($currencies as $data)
                  <option value="{{$data->id}}">{{$data->name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-sm-2"></div>
              <div class="col-sm-9">
                <label id="currency-error" class="error display_none" for="currency">{{ __('This field is required.') }}.</label>
              </div>
            </div>
            <div class="form-group row">
              <label for="phone" class="col-sm-2 col-form-label">{{ __('Phone') }}</label>
              <div class="col-sm-9">
                  <input type="text" class="form-control" value="{{ old('phone')}}" id="phone" name="phone" placeholder="{{ __('Phone') }}">
              </div>
            </div>
            <div class="form-group row">
              <label for="tax_id" class="col-sm-2 col-form-label">{{__('Tax Id') }}</label>
              <div class="col-sm-9">
                  <input type="text" class="form-control" value="{{ old('tax_id')}}" id="tax_id" name="tax_id" placeholder="{{__('Tax Id') }}">
              </div>
            </div>
            <div class="form-group row">
              <label for="bill_street" class="col-sm-2 col-form-label">{{ __('Street')  }}</label>
              <div class="col-sm-9">
                  <input type="text" class="form-control" value="{{ old('bill_street')}}" id="bill_street" name="bill_street" placeholder="{{ __('Billing Street') }}">
              </div>
            </div>
            <div class="form-group row">
              <label for="bill_city" class="col-sm-2 col-form-label">{{ __('City')  }}</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" value="{{ old('bill_city')}}" id="bill_city" name="bill_city" placeholder="{{ __('Billing City') }}">
              </div>
            </div>
            <div class="form-group row">
              <label for="bill_state" class="col-sm-2 col-form-label">{{ __('State')  }}</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" value="{{ old('bill_state')}}" id="bill_state" name="bill_state" placeholder="{{ __('Billing State') }}">
              </div>
            </div>
            <div class="form-group row">
              <label for="bill_zipCode" class="col-sm-2 col-form-label">{{ __('Zip Code')  }}</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" value="{{ old('bill_zipCode')}}" id="bill_zipCode" name="bill_zipCode" placeholder="{{ __('Billing Zip Code') }}">
              </div>
            </div>
            <div class="form-group row">
              <label for="bill_country_id" class="col-sm-2 col-form-label">{{ __('Country')  }}</label>
              <div class="col-sm-9">
                <select class="form-control select2-dropdown" id="bill_country_id" name="bill_country_id">
                  <option value="">{{ __('Select One')  }}</option>
                @foreach ($countries as $data)
                  <option value="{{ $data->id }}">{{ $data->name }}</option>
                @endforeach
                </select>
              </div>
              <div class="col-sm-2"></div>
              <div class="col-sm-9">
                <label id="bill_country_id-error" class="error display_none" for="bill_country_id">{{ __('This field is required.') }}</label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group row">
              <div class="col-sm-11"><span id="copy" data-toggle="tooltip" title="{{ __('Copy address from customer information') }}" class="badge badge-pill  theme-bg2 text-white float-right">{{ __('Copy Address') }}</span></div>
            </div>
            <div class="form-group row">
              <label for="ship_street" class="col-sm-2 col-form-label">{{ __('Street')  }}</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="ship_street" name="ship_street" value="{{old('ship_street')}}" placeholder="{{ __('Street')  }}">
              </div>
            </div>
            <div class="form-group row">
              <label for="ship_city" class="col-sm-2 col-form-label">{{ __('City')  }}</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="ship_city" name="ship_city" value="{{old('ship_city')}}" placeholder="{{ __('City')  }}">
              </div>
            </div>
            <div class="form-group row">
              <label for="ship_state" class="col-sm-2 col-form-label">{{ __('State')  }}</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="ship_state" name="ship_state" value="{{old('ship_state')}}" placeholder="{{ __('State')  }}">
              </div>
            </div>
            <div class="form-group row">
              <label for="ship_zipCode" class="col-sm-2 col-form-label">{{ __('Zip Code')  }}</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="ship_zipCode" name="ship_zipCode" value="{{old('ship_zipCode')}}" placeholder="{{ __('Zip Code')  }}">
              </div>
            </div>
            <div class="form-group row">
                <label for="ship_country_id" class="col-sm-2 col-form-label">{{ __('Country')  }}</label>
                <div class="col-sm-9">
                  <select class="form-control select2-dropdown" id="ship_country_id" name="ship_country_id">
                    <option value="">{{ __('Select One')  }}</option>
                    @foreach ($countries as $data)
                      <option value="{{$data->id}}">{{$data->name}}</option>
                    @endforeach
                  </select>
                  <label id="ship_country_id-error" class="error display_none" for="ship_country_id">{{ __('This field is required.') }}</label>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
<!-- [ Tabs ] end -->
{{-- Customer add modal end --}}
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/pos-modals.min.js') }}"></script>
</div>