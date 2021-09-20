@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Team Member List Pdf') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Team Member Lists') }}</strong>
    </p>
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
            <td class="text-center list-th"> {{ __('Role') }} </td>
            <td class="text-center list-th"> {{ __('Phone') }} </td>
            <td class="text-center list-th"> {{ __('Status') }} </td>
            <td class="text-center list-th"> {{ __('Created At') }} </td>
        </tr>
    </thead>
    @foreach($teamMemberList as $key => $user) 
        <tr>
            <td class="text-center list-td"> {{ $user->full_name }} </td>
            <td class="text-center list-td"> {{ $user->email }} </td>
            <td class="text-center list-td"> {{ !empty($user->role) ? $user->role->display_name : '' }} </td>
            <td class="text-center list-td"> {{ $user->phone }} </td>
            <td class="text-center list-td"> {{ $user->is_active == 1 ? __('Active') : __('Inactive') }} </td>
            <td class="text-center list-td"> {{ timeZoneformatDate($user->created_at) }} {{ timeZonegetTime($user->created_at) }} </td>
        </tr> 
    @endforeach
</table>
@endsection
