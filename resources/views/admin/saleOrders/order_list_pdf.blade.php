@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Quotation Lists') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Quotation Lists') }}</strong>
    </p>
    <p class="title">
      <span class="title-text">{{ __('Name:') }} </span>{{ isset($customerData) && !empty($customerData) ? $customerData->first_name . ' '. $customerData->last_name : __('All Customer') }}
    </p>
    <p class="title">
      <span class="title-text">{{ __('Location:') }} </span>{{ !empty($locationName) ? $locationName : __('All Locations') }}
    </p>
    @if (isset($date_range) && !empty($date_range))
        <p class="title">
          <span class="title-text">{{ __('Period:') }} </span> {{ $date_range }}
        </p>
    @endif
    <p class="title">
      <span class="title-text">{{ __('Print Date:') }} </span> {{ formatDate(date('d-m-Y')) }}
    </p>
</td>
@endsection

@section('list-table')
<table class="list-table">
    <thead class="list-head">
        <tr>   
          <td class="text-center list-th"> {{ __('Quotation no') }} # </td>
          <td class="text-center list-th"> {{ __('Customer name') }} </td>
          <td class="text-center list-th"> {{ __('Quantity') }} </td>
          <td class="text-center list-th"> {{ __('Total') }} </td>
          <td class="text-center list-th"> {{ __('Currency') }} </td>
          <td class="text-center list-th"> {{ __('Date') }} </td>
        </tr>
    </thead>
    @foreach($orderList as $data)
      <tr>
        <td class="text-center list-td"> {{ $data->reference }} </td>
        <td class="text-center list-td"> {{ isset($data->customer->name) ? $data->customer->name : '' }} </td>
        <td class="text-center list-td"> {{ !empty($data->saleOrderDetails) ? formatCurrencyAmount($data->saleOrderDetails->sum('quantity')) : '0' }} </td>
        <td class="text-center list-td"> {{ formatCurrencyAmount($data->total) }} </td>
        <td class="text-center list-td"> {{ isset($data->currency->name) ? $data->currency->name : '' }} </td>
        <td class="text-center list-td"> {{ formatDate($data->order_date) }} </td>
      </tr>
    @endforeach 
</table>
@endsection
