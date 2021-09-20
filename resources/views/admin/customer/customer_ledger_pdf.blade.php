@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Customer Ledger') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
  <p class="title">
    <span class="title-text">{{ __('Customer Ledger') }}</span>
  </p>
  <p class="title">
    <span class="title-text">{{ __('Name:') }} </span><b>{{ isset($customerData) ? $customerData->name : '' }}</b>
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
      <td class="text-center list-th"> {{ __('S/N') }} </th>
      <td class="text-center list-th"> {{ __('Date') }} </th>
      <td class="text-center list-th"> {{ __('Invoice No') }} </th>
      <td class="text-center list-th"> {{ __('Paid Amount') }} </th>
      <td class="text-center list-th"> {{ __('Bill Amount') }} </th>
        <td class="text-center list-th"> {{ __('Currency') }} </th>
      <td class="text-center list-th"> {{ __('Balance') }} </th>   
    </tr>
  </thead>
  @php
    $balance = $totalPaid = $totalBill = 0;
  @endphp 
  @foreach($customer_ledger as $key => $data)
   @php
     $balance += isset($data['total']) ? $data['total'] : 0;
     $balance -= isset($data['amount']) ? $data['amount'] : 0;
     $totalPaid += isset($data['amount']) ? $data['amount'] : 0;
     $totalBill += isset($data['total']) ? $data['total'] : 0;
   @endphp
  <tr>
    <td class="text-center list-td"> {{ ++$key }} </td>
    <td class="text-center list-td"> {{ formatDate($data['transaction_date']) }} </td>
    <td class="text-center list-td"> 
      @if(!empty($data["reference"]))
        {{ $data["reference"] }}
      @elseif(isset($data['sale_order']['reference']))
        {{ $data['sale_order']["reference"] }}
      @endif
     </td>
    <td class="text-center list-td"> 
      @if(isset($data['amount']))
         {{ formatCurrencyAmount($data['amount']) }}
      @else
         {{ '-' }}
      @endif
     </td>
    <td class="text-center list-td"> 
      @if(isset($data['total']))
         {{ formatCurrencyAmount($data['total']) }}
      @else
         {{ '-' }}
      @endif
     </td>
    <td class="text-center list-td"> {{ $customerData->currency->name }} </td>
    <td class="text-center list-td"> {{ formatCurrencyAmount($balance) }} </td>
  </tr>  
  @endforeach
  <tr>
    <td></td>
    <td></td>
    <td><strong> {{ __('Total') }} </strong> </td>
    <td><strong> {{ formatCurrencyAmount($totalPaid, $customerData->currency->name) }}</strong> </td>
    <td><strong> {{ formatCurrencyAmount($totalBill, $customerData->currency->name) }}</strong> </td>
    <td><strong> {{ formatCurrencyAmount($balance, $customerData->currency->name) }}</strong> </td>
  </tr> 
</table>
@endsection
