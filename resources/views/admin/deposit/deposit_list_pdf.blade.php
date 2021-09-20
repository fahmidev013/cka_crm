@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Bank Account Deposit Lists') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Bank Account Deposit Lists') }}</strong>
    </p>
    <p class="title">
      <span class="title-text">{{ __('Name:') }} </span>{{ isset($account_name) && !empty($account_name) ? $account_name->name : __('All') }}
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
        <td class="text-center list-th"> {{ __('A/c Name') }} </td>
        <td class="text-center list-th"> {{ __('A/c Number') }} </td>
        <td class="text-center list-th"> {{ __('Description')  }} </td>
        <td class="text-center list-th"> {{ __('Amount')  }} </td>
        <td class="text-center list-th"> {{ __('Currency') }} </td>
        <td class="text-center list-th"> {{ __('Date') }} </td>
      </tr>
    </thead>
    @foreach($depositList as $data)
      <tr>
        <td class="text-center list-td"> {{ $data->account->name }} </td>       
        <td class="text-center list-td"> {{ $data->account->account_number }} </td>       
        <td class="text-center list-td"> {{ $data->description }} </td>       
        <td class="text-center list-td"> {{ (string) formatCurrencyAmount($data->amount) }} </td> 
        <td class="text-center list-td"> {{ $data->account->currency->name }} </td>      
        <td class="text-center list-td"> {{ formatDate($data->transaction_date) }} </td>       
      </tr>
    @endforeach
</table>
@endsection
