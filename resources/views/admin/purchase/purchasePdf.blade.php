@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Purchase Lists') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Purchase Lists') }}</strong>
    </p>
    <p class="title">
      <span class="title-text">{{ __('Name:') }}</span>{{ isset($supplierData) && !empty($supplierData) ? $supplierData->name : __('All Suppliers') }}
    </p>
    @if(isset($date_range) && !empty($date_range))
        <p class="title">
          <span class="title-text">{{ __('Period:') }}</span> {{ $date_range }}
        </p>
    @endif
    <p class="title">
      <span class="title-text">{{ __('Print Date:') }}: </span> {{ formatDate(date('d-m-Y')) }}
    </p>
</td>
@endsection

@section('list-table')
<table class="list-table">
    <thead class="list-head">
        <tr>   
          <td class="text-center list-th"> {{ __('Purchase no') }} # </td>
          <td class="text-center list-th"> {{ __('Supplier name') }} </td>
          <td class="text-center list-th"> {{ __('Total') }} </td>
          <td class="text-center list-th"> {{ __('Paid amount') }} </td>
          <td class="text-center list-th"> {{ __('Currency') }} </td>
          <td class="text-center list-th"> {{ __('Date') }} </td>
          <td class="text-center list-th"> {{ __('Status') }} </td>
        </tr>
    </thead>
    @foreach($purchData as $data)
      <tr>
          <td class="text-center list-td"> {{ $data->reference }} </td>     
          <td class="text-center list-td"> {{ $data->supplier->name }} </td>     
          <td class="text-center list-td"> {{ $data->total == 0 ? formatCurrencyAmount(0) : formatCurrencyAmount($data->total) }} </td><td class="text-center list-td"> {{ $data->total == 0 ? formatCurrencyAmount(0) : formatCurrencyAmount($data->paid) }} </td> 
          <td class="text-center list-td"> {{ isset($data->currency->name) ? $data->currency->name : '' }} </td>  
          <td class="text-center list-td"> {{ formatDate($data->order_date) }} </td>       
          <td class="text-center list-td"> 
             @if($data->paid == 0)
                {{ __('Unpaid') }}
              @elseif($data->paid > 0 && $data->total > $data->paid)
                {{ __('Partially Paid') }}
              @elseif($data->paid <= $data->paid)
                {{ __('Paid') }}
              @endif
          </td>
      </tr> 
    @endforeach 
</table>
@endsection
