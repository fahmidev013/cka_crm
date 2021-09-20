@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Customer Payment Lists') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Customer Payment Lists') }}</strong>
    </p>
    <p class="title">
      <span class="title-text">{{__('Name:')}} </span>{{ isset($customerData) && !empty($customerData) ? $customerData->first_name . ' '. $customerData->last_name : __('All Customer') }}
    </p>                                 
    @if (isset($date_range) && !empty($date_range))
        <p class="title">
          <span class="title-text">{{__('Period:')}} </span> {{ $date_range }}
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
          <td class="text-center list-th"> {{ __('No') }} # </td>
          <td class="text-center list-th"> {{ __('Invoice No') }} </td>
          <td class="text-center list-th"> {{ __('Customer Name') }} </td>
          <td class="text-center list-th"> {{ __('Payment Method') }} </td>
          <td class="text-center list-th"> {{ __('Amount') }} </td>
          <td class="text-center list-th"> {{ __('Currency') }} </td>
          <td class="text-center list-th"> {{ __('Status') }} </td>
          <td class="text-center list-th"> {{ __('Date') }} </td>
        </tr>
    </thead>
    @foreach($paymentList as $data)
    <tr>
        <td class="text-center list-td"> {{ sprintf("%04d", $data->id) }} </td>
        <td class="text-center list-td"> {{ $data->saleOrder->reference }} </td>
        <td class="text-center list-td"> {{ $data->customer->name }} </td>
        <td class="text-center list-td"> {{ isset($data->paymentMethod) ? $data->paymentMethod->name : '-' }} </td>
        <td class="text-center list-td"> {{ formatCurrencyAmount($data->amount) }} </td>
        <td class="text-center list-td"> {{ $data->currency->name }} </td>
        <td class="text-center list-td"> {{ $data->status }} </td>
        <td class="text-center list-td"> {{ formatDate($data->transaction_date) }} </td>
    </tr>
    @endforeach
</table>
@endsection
