@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Team Member Project Lists') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Team Member Projects') }}</strong>
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
          <td class="text-center list-th"> {{ __('Customer Name') }} </td>
          <td class="text-center list-th"> {{ __('Start Date') }} </td>
          <td class="text-center list-th"> {{ __('Deadline') }} </td>
          <td class="text-center list-th"> {{ __('Billing Type') }} </td>
          <td class="text-center list-th"> {{ __('Total Task') }} </td>
          <td class="text-center list-th"> {{ __('Status') }} </td>
        </tr>
    </thead>
    @foreach($project as $data)
      <tr>
        <td class="text-center list-td"> {{ $data['name'] }} </td>
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
