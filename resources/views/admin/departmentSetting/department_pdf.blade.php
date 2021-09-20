@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Departments List') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
        <span class="title-text"></span><strong>{{ __('Departments List') }}</strong>
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
            <td class="text-center list-th"> {{ __('Department Name')  }} </td>
        </tr>
    </thead>

    @foreach($categorydata as $data)
    <tr>
        <td class="text-center list-td"> {{ $data->name }} </td>
    </tr>
    @endforeach
</table>
@endsection
