@extends('layouts.list_pdf')
@section('pdf-title')
<title>{{ __('Bank Account Lists') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text">{{ __('Bank Account Lists') }}</span>
    </p>
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
          <td class="text-center list-th"> {{ __('Bank Name') }} </td>
          <td class="text-center list-th"> {{ __('Currency') }} </td>
          <td class="text-center list-th"> {{ __('Bank Address') }} </td>
          <td class="text-center list-th"> {{ __('Balance') }} </td>
        </tr>
    </thead>
    @foreach($banks as $bank)
      <tr>
        <td class="text-center list-td"> {{ ucfirst($bank->name) }}<br>({{ $bank->accountType['name'] }}) </td>
        <td class="text-center list-td"> {{ $bank->account_number }} </td>             
        <td class="text-center list-td"> {{ $bank->bank_name }} </td>
        <td class="text-center list-td"> {{ $bank->currency->name }} </td>
        <td class="text-center list-td"> {!! $bank->bank_address !!} </td>      
        <td class="text-center list-td"> {{ formatCurrencyAmount($bank->transactions->sum('amount')) }} </td>      
      </tr>
    @endforeach
</table>
@endsection
