@extends('layouts.app')
@section('css')
  {{-- Select2  --}}
  <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
  <!-- Main content -->
  <div class="col-sm-12" id="company-settings-container">
    <div class="row">
      <div class="col-sm-3">
        @include('layouts.includes.company_menu')
      </div>
      <div class="col-sm-9">
        <div class="card card-info">
          <div class="card-header">
            <h5><a href="{{ url('company/setting') }}">{{ __('Company Settings') }} </a></h5>
            <div class="card-header-right">
              
            </div>
          </div>
          <div class="card-body">
            <form action="{{ url('company/setting/save') }}" method="post" id="settingForm" class="form-horizontal" enctype="multipart/form-data">
              <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
              <div class="form-group row p-t-10">
                <label class="col-sm-3 control-label " for="inputEmail3">
                  {{ __('Name') }}
                  <span class="text-danger"> *</span>
                </label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" name="company_name" id="company_name" value="{{isset($companyData['company']['company_name']) ? $companyData['company']['company_name'] : ''}}" >
                </div>
              </div>

              <div class="form-group row">
                <label id='siteshortlabel' class="col-sm-3 control-label" for="inputEmail3">
                  {{ __('Site Short Name') }}
                </label>

                <div class="col-sm-7">
                  <input type="text" readonly="readonly" name="site_short_name" value="{{isset($companyData['company']['site_short_name']) ? $companyData['company']['site_short_name'] : ''}}" id="site_short_name" class="form-control">
                </div>
              </div>

              <div class="form-group row marginTop">
                <label class="col-sm-3 control-label " for="inputEmail3">
                  {{ __('Email') }}
                  <span class="text-danger"> *</span>
                </label>

                <div class="col-sm-7">
                  <input type="text" class="form-control" name="company_email" id="company_email" value="{{isset($companyData['company']['company_email']) ? $companyData['company']['company_email'] : ''}}" >
                </div>
              </div>
              
              <div class="form-group row">
                <label class="col-sm-3 control-label " for="inputEmail3">
                  {{ __('Phone') }}
                  <span class="text-danger"> *</span>
                </label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" name="company_phone" id="company_phone" value="{{isset($companyData['company']['company_phone']) ? $companyData['company']['company_phone'] : ''}}" >
                </div>
              </div>
                
              <div class="form-group row">
                  <label class="col-sm-3 control-label " for="inputEmail3">
                    {{ __('Tax Id') }}
                  </label>
                  <div class="col-sm-7">
                    <input type="text" value="{{isset($companyData['company']['company_gstin']) ? $companyData['company']['company_gstin'] : ''}}" class="form-control" name="company_gstin">
                  </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 control-label " for="inputEmail3">
                  {{ __('Street') }}
                </label>

                <div class="col-sm-7">
                  <input type="text" class="form-control" name="company_street" id="company_street" value="{{isset($companyData['company']['company_street']) ? $companyData['company']['company_street'] : ''}}" >
                </div>
              </div>
                
              <div class="form-group row">
                <label class="col-sm-3 control-label " for="inputEmail3">
                  {{ __('City') }}
                  <span class="text-danger"> *</span>
                </label>

                <div class="col-sm-7">
                  <input type="text" class="form-control" name="company_city" id="company_city" value="{{isset($companyData['company']['company_city']) ? $companyData['company']['company_city'] : ''}}" >
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-3 control-label " for="inputEmail3">
                  {{ __('State') }}
                  <span class="text-danger"> *</span>
                </label>

                <div class="col-sm-7">
                  <input type="text" class="form-control" name="company_state" id="company_state" value="{{isset($companyData['company']['company_state']) ? $companyData['company']['company_state'] : ''}}">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-3 control-label " for="inputEmail3">
                  {{ __('Zip code') }}
                </label>

                <div class="col-sm-7">
                  <input type="text" class="form-control" name="company_zip_code" id="company_zip_code" value="{{isset($companyData['company']['company_zip_code']) ? $companyData['company']['company_zip_code'] : ''}}" >
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-3 control-label " for="inputEmail3">
                  {{ __('Country') }}
                  <span class="text-danger"> *</span>
                </label>

                <div class="col-sm-7">
                  <select class="form-control js-example-basic-single" name="company_country" id="company_country" >
                  @foreach ($countries as $data)
                    <option value="{{$data->id}}" <?=isset($companyData['company']['company_country']) && $companyData['company']['company_country'] == $data->id ? 'selected':""?> >{{$data->name}}</option>
                  @endforeach
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label id="default-language" class="col-sm-3 control-label " for="inputEmail3">{{ __('Default language') }}</label>

                <div class="col-sm-7">
                  <select name="dflt_lang" id="dflt_lang" class="form-control js-example-basic-single" >
                    @foreach($languages as $language)
                    <option data-rel="{{ $language->id }}" value="{{$language->short_name}}" {{$companyData['company']['dflt_lang'] == $language->short_name ? 'selected':""}}>{{$language->name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label id="default-currency" class="col-sm-3 control-label " for="inputEmail3">{{ __('Default currency') }}</label>

                <div class="col-sm-7">
                  <select class="form-control js-example-basic-single" name="dflt_currency_id" >
                  @foreach ($currencyData as $data)
                    <option value="{{$data->id}}" <?=isset($companyData['company']['dflt_currency_id']) && $companyData['company']['dflt_currency_id'] == $data->id ? 'selected':""?> >{{$data->name}}</option>
                  @endforeach
                  </select>
                </div>
              </div>                

              <div class="form-group row display_none">
                <label class="col-sm-3 control-label " for="inputEmail3">{{ __('Price type') }}</label>

                <div class="col-sm-7">
                  <select class="form-control js-example-basic-single" name="sates_type_id" >
                    <option value="">{{ __('Select One') }}</option>
                      @foreach ($saleTypes as $saleType)
                        <option value="{{$saleType->id}}" <?=isset($companyData['company']['sates_type_id']) && $companyData['company']['sates_type_id'] == $saleType->id ? 'selected':""?> >{{$saleType->sales_type}}</option>
                      @endforeach          
                  </select>
                </div>
              </div>
                <div class="form-group row" id="getBottomMargin">
                  <label class="col-sm-3 control-label " for="inputEmail3">{{ __('Logo') }}</label>
                    <div class="custom-file col-sm-7">
                      <div class="custom-file">
                      <input name="company_logo" class="form-control custom-file-input" type="file" id="company_logo" data-rel="{{isset($companyData['company']['company_logo'])?$companyData['company']['company_logo']:''}}">
                        <label class="custom-file-label overflow_hidden" for="item_image">{{ __('Upload logo...') }}</label>
                        <label for="company_logo" id="company_logo-error" generated="true" class="error display_none">{{ __('Invalid image type') }}</label>
                        <div class="py-1" id="note_txt_1">
                          <span class="badge badge-danger">{{ __('Note') }}! </span> {{ __('Allowed File Extensions: jpg, jpeg, png') }}
                        </div>
                        <div class="col-md-12 p-0" id='note_txt_2'>                      
                       </div>
                      </div>
                    </div>
                </div>
                  @if (!empty($companyData['company']['company_logo']))
                    <input name="company_logo" class="form-control" type="hidden"  value="{{$companyData['company']['company_logo']}}"><br class="logo-picture-2">

                    <?php $dir = $companyData['imgDir']; 
                          $image_url = $companyData['company']['company_logo'];
                    ?>  
                      @if(file_exists("$dir/$image_url")) 
                      <div class="form-group row">
                      <label class="col-sm-3 control-label " for="inputEmail3"></label>
                        <div class="col-sm-7">
                          <div id="logoCompany">
                            <img alt="Company Logo" src='{{url("public/uploads/companyPic/$image_url")}}' class="img-responsive asa company_logo_padding" id="pro_img" alt="Logo"><span class="remove_img_preview"></span>
                          </div>
                        <input type="hidden" name="pic" value="{{ isset($companyData['company']['company_logo']) ? $companyData['company']['company_logo'] : 'NULL' }}">
                        </div> 
                      </div>
                      @endif
                    @endif

                  <div class="form-group row" id="iconTop">
                    <label class="col-sm-3 control-label " for="inputEmail3">{{ __('Favicon') }}</label>
                    <div class="custom-file col-sm-7">
                      <div class="custom-file">
                        <input name="company_icon" class="form-control custom-file-input" type="file" id="company_icon" data-icon="{{isset($companyData['company']['company_icon'])?$companyData['company']['company_icon']:''}}">
                        <label class="custom-file-label overflow_hidden" for="item_image"> {{ __('Upload Favicon') }}...</label>
                          <div class="mt-1" id="note_txt_3">
                            <span class="badge badge-danger">{{ __('Note') }}!</span> {{ __('Allowed File Extensions: ico') }}
                          </div>
                           <div class="col-md-12 p-0" id='note_txt_4'>                      
                          </div>
                      </div>
                    </div>
                  </div>

                  @if (!empty($companyData['company']['company_icon']))
                    <input name="company_icon" class="form-control" type="hidden"  value="{{$companyData['company']['company_icon']}}"><br class="logo-picture-1">

                    <?php
                      $dir = $companyData['icon']; 
                      $image_url = $companyData['company']['company_icon'];
                    ?>
                  
                    @if(file_exists("$dir/$image_url")) 
                      <div class="form-group row pt-1p5rem">
                        <label class="col-sm-3 control-label " for="inputEmail3"></label>
                        <div class="col-sm-7">
                          <div id="iconCompany" class="img-wrap-favicon">
                            <img alt="Company Icon" src='{{url("public/uploads/companyIcon/$image_url")}}' class="img-responsive" id="pro_icon" alt="Icon"><span class="remove_icon_preview"></span>
                          </div>
                          <input type="hidden" name="icon" value="{{ isset($companyData['company']['company_icon']) ? $companyData['company']['company_icon'] : 'NULL' }}">
                        </div> 
                      </div>
                    @endif
                  @endif

                  @if(Helpers::has_permission(Auth::user()->id, 'manage_company_setting'))
                    <div class="form-group row" id="addTop">
                      <label for="btn_save" class="col-sm-0 pl-2 ml-2 control-label"></label>
                      <button type="submit" class="btn btn-primary custom-btn-small float-left" id="btnSubmits">{{ __('Submit') }}</button>
                    </div>
                  @endif
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
    
@endsection

@section('js')
    {{-- Using local does not have the required file --}}
    <script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>
    {!! translateValidationMessages() !!}
    <script src="{{ asset('public/dist/js/custom/settings.min.js') }}"></script>
@endsection