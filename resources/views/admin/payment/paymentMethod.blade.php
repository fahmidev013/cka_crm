@extends('layouts.app')
@section('css')
  {{-- Data table --}}
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
  {{-- Select2  --}}
  <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection
@section('content')
  <!-- Main content -->
  <div class="col-sm-12" id="paymentMethod-finance-container">
    <div class="row">
      <div class="col-sm-3">
        @include('layouts.includes.finance_menu')
      </div>
      <div class="col-sm-9">
        <div class="card card-info">
          <div class="card-header">
            <h5><a href="{{ url('tax') }}">{{ __('Finance')  }}</a> >> {{ __('Payment Methods')  }}</h5>
          </div>
          <div class="card-body">
            <div class="row p-l-15">
              <div class="table-responsive">
                <table id="dataTableBuilder" class="table table-bordered table-hover table-striped dt-responsive">
                  <thead>
                    <tr>
                      <th>{{ __('Name')  }}</th>
                      <th>{{ __('Default')  }}</th>
                      <th>{{ __('Status') }}</th>
                      <th width="5%">{{ __('Action')  }}</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach ($paymentMethodData as $data)
                    <tr>
                      <td>{{ $data->name }}</td>
                      <td>{{ $data->is_default == 1 ? __('Yes') : __('No') }}</td>
                      <td>
                        @if ($data->is_active == 1)
                          <span class="badge theme-bg text-white f-12">{{ __('Active') }}</span>
                        @else 
                           <span class="badge theme-bg2 text-white f-12">{{ __('Inactive') }}</span>
                        @endif
                      </td>
                      <td>
                  
                      @if(Helpers::has_permission(Auth::user()->id, 'edit_payment_method'))
                        <a title="{{ __('Edit')  }}" href="javascript:void(0)" data-toggle="modal" data-target="#edit-unit" class="btn btn-xs btn-primary edit_unit" id="{{$data->id}}" ><span class="feather icon-edit"></span></a> &nbsp;

                        <a  title="{{ __('Payment Method Settings')}}" href="javascript:void(0)" data-toggle="modal" data-target="#settings" class="btn btn-xs btn-primary settings" id="{{$data->id}}" ><span class="feather icon-settings"></span></a> &nbsp;
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
    <div id="edit-unit" class="modal fade display_none" role="dialog">
      <div class="modal-dialog">
  
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">{{ __('Edit Payment Method') }}</h4>
            <button type="button" class="close" data-dismiss="modal">×</button>
          </div>
          <div class="modal-body">
            <form action="{{ url('payment/method/update') }}" method="post" id="editUnit" class="form-horizontal">
                {!! csrf_field() !!}
              <input type="hidden" name="id" id="m_id">                                    
              <div class="form-group row">
                <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Name') }}</label>
  
                <div class="col-sm-6">
                  <input type="text" placeholder="Name" class="form-control" name="name" id="name" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Default')  }}</label>
                <div class="col-sm-6">
                    <select class="form-control js-example-basic-single-2" name="defaults" id="defaults">
                        <option value="0" >{{ __('No') }}</option>
                        <option value="1" >{{ __('Yes') }}</option>
                    </select>
                </div>
              </div>    
  
               <div class="form-group row">
                <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Status') }}</label>
                <div class="col-sm-6">
                    <select class="form-control js-example-basic-single-2" name="status" id="status">
                        <option value="1" >{{ __('Active') }}</option>
                        <option value="0" >{{ __('Inactive') }}</option>
                    </select>
                </div>
              </div>
  
              <div class="form-group row">
                <label for="btn_save" class="col-sm-3 control-label"></label>
                <div class="col-sm-12">
                  <button type="submit" class="btn btn-primary custom-btn-small float-right">{{ __('Submit')  }}</button>
                  <button type="button" class="btn btn-secondary custom-btn-small float-right" data-dismiss="modal">{{ __('Close')  }}</button>
                </div>
              </div>
            </form>
          </div>
  
        </div>
  
      </div>
    </div>
  
      <div id="settings" class="modal fade display_none" role="dialog">
        <div class="modal-dialog">
  
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modalTitle"></h4>
              <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
              <form action="{{url('payment/method/settings/update')}}" method="post" id="paymentSettings" class="form-horizontal">
                  {!! csrf_field() !!}
                  <input type="hidden" name="paymentId" id="paymentId">    
                <div class="form-group row display_none" id="client">
                  <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Client Id') }}</label>
                  <div class="col-sm-9">
                    <input type="text" placeholder="Client Id" class="form-control" name="client_id" id="client_id">
                  </div>
                </div>
                <div class="form-group row display_none" id="key">
                  <label class="col-sm-3 control-label require" id="consumer-key" for="inputEmail3">{{ __('Consumer Key') }}</label>
                  <div class="col-sm-9">
                      <input type="text" placeholder="Key" class="form-control" name="consumer_key" id="consumer_key">
                  </div>
                </div>  
  
                <div class="form-group row" id="secret">
                  <label class="col-sm-3 control-label require" id="customer-secret" for="inputEmail3">{{ __('Consumer Secret') }}</label>
                  <div class="col-sm-9">
                      <input type="text" placeholder="Secret" class="form-control" name="consumer_secret" id="consumer_secret">
                  </div>
                </div>  
  
                <div class="form-group row display_none" id="mode">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ __('Mode') }}</label>
                  <div class="col-sm-6">
                      <select class="form-control js-example-basic-single-1" name="modeVal" id="modeVal">
                          <option value="sandbox">{{ __('sandbox') }}</option>
                          <option value="live">{{ __('live') }}</option>
                      </select>
                  </div>
                </div>
                <div class="form-group row display_none" id="bankAccount">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ __('Account') }}</label>
                  <div class="col-sm-6">
                      <select class="form-control js-example-basic-single-1" name="account" id="account">
                          @foreach($accounts as $data)
                          <option value="{{ $data->id }}" data-bank="{{ $data->bank_name }}" data-branch="{{ $data->branch_name }}" data-city="{{ $data->branch_city }}" data-code="{{ $data->swift_code }}" data-address="{{ $data->bank_address }}">{{ $data->name}} ({{ $data->currency->name }})</option>
                          @endforeach
                      </select>
                  </div>
                </div>
                <div class="display_none" id="accountInfo">
                  <div class=" row" >
                    <label class="col-sm-3 control-label" for="inputEmail3">{{ __('Bank Name') }}</label>
                    <div class="col-sm-6 mt-2">
                      <span id="bank"></span>
                    </div>
                  </div>
                  <div class=" row" >
                    <label class="col-sm-3 control-label" for="inputEmail3">{{ __('Branch Name') }}</label>
                    <div class="col-sm-6 mt-2">
                      <span id="branch"></span>
                    </div>
                  </div>
                  <div class=" row" >
                    <label class="col-sm-3 control-label" for="inputEmail3">{{ __('Branch City') }}</label>
                    <div class="col-sm-6 mt-2">
                      <span id="city"></span>
                    </div>
                  </div>
                  <div class=" row" >
                    <label class="col-sm-3 control-label" for="inputEmail3">{{ __('Swift Code') }}</label>
                    <div class="col-sm-6 mt-2">
                      <span id="code"></span>
                    </div>
                  </div>
                  <div class="row" >
                    <label class="col-sm-3 control-label" for="inputEmail3">{{ __('Bank Address') }}</label>
                    <div class="col-sm-6 mt-2">
                      <span id="address"></span>
                    </div>
                  </div>
                  <div class="row">
                  <label class="col-sm-3 control-label" for="inputEmail3">{{ __('Approval') }}</label>
                    <div class="col-sm-6">
                      <select class="form-control js-example-basic-single-1" name="approval" id="approval">
                        <option value="auto">{{ __('Automatic') }}</option>
                        <option value="manual">{{ __('Manual') }}</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="btn_save" class="col-sm-3 control-label"></label>
                  <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary custom-btn-small float-right">{{ __('Submit')  }}</button>
                    <button type="button" class="btn btn-secondary custom-btn-small float-right" data-dismiss="modal">{{ __('Close')  }}</button>
                  </div>
                </div>
              </form>
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
@endsection