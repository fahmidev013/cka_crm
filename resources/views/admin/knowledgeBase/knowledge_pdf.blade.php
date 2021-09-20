@extends('layouts.list_pdf')
@section('pdf-title')
    <title>{{ __('Knowledge Base Lists') }}</title>
@endsection
@section('header-info')
    <td colspan="2" class="tbody-td">
        <p class="title">
            <span class="title-text"></span><strong>{{ __('Knowledge Base Lists') }}</strong>
        </p>
        @if (isset($groupSelected) && !empty($groupSelected))
            <p class="title">
                <span class="title-text">{{__('Group:')}} </span>{{ $groupSelected->name }}
            </p>
        @endif
        @if (isset($statusSelected) && !empty($statusSelected))
            <p class="title">
                <span class="title-text">{{__('Status:')}} </span>{{ $statusSelected }}
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
            <td class="text-center list-th"> {{ __('Group') }} </td>
            <td class="text-center list-th"> {{ __('Subject') }} </td>
            <td class="text-center list-th"> {{ __('Status') }} </td>
            <td class="text-center list-th"> {{ __('Date Published') }} </td>
        </tr>
        </thead>
        @foreach($knowledgeList as $value)
            <tr>
                <td class="text-center list-td"> {{ $value->group_id ? $value->group->name : '' }} </td>
                <td class="text-center list-td"> {{ $value->subject }} </td>
                <td class="text-center list-td"> {{ ucfirst($value->status)}} </td>
                <td class="text-center list-td"> {{ !empty($value->publish_date) ? formatDate($value->publish_date) : null }} </td>
            </tr>
        @endforeach
    </table>
@endsection
