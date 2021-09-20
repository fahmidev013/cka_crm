@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Milestone Lists') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Milestone Lists') }}</strong>
    </p>
    @if (isset($projectData) && !empty($projectData))
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
          <td class="text-center list-th"> {{ __('Name') }} </td>
          <td class="text-center list-th"> {{ __('Due Time') }} </td>
          <td class="text-center list-th"> {{ __('Created') }} </td>
        </tr>
    </thead>
    @foreach($milestones as $milestone)           
      <tr>
        <td class="text-center list-td">{{ $milestone->name }}</td>
        <td class="text-center list-td">{{ formatDate($milestone->due_date) }}</td>
        <td class="text-center list-td">{{ formatDate($milestone->created_at) }}</td>
      </tr>
    @endforeach
</table>
@endsection
