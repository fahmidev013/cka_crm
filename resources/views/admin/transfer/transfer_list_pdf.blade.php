@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Bank Account Transfer Lists') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Bank Account Transfer Lists') }}</strong>
    </p>
    <p class="title">
      <span class="title-text">{{ __('From Bank Account') }}:  </span>{{ !empty($fromAcc) ? $fromAcc->name : __('No account selected') }}
    </p>
    <p class="title">
      <span class="title-text">{{ __('To Bank Account') }}:  </span>{{ !empty($toAcc) ? $toAcc->name : __('No account selected') }}
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
        <td class="text-center list-th"> {{ __('S/N ') }} </td>
        <td class="text-center list-th"> {{ __('Reference') }} </td>
        <td class="text-center list-th"> {{ __('From Bank Account') }} </td>
        <td class="text-center list-th"> {{ __('To Bank Account') }} </td>
        <td class="text-center list-th"> {{ __('Description') }} </td>
        <td class="text-center list-th"> {{ __('Transfer Amount') }} </td>
        <td class="text-center list-th"> {{ __('Bank Charge') }} </td>
        <td class="text-center list-th"> {{ __('Incoming Amount') }} </td>
        <td class="text-center list-th"> {{ __('Date') }} </td>
      </tr>
    </thead>
    @foreach($transferList as $key=>$data)
      <tr>
         <td class="text-center list-td"> {{ ++$key }} </td>       
         <td class="text-center list-td"> {{ $data->transactionReference->code }} </td>       
         <td class="text-center list-td"> {{ $data->fromBank->name }} <br> {{ $data->fromBank->currency->name }} </td>       
         <td class="text-center list-td"> {{ $data->toBank->name }} <br> {{ $data->toBank->currency->name }} </td>       
         <td class="text-center list-td"> {{ $data->description }} </td>       
         <td class="text-center list-td"> {{ formatCurrencyAmount($data->amount) }} <br> {{ $data->currency->name }} </td>       
         <td class="text-center list-td"> {{ formatCurrencyAmount($data->fee) }} <br> {{ $data->fromBank->currency->name }} </td>       
         <td class="text-center list-td"> 
           @if($data->incoming_amount > 0)
             {{ formatCurrencyAmount($data->incoming_amount) }} <br> {{ $data->toBank->currency->name }}
           @else
              {!! '-' !!}
           @endif
         
        </td>       
        <td class="text-center list-td"> {{ formatDate($data->transaction_date) }} </td>       
      </tr>
    @endforeach
</table>
@endsection
