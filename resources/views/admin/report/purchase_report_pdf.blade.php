@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Purchase Reports') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Purchase Reports') }}</strong>
    </p>
    @if (isset($supplierDetails) && !empty($supplierDetails))
    <p class="title">
      <span class="title-text">{{__('Supplier:')}} </span>{{ $supplierDetails }}
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
        <td class="text-center list-th"> {{ __('Purchase volume') }} </td>
        <td class="text-center list-th"> {{ __('Cost') }}({{ $currencyShortName }})</td>
        <td class="text-center list-th"> {{ __('Tax') }}({{ $currencyShortName }})</td>
        <td class="text-center list-th"> {{ __('Discount') }}({{ $currencyShortName }})</td>
      </tr>
    </thead>
    <?php
      $qty = $cost = $order = $totalPurchaseTax = $totalProfitAmount = $totalDiscount = 0; ?>
    @foreach ($list as $key => $value)
    <?php
      $qty   += $value['totalQuantity'];
      $cost  += $value['totalAmount'];
      $order += $value['totalInvoice'];
      $totalPurchaseTax += $value['totalPurchaseTax'];
      $totalDiscount += $value['itemDiscountAmount'] + $value['otherDiscountAmount'];
    ?>
    <tr>  
      <td class="text-center list-td"> 
        <?php
          if ($type == 'custom' || $type == 'daily') {
            echo date('d-m-Y',strtotime($key));
          }else if($type == 'monthly' || $type == 'yearly'){
            echo date('F-Y',strtotime($key));
          }
        ?>
      </td>
      <td class="text-center list-td"> {{ $value['totalInvoice'] }} </td>
      <td class="text-center list-td"> {{ $value['totalQuantity'] }} </td>
      <td class="text-center list-td"> {{ formatCurrencyAmount($value['totalAmount']) }} </td>
      <td class="text-center list-td"> {{ formatCurrencyAmount($value['totalPurchaseTax']) }} </td>
    <td class="text-center list-td"> {{ formatCurrencyAmount($value['itemDiscountAmount'] + $value['otherDiscountAmount']) }} </td>
    </tr>
    @endforeach  
    <tr>
      <td class="text-center list-td"> <strong>{{ __('Total') }} </strong></td>
      <td class="text-center list-td"> <strong>{{ $order }} </strong></td>
      <td class="text-center list-td"> <strong>{{ $qty }} </strong></td>
      <td class="text-center list-td"> <strong>{{ formatCurrencyAmount($cost) }} </strong></td> 
      <td class="text-center list-td"> <strong>{{ formatCurrencyAmount($totalPurchaseTax) }}</strong> </td>
    <td class="text-center list-td"> <strong>{{ formatCurrencyAmount($totalDiscount) }}</strong> </td>
    </tr>
</table>
@endsection
