@extends('layouts.list_pdf')
@section('pdf-title')
<title>{{ __('Stock Adjustment Lists') }}</title>
@endsection
@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text">{{ __('Stock Adjustment Lists') }}</span>
    </p>
    <p class="title">
      <span class="title-text">{{ __('Location:') }} {{ isset($adjustmentLocation) && !empty($adjustmentLocation) ? $adjustmentLocation->name : __('All') }}</span>
    </p>
    @if (isset($date_range) && !empty($date_range))
        <p class="title">
          <span class="title-text">{{ __('Period:') }} </span > {{ $date_range }}
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
              <td class="text-center list-th"> {{ __('S/N') }} # </td>
              <td class="text-center list-th"> {{ __('Transaction type') }} </td>
              <td class="text-center list-th"> {{ __('Location') }} </td>
              <td class="text-center list-th"> {{ __('Quantity') }} </td>
              <td class="text-center list-th"> {{ __('Note') }} </td>
              <td class="text-center list-th"> {{ __('Date') }} </td>
            </tr>
        </thead>
        @foreach($stocks as $data)
            <tr>
                <td class="text-center list-td white-space-nowrap">{{ sprintf("%04d", $data->id) }}</td>
                <td class="text-center list-td white-space-nowrap">{{ ($data->transaction_type == 'STOCKIN') ? "Stock In" : "Stock Out" }}</td>
                <td class="text-center list-td white-space-nowrap">{{ isset( $data->location->name ) ? $data->location->name : "N/A" }}</td>
                <td class="text-center list-td white-space-nowrap">{{ formatCurrencyAmount($data->total_quantity) }}</td>
                <td class="text-center list-td text-justify">{{ $data->note }}</td>
                <td class="text-center list-td white-space-nowrap">{{ timeZoneformatDate($data->transaction_date) }}</td>
            </tr>
        @endforeach 
    </table>
@endsection
