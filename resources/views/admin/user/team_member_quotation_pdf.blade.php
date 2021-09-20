@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Team Member Quation Lists') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Team Member Quations') }}</strong>
    </p>
    <p class="title">
      <span class="title-text">{{ __('User:') }} </span>{{ !empty($teamMembers) ? $teamMembers->full_name : __('All') }}
    </p>
    <p class="title">
      <span class="title-text">{{ __('Customer:') }} </span>{{ !empty($customers) ? $customers->first_name . ' ' . $customers->last_name : __('All') }}
    </p>
    <p class="title">
      <span class="title-text">{{ __('Location:') }} </span>{{ isset($locations) ? $locations->name : __('All') }}
    </p>
    @if(isset($date_range) && !empty($date_range))
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
          <td class="text-center list-th"> {{ __('Quotation No') }} # </td>
          <td class="text-center list-th"> {{ __('Customer Name') }} </td>
          <td class="text-center list-th"> {{ __('Location') }} </td>
          <td class="text-center list-th"> {{ __('Quantity') }} </td>
          <td class="text-center list-th"> {{ __('Total') }} </td>
          <td class="text-center list-th"> {{ __('Currency') }} </td>
          <td class="text-center list-th"> {{ __('Date') }} </td>
        </tr>
    </thead>

    @foreach($salesData as $data)
      <tr>
        <td class="text-center list-td"> {{ $data->reference }} </td>
        <td class="text-center list-td"> {{ $data->customer->first_name . ' ' . $data->customer->last_name}} </td>
        <td class="text-center list-td"> {{ isset($data->location->name) && !empty($data->location->name) ? $data->location->name : ''}} </td>
        <td class="text-center list-td"> {{ !empty($data->saleOrderDetails) ? formatCurrencyAmount($data->saleOrderDetails->sum('quantity')) : '0' }} </td>
        <td class="text-center list-td"> {{ formatCurrencyAmount($data->total) }} </td>
        <td class="text-center list-td"> {{ isset($data->currency->name) ? $data->currency->name : '' }} </td>
        <td class="text-center list-td"> {{ formatDate($data->order_date) }} </td>
      </tr>
    @endforeach 
</table>
@endsection
