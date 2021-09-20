@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Purchase Receive Lists') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Purchase Receive Lists') }}</strong>
    </p>
    <p class="title">
      <span class="title-text">{{ __('Name') }} : </span>{{ isset($supplierData) && !empty($supplierData) ? $supplierData->name : __('All Suppliers') }}
    </p>
    @if (isset($date_range) && !empty($date_range))
      <p class="title">
        <span class="title-text">{{ __('Period') }} : </span> {{ $date_range }}
      </p>
    @endif
    <p class="title">
      <span class="title-text">{{ __('Print Date') }} : </span> {{ formatDate(date('d-m-Y')) }}
    </p>
</td>
@endsection

@section('list-table')
<table class="list-table">
    <thead class="list-head">
        <tr>   
          <td class="text-center list-th"> {{ __('Recieve no') }} # </td>
          <td class="text-center list-th"> {{ __('Purchase no') }} </td>
          <td class="text-center list-th"> {{ __('Supplier') }} </td>
          <td class="text-center list-th"> {{ __('Receipt no') }} </td>
          <td class="text-center list-th"> {{ __('Quantity') }} </td>
          <td class="text-center list-th"> {{ __('Date') }} </td>
        </tr>
    </thead>
    @foreach($receiveData as $data)
      <tr>
         <td class="text-center list-td"> {{ $data->ro_id }} </td>       
         <td class="text-center list-td"> {{ $data->reference }} </td>       
         <td class="text-center list-td"> {{ $data->supplier->name }} </td> 
         <td class="text-center list-td"> {{ isset($data->order_receive_no) && !empty($data->order_receive_no) ? $data->order_receive_no : '-' }} </td>       
         <td class="text-center list-td"> {{ $data->receivedOrderDetails->sum('quantity') ? formatCurrencyAmount($data->receivedOrderDetails->sum('quantity')) : formatCurrencyAmount(0) }} </td>      
         <td class="text-center list-td"> {{ formatDate($data->receive_date) }} </td>
      </tr>
    @endforeach 
</table>
@endsection
