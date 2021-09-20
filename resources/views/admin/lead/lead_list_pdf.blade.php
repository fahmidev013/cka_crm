@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Lead Lists') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Lead Lists') }}</strong>
    </p>
    @if (isset($userSelected) && !empty($userSelected))
    <p class="title">
      <span class="title-text">{{__('Assignee:')}} </span>{{ $userSelected->full_name }}
    </p>
    @endif
    @if (isset($sourceSelected) && !empty($sourceSelected))
    <p class="title">
      <span class="title-text">{{__('Source:')}} </span>{{ $sourceSelected->name }}
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
            <td class="text-center list-th"> {{ __('Name') }} </td>
            <td class="text-center list-th"> {{ __('Email') }} </td>
            <td class="text-center list-th"> {{ __('Phone') }} </td>
            <td class="text-center list-th"> {{ __('Company') }} </td>
            <td class="text-center list-th"> {{ __('Website') }} </td>
            <td class="text-center list-th"> {{ __('Country') }} </td>
            <td class="text-center list-th"> {{ __('Status') }} </td>
            <td class="text-center list-th"> {{ __('Source') }} </td>
            <td class="text-center list-th"> {{ __('Last Contact') }} </td>
        </tr>
    </thead>
    @foreach($leadsList as $lead)
        <tr>
            <td class="text-center list-td"> {{ $lead->first_name }} {{ $lead->last_name }} </td>
            <td class="text-center list-td"> {{ $lead->email }} </td>
            <td class="text-center list-td"> {{ $lead->phone}} </td>
            <td class="text-center list-td"> {{ $lead->company }} </td>
            <td class="text-center list-td"> {{ $lead->website }} </td>
            <td class="text-center list-td"> {{ !empty($lead->country)? $lead->country->name:"" }} </td>
            <td class="text-center list-td"> {{ isset($lead->leadStatus->name) ? $lead->leadStatus->name : '' }} </td>
            <td class="text-center list-td"> {{ $lead->leadSource->name }} </td>
            <td class="text-center list-td"> {{ (@strtotime($lead->last_contact)) > 0 ? $lead->last_contact : '-' }} </td>
        </tr>
    @endforeach

</table>
@endsection
