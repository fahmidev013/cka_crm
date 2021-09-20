@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Supplier Payments') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Supplier Payments') }}</strong>
    </p>
    <p class="title">
      <span class="title-text">{{ __('Name:') }} </span>{{ isset($supplierData) ? $supplierData->name : '' }}
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
            <td class="text-center list-th"> {{ __('Payment no') }} </td>
            <td class="text-center list-th"> {{ __('Purchase no') }} </td>
            <td class="text-center list-th"> {{ __('Payment method') }} </td>
            <td class="text-center list-th"> {{ __('Amount') }} </td>
            <td class="text-center list-th"> {{ __('Currency') }} </td>
            <td class="text-center list-th"> {{ __('Payment date') }} </td>
        </tr>
    </thead>
    <?php
     $total_amount = 0;
    ?>
    @if (isset($paymentList) && !empty($paymentList))
        @foreach($paymentList as $data)
          <?php
            $total_amount += $data->amount;
          ?>
          <tr>
              <td class="text-center list-td"> {{ sprintf("%04d", $data->id) }} </td>
              <td class="text-center list-td"> {{ isset($data->purchaseOrder->reference) ? $data->purchaseOrder->reference : '' }} </td>
              <td class="text-center list-td"> {{ isset($data->paymentMethod->name) ? $data->paymentMethod->name : '' }} </td>
              <td class="text-center list-td"> {{ formatCurrencyAmount($data->amount) }} </td>
              <td class="text-center list-td"> {{ isset($data->currency->name) ? $data->currency->name : '' }} </td>
              <td class="text-center list-td"> {{ formatDate($data->transaction_date) }} </td>
          </tr> 
        @endforeach 
    @endif
    <tr>
        <td class="text-center list-td"></td>
        <td class="text-center list-td"></td>
        <td class="text-center list-td"> <strong>{{ __('Total') }} = </strong></td>
        <td class="text-center list-td"><strong>{{ formatCurrencyAmount($total_amount, isset($data->currency->name) ? $data->currency->name : '') }}</strong></td>
        <td class="text-center list-td"></td>
    </tr>
</table>
@endsection
