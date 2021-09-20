@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Inventory Stock on Hand') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Inventory Stock on Hand') }}</strong>
    </p>
    @if (isset($locationSelected) && !empty($locationSelected))
    <p class="title">
      <span class="title-text">{{__('Location:')}} </span>{{ $locationSelected->name }}
    </p>
    @endif
    @if (isset($categorySelected) && !empty($categorySelected))
    <p class="title">
      <span class="title-text">{{__('Category:')}} </span>{{ $categorySelected->name }}
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
          <td class="text-center list-th"> {{ __('Product (Stock id)') }} </td>
          <td class="text-center list-th"> {{ __('In stock') }} </td>
          <td class="text-center list-th"> {{ __('Purchase price') }} </td>
          <td class="text-center list-th"> {{ __('Retail price') }} </td>
          <td class="text-center list-th"> {{ __('In value') }} </td>
          <td class="text-center list-th"> {{ __('Retail value') }} </td>
          <td class="text-center list-th"> {{ __('Profit value') }} </td>
        </tr>
    </thead>
    <?php
      $inValue = 0;
      $retailValue = 0;
      $profitValue = 0;
      $profitMargin = 0;
    ?>
    @foreach ($itemList as $onHand)
      <?php
        if ($onHand->available_qty != 0 ) {
          $inValue = $onHand->purchase_price * $onHand->available_qty;
          $retailValue = $onHand->retail_price * $onHand->available_qty;
        }
        $profitValue = ($retailValue - $inValue);
        if ($inValue != 0) {
          $profitMargin = ($profitValue*100/$inValue);
        }
      ?>

    <tr>
      <td class="text-center list-td"> {{ $onHand->description . ' (' . $onHand->item_id . ')' }} </td>
      <td class="text-center list-td"> {{ formatCurrencyAmount($onHand->available_qty) }} </td>
      <td class="text-center list-td"> {{ formatCurrencyAmount($onHand->purchase_price, $currency_symbol) }} </td>
      <td class="text-center list-td"> {{ formatCurrencyAmount($onHand->retail_price, $currency_symbol) }} </td>
      <td class="text-center list-td"> {{ formatCurrencyAmount($inValue, $currency_symbol) }} </td>
      <td class="text-center list-td"> {{ formatCurrencyAmount($retailValue, $currency_symbol) }} </td>
      <td class="text-center list-td"> {{ formatCurrencyAmount($profitValue, $currency_symbol) }} <br> {{ formatCurrencyAmount($profitMargin) }}% </td>
    </tr>
    @endforeach 
    <tr>
      <td class="text-center list-td"> <strong>{{ __('Total =') }}</strong> </td>
      <td class="text-center list-td"> <strong>{{ $qtyOnHand }}</strong> </td>
      <td class="text-center list-td"> <strong>&nbsp;</strong> </td>
      <td class="text-center list-td"> <strong>&nbsp;</strong> </td>
      <td class="text-center list-td"> 
        <strong>
          {{ $currency_symbol . formatCurrencyAmount($costValueQtyOnHand) }}
        </strong>
      </td>
      <td class="text-center list-td"> 
        <strong>{{ $currency_symbol . formatCurrencyAmount($retailValueOnHand) }}</strong> </td>
      <td class="text-center list-td"> <strong>{{ $currency_symbol . formatCurrencyAmount($profitValueOnHand) }}</strong> </td>
    </tr>
</table>
@endsection
