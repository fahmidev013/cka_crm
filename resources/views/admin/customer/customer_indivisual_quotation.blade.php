@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Customer Quotations') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"><strong>{{ __('Customer Quotations') }}</strong></span>
    </p>
    <p class="title">
      <span class="title-text">{{ __('Name:') }}</span>{{ isset($customerName) ? $customerName->name : __('')}}
    </p>
    @if(isset($date_range) && !empty($date_range))
        <p class="title">
          <span class="title-text">{{ __('Period:') }}</span> {{$date_range}}
        </p>
    @endif
    <p class="title">
      <span class="title-text">{{ __('Print Date:') }}</span> {{formatDate(date('d-m-Y'))}}
    </p>
</td>
@endsection

@section('list-table')
<table class="list-table">
    <thead class="list-head">
        <tr>
          <th class="text-center list-th"> {{ __('Quotation') }} </th>
          <th class="text-center list-th"> {{ __('Total Quantity') }} </th>
          <th class="text-center list-th"> {{ __('Total Amount') }} </th>
          <th class="text-center list-th"> {{ __('Currency') }} </th>
          <th class="text-center list-th"> {{ __('Quotation Converted') }} </th>
          <th class="text-center list-th"> {{ __('Quotation Date') }} </th>
        </tr>
    </thead>
    <?php $amount = $qty = 0; ?>
    @foreach ($customerOrder as $data)
      <?php
        $amount += $data->total;
        $currency = $data->currency->name;
        $qty += !empty($data->saleOrderDetails) ? $data->saleOrderDetails->sum('quantity') : 0;
      ?>
       <tr>
          <td class="text-center list-td"> {{ $data->reference }}</td>
          <td class="text-center list-td"> {{ !empty($data->saleOrderDetails) ? $data->saleOrderDetails->sum('quantity') : '0' }} </td>
          <td class="text-center list-td"> {{ formatCurrencyAmount($data->total) }} </td>
          <td class="text-center list-td">{{ $currency }}</td>
          <?php
            $checkInvoice = (new App\Models\SaleOrder)->checkConversion($data->id)->first();
            if (!isset($checkInvoice->reference)) {
                $quotationConverted =  __('No');
            } else {
                $quotationConverted = __('Yes');
            }
          ?>
          <td class="text-center list-td"> {{ $quotationConverted }} </td>
          <td class="text-center list-td"> {{ formatDate($data->order_date) }} </td>
      </tr>
    @endforeach
    <tr>
       <td> <strong class="quotation-summation-padding-left"> {{ __('Total') }} </strong> </td>
       <td><strong class="quotation-summation-padding-left"> {{ $qty }} </strong></td>
       <td><strong class="quotation-summation-padding-left"> {{ !empty($currency) ? formatCurrencyAmount($amount, $currency) : 0 }} </strong></td>
       <td></td>
       <td></td>
    </tr>
</table>
@endsection
