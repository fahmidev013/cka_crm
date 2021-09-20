@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Invoice Lists') }}</title>
@endsection

@section('header-info')
 <td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Invoice Lists') }}</strong>
    </p>
    <p class="title">
      <span class="title-text">{{__('Name:')}} </span>{{ isset($customerData) && !empty($customerData) ? $customerData->first_name . ' '. $customerData->last_name : __('All Customer') }}
    </p>
    <p class="title">
      <span class="title-text">{{__('Location:')}} </span>{{ isset($locationName) && !empty($locationName) ? $locationName : __('All Locations') }}
    </p>                                    
    @if(isset($date_range) && !empty($date_range))
        <p class="title">
          <span class="title-text">{{__('Period:')}} </span> {{ $date_range }}
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
          <td class="text-center list-th"> {{ __('Invoice No') }} </td>
          <td class="text-center list-th"> {{ __('Customer Name') }} </td>
          <td class="text-center list-th"> {{ __('Total Price') }} </td>
          <td class="text-center list-th"> {{ __('Paid Amount') }} </td>
          <td class="text-center list-th"> {{ __('Currency') }} </td>
          <td class="text-center list-th"> {{ __('Status') }} </td>
          <td class="text-center list-th"> {{ __('Invoice Date') }} </td>
        </tr>
    </thead>
    <?php $name = 'aaaa'; ?>
    @foreach ($slaesList as $data)
      @php 
        if (isset($data->customer->name) && !empty($data->customer->name)) {
            $name = $data->customer->name . '(' . isset($data->currency->name) ? $data->currency->name : '' . ')';
        } else {
            $name = '-';
        }
      @endphp
      <tr> 
        @php $paid_amount = $data->paid == 0 ? 0 : $data->paid; @endphp
          <td class="text-center list-td"> {{ $data->reference }} </td>
          <td class="text-center list-td"> {{ $name }} </td>
          <td class="text-center list-td"> {{ formatCurrencyAmount($data->total) }} </td>
          <td class="text-center list-td"> {{ formatCurrencyAmount($paid_amount) }} </td>
          <td class="text-center list-td"> {{ $data->currency->name }} </td>
          <td class="text-center list-td"> 
            @if($paid_amount == 0)
              {{ __("Unpaid") }}
            @elseif($paid_amount > 0 && $data->total > $paid_amount)
              {{ __("Partially paid") }}
            @elseif($data->total <= $paid_amount) 
              {{ __("Paid") }}
            @endif
          </td>
          <td class="text-center list-td"> {{ formatDate($data->order_date) }} </td>
      </tr>
    @endforeach 
</table>
@endsection
