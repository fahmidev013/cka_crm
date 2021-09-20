@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Team Member Purchase Payment Lists') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Team Member Purchase Payments') }}</strong>
    </p>
    <p class="title">
      <span class="title-text">{{ __('User:') }} </span>{{ !empty($teamMembers) ? $teamMembers->full_name : __('All') }}
    </p>
    <p class="title">
      <span class="title-text">{{ __('Supplier:') }} </span>{{ isset($supplierData) && !empty($supplierData) ? $supplierData->name : __('All') }}
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
          <td class="text-center list-th"> {{ __('Payment No') }} #</td>
          <td class="text-center list-th"> {{ __('Purchase No') }} </td>
          <td class="text-center list-th"> {{ __('Supplier Name') }} </td>
          <td class="text-center list-th"> {{ __('Payment Method') }} </td>
          <td class="text-center list-th"> {{ __('Amount') }} </td>
          <td class="text-center list-th"> {{ __('Currency') }} </td>
          <td class="text-center list-th"> {{ __('Date') }} </td>
        </tr>
    </thead>

    @foreach($purchPaymentList as $data) 
      <tr>
        <td class="text-center list-td"> {{ sprintf("%04d", $data->id) }} </td>
        <td class="text-center list-td"> {{ !empty($data->purchaseOrder) ? $data->purchaseOrder->reference : '' }} </td>
        <td class="text-center list-td"> {{ $data->supplier->name }} </td>
        <td class="text-center list-td"> {{ !empty($data->paymentMethod) ? $data->paymentMethod->name : '-'}} </td>
        <td class="text-center list-td"> {{ formatCurrencyAmount($data->amount) }}  </td>
        <td class="text-center list-td"> {{ $data->currency->name }}  </td>
        <td class="text-center list-td"> {{ formatDate($data->transaction_date) }} </td>
      </tr>
    @endforeach 
</table>
@endsection
