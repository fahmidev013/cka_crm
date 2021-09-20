@extends('layouts.list-layout')

@section('list-title')
  <h5><a href="{{ url('transaction/list') }}">{{ __('Transactions') }}</a></h5>
@endsection

@section('list-form-content')
  <form class="form-horizontal" action="{{ url('transaction/list') }}" method="GET" id='transactionFilter'>
    <input class="form-control" id="startfrom" type="hidden" name="from" value="<?= isset($from) ? $from : '' ?>">
    <input class="form-control" id="endto" type="hidden" name="to" value="<?= isset($to) ? $to : '' ?>">           
    <div class="col-md-12 col-sm-12 col-xs-12 m-l-10 mt-15">
      <div class="row mt-3">
        <div class=" col-xl-4 col-md-5 col-sm-6 col-xs-12 mb-1">
            <div class="input-group">
              <button type="button" class="form-control float-left w-200p" id="daterange-btn">
                <span class="float-left">
                  <i class="fa fa-calendar"></i> {{ __('Date range picker') }}
                </span>
                <i class="fa fa-caret-down arrow"></i>
              </button>
            </div>
        </div>
        <div class="col-xl-3 col-md-3 col-sm-3 col-xs-12 mb-1">
          <div class="form-group">
          <select class="form-control select2" name="method" id="method">
            <option value="">{{ __('All Payment Methods') }}</option>
            @foreach($paymentMethod as $key=>$data)
            <option value="{{ $key }}" {{ $key == $method ? 'selected' : ''}} >{{ $data }}</option>
            @endforeach
          </select>
        </div>
        </div>

         <div class="col-xl-2 col-md-3 col-sm-3 col-xs-12 mb-1">
          <div class="form-group">
          <select class="form-control select2" name="type" id="type">
            <option value="">{{ __('All Types') }}</option>
            @foreach($type as $data)
            <option value="{{ $data->transaction_method }}" {{ $data->transaction_method == $typeVal  ? 'selected' : ''}} >{{ ucwords(str_replace ('_',' ', strtolower($data->transaction_method))) }}</option>
            @endforeach
          </select>
        </div>
        </div>

        <div class="col-xl-2 col-md-2 col-sm-3 col-xs-12 mb-1">
          <div class="form-group">
          <select class="form-control select2" name="mode" id="mode">
            <option value="">{{ __('All Modes') }}</option>
            <option value="1" {{ $modeVal == '1' ? 'selected' : ''}} >Cash in (Debit)</option>
            <option value="2" {{ $modeVal == '2' ? 'selected' : ''}} >Cash out (Credit)</option>
          </select>
        </div>
        </div>

        <div class="col-xl-1 col-md-1 col-sm-1 col-xs-12">
          <button type="submit" name="btn" id="btn" title="{{ __('Click to filter') }}" class="btn btn-primary custom-btn-small push-sm-right mt-0 mr-0">{{ __('Go') }}</button>
        </div>
      </div>
    </div>
  </form>
  <div class="row pt-4 pb-2 mx-2">
    <div class="col-sm-6 text-white">
      <div class="align-left p-26 mt-1 status-border color_03a9f4">
          <span class="f-w-700 f-20">{{ __('Total Cash in (Debit)') }}</span><br>
          @if (count($debit) > 0 )
            @foreach ($debit as $data)
              <span class="f-16">{{ formatCurrencyAmount($data->total, $currencies[$data->currency_id]) }}</span><br>
            @endforeach
          @else 
             <span class="f-16">{{ formatCurrencyAmount(0) }}</span>
          @endif
      </div>
    </div>
    <div class="col-sm-6 text-white">
      <div class=" align-left p-26 mt-1 status-border color_000">
          <span class="f-w-700 f-20">{{ __('Total Cash out (Credit)') }}</span><br>
          @if (count($credit) > 0 )
            @foreach ($credit as $data)
              <span class="f-16">{{ formatCurrencyAmount(abs($data->total), $currencies[$data->currency_id]) }}</span><br>
            @endforeach
           @else 
             <span class="f-16">{{ formatCurrencyAmount(0) }}</span>
          @endif
      </div>
    </div>
</div>

@endsection

@section('list-js')
<script src="{{ asset('public/dist/js/custom/transactions-list.min.js') }}"></script>
@endsection