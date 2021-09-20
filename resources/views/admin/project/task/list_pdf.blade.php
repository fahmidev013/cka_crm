@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Tasks List PDF') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text">{{ __('Name:') }} </span>{{ __('Task List') }}
    </p>
    @if(!empty($projectName))
      <p class="title">
        <span class="title-text">{{ __('Project:') }} </span>{{ $projectName->name }}
      </p>
    @endif
    @if(!empty($assignees))
      <p class="title">
        <span class="title-text">{{ __('Assignee:') }} </span>{{ $assignees->full_name }}
      </p>
    @endif
    @if(!empty($taskPriority))
      <p class="title">
        <span class="title-text">{{ __('Priority:') }} </span>{{ $taskPriority->name }}
      </p>
    @endif
    @if(!empty($taskStatus))
      <p class="title">
        <span class="title-text">{{ __('Status:') }} </span>{{ $taskStatus->name }}
      </p>
    @endif
    @if (isset($date_range) && !empty($date_range))
        <p class="title">
          <span class="title-text"> {{ __('Period:') }} </span> {{ $date_range }}
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
          <td class="text-center list-td"> {{ __('Task name') }} </td>
          <td class="text-center list-td"> {{ __('Assignee') }} </td>
          <td class="text-center list-td"> {{ __('Start date') }} </td>
          <td class="text-center list-td"> {{ __('Due date') }} </td>
          <td class="text-center list-td"> {{ __('Priority') }} </td>
          <td class="text-center list-td"> {{ __('Status') }} </td>
        </tr>
    </thead>
    @foreach($tasks as $data)
      <tr>
        <td class="text-center list-td"> {{ $data->name }} </td>
        <td class="text-center list-td"> {{ $data->assignee }} </td>
        <td class="text-center list-td"> {{ !empty($data->start_date) ? formatDate($data->start_date) : '-' }} </td>
        <td class="text-center list-td"> {{ !empty($data->due_date) ? formatDate($data->due_date) : '-' }} </td>
        <td class="text-center list-td"> {{ $data->priority_name }} </td>
        <td class="text-center list-td"> {{ $data->status_name }} </td>
      </tr>
    @endforeach 
</table>
@endsection
