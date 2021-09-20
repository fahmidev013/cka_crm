@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Item Lists') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Item Lists') }}</strong></b>
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
          <td class="text-center list-th"> {{ __('Item Name')  }} </td>
          <td class="text-center list-th"> {{ __('Category') }} </td>
          <td class="text-center list-th"> {{ __('Purchase') }} ({{ $currency->name }})</td>
          <td class="text-center list-th"> {{ __('Retail') }} ({{ $currency->name }})</td>
        </tr>
    </thead>

    @foreach($item as $data)
      <tr>
        <td class="text-center list-td"> {{ $data->name }} </td>
        <td class="text-center list-td"> {{ $data->category}} </td>
        <td class="text-center list-td"> {{ $data->purcashe_price ? formatCurrencyAmount($data->purcashe_price) : formatCurrencyAmount(0)  }}</td>
        <td class="text-center list-td"> {{ $data->retail_price ? formatCurrencyAmount($data->retail_price) : formatCurrencyAmount(0) }}</td>
      </tr>
    @endforeach 
</table>
@endsection
