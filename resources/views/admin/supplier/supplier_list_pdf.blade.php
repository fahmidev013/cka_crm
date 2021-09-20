@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Supplier Lists') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Supplier Lists') }}</strong>
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
            <td class="text-center list-th" width="10%"> {{ __('Name') }} </td>
            <td class="text-center list-th" width="10%"> {{ __('Email') }} </td>
            <td class="text-center list-th" width="10%"> {{ __('Phone') }} </td>
            <td class="text-center list-th" width="15%"> {{ __('Address') }} </td>
            <td class="text-center list-th" width="5%"> {{ __('Currency') }} </td>
            <td class="text-center list-th" width="10%"> {{ __('Country') }} </td>
            <td class="text-center list-th" width="5%"> {{ __('Status') }} </td>
            <td class="text-center list-th" width="20%"> {{ __('Created At') }} </td>
        </tr>
    </thead>
    @foreach($suppliersList as $supplier) 
       @php
            $address = (isset($supplier->street)?$supplier->street:'').''.(isset($supplier->city)?', '.$supplier->city:'').(isset($supplier->state) ? ', '.$supplier->state:'').''.(isset($supplier->zipcode)?', '.$supplier->zipcode:'').''.(isset($supplier->countryName) ?', '.$supplier->countryName:'');
        @endphp
        <tr>
            <td class="text-center list-td"> {{ $supplier->name }} </td>
            <td class="text-center list-td"> {{ $supplier->email }} </td>
            <td class="text-center list-td"> {{ $supplier->contact }} </td>
            <td class="text-center list-td"> {{ $address }} </td>
            <td class="text-center list-td"> {{ isset($supplier->currency->name) ? $supplier->currency->name : '' }} </td>
            <td class="text-center list-td"> {{ isset($supplier->country->name) ? $supplier->country->name : ''}} </td>
            <td class="text-center list-td"> {{ $supplier->is_active == 1 ? "Active" : "Inactive" }} </td>
            <td class="text-center list-td"> {{ !empty($supplier->created_at) ? timeZoneformatDate($supplier->created_at).' '.timeZonegetTime($supplier->created_at) : '' }} </td>
        </tr> 
        @php
            $address = '';
        @endphp
    @endforeach
</table>
@endsection
