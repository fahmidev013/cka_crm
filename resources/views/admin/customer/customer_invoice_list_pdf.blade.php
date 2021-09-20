@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Customer Invoices') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
  <p class="title">
      <span class="title-text">{{ __('Customer Invoices') }}</span>
    </p>
    <p class="title">
      <span class="title-text">{{ __('Name:') }} </span>{{ isset($customers) ? $customers->name : '' }}</b>
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
      <td class="text-center list-th"> {{ __('Invoice No') }} </td>
      <td class="text-center list-th"> {{ __('Total Price') }} </td>
      <td class="text-center list-th"> {{ __('Paid Amount') }} </td>
      <td class="text-center list-th"> {{ __('Currency') }} </td>
      <td class="text-center list-th"> {{ __('Status') }} </td>
      <td class="text-center list-th"> {{ __('Invoice Date') }} </td>
    </tr>
  </thead>
  <?php
      $total       = 0;
      $paid_amount = 0;
  ?> 
  @foreach($salesList as $data)
    <?php
        $total += $data->total;
        $paid_amount += $data->paid;
        if ($data->paid == 0) {
           $status = 'Unpaid';
        } elseif ($data->paid > 0 && $data->total > $data->paid ) {
           $status = 'Partially Paid';
        } elseif ($data->total<=$data->paid) {
           $status = 'Paid';
        }        
    ?> 

  <tr> 
    <td class="text-center list-td"> {{ $data->reference }} </td>
    <td class="text-center list-td"> {{ formatCurrencyAmount($data->total) }} </td>
    <td class="text-center list-td"> {{ formatCurrencyAmount($data->paid) }} </td>
    <td class="text-center list-td"> {{ isset($customers->currency->name) && !empty($customers->currency->name) ? $customers->currency->name : '' }} </td>
    <td class="text-center list-td"> {{ $status }} </td>
    <td class="text-center list-td"> {{ formatDate($data->order_date) }} </td>
  </tr>
  @endforeach  
  <tr>
    <td><strong> {{ __('Total') }} </strong></td>
    <td><strong> {{ formatCurrencyAmount(abs($total), $customers->currency->name) }}</strong></td>
    <td><strong> {{ formatCurrencyAmount(abs($paid_amount), $customers->currency->name) }}</strong></td>
    <td></td>
    <td></td>
  </tr> 
</table>
@endsection
