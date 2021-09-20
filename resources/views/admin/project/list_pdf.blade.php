@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Project Lists') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Project Lists') }}</strong>
    </p>
    @if (isset($statusSelected) && !empty($statusSelected))
      <p class="title">
        <span class="title-text">{{ __('Status:') }} </span>{{ $statusSelected->name }}
      </p>
    @endif
    @if (isset($project_type) && !empty($project_type))
      <p class="title">
        <span class="title-text">{{ __('Project Type:') }} </span>{{ $project_type }}
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
          <td class="text-center list-th"> {{ __('Project name') }} </td>
          <td class="text-center list-th"> {{ __('Customer') }} </td>
          <td class="text-center list-th"> {{ __('Start date') }} </td>
          <td class="text-center list-th"> {{ __('Deadline') }} </td>
          <td class="text-center list-th"> {{ __('Billing type') }} </td>
          <td class="text-center list-th"> {{ __('Total Task') }} </td>
          <td class="text-center list-th"> {{ __('Status') }} </td>
        </tr>
    </thead>
    @foreach($project as $data)
      <tr>
        <td class="text-center list-td"> {{ $data['name'] }} 
            <br/><span> {{ str_replace('_', ' ', ucwords($data['project_type'], '_')) }} </span>
        </td>
        <td class="text-center list-td"> {{ $data['first_name'] . ' ' . $data['last_name'] }} </td>
        <td class="text-center list-td"> {{ formatDate($data['begin_date']) }} </td>
        <td class="text-center list-td"> {{ strtotime($data['begin_date']) < strtotime($data['due_date']) ? formatDate($data['due_date']) : '-' }} </td>
        <td class="text-center list-td"> {{ ($data['charge_type'] == 1) ? 'Fixed Rate' : 'Project Hour' }} </td>
        <td class="text-center list-td"> {{ $data['totalTask'] }} {{ $data['completedTask'] > 0 ? ' / ' . $data['completedTask'] : '' }} </td>
        <td class="text-center list-td"> {{ $data['status_name'] }} </td>
      </tr>
     @endforeach 
</table>
@endsection
