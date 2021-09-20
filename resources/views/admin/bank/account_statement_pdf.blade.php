@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Bank Statement') }}</title>
@endsection

@section('header-info')
  <td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text">{{ __('Bank Statement') }}</span>
    </p>
    <p class="title">
      <span class="title-text">{{ __('Account') }}: </span>
      @if($bankAccounts != 'All')
       {{ $bankAccounts->name }} ({{ $bankAccounts->currency->name }})
     @else
       {{ $bankAccounts }}
     @endif
    </p>
    <p class="title">
      <span class="title-text">{{ __('Period') }}: </span>{{ $date_range }}
    </p>
    <p class="title">
      <span class="title-text">{{ __('Print Date') }}: </span> {{ formatDate(date('d-m-Y')) }}
    </p>
  </td>
@endsection

@section('list-table')
  <table class="list-table">
    <thead class="list-head">
        <tr>   
          <td class="text-center list-th">{{ __('Date') }}</td>
          <td class="text-center list-th">{{ __('Type') }}</td>
          <td class="text-center list-th">{{ __('Description') }}</td>
          <td class="text-center list-th">{{ __('Cash out (Credit)') }}</td>
          <td class="text-center list-th">{{ __('Cash in (Debit)') }}</td>
          <td class="text-center list-th">{{ __('Balance') }}</td>
        </tr>
     </thead> 
     <tr>
      <td class="text-center list-td"></td>
      <td class="text-center list-td"></td>
      <td class="text-center list-th"><strong>{{ __('Balance On') }} {{ formatDate($from) }}</strong></td>
      <td class="text-center list-td"></td>
      <td class="text-center list-td"></td>
      <td class="text-center list-th"><strong>{{ formatCurrencyAmount($amount) }}</strong></td>
     </tr>  
        <?php
          $totalCredit = 0;
          $totalDebit = 0;
        ?>
          @foreach($transactionList as $key=>$data)
          <?php
            if ($key == 0) {
              $openingBalance = $amount;
            } 
            $newBalance = $openingBalance + $data->amount;
            $openingBalance = $newBalance;

            if ($data->amount < 0) {
              $totalDebit += $data->amount;
            } else {
              $totalCredit += $data->amount;
            }
          ?>
        <tr>
          <td class="text-center list-td">{{ formatDate($data->transaction_date) }}</td>
          <td class="text-center list-td">{{ ucwords(str_replace ('_',' ', strtolower($data->transaction_method))) }}</td>
          <td class="text-center list-td">{{ $data->description }}</td>
          <td class="text-center list-td">
            @if($data->amount <= 0)
              {{ formatCurrencyAmount(abs($data->amount)) }}
            @else
              {{ formatCurrencyAmount(0) }}
            @endif
          </td>
          <td class="text-center list-td">
            @if($data->amount > 0)
              {{ formatCurrencyAmount(abs($data->amount)) }}
            @else
              {{ formatCurrencyAmount(0) }}
            @endif
          </td>      
          <td class="text-center list-td">{{ formatCurrencyAmount($newBalance) }}</td>      
        </tr>
        @endforeach 
        <tr>
          <td class="text-center list-td"></td>
          <td class="text-center list-td"></td>
          <td class="text-center list-td"><strong>{{ __('Balance On') }} {{ formatDate(date('Y-m-d')) }}</strong></td>
          <td class="text-center list-td"><strong>{{ formatCurrencyAmount(abs($totalDebit), $presentCurrency->name) }}</strong></td>
          <td class="text-center list-td"><strong>{{ formatCurrencyAmount(abs($totalCredit), $presentCurrency->name) }}</strong></td>
          <td class="text-center list-td"><strong>{{ formatCurrencyAmount($totalCredit + $totalDebit + $amount, $presentCurrency->name) }}</strong></td>
       </tr>
    </table>
@endsection