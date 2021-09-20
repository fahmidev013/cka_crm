@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Team Member Invoice Lists') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Team Member Invoices') }}</strong>
    </p>
    <p class="title">
      <span class="title-text">{{ __('User:') }} </span>{{ !empty($teamMembers) ? $teamMembers->full_name : __('All') }}
    </p>
    <p class="title">
      <span class="title-text">{{ __('Customer:') }} </span>{{ !empty($customers) ? $customers->first_name.' '.$customers->last_name : __('All') }}
    </p>
    <p class="title">
      <span class="title-text">{{ __('Location:') }} </span>{{ isset($locations) ? $locations->name : __('All') }}
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
          <td class="text-center list-th"> {{ __('Invoice') }} </td>
          <td class="text-center list-th"> {{ __('Customer Name') }} </td>
          <td class="text-center list-th"> {{ __('Total Price') }} </td>
          <td class="text-center list-th"> {{ __('Paid Amount') }} </td>
          <td class="text-center list-th"> {{ __('Currency') }} </td>
          <td class="text-center list-th"> {{ __('Status') }} </td>
          <td class="text-center list-th"> {{ __('Date') }} </td>  
        </tr>
    </thead>
    @foreach($salesData as $data)
      <tr>
        <td class="text-center list-td"> {{ $data->reference }} </td>
        <td class="text-center list-td"> {{ isset($data->customer) ? $data->customer->name : __('Walking customer') . ' ('. $data->currency->name .')' }} </td>
        <td class="text-center list-td"> {{ formatCurrencyAmount($data->total) }} </td>
        <td class="text-center list-td"> {{ formatCurrencyAmount($data->paid_amount) }} </td>
        <td class="text-center list-td"> {{ $data->currency->name }} </td>
        @if ($data->paid == 0  && $data->total != 0)
          <td class="text-center list-td"> <span class="label label-danger">{{ __('Unpaid') }}</span> </td>
        @elseif ($data->paid > 0 && $data->total > $data->paid)
          <td class="text-center list-td"> <span class="label label-warning">{{ __('Partially Paid') }}</span> </td>
        @elseif ($data->paid <= $data->paid || $data->paid == 0)
          <td class="text-center list-td"> <span class="label label-success">{{ __('Paid') }}</span> </td>
        @endif
        <td class="text-center list-td"> {{ formatDate($data->order_date) }} </td>
      </tr>
    @endforeach
</table>
@endsection
