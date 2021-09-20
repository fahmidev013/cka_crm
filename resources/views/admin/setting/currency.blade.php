@extends('layouts.app')
@section('css')
{{-- Data table --}}
<link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
{{-- Select2  --}}
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection
@section('content')
<!-- Main content -->
<div class="col-sm-12" id="currency-settings-container">
  <div class="row">
    <div class="col-sm-3">
      @include('layouts.includes.finance_menu')
    </div>
    <div class="col-sm-9">
      <div class="card card-info">
        <div class="card-header">
          <h5><a href="{{ url('tax') }}">{{ __('Finance') }}</a> >> {{ __('Currencies') }}</h5>
          <div class="card-header-right">
            @if(Helpers::has_permission(Auth::user()->id, 'add_currency'))
            <a href="javascript:void(0)" data-toggle="modal" data-target="#add-unit" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Currency') }}</a>
            @endif
          </div>
        </div>
        <div class="card-body">
          <div class="row p-l-15">
            <div class="table-responsive">
              <table id="dataTableBuilder" class="table table-bordered table-hover table-striped dt-responsive">
                <thead>
                  <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Symbol') }}</th>
                    <th>{{ __('Rate') }}</th>
                    <th width="5%">{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($currencyData as $data)
                  <tr>
                    <td><a href="javascript:void(0)" class="edit_unit" id="{{$data->id}}">{{ $data->name }}</a></td>
                    <td>{{ $data->symbol }}</td>
                    <td>{{ formatCurrencyAmount($data->exchange_rate) }}</td>
                    <td>

                      @if(Helpers::has_permission(Auth::user()->id, 'edit_currency'))
                      <a title="{{ __('Edit') }}" href="javascript:void(0)" data-toggle="modal" data-target="#edit-unit" class="btn btn-xs btn-primary edit_unit" id="{{$data->id}}"><span class="feather icon-edit"></span></a> &nbsp;
                      @endif

                      @if(Helpers::has_permission(Auth::user()->id, 'delete_currency'))
                      @if(!in_array($data->id,[$default_currency->id]))
                      <form method="POST" action="{{ url('delete-currency') }}" id="delete-currency-{{$data->id}}" accept-charset="UTF-8" class="display_inline">
                        {!! csrf_field() !!}
                        <input type="hidden" name="id" value="{{$data->id}}">
                        <button title="{{ __('Delete') }}" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-id="{{$data->id}}" data-label="Delete" data-target="#confirmDelete" data-title="{{ __('Delete Currency') }}" data-message="{{ __('Are you sure you want to delete this Currency?') }}">
                          <i class="feather icon-trash-2"></i>
                        </button>
                      </form>
                      @endif
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmDeleteLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary custom-btn-small" data-dismiss="modal">{{ __('Close') }}</button>
          <button type="button" id="confirmDeleteSubmitBtn" data-task="" class="btn btn-danger custom-btn-small">{{ __('Submit') }}</button>
          <span class="ajax-loading"></span>
        </div>
      </div>
    </div>
  </div>
  <div id="add-unit" class="modal fade display_none" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('Add New') }} &nbsp; </h4>
          <p class="float-right p-t-5"><span class="badge badge-success">{{ __('Home Currency') }} &nbsp;{{ $default_currency->name }}</span></p>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        <div class="modal-body">
          <form action="{{url('save-currency')}}" method="post" id="addUnit" class="form-horizontal">
            {!! csrf_field() !!}

            <div class="form-group row">
              <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Name') }}</label>

              <div class="col-sm-6">
                <input type="text" placeholder="{{ __('Name') }}" class="form-control" name="name">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Symbol') }}</label>

              <div class="col-sm-6">
                <input type="text" placeholder="{{ __('Symbol') }}" class="form-control" name="symbol">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 control-label require pr-0 " for="inputEmail3">{{ __('Exchange Rate') }}</label>
              <div class="col-sm-6">
                <input type="text" placeholder="{{ __('Exchange Rate') }}" class="form-control positive-float-number" name="exchange_rate">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 control-label require pr-0" for="exchange_from">{{ __('Exchange From') }}</label>
              <div class="col-sm-6">
                <select class="form-control js-example-basic-single-1" name="exchange_from" id="add_exchange_from">
                  <option value='local'>{{ __('local') }}</option>
                  <option value="api">{{ __('api') }}</option>
                </select>
                <small class="color_red add-row display_none" data-toggle="modal" data-target="#currency-converter" id="note">{{ __('Setup Currency Converter. Click here.') }}&nbsp;</small>
              </div>
            </div>


            <div class="form-group row">
              <label for="btn_save" class="col-sm-3 control-label"></label>
              <div class="col-sm-12">
                <button type="submit" class="btn btn-primary custom-btn-small float-right">{{ __('Submit') }}</button>
                <button type="button" class="btn btn-secondary custom-btn-small float-right" data-dismiss="modal">{{ __('Close') }}</button>
              </div>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>

  <div id="edit-unit" class="modal fade display_none" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('Edit Currency') }} &nbsp;</h4>
          <p class="float-right p-t-5"><span class="badge badge-success">{{ __('Home Currency') }} &nbsp;{{ $default_currency->name }}</span></p>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        <div class="modal-body">
          <form action="{{ url('update-currency') }}" method="post" id="editUnit" class="form-horizontal">
            {!! csrf_field() !!}
            <input type="hidden" name="curr_id" id="curr_id">

            <div class="form-group row">
              <label class="col-sm-3 control-label require" for="curr_name">{{ __('Name') }}</label>

              <div class="col-sm-6">
                <input type="text" class="form-control" placeholder="{{ __('Name') }}" id="curr_name" name="name">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 control-label require" for="curr_symbol">{{ __('Symbol') }}</label>

              <div class="col-sm-6">
                <input type="text" class="form-control" placeholder="{{ __('Symbol') }}" id="curr_symbol" name="symbol">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 control-label require pr-0" for="exchange_rate">{{ __('Exchange Rate') }}</label>
              <div class="col-sm-6">
                <input type="text" class="form-control positive-float-number" placeholder="{{ __('Exchange Rate') }}" id="exchange_rate" name="exchange_rate">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 control-label require pr-0" for="exchange_from">{{ __('Exchange From') }}</label>
              <div class="col-sm-6">
                <select class="js-example-basic-single-2 form-control" name="exchange_from" id="edit_exchange_from">
                  <option value='local'>{{ __('local') }}</option>
                  <option value='api'>{{ __('api') }}</option>
                </select>
                <small class="color_red add-row display_none" data-toggle="modal" data-target="#currency-converter" id="note_edit">{{ __('Setup Currency Converter. Click here.') }}&nbsp;</small>
              </div>
            </div>
            <div class="form-group row">
              <label for="btn_save" class="col-sm-3 control-label"></label>
              <div class="col-sm-12">
                <button type="submit" class="btn btn-primary custom-btn-small float-right">{{ __('Submit') }}</button>
                <button type="button" class="btn btn-secondary custom-btn-small float-right" data-dismiss="modal">{{ __('Close') }}</button>
              </div>
            </div>
          </form>
        </div>

      </div>

    </div>
  </div>

 <div id="currency-converter" class="modal fade display_none" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header header-background">
          <h3 class="header-text">{{ __('How to set up currrency conveter') }}</h3>
        </div>
        <div class="modal-body p-4">
          <div>
            <p>{{ __('Set up anyone currency converter api') }}</p>
            <p class="blue-text">{{ __('Currency Converter Api') }}</p>
              <ul>
                <li><strong>{{ __('URL') }}: </strong><a target="_blank" href="//free.currencyconverterapi.com">free.currencyconverterapi.com</a>
                <li><strong>{{ __('Api Key') }}:</strong> {{ __('Get an api key from URL') }}</li>
                <li>{{ __('After that set the value in the Api Key field.') }}</li>
              </ul>
            <p class="blue-text">{{ __('Exchange Rate Api') }}</p>
              <ul>
                <li><strong>{{ __('URL') }}: </strong><a target="_blank" href="//exchangerate-api.com/">exchangerate-api.com</a>
                <li><strong>{{ __('Api Key') }}:</strong>{{ __('Get an api key from URL') }}</li>
                <li>{{ __('After that set the value in the Api Key field.') }}</li>
              </ul>
            <a target="_blank" href="{{ url('currency-converter/setup') }}" class="currency-converter-link">{{ __('Click this for Currency Converter Setup') }}</a>
            <div>
              <button type="button" class="btn btn-secondary custom-btn-small float-right mt-2" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@include('layouts.includes.message_boxes')
@endsection

@section('js')
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/finance.min.js') }}"></script>
<script type="text/javascript"> 
  'use strict';
  var currencyConverter = "{{ $currencyConverter }}";
</script>
@endsection