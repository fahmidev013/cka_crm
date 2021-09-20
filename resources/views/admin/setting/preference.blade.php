@extends('layouts.app')
@section('css')
{{-- Select2  --}}
  <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
  <!-- daterange picker -->
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content')
  <!-- Main content -->
<div class="col-sm-12" id="preference-settings-container">
  <div class="row">
    <div class="col-sm-3">
      @include('layouts.includes.preference_menu')
    </div>
    <div class="col-sm-9">
      <div class="card">
      <div class="card-header">
        <h5>{{ __('General Preferences') }}</h5>
        <div class="card-header-right">

        </div>
      </div>
      <div class="card-block table-border-style">
        <form action="{{ url('save-preference') }}" method="post" id="myform1" class="form-horizontal">
        {!! csrf_field() !!}
          <div class="card-body p-0">

            <div class="form-group row">
              <label class="col-sm-3 control-label text-left" for="inputEmail3">{{ __('Rows per page') }}</label>

              <div class="col-sm-6">
                <select name="row_per_page" class="form-control select" >
                    <option value="10" <?=isset($prefData['preference']['row_per_page']) && $prefData['preference']['row_per_page'] == 10 ? 'selected':""?>>10</option>
                    <option value="25" <?=isset($prefData['preference']['row_per_page']) && $prefData['preference']['row_per_page'] == 25 ? 'selected':""?>>25</option>
                    <option value="50" <?=isset($prefData['preference']['row_per_page']) && $prefData['preference']['row_per_page'] == 50 ? 'selected':""?>>50</option>
                    <option value="100" <?=isset($prefData['preference']['row_per_page']) && $prefData['preference']['row_per_page'] == 100 ? 'selected':""?>>100</option>
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 control-label text-left" for="inputEmail3">{{ __('Date format') }}</label>

              <div class="col-sm-6">
                <select name="date_format" class="form-control select" >
                    <option value="0" <?=isset($prefData['preference']['date_format']) && $prefData['preference']['date_format'] == 0 ? 'selected':""?>>yyyymmdd {2020 12 31}</option>
                    <option value="1" <?=isset($prefData['preference']['date_format']) && $prefData['preference']['date_format'] == 1 ? 'selected':""?>>ddmmyyyy {31 12 2020}</option>
                    <option value="2" <?=isset($prefData['preference']['date_format']) && $prefData['preference']['date_format'] == 2 ? 'selected':""?>>mmddyyyy {12 31 2020}</option>
                    <option value="3" <?=isset($prefData['preference']['date_format']) && $prefData['preference']['date_format'] == 3 ? 'selected':""?>>ddMyyyy &nbsp;&nbsp;&nbsp;{31 Dec 2020}</option>
                    <option value="4" <?=isset($prefData['preference']['date_format']) && $prefData['preference']['date_format'] == 4 ? 'selected':""?>>yyyyMdd &nbsp;&nbsp;&nbsp;{2020 Dec 31}</option>
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 control-label text-left" for="inputEmail3">{{ __('Date Separator') }}</label>

              <div class="col-sm-6">
                <select name="date_sepa" class="form-control select">
                    <option value="-" <?=isset($prefData['preference']['date_sepa']) && $prefData['preference']['date_sepa'] == '-' ? 'selected':""?>>-</option>
                    <option value="/" <?=isset($prefData['preference']['date_sepa']) && $prefData['preference']['date_sepa'] == '/' ? 'selected':""?>>/</option>
                    <option value="." <?=isset($prefData['preference']['date_sepa']) && $prefData['preference']['date_sepa'] == '.' ? 'selected':""?>>.</option>
                    <option value="  " <?=isset($prefData['preference']['date_sepa']) && $prefData['preference']['date_sepa'] == '  ' ? 'selected':""?>>  </option>
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 control-label text-left" for="decimal_digits">{{ __('Decimal Format') }}(.)</label>
              <div class="col-sm-6">
                <select name="decimal_digits" class="form-control select">
                    @for($i=1; $i<=8; $i++)
                      <option value={{$i}} <?= isset($prefData['preference']['decimal_digits']) && $prefData['preference']['decimal_digits'] == $i ? 'selected' : "" ?>>{{$i}}</option>
                    @endfor
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 control-label text-left" for="exchange_rate_decimal_digits">{{ __('Exchange Rate Decimal Format') }}(.)</label>
              <div class="col-sm-6">
                <select name="exchange_rate_decimal_digits" class="form-control select">
                    @for($i=1; $i<=8; $i++)
                      <option value={{$i}} <?= isset($prefData['preference']['exchange_rate_decimal_digits']) && $prefData['preference']['exchange_rate_decimal_digits'] == $i ? 'selected' : "" ?>>{{$i}}</option>
                    @endfor
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 control-label text-left" for="thousand_separator">{{ __('Thousand Separator') }}</label>
              <div class="col-sm-6">
                <select name="thousand_separator" class="form-control select">
                  <option value="," <?= isset($prefData['preference']['thousand_separator']) && $prefData['preference']['thousand_separator'] == ',' ? 'selected' : "" ?>> , </option>
                  <option value="." <?= isset($prefData['preference']['thousand_separator']) && $prefData['preference']['thousand_separator'] == '.' ? 'selected' : "" ?>> . </option>
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 control-label text-left" for="symbol_position">{{ __('Symbol Position') }}</label>
              <div class="col-sm-6">
                <select name="symbol_position" class="form-control select">
                  <option value="before" <?= isset($prefData['preference']['symbol_position']) && $prefData['preference']['symbol_position'] == 'before' ? 'selected' : "" ?>> Before </option>
                  <option value="after" <?= isset($prefData['preference']['symbol_position']) && $prefData['preference']['symbol_position'] == 'after' ? 'selected' : "" ?>> After </option>
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 control-label text-left" for="symbol_position">{{ __('Captcha') }}</label>
              <div class="col-sm-6">
                <select name="captcha" class="form-control select">
                  <option value="enable" <?= isset($prefData['preference']['captcha']) && $prefData['preference']['captcha'] == 'enable' ? 'selected' : "" ?>> {{ __('Enabled') }}</option>
                  <option value="disable" <?= isset($prefData['preference']['captcha']) && $prefData['preference']['captcha'] == 'disable' ? 'selected' : "" ?>>{{ __('Disabled') }}</option>
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 control-label text-left require" for="file_size">{{ __('Max FileSize') }}</label>
              <div class="col-sm-6 input-group">
                <input type="number" name="file_size" id="file_size" value="{{ isset($prefData['preference']['file_size']) ? $prefData['preference']['file_size'] : ''}}">
                <div class="input-group-prepend">
                  <span class="input-group-text">{{ __('MB') }}</span>
                </div>
              </div>

            </div>

            <div class="form-group row">
                <label class="col-sm-3 control-label text-left" for="inputEmail3">{{ __('Timezone') }}</label>
                  <?php
                   $timezones = timeZoneList();
                  ?>
                <div class="col-sm-6">
                  <select class="form-control select" name="default_timezone" >
                  @foreach($timezones as $timezone)
                    <option value="{{$timezone['zone']}}" <?=isset($prefData['preference']['default_timezone']) && $prefData['preference']['default_timezone'] == $timezone['zone'] ? 'selected':""?>>
                      {{$timezone['diff_from_GMT'] . ' - ' . $timezone['zone'] }}
                    </option>
                  @endforeach
                  </select>
                   <br>
                   <br>
                </div>
            </div>
              <div class="form-group row">
                  <label class="col-sm-3 control-label text-left" for="inputEmail3">{{ __('Facebook Comments') }}</label>
                  <div class="col-sm-6">
                      <select name="facebook_comments" class="form-control select">
                          <option value="enable" <?= isset($prefData['preference']['facebook_comments']) && $prefData['preference']['facebook_comments'] == 'enable' ? 'selected' : "" ?>> {{__('Enable')}} </option>
                          <option value="disable" <?= isset($prefData['preference']['facebook_comments']) && $prefData['preference']['facebook_comments'] == 'disable' ? 'selected' : "" ?>> {{__('Disable')}} </option>
                      </select>
                      <br>
                      <br>
                  </div>
              </div>

            <div class="form-group row display_none">
              <label class="col-sm-3 control-label text-left" for="inputEmail3">{{ __('Percentage') }} (%)</label>

              <div class="col-sm-6">
                <select name="percentage" class="form-control select" >
                    <option value="0" <?=isset($prefData['preference']['percentage']) && $prefData['preference']['percentage'] == 0 ? 'selected':""?>>0</option>
                    <option value="1" <?=isset($prefData['preference']['percentage']) && $prefData['preference']['percentage'] == 1 ? 'selected':""?>>1</option>
                    <option value="2" <?=isset($prefData['preference']['percentage']) && $prefData['preference']['percentage'] == 2 ? 'selected':""?>>2</option>
                    <option value="3" <?=isset($prefData['preference']['percentage']) && $prefData['preference']['percentage'] == 3 ? 'selected':""?>>3</option>
                    <option value="4" <?=isset($prefData['preference']['percentage']) && $prefData['preference']['percentage'] == 4 ? 'selected':""?>>4</option>
                    <option value="5" <?=isset($prefData['preference']['percentage']) && $prefData['preference']['percentage'] == 5 ? 'selected':""?>>5</option>
                    <option value="6" <?=isset($prefData['preference']['percentage']) && $prefData['preference']['percentage'] == 6 ? 'selected':""?>>6</option>
                    <option value="7" <?=isset($prefData['preference']['percentage']) && $prefData['preference']['percentage'] == 7 ? 'selected':""?>>7</option>
                    <option value="8" <?=isset($prefData['preference']['percentage']) && $prefData['preference']['percentage'] == 8 ? 'selected':""?>>8</option>
                    <option value="9" <?=isset($prefData['preference']['percentage']) && $prefData['preference']['percentage'] == 9 ? 'selected':""?>>9</option>
                    <option value="10" <?=isset($prefData['preference']['percentage']) && $prefData['preference']['percentage'] == 10 ? 'selected':""?>>10</option>
                </select>
              </div>

            </div>

            <div class="form-group row display_none">
              <label class="col-sm-3 control-label text-left" for="inputEmail3">{{ __('Quantities') }}</label>

              <div class="col-sm-6">
                <select name="quantity" class="form-control select" >
                    <option value="0" <?=isset($prefData['preference']['quantity']) && $prefData['preference']['quantity'] == 0 ? 'selected':""?>>0</option>
                    <option value="1" <?=isset($prefData['preference']['quantity']) && $prefData['preference']['quantity'] == 1 ? 'selected':""?>>1</option>
                    <option value="2" <?=isset($prefData['preference']['quantity']) && $prefData['preference']['quantity'] == 2 ? 'selected':""?>>2</option>
                    <option value="3" <?=isset($prefData['preference']['quantity']) && $prefData['preference']['quantity'] == 3 ? 'selected':""?>>3</option>
                    <option value="4" <?=isset($prefData['preference']['quantity']) && $prefData['preference']['quantity'] == 4 ? 'selected':""?>>4</option>
                    <option value="5" <?=isset($prefData['preference']['quantity']) && $prefData['preference']['quantity'] == 5 ? 'selected':""?>>5</option>
                    <option value="6" <?=isset($prefData['preference']['quantity']) && $prefData['preference']['quantity'] == 6 ? 'selected':""?>>6</option>
                    <option value="7" <?=isset($prefData['preference']['quantity']) && $prefData['preference']['quantity'] == 7 ? 'selected':""?>>7</option>
                    <option value="8" <?=isset($prefData['preference']['quantity']) && $prefData['preference']['quantity'] == 8 ? 'selected':""?>>8</option>
                    <option value="9" <?=isset($prefData['preference']['quantity']) && $prefData['preference']['quantity'] == 9 ? 'selected':""?>>9</option>
                    <option value="10" <?=isset($prefData['preference']['quantity']) && $prefData['preference']['quantity'] == 10 ? 'selected':""?>>10</option>
                </select>
              </div>
            </div>
            <div class="col-sm-8 px-0">
              <button class="btn btn-primary custom-btn-small" type="submit" id="btnSubmit">{{  __('Submit')  }}</button>
            </div>
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
<script src="{{ asset('public/dist/js/custom/preference.min.js') }}"></script>
@endsection
