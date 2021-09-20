@extends('layouts.list-layout')

@section('listCSS')
  <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection

@section('list-title')
  <h5><a href="{{ url('bank/list') }}">{{ __('Bank Accounts') }}</a></h5>
@endsection

@section('list-add-button')
  @if(Helpers::has_permission(Auth::user()->id, 'add_bank_account'))
    <a href="{{ url('bank/add-account') }}" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Account') }}</a>
  @endif
@endsection

@section('list-form-content')
  @php 
    $from = '';
    $to = '';
  @endphp
  <div class="mb-1"></div>
@endsection

@section('list-js')
<script src="{{ asset('public/dist/js/custom/bank.min.js') }}"></script>
@endsection