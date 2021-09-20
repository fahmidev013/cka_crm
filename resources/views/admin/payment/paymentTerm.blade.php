@extends('layouts.app')
@section('css')
  {{-- Data table --}}
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
  {{-- Select2  --}}
  <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
  
@endsection
@section('content')
  <!-- Main content -->
  <div class="col-sm-12" id="paymentTerm-finance-container">
    <div class="row">
      <div class="col-sm-3">
        @include('layouts.includes.finance_menu')
      </div>
      <div class="col-sm-9">
        <div class="card card-info">
          <div class="card-header">
            <h5><a href="{{ url('tax') }}">{{ __('Finance')  }}</a> >> {{ __('Payment Terms')  }}</h5>
            <div class="card-header-right">
              @if(Helpers::has_permission(Auth::user()->id, 'add_payment_term'))
                <a href="javascript:void(0)" data-toggle="modal" data-target="#add-unit" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('Add New')  }}</a>
              @endif
              
            </div>
          </div>
          <div class="card-body">
            <div class="row p-l-15">
              <div class="table-responsive">
                <table id="dataTableBuilder" class="table table-bordered table-hover table-striped dt-responsive">
                  <thead>
                    <tr>
                      <th>{{ __('Term') }}</th>
                      <th>{{__('Due Day')  }}</th>
                      <th>{{ __('Default')  }}</th>
                      <th width="5%">{{ __('Action')  }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($paymentTermData as $data)
                      <tr>
                        <td>{{ $data->name }}</td>
                        <td>{{ $data->days_before_due }}</td>
                        <td>{{ $data->is_default == 1 ? __('Yes') : __('No') }}</td>
                        <td width="10%">
                          @if(Helpers::has_permission(Auth::user()->id, 'edit_payment_term'))
                            <a title="{{ __('Edit')  }}" href="javascript:void(0)" data-toggle="modal" data-target="#edit-unit" class="btn btn-xs btn-primary edit_unit" id="{{$data->id}}" ><span class="feather icon-edit"></span></a> &nbsp;
                          @endif

                          @if(Helpers::has_permission(Auth::user()->id, 'delete_payment_term'))
                          @if(!in_array($data->id,[1,2,3]))
                            <form method="POST" action="{{ url('payment/terms/delete/'.$data->id) }}" id="delete-term-{{$data->id}}" accept-charset="UTF-8" class="display_inline">
                                {!! csrf_field() !!}
                                <button title="{{ __('Delete')  }}" data-id="{{$data->id}}" data-label="Delete" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ __('Delete Term')  }}" data-message="{{ __('Are you sure you want to delete this term?') }}">
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
            <h4 class="modal-title">{{ __('Add New')  }}</h4>
            <button type="button" class="close" data-dismiss="modal">×</button>
          </div>
          <div class="modal-body">
            <form action="{{url('payment/terms/add')}}" method="post" id="addUnit" class="form-horizontal">
                {!! csrf_field() !!}
              
              <div class="form-group row">
                <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Term') }}</label>
  
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="terms" placeholder="{{ __('Name') }}">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 control-label require" for="inputEmail3">{{__('Due Day')  }}</label>
  
                <div class="col-sm-6">
                    <input type="number" min=0 class="form-control" name="days_before_due" placeholder="{{ __('Due Day') }}">
                    <span class="badge badge-danger f-8 mt-p5 display_none zero-day-note">{{ __('Note') }}!
                      <span class="note-message"></span>
                    </span>
                </div>
              </div>
  
              <div class="form-group row">
                <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Default')  }}</label>
  
                <div class="col-sm-6">
                    <select class="form-control js-example-basic-single-1" name="defaults" >
                        <option value="0" >{{ __('No') }}</option>
                        <option value="1" >{{ __('Yes') }}</option>
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
  
    <div id="edit-unit" class="modal fade display_none" role="dialog">
      <div class="modal-dialog">
  
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">{{ __('Edit Payment Term')  }}</h4>
            <button type="button" class="close" data-dismiss="modal">×</button>
          </div>
          <div class="modal-body">
            <form action="{{ url('payment/terms/update') }}" method="post" id="editUnit" class="form-horizontal">
                {!! csrf_field() !!}
                <input type="hidden" name="id" id="term_id">
              <div class="form-group row">
                <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Term') }}</label>
  
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="terms" id="terms">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 control-label require" for="inputEmail3">{{__('Due Day')  }}</label>
  
                <div class="col-sm-6">
                    <input type="number" min=0 class="form-control" name="days_before_due" id="days_before_due">
                    <span class="badge badge-danger f-8 mt-p5 display_none zero-day-note">{{ __('Note')  }}!
                      <span class="note-message"></span>
                    </span>
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