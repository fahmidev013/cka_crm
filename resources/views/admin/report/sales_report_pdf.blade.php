@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Sale Reports') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Sale Reports') }}</strong>
    </p>
    @if (isset($customerDetails) && !empty($customerDetails))
    <p class="title">
      <span class="title-text">{{__('Customer:')}} </span>{{ $customerDetails }}
    </p>
    @endif
    @if (isset($locationDetails) && !empty($locationDetails))
    <p class="title">
      <span class="title-text">{{__('Location:')}} </span>{{ $locationDetails }}
    </p>
    @endif
    @if (isset($itemDetails) && !empty($itemDetails))
    <p class="title">
      <span class="title-text">{{__('Product/Service:')}} </span>{{ $itemDetails }}
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
        <td class="text-center list-th">
          <?php
            if ($searchType == 'daily' || $searchType == 'custom') {
                echo __('Date');
            } else if ($searchType == 'monthly' || $searchType == 'yearly' ) {
                echo  __('Month');
            } else {
                echo  __('Date');
            }
           ?>
        </td>
        <td class="text-center list-th"> {{ __('No of invoice') }} </td>
        <td class="text-center list-th"> {{ __('Volume') }} </td>
        <td class="text-center list-th"> {{ __('Sales value') }}({{ $currencyShortName }})</td>
        <td class="text-center list-th"> {{ __('Cost') }}({{ $currencyShortName }})</td>
        <td class="text-center list-th"> {{ __('Tax') }}({{ $currencyShortName }})</td>
        <td class="text-center list-th"> {{ __('Discount') }}({{ $currencyShortName }})</td>
        <td class="text-center list-th"> {{ __('Profit') }}({{ $currencyShortName }})</td>
      </tr>
    </thead>
    <?php
      $cost = $actualSale = $order = $qty = $totalPurchase = $totalSaleTax = $totalProfitAmount = $totalDiscount  = 0;
      $orderDate          = '';
    ?>
  @foreach ($itemList as $key => $value)
    <?php
      $cost               += $value['totalAmount'];
      $actualSale         += $value['totalactualSalePrices'];
      $order              += $value['totalInvoice'];
      $qty                += $value['totalQuantity'];
      $totalPurchase      += $value['totalPurchase'];
      $totalSaleTax       += $value['totalSaleTax'];
      $totalProfitAmount  += $value['totalProfitAmount'];
      $totalDiscount      += $value['itemDiscountAmount'] + $value['otherDiscountAmount'];
      $orderDate           = $value['orderDate'];
    ?>
  <tr>  
    <td class="text-center list-td"> 
       <?php
        if ($searchType == 'daily' || $searchType == 'custom') {
            echo formatDate(date('d-m-Y', strtotime($key)));
        } else if ($searchType == 'monthly' || $searchType == 'yearly' ) {
            echo date('F-Y', strtotime($key));
        } else {
            echo formatDate(date('d-m-Y', strtotime($key)));
        }
       ?>    
    </td>
    <td class="text-center list-td"> {{ $value['totalInvoice'] }} </td>
    <td class="text-center list-td"> {{ $value['totalQuantity'] }} </td>
    <td class="text-center list-td"> {{ formatCurrencyAmount($value['totalactualSalePrices']) }} </td>
    <td class="text-center list-td"> {{ formatCurrencyAmount($value['totalPurchase']) }} </td>
    <td class="text-center list-td"> {{ formatCurrencyAmount($value['totalSaleTax']) }} </td>
    <td class="text-center list-td"> {{ formatCurrencyAmount($value['itemDiscountAmount'] + $value['otherDiscountAmount']) }} </td>
    <td class="text-center list-td"> {{ formatCurrencyAmount($value['totalProfitAmount']) }} </td>
  </tr>
  @endforeach  
  <tr>
    <td class="text-center list-td"> <strong>{{ __('Total =') }}</strong> </td>
    <td class="text-center list-td"> <strong>{{ $order}}</strong> </td>
    <td class="text-center list-td"> <strong>{{ $qty}}</strong> </td>
    <td class="text-center list-td"> <strong>{{ formatCurrencyAmount($actualSale) }}</strong> </td>
    <td class="text-center list-td"> <strong>{{ formatCurrencyAmount($totalPurchase) }}</strong> </td>
    <td class="text-center list-td"> <strong>{{ formatCurrencyAmount($totalSaleTax) }}</strong> </td>
    <td class="text-center list-td"> <strong>{{ formatCurrencyAmount($totalDiscount) }}</strong> </td>
    <td class="text-center list-td"> <strong>{{ formatCurrencyAmount($totalProfitAmount) }}</strong> </td>            
  </tr> 
</table>
@endsection
