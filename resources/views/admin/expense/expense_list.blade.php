@extends('layouts.list-layout')

@section('list-title')
  <h5> <a href="{{ url('expense/list') }}"> {{ __('Expenses') }} </a> </h5>
@endsection

@section('list-add-button')

  @if(Helpers::has_permission(Auth::user()->id, 'add_expense'))
    <a href="{{ url('expense/add-expense') }}" class="btn btn-outline-primary custom-btn-small"><span class="feather icon-plus"> &nbsp;</span>{{ __('New Expense') }}</a>
  @endif

@endsection

@section('list-form-content')
  <form class="form-horizontal" action="{{ url('expense/list') }}" method="GET" id='orderListFilter'>
    <input class="form-control" id="startfrom" type="hidden" name="from" value="<?= isset($from) ? $from : '' ?>">
    <input class="form-control" id="endto" type="hidden" name="to" value="<?= isset($to) ? $to : '' ?>">
    <div class="col-md-12 col-sm-12 col-xs-12 m-l-10">
      <div class="row mt-3">
        <div class="col-md-12 col-xl-4 col-lg-4 col-sm-12 col-xs-12 mb-2">
          <div class="input-group">
            <button type="button" class="form-control" id="daterange-btn">
              <span class="float-left">
                <i class="fa fa-calendar"></i>  {{ __('Pick a date range') }}
              </span>
              <i class="fa fa-caret-down float-right pt-1"></i>
            </button>
          </div>
        </div>

        <div class="col-md-4 col-sm-3 col-xs-11 mb-2">
          <select class="form-control select2" name="categoryName" id="categoryName">
            <option value="all">{{ __('All Categories') }}</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ $category->id == $categoryId ? 'selected' : ''}}>{{ $category->name }}</option>
            @endforeach
          </select>
        </div>

         <div class="col-md-3 col-sm-3 col-xs-11 mb-2">
          <select class="form-control select2" name="methodName" id="methodName">
            <option value="all">{{ __('All Payment Methods') }}</option>
            @foreach($paymentMethods as $method)
            <option value="{{ $method->id }}" {{ $method->id == $methodId ? 'selected' : ''}}>{{ $method->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-1 col-sm-1 col-xs-12 p-md-0">
          <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small push-sm-right mt-0 mr-0">{{ __('Go') }}</button>
        </div>
      </div>
    </div>
  </form>
@endsection
@section('list-js')
<script src="{{ asset('public/dist/js/custom/expense.min.js') }}"></script>
@endsection
