@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Project Invoice Lists') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Project Invoice Lists') }}</strong>
    </p>
    @if(isset($projectData) && !empty($projectData))
    <p class="title">
      <span class="title-text">{{ __('Project:') }} </span>{{ $projectData->name }}
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
    @foreach ($saleslist as $data)
      <tr> 
          <td class="text-center list-td"> {{ $data->reference }} </td>
          <td class="text-center list-td"> {{ $data->first_name . ' ' . $data->last_name  }} </td>
          <td class="text-center list-td"> {{ number_format($data->total, 2, '.', ',') }} </td>
          <td class="text-center list-td"> {{ number_format($data->paid_amount,2,'.',',') }} </td>
          <td class="text-center list-td"> {{ $data->currency_name }} </td>
          <td class="text-center list-td"> 
            @if($data->paid_amount == 0)
              {{ __('Unpaid') }}
            @elseif($data->paid_amount > 0 && $data->total > $data->paid_amount)
              {{ __('Partially Paid') }}
            @elseif($data->paid_amount<=$data->paid_amount) 
              {{ __('Paid') }} 
            @endif
          </td>
          <td class="text-center list-td"> {{ formatDate($data->order_date) }} </td>
      </tr>
    @endforeach 
</table>
@endsection
