@extends('layouts.app')
@section('css')
{{-- select2 css --}}
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css')}}"> 
{{-- DataTable --}}
<link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
@endsection
@section('content')

<div class="col-sm-12" id="stock-details-container">
  <div class="card user-list">
    <div class="card-header">
      <a href="{{url('adjustment/list')}}"><h5>{{ __('Stock Adjustment') }}</a> >> #{{ sprintf("%04d", $Info->id) }} </h5>
      <div class="card-header-right">
        
      </div>
    </div>
    <div class="card-block">
      <div class="row mb-3">
        <div class="col-md-6">
          <div>{{ __('Location') }} : {{$Info->name}}</div>
          <div>{{ __('Adjustment Type') }} : {{($Info->transaction_type=='STOCKIN') ? __('Stock In') : __('Stock Out')}}</div>
          <div>{{ __('Date') }} : {{formatDate($Info->transaction_date)}}</div>
        </div>
      </div>
      <div class="form-tabs">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link text-uppercase active" data-toggle="tab" href="#transaction" role="tab" aria-expanded="false">{{ __('Stock Adjustment Details') }}</a>
          </li>
        </ul>
      </div>
      <div class="tab-content">
        <div class="tab-pane fade show active" id="transaction">
          <div class="row">
            <div class="table-responsive">
              <table id="dataTableBuilder" class="table table-bordered table-hover table-striped dt-responsive dataTableAllignment" width="100%">
                <thead>
                  <tr class="tbl_header_color dynamicRows">
                    <th width="75%" class="text-center">{{ __('Item Name') }}</th>
                    <th width="20%" class="text-center">{{ __('Quantity') }}</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $sum = 0;
                  ?>
                  @foreach($List as $value)
                  <?php 
                    $sum += abs($value->quantity);
                  ?>
                  <tr><td class="text-center">{{$value->description}}</td><td class="text-center">{{formatCurrencyAmount(abs($value->quantity))}}</td></tr>
                  @endforeach
                </tbody>
                <tfoot>
                  <tr><td align="right"><strong>{{ __('Total') }}</strong></td><td class="f-bold text-center" width="20%">{{formatCurrencyAmount($sum)}}</td></tr>
                </tfoot>
              </table>
            </div>
          </div>
          @if (! empty($Info->note))
          <div class="row">
            <div class="col-md-12">
                <div class="text-uppercase color_black"><strong>{{ __('Note') }} :</strong> </div>
                <div>{{$Info->note}}</div>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
@section('js')
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/adjustment.min.js') }}"></script>
@endsection