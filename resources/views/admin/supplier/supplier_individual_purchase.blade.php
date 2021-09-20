@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Supplier Purchase Orders') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Supplier Purchase Orders') }}</strong>
    </p>
    <p class="title">
      <span class="title-text">{{ __('Name:') }} </span>{{ isset($supplier) ? $supplier : '' }}
    </p>
    @if(isset($date_range) && !empty($date_range))
    <p class="title">
      <span class="title-text">{{ __('Period:') }} </span> {{ $date_range }}
    </p>
    @endif
    <p class="title">
      <span class="title-text">{{ __('Print Date:') }}</span>{{ formatDate(date('d-m-Y')) }}
    </p>
</td>
@endsection

@section('list-table')
<table class="list-table">
    <thead class="list-head">
        <tr>
            <td class="text-center list-th"> {{ __('Purchase no') }} </td>
            <td class="text-center list-th"> {{ __('Total') }} </td>
            <td class="text-center list-th"> {{ __('Currency') }} </td>
            <td class="text-center list-th"> {{ __('Purchase date') }} </td>
        </tr>
    </thead>
    <?php $total_amount = 0; ?>
    @foreach($purchaseFilter as $data)
      <?php $total_amount += $data->total; ?>
    <tr>
      <td class="text-center list-td">{{ $data->reference }}</td>
      <td class="text-center list-td">{{ formatCurrencyAmount($data->total) }}</td>
      <td class="text-center list-td">{{ $data->currency_name }}</td>
      <td class="text-center list-td">{{ formatDate($data->order_date)}}</td> 
    </tr>
    @endforeach
    <tr>
      @php
        $total_amount = isset($purchaseFilter[0]->total) ? $purchaseFilter[0]->total : 0;
      @endphp
      <td class="text-center list-td"><strong>{{ __('Total') }} = </stong></td>
      <td class="text-center list-td"><strong>{{ formatCurrencyAmount(($total_amount), isset($data->currency_name) && !empty($data->currency_name) ? $data->currency_name : '' )}}</stong></td>
      <td class="text-center list-td"></td> 
      <td class="text-center list-td"></th> 
    </tr> 
</table>
@endsection
