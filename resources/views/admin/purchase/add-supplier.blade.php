<div class="modal-body-content">
  <div class="row form-tabs">
    <form action="{{ url('save-supplier') }}" method="post" id="addSupplier" class="form-horizontal col-sm-12">
      <input type="hidden" value="{{ md5('add-supplier') }}" name="type">
      <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active text-uppercase" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __('Supplier Information')  }}</a>
        </li>
      </ul>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group row">
                <label for="first_name" class="col-sm-3 col-form-label">{{ __('Supplier Name')  }} <span class="text-danger"> *</span>
                </label>
                <div class="col-sm-8">
                    <input type="text" placeholder="{{ __('Full Name') }}" class="form-control valdation_check" id="supp_name" name="supp_name">
                    <span id="val_fname" class="color_red"></span>
                </div>
              </div>
              <div class="form-group row">
                <label for="first_name" class="col-sm-3 col-form-label">{{ __('Email')  }}
                </label>
                <div class="col-sm-8">
                <input type="text" placeholder="{{ __('Email')  }}" class="form-control" id="email" name="email" value="{{ old('email') }}">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="currency_id">{{ __('Currency')  }}<span class="text-danger"> *</span></label>
                <div class="col-sm-8">
                  <select class="js-example-basic-single form-control" name="currency_id" id="currency_id">
                    <option value="">{{ __('Select One')  }}</option>
                    @foreach ($currencies as $data)
                      <option value="{{ $data->id }}">{{ $data->name }}</option>
                    @endforeach
                  </select>
                  <label id="currency_id-error" class="error display_none" for="currency_id"></label>
                </div>
              </div>
              <div class="form-group row">                    
                <label class="col-sm-3 col-form-label" for="supp_phone">{{ __('Phone')  }}</label>
                <div class="col-sm-8">
                  <input type="text" placeholder="{{ __('Phone')  }}" class="form-control" id="contact" name="contact">                 
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="supp_tax">{{ __('Tax Id')  }}</label>
                <div class="col-sm-8">
                  <input type="text" placeholder="{{ __('Tax Id')  }}" class="form-control" id="tax_id" name="tax_id">
                 
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="supp_street">{{ __('Street')  }}</label>
                <div class="col-sm-8">
                  <input type="text" placeholder="{{ __('Street')  }}" class="form-control" id="street" name="street">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="supp_city">{{ __('City')  }}</label>
                <div class="col-sm-8">
                  <input type="text" placeholder="{{ __('City')  }}" class="form-control" id="city" name="city">
                </div> 
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="supp_state">{{ __('State')  }}</label>
                <div class="col-sm-8">
                  <input type="text" placeholder="{{ __('State')  }}" class="form-control" id="state" name="state">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="supp_zipcode">{{ __('Zip Code')  }}</label>
                <div class="col-sm-8">
                  <input type="text" placeholder="{{ __('Zip Code')  }}" class="form-control" id="zipcode" name="zipcode">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="supp_country">{{ __('Country')  }}</label>
                <div class="col-sm-8">
                  <select class="form-control js-example-basic-single" name="country" id="country">
                  <option value="">{{ __('Select One')  }}</option>
                  @foreach ($countries as $data)
                    <option value="{{$data->id}}" >{{ $data->name }}</option>
                  @endforeach
                  </select>
                  <label id="country-error" class="error" for="country"></label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/addsupplier.min.js') }}"></script>
