@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Customer Tickets') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Customer Tickets') }}</strong>
    </p>
    <p class="title">
      <span class="title-text">{{ __('Name:') }} </span>{{ isset($customers) ? $customers->name : '' }}
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
          <td class="text-center list-th" width="46%"> {{ __('Subject') }} </td>
          <td class="text-center list-th" width="12%"> {{ __('Assignee') }} </td>
          <td class="text-center list-th" width="10%"> {{ __('Department') }} </td>
          <td class="text-center list-th" width="10%"> {{ __('Status') }} </td>
          <td class="text-center list-th" width="10%"> {{ __('Priority') }} </td>
          <td class="text-center list-th" width="12%"> {{ __('Last Reply') }} </td>
          <td class="text-center list-th" width="12%"> {{ __('Created at') }} </td>
        </tr>
    </thead>
    @foreach ($ticketList as $data)
      <tr>
          <td class="text-center list-td"> {{ $data->subject }}<br><small>{{ $data->project_name }}</small></td>
          <td class="text-center list-td"> {{ $data->assignee_name }} </td>
          <td class="text-center list-td"> {{ $data->department_name }} </td>
          <td class="text-center list-td"> {{ $data->status }} </td>
          <td class="text-center list-td"> {{ $data->priority }} </td>
          <td class="text-center list-td"> {{ formatDate($data->last_reply) }}<br>{{ getTime($data->last_reply) }} </td>
          <td class="text-center list-td"> {{ formatDate($data->date) }}<br>{{  getTime($data->date) }} </td>
      </tr>
    @endforeach
</table>
@endsection
