@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Customer Payments') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Customer Payments') }}</strong>
    </p>
    <p class="title">
      <span class="title-text">{{ __('Name:') }} </span>{{ isset($customers) ? $customers->name : '' }}
    </p>
    @if (isset($date_range) && !empty($date_range))
        <p class="title">
          <span class="title-text">{{ __('Period:') }} </span> {{$date_range}}
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
          <td class="text-center list-th"> {{__('No')}} </td>
          <td class="text-center list-th"> {{ __('Invoice No') }} </td>
          <td class="text-center list-th"> {{ __('Payment Method') }} </td>
          <td class="text-center list-th"> {{ __('Amount') }} </td>
          <td class="text-center list-th"> {{ __('Currency') }} </td>
          <td class="text-center list-th"> {{ __('Payment Date') }} </td>
        </tr>
    </thead>
    <?php $amount = 0; ?> 
    @foreach ($paymentList as $data)
      <?php $amount += $data->amount; ?>
      <tr>  
        <td class="text-center list-td"> {{ sprintf("%04d", $data->id) }} </td>
        <td class="text-center list-td"> {{ $data->saleOrder->reference }} </td>
        <td class="text-center list-td"> {{ !empty($data->paymentMethod) ? $data->paymentMethod->name : '' }} </td>
        <td class="text-center list-td"> {{ !empty($data->amount) ? formatCurrencyAmount($data->amount) : '' }} </td>
        <td class="text-center list-td"> {{ $data->currency->name }} </td>
        <td class="text-center list-td"> {{ formatDate($data->transaction_date) }} </td>
      </tr>
    @endforeach  
    @if (!empty($data->amount))
      <tr>
        <td class="text-center list-td"><strong>{{  __('Total') }}</strong></td>
        <td></td>
        <td></td>
        <td class="text-center list-td"><strong>{{ !empty($data->amount) ? formatCurrencyAmount(abs($amount), $data->currency->name) : '' }}</strong></td>
        <td></td>
        <td></td>
      </tr>
    @endif 
  </table>
@endsection
