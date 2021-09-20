@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Supplier Ledger') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Supplier Ledger') }}</strong>
    </p>
    <p class="title">
      <span class="title-text">{{ __('Name:') }} </span>{{ isset($supplier) ? $supplier : ''}}
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
            <td class="text-center list-th"> {{ __('SL No') }} </td>
            <td class="text-center list-th"> {{ __('Date') }} </td>
            <td class="text-center list-th"> {{ __('Purchase Order') }} </td>
            <td class="text-center list-th"> {{ __('Paid Amount') }} </td>
            <td class="text-center list-th"> {{ __('Bill Amount') }} </td>
            <td class="text-center list-th"> {{ __('Balance') }} </td>
            <td class="text-center list-th"> {{ __('Currency') }} </td>
        </tr>
    </thead>
    @php
      $balance = $totalPaid = $totalBill = 0;
    @endphp 
    @foreach($supplierLedger as $key=>$data)
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
        @if(!empty($data['purchase_order_id']))
          {{ $data['purchase_order']['reference'] }}
        @else
          {{ $data['reference'] }}
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
      <td class="text-center list-td"> {{ formatCurrencyAmount($balance) }} </td>
      <td class="text-center list-td"> {{ $data['currency']['name'] }} </td>
    </tr> 
    @endforeach
    <tr>
      <td></td>
      <td></td>
      <td><strong>{{ __('Total') }} = </strong></td>
      @php
        $supplierLedger = !empty($supplierLedger[0]['currency']['name']) ? $supplierLedger[0]['currency']['name'] : '';
      @endphp
      <td><strong>{{ formatCurrencyAmount($totalPaid, $supplierLedger) }}</strong></td>
      <td><strong>{{ formatCurrencyAmount($totalBill, $supplierLedger) }}</strong></td>
      <td><strong>{{ formatCurrencyAmount($balance, $supplierLedger) }}</strong></td>
      <td></td>
    </tr>
</table>
@endsection
