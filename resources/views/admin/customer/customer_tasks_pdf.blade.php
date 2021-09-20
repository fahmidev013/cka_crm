@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Customer Tasks') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
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
            <td class="text-center list-td" width="30%"> {{ __('Task Name') }} </td>
            <td class="text-center list-td" width="26%"> {{ __('Assignee') }} </td>
            <td class="text-center list-td" width="12%"> {{ __('Start Date') }} </td>
            <td class="text-center list-td" width="12%"> {{ __('Due Date') }} </td>
            <td class="text-center list-td" width="10%"> {{ __('Priority') }} </td>
            <td class="text-center list-td" width="10%"> {{ __('Status') }} </td>
        </tr>
    </thead>
    @foreach ($taskList as $data)
      <tr>  
          <td class="text-center list-td"> {{ $data->name }} </td>
          <td class="text-center list-td"> {{ $data->assignee }} </td>
          <td class="text-center list-td"> {{ isset($data->start_date) && !empty($data->start_date) ? formatDate($data->start_date) : '-' }} </td>
          <td class="text-center list-td"> {{ isset($data->due_date) && !empty($data->due_date) ? formatDate($data->due_date) : '-' }} </td>
          <td class="text-center list-td"> {{ $data->priority_name }} </td>
          <td class="text-center list-td"> {{ $data->status_name }} </td>
      </tr>
    @endforeach
</table>
@endsection
