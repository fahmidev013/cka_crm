@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content')
<div class="col-sm-12" id="card-with-header-buttons">
  <div class="card">
    <div class="card-header">
      <h5>{{ __('Inventory Stock on Hand')  }}</h5>
      <div class="card-header-right">
        
      </div>
    </div>
    <div class="card-body p-0">
      <div class="col-sm-12 mt-3 p-0">
        <form class="form-horizontal" action="{{ url('report/inventory-stock-on-hand') }}" method="get">
          <div class="col-md-12">
            <div class="shadow-sm p-3 mb-3 rounded alert alert-primary" id="on_hand_msg"><button type="button" class="close">×</button><span class="badge badge-info">{{ __('Note')  }}</span> {{ __('Stock on hand report only for Active and Managed item.')  }}</div>
            <div class="row">
              <div class="col-md-3 col-sm-5 col-xs-12 mb-1">
                <select class="form-control select2" name="type" id="category">
                  <option value="all">{{ __('All Categories') }}</option>
                  @foreach($categoryList as $category)
                  <option value="{{$category->id}}" <?=isset($category->id) && $category->id == $type ? 'selected':""?>>{{$category->name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3 col-sm-5 col-xs-12 mb-1" >
                <select class="form-control select2" name="location" id="location">
                  <option value="all">{{ __('All Locations')  }}</option>
                  @foreach($locationList as $location)
                  <option value="{{$location->id}}" <?=isset($location->id) && $location->id == $location_id ? 'selected':""?>>{{$location->name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-1 col-sm-1 col-xs-12">
                <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small push-sm-right mt-0 mr-0">{{ __('Go') }}</button>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="box m-t-15">
        <div class="box-body row m-0">
          <div class="col-md-3 col-xs-6 border-right">
              <h3 class="bold">{{formatCurrencyAmount($qtyOnHand)}}</h3>
              <span class="text-info">{{ __('Total Units on Hand')  }}</span>
          </div>
          <div class="col-md-3 col-xs-6 border-right">
              <h3 class="bold">
                {{ formatCurrencyAmount(abs($costValueQtyOnHand), $currency_symbol)}}
               </h3>
              <span class="text-info">{{ __('Cost Value of Stock on Hand')  }}</span>
          </div>
          <div class="col-md-3 col-xs-6 border-right">
              <h3 class="bold">
                {{ formatCurrencyAmount(abs($retailValueOnHand), $currency_symbol)}}
              </h3>
              <span class="text-info">{{ __('Retail Value of Stock on Hand')  }} </span>
          </div>
          <div class="col-md-3 col-xs-6">
              <h3 class="bold">
                {{ formatCurrencyAmount(abs($profitValueOnHand), $currency_symbol)}}
              </h3>
              <span class="text-info">{{ __('Profit Value of Stock on Hand')  }}</span>
          </div>          
        </div>
      </div>
      <div class="col-sm-12 m-t-35">
        <div class="table-responsive">
          {!! $dataTable->table(['class' => 'table table-striped table-hover dt-responsive inventory-report', 'width' => '100%', 'cellspacing' => '0']) !!}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
{!! $dataTable->scripts() !!}
<script src="{{ asset('public/dist/js/custom/report.min.js') }}"></script>
@endsection