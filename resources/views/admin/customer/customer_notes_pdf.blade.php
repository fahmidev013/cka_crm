@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Customer Notes') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"><strong>{{ __('Customer Notes') }}</strong></span>
    </p>
    <p class="title">
      <span class="title-text">{{ __('Name:') }} </span>{{ isset($customers) ? $customers->name : '' }}
    </p>
    @if(isset($date_range) && !empty($date_range))
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
          <td class="text-center list-th"> {{ __('Subject') }} </td>
          <td class="text-center list-th"> {{ __('Content') }} </td>
          <td class="text-center list-th"> {{ __('Created At') }} </td>
        </tr>
    </thead>
    @foreach ($noteList as $data)
      <tr>  
        <td width="30%" class="list-td"> {{ $data->subject }} </td>
        <td width="50%" class="list-td"> {{ $data->content }} </td>
        <td width="20%" class="list-td"> {{ timeZoneformatDate($data->created_at) }} {{ timeZonegetTime($data->created_at) }} </td>
      </tr>
    @endforeach  
</table>
@endsection
