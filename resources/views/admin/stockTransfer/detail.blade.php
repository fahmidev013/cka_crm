@extends('layouts.app')
@section('css')
{{-- DataTable --}}
<link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
@endsection
@section('content')

<div class="col-sm-12" id="stock-transfer-details">
  <div class="card user-list">
    <div class="card-header">
      <a href="{{url('stock_transfer/list')}}"><h5>{{ __('Stock Transfer') }}</a> >> #{{ sprintf("%04d", $Info->id) }} </h5>
      <div class="card-header-right">
        
      </div>
    </div>
    <div class="card-block">
      <div class="row">
        <div class="col-md-6">
          <div>{{ __('Source') }} : {{ isset($Info->sourceLocation->name) ? $Info->sourceLocation->name : '' }}</div>
          <div>{{ __('Destination') }} : {{ isset($Info->destinationLocation->name) ? $Info->destinationLocation->name : '' }}</div>
          <div>{{ __('Date') }} : {{ formatDate($Info->transfer_date) }}</div>
        </div>
      </div>
      <br>
      <div class="form-tabs">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link text-uppercase active" data-toggle="tab" href="#transaction" role="tab" aria-expanded="false">{{ __('Stock Transfer Details') }}</a>
          </li>
        </ul>
      </div>
      <div class="tab-content">
        <div class="tab-pane fade show active" id="transaction">
          <div class="row">
            <div class="table-responsive">
              <table id="transferTbl" class="table table-bordered table-hover table-striped dt-responsive" width="100%">
                <thead>
                  <tr  class="tbl_header_color dynamicRows">
                    <th width="75%" class="text-center">{{ __('Item Name') }}</th>
                    <th width="25%" class="text-center">{{ __('Quantity') }}</th>
                  </tr>
                </thead>
                <tbody>                  
                  <?php
                    $sum = 0;
                  ?>
                  @foreach($List as $value)
                  <?php 
                    $sum += $value->quantity;
                  ?>
                  <tr><td class="text-center">{{$value->item->name}}</td><td class="text-center">{{ formatCurrencyAmount($value->quantity) }}</td></tr>
                  @endforeach
                </tbody>
                <tfoot>
                  <tr><td align="right"><strong>{{ __('Total') }}</strong></td><td class="f-bold text-center" width="20%">{{ formatCurrencyAmount($sum) }}</td></tr>                  
                </tfoot>
              </table>
            </div>
          </div>
          @if (!empty($Info->note))
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

{{--DataTable JS--}}
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/stock-transfer.min.js') }}"></script>
@endsection