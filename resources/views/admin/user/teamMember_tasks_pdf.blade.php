@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Team Member Task Lists') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Team Member Tasks') }}</strong>
    </p>
    <p class="title">
      <span class="title-text">{{ __('Status:') }} </span>{{ isset($taskStatus) && !empty($taskStatus) ? $taskStatus->name : __('All') }}
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
          <td class="text-center list-th" width="40%"> {{ __('Task Name') }} </td>
          <td class="text-center list-th" width="16%"> {{ __('Assignee') }} </td>
          <td class="text-center list-th" width="12%"> {{ __('Start Date') }} </td>
          <td class="text-center list-th" width="12%"> {{ __('Due Date') }} </td>
          <td class="text-center list-th" width="10%"> {{ __('Priority') }} </td>
          <td class="text-center list-th" width="10%"> {{ __('Status') }} </td>
        </tr>
    </thead>

    @foreach($tasks as $data) 
      @php $data @endphp
      <tr>
        <td class="text-center list-td"> {{ $data->name }} </td>
        <td class="text-center list-td"> {{ $data->assignee }} </td>
        <td class="text-center list-td"> {{ $data->start_date ? $data->start_date : '-' }} </td>
        <td class="text-center list-td"> {{ $data->due_date ? $data->due_date : '-' }} </td>
        <td class="text-center list-td"> {{ $data->priorityName }} </td>
        <td class="text-center list-td"> {{ $data->status_name }} </td>
      </tr>
    @endforeach
</table>
@endsection
