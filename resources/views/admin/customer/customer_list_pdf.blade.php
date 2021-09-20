@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Customer List') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"><strong>{{ __('Customer List') }}</strong></span>
    </p>
    <p class="title">
      <span class="title-text">{{ __('Print Date') }}: </span>{{ formatDate(date('d-m-Y')) }}
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
            <td class="text-center list-th"> {{ __('Currency') }} </td>
            <td class="text-center list-th"> {{ __('Status') }} </td>
            <td class="text-center list-th"> {{ __('Created At') }} </td>
        </tr>
    </thead> 
    @foreach($customersList as $customer) 
        <tr>
            <td class="text-center list-td"> {{ $customer->name }} </td>
            <td class="text-center list-td"> {{ $customer->email }} </td>
            <td class="text-center list-td"> {{ $customer->phone}} </td>
            <td class="text-center list-td"> {{ isset($customer->currency->name) && !empty($customer->currency->name) ? $customer->currency->name : "" }} </td>
            <td class="text-center list-td"> {{ $customer->is_active == 1 ? __('Active') :  __('Inactive') }} </td>
            <td class="text-center list-td"> {{ !empty($customer->created_at) ?  timeZoneformatDate($customer->created_at) . ' ' . timeZonegetTime($customer->created_at) : '' }} </td>
        </tr> 
    @endforeach  
</table>
@endsection
