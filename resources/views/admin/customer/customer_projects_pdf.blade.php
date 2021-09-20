@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Customer Projects') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Customer Projects') }}</strong>
    </p>
    <p class="title">
      <span class="title-text">{{ __('Name:') }} </span>{{ isset($customers) ? $customers->name : ''}}
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
            <td class="text-center list-th"> {{ __('Project Name') }} </td>
            <td class="text-center list-th"> {{ __('Start Date') }} </td>
            <td class="text-center list-th"> {{ __('Deadline') }} </td>
            <td class="text-center list-th"> {{ __('Billing Type') }} </td>
            <td class="text-center list-th"> {{ __('Status') }} </td> 
        </tr>
    </thead>
    @foreach($projectList as $data)
      <tr>  
          <td class="text-center list-td"> {{ $data->name }} </td>
          <td class="text-center list-td"> {{ formatDate($data->begin_date) }} </td>
          <td class="text-center list-td"> {{ !empty($data->due_date) ? formatDate($data->due_date) : '-' }} </td>
          @php
            if($data->charge_type==3) {
              $billing_type = "Task Hours";
            } elseif ($data->charge_type==2) {
                $billing_type = "Project Hours";
            } elseif ($data->charge_type==1) {
                $billing_type = "Fixed Rate";
            }
          @endphp
          <td class="text-center list-td"> {{ $billing_type }} </td>
          <td class="text-center list-td"> {{ !empty($data->projectStatuses) ? $data->projectStatuses->name : '' }} </td>
      </tr>
    @endforeach
</table>
@endsection

