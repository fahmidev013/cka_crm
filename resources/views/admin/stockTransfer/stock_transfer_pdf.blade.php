@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Stock Transfers') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Stock Transfers') }}</strong>
    </p>
    <p class="title">
      <span class="title-text">{{ __('Source:') }} </span>{{ isset($sourceTransfer) && !empty($sourceTransfer) ? $sourceTransfer->name : __('All') }}
    </p>
    <p class="title">
      <span class="title-text">{{ __('Destination:') }} </span>{{ isset($destinationTransfer) && !empty($destinationTransfer) ? $destinationTransfer->name : __('All') }}
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
            <td class="text-center list-th" width="5%"> {{ __('Transfer No') }} </th>
            <td class="text-center list-th" width="15%"> {{ __('Source') }} </th>
            <td class="text-center list-th" width="15%"> {{ __('Destination') }} </th>
            <td class="text-center list-th" width="40%"> {{ __('Note') }} </th>
            <td class="text-center list-th" width="10%"> {{ __('Quantity') }} </th>
            <td class="text-center list-th" width="15%"> {{ __('Date') }} </th>
        </tr>
    </thead>
    @foreach($stocks as $data)
    <tr>
        <td class="text-center list-td"> {{ sprintf("%04d", $data->id) }} </td>
        <td class="text-center list-td"> {{ isset($data->sourceLocation->name) ? $data->sourceLocation->name : ''  }} </td>
        <td class="text-center list-td"> {{ isset($data->destinationLocation->name) ? $data->destinationLocation->name : '' }} </td>
        <td class="text-center list-td"> {{ $data->note }} </td>
        <td class="text-center list-td"> {{ formatCurrencyAmount($data->quantity) }} </td>
        <td class="text-center list-td"> {{ timeZoneformatDate($data->transfer_date) }} </td>
    </tr>
    @endforeach 
</table>
@endsection
