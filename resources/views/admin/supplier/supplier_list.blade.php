@extends('layouts.list-layout')

@section('list-title')
  <h5><a href="{{url('supplier')}}">{{ __('Suppliers') }}</a></h5>
@endsection

@section('list-form-content')
  @php 
    $from='';
    $to = '';
  @endphp
  <div class="col-md-12 col-sm-12 col-xs-12 m-l-10 m-b-0" id="supplier-list-container">
    @if(Helpers::has_permission(Auth::user()->id, 'add_supplier'))
    <div class="buttonRelation mt-3">
      <a href="{{ URL::to('supplierimport') }}" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-upload"> &nbsp;</span>{{ __('Import Suppliers') }}</a>
    @endif
    
    @if(Helpers::has_permission(Auth::user()->id, 'add_supplier'))
      <a href="{{ url('create-supplier') }}" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Supplier') }}</a>
    </div>
    @endif
    <div class="row mt-3">      
      <div class="col-md-4 col-sm-4 col-xs-12">
        <div class="card project-task theme-bg">
          <div class="card-block">
            <div class="row  align-items-center">
              <div class="col-9">
                <a href="{{ url('supplier?supplier=total') }}">
                  <h5 class="text-white"><i class="fas fa-users m-r-10 f-20"></i>{{ __('Total') }}</h5>
                </a>
              </div>
              <div class="col-3 text-right">
                <a href="{{ url('supplier?supplier=total') }}">
                  <h3 class="text-white" id="supplierCount">{{ $supplierCount }}</h3>
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
              <div class="col-9">
                <a href="{{ url('supplier?supplier=active') }}">
                  <h5 class="text-white"><i class="fas fa-users m-r-10 f-20"></i>{{ __('Active') }}</h5>
                </a>
              </div>
              <div class="col-3 text-right">
                <a href="{{ url('supplier?supplier=active') }}">
                  <h3 class="text-white" id="supplierActive">{{ $supplierActive }}</h3>
                </a>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12">
        <div class="card project-task theme-bg2">
          <div class="card-block">
            <div class="row  align-items-center">
              <div class="col-9">
              <a href="{{ url('supplier?supplier=inactive') }}">
                <h5 class="text-white"><i class="fas fa-users m-r-10 f-20"></i>{{ __('Inactive') }}</h5>
              </a>
              </div>
              <div class="col-3 text-right">
                <a href="{{ url('supplier?supplier=inactive') }}">
                  <h3 class="text-white" id="supplierInActive">{{ $supplierInActive }}</h3>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('list-js')
  <script src="{{ asset('public/dist/js/custom/supplier.min.js') }}"></script>
@endsection