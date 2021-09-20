@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Bank Accounts Transaction Lists') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Bank Accounts Transaction Lists') }}</strong>
    </p>
    <p class="title">
      <span class="title-text">{{ __('Payment Method') }}:  </span>{{ isset($method) && !empty($method) ? $method->name : __('All') }}
    </p>
    <p class="title">
      <span class="title-text">{{ __('Mode') }}:  </span>{{ isset($modeSelected) && !empty($modeSelected) ? $modeSelected : __('All') }}
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
          <td class="text-center list-th"> {{ __('Reference') }} </td>
          <td class="text-center list-th"> {{ __('Payment Method') }} </td>
          <td class="text-center list-th"> {{ __('Type') }} </td>
          <td class="text-center list-th"> {{ __('Description') }} </td>
          <td class="text-center list-th"> {{ __('Debit') }} </td>
          <td class="text-center list-th"> {{ __('Credit') }} </td> 
          <td class="text-center list-th"> {{ __('Currency') }} </td> 
          <td class="text-center list-th"> {{ __('Date') }} </td> 
        </tr>
    </thead>
    @foreach($transactionList as $data)
      <tr>
        <td class="text-center list-td"> {{ $data->reference }} </td>
        <td class="text-center list-td"> {{ $data->payment_method }} <br> {{ !empty($data->name) ?  $data->name : '' }} </td>
        <td class="text-center list-td"> {{ ucwords(str_replace ('_',' ', strtolower($data->transaction_method))) }} </td>
        <td class="text-center list-td"> {{ $data->description }} </td>
        <td class="text-center list-td"> 
          @if($data->amount > 0)
            {{ formatCurrencyAmount($data->amount) }}
          @else
            {{ formatCurrencyAmount(0) }}  
          @endif
        </td>
        <td class="text-center list-td"> 
          @if($data->amount < 0)
            {{ formatCurrencyAmount(abs($data->amount)) }}
          @else
            {{ formatCurrencyAmount(0) }}  
          @endif
        </td>
        <td class="text-center list-td"> {{ $data->currency_name }} </td>
        <td class="text-center list-td"> {{ formatDate($data->transaction_date) }} </td>
      </tr>
    @endforeach
</table>
@endsection
