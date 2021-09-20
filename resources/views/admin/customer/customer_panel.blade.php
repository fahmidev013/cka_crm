@extends('layouts.customer_panel')

@section('content')
  <!-- Main content -->
  <div class="col-sm-12" id="cus-panel-container">
    <div class="card cardMinWidthCustomer">
      <div class="card-header">
        <h5><a href="{{ url('customer/dashboard') }}">{{ __('Home') }}</a></h5>
        <div class="card-header-right">

        </div>
      </div>
      <div class="card-body p-0" id="no_shadow_on_card">
        <div class="col-sm-12">
          <div class="row m-10">
            <div class="col-md-4 col-xs-12 col-sm-12 col-xl-4 mt-xl-2 customHomeMarginCustomer">
              <div class="card theme-bg bitcoin-wallet">
                <div class="card-block">
                    <h5 class="text-white mb-2">{{ __('Quotations') }}</h5>
                    <h2 class="text-white f-22 mb-2 f-w-300">{{ ($totalOrder) }}</h2>
                    <span class="d-block">  <h6 class="fa fa-arrow-circle-right text-white"> <a class="text-white" href='{{ url("customer-panel/order") }}' >{{ __('More Info') }}</a></h6></span>
                    <i class="fa fa-shopping-cart f-60 text-white dashBoardIcon"></i>
                </div>
              </div>
            </div>
            <div class="col-md-4 col-xs-12 col-sm-12 col-xl-4 mt-xl-2">
              <div class="card theme-bg2 bitcoin-wallet">
                <div class="card-block">
                    <h5 class="text-white mb-2">{{ __('Invoices') }}</h5>
                    <h2 class="text-white f-22 mb-2 f-w-300">{{ ($totalInvoice) }}</h2>
                    <span class="d-block">  <h6 class="fa fa-arrow-circle-right text-white "> <a class="text-white" href='{{ url("customer-panel/invoice") }}'>{{ __('More Info') }}</a></h6></span>
                    <i class="fas fa-cart-arrow-down f-60 text-white dashBoardIcon"></i>
                </div>
              </div>
            </div>
            <div class="col-md-4 col-xs-12 col-sm-12 col-xl-4 mt-xl-2">
              <div class="card bg-c-blue bitcoin-wallet">
                <div class="card-block">
                    <h5 class="text-white mb-2">{{ __('Supports') }}</h5>
                    <h2 class="text-white f-22 mb-2 f-w-300">{{ ($totalSupport) }}</h2>
                    <span class="d-block"><h6 class="fa fa-arrow-circle-right text-white"> <a class="text-white" href='{{ url("customer-panel/support/list") }}'>{{ __('More Info') }}</a></h6></span>
                    <i class="mdi mdi-monitor-multiple f-60 text-white dashBoardIcon"></i>
                </div>
              </div>
            </div>
              <div class="col-md-4 col-xs-12 col-sm-12 col-xl-4 mt-xl-2">
                  <div class="card bg-c-blue bitcoin-wallet">
                      <div class="card-block">
                          <h5 class="text-white mb-2">{{ __('Knowledge Base') }}</h5>
                          <h2 class="text-white f-22 mb-2 f-w-300">{{ ($totalKnowledge) }}</h2>
                          <span class="d-block"><h6 class="fa fa-arrow-circle-right text-white"> <a class="text-white" href='{{ url("customer-panel/knowledge-base") }}'>{{ __('More Info') }}</a></h6></span>
                          <i class="mdi mdi-book-multiple f-60 text-white dashBoardIcon"></i>
                      </div>
                  </div>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('list-js')
<script src="{{ asset('public/dist/js/custom/customer.min.js') }}"></script>
@endsection
