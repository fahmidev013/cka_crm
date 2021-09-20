@extends('layouts.list-layout')

@section('list-title')
  <h5><a href="{{ url('customer/list') }}">{{ __('Customers') }}</a></h5>
@endsection

@section('list-form-content')
  @php  
    $from = $to = ''; 
  @endphp
  <div class="col-md-12 col-sm-12 col-xs-12 m-l-10 m-b-0">
     @if(Helpers::has_permission(Auth::user()->id, 'add_customer'))
    <div class="buttonRelation mt-3">      
      <a href="{{ URL::to('customerimport') }}" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-upload"> &nbsp;</span>{{ __('Import Customer') }}</a>

      <a href="{{ url('create-customer') }}" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Customer') }}</a>
    </div>
    @endif
    <div class="row mt-3">
      <div class="col-md-4 col-sm-4 col-xs-12">
        <div class="card project-task theme-bg">
          <div class="card-block">
            <div class="row  align-items-center">
              <div class="col-6">
                <a href="{{ url('customer/list?customer=total') }}">
                  <h5 class="text-white"><i class="fas fa-users m-r-10 f-20"></i>{{ __('Total') }}</h5>
                </a>
              </div>
              <div class="col-6 text-right">
                <a href="{{ url('customer/list?customer=total') }}">
                  <h3 class="text-white" id="customerCount">{{ $customerCount }}</h3>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12">
        <div class="card project-task bg-c-blue">
          <div class="card-block">
            <div class="row  align-items-center">
              <div class="col-6">
                <a href="{{ url('customer/list?customer=active') }}">
                  <h5 class="text-white"><i class="fas fa-users m-r-5 f-20"></i>{{ __('Active') }}</h5>
                </a>
              </div>
              <div class="col-6 text-right">
                <a href="{{ url('customer/list?customer=active') }}">
                  <h3 class="text-white" id="customerActive">{{ $customerActive }}</h3>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12">
        <div class="card project-task theme-bg2">
          <div class="card-block">
            <div class="row align-items-center">
              <div class="col-7">
                <a href="{{ url('customer/list?customer=inactive') }}">
                  <h5 class="text-white"><i class="fas fa-users m-r-10 f-20"></i>{{ __('Inactive') }}</h5>
                </a>
              </div>
              <div class="col-5 text-right">
                <a href="{{ url('customer/list?customer=inactive') }}">
                  <h3 class="text-white" id="customerInActive">{{ $customerInActive }}</h3>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="alert alert-danger display_none" role="alert" id="alert">
    {{ __('Something went wrong, please try again.') }}
  </div>
@endsection

@section('list-js')
<script src="{{ asset('public/dist/js/custom/customer.min.js') }}"></script>
@endsection