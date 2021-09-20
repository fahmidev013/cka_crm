@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Supplier Payment Lists') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Supplier Payment Lists') }}</strong>
    </p>
    <p class="title">
      <span class="title-text">{{__('Name:')}} </span>{{ isset($supplierData) && !empty($supplierData) ? $supplierData->name : __('All Suppliers') }}
    </p>
    @if (isset($date_range) && !empty($date_range))
      <p class="title">
        <span class="title-text">{{__('Period:')}} </span> {{$date_range}}
      </p>
    @endif
    <p class="title">
      <span class="title-text">{{ __('Print Date:') }}: </span> {{ formatDate(date('d-m-Y')) }}
    </p>
</td>
@endsection

@section('list-table')
<table class="list-table">
    <thead class="list-head">
        <tr>   
          <td class="text-center list-th"> {{ __('Payment no') }} # </td>
          <td class="text-center list-th"> {{ __('Purchase no') }} </td>
          <td class="text-center list-th"> {{ __('Supplier') }} </td>
          <td class="text-center list-th"> {{ __('Payment method') }} </td>
          <td class="text-center list-th"> {{ __('Amount') }} </td>
          <td class="text-center list-th"> {{ __('Currency') }} </td>
          <td class="text-center list-th"> {{ __('Date') }} </td>
        </tr>
    </thead>
    @foreach($paymentList as $data)
      <tr>
          <td class="text-center list-td"> {{ sprintf("%04d", $data->st_id) }} </td>
          <td class="text-center list-td"> {{ $data->purchaseOrder->reference }} </td>
          <td class="text-center list-td"> {{ $data->supplier->name }} </td>
          <td class="text-center list-td"> {{ isset($data->paymentMethod) ? $data->paymentMethod->name : 'N/A' }} </td>
          <td class="text-center list-td"> {{ formatCurrencyAmount($data->amount) }} </td>
          <td class="text-center list-td"> {{ $data->currency->name }} </td>
          <td class="text-center list-td"> {{ formatDate($data->transaction_date) }} </td>
      </tr> 
    @endforeach 
</table>
@endsection
