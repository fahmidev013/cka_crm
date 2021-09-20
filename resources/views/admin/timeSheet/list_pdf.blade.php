@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Project Timesheet Lists') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Timesheet Lists') }}</strong>
    </p>
    @if (isset($projectData) && !empty($projectData))
    <p class="title">
      <span class="title-text">{{__('Project:')}} </span>{{ $projectData->name }}
    </p>
    @endif
    @if (isset($totalTimesheet) && !empty($totalTimesheet))
    <p class="title">
      <span class="title-text">{{__('Total Time Spent:')}} </span>{{ $totalTimesheet }}
    </p>
    @endif
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
          <td class="text-center list-th"> {{ __('Assignee') }} </td>
          <td class="text-center list-th"> {{ __('Task Name') }} </td>
          <td class="text-center list-th"> {{ __('Start Time') }} </td>
          <td class="text-center list-th"> {{ __('End Time') }} </td>
          <td class="text-center list-th"> {{ __('Time Spent') }} </td>
          <td class="text-center list-th"> {{ __('Note') }} </td>
        </tr>
    </thead>
    @foreach($timerDetails as $timesheetDetail)
      <tr>
        <td class="text-center list-td"> {{ $timesheetDetail->full_name }} </td>
        <td class="text-center list-td"> {{ $timesheetDetail->name }} </td>
        <td class="text-center list-td"> {{ timeZoneformatDate(date("m/d/Y h:i:s A T", $timesheetDetail->start_time)) . ' ' . timeZonegetTime(date("m/d/Y h:i:s A T", $timesheetDetail->start_time)) }} </td>
        <td class="text-center list-td"> {{ ($timesheetDetail->end_time != "") ? timeZoneformatDate(date("m/d/Y h:i:s A T", $timesheetDetail->end_time)) . ' ' . timeZonegetTime(date("m/d/Y h:i:s A T", $timesheetDetail->end_time)) : " " }} </td>
        <td class="text-center list-td"> {{ getTimeSpent($timesheetDetail) }} </td>
        <td class="text-center list-td"> {{ $timesheetDetail->note }} </td>
      </tr>
    @endforeach
</table>
@endsection
