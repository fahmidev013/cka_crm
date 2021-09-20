@extends('layouts.app')
@section('css')
  {{-- Datatable --}}
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
@endsection
@section('content')
<div class="col-sm-12" id="sales-report-datewise">
  <div class="card">
    <div class="card-header" id="headerDiv">
      @php
        $date = '' ;
        $url = url()->current();
        $chkYearMonth = 'sale_report_filterwise';
        if (strpos($url, $chkYearMonth) == true) {
            $date = $reportOn;
        } else {
            $date = formatDate($reportDate);
        } 
      @endphp
      <h5>{{ __('Sales Report On')  }} {{$date}}</h5>
    </div>
    <div class="card-body p-0">
      <div class="card-block">
        <?php
          $tax_total = 0;
          $qty_total = 0;
          $sales_total = 0;
          $cost_total = 0;
          $profit_total = 0;
        ?>
        @foreach ($saleData as $data)
          @foreach ($data->saleOrderDetails as $item)
            <?php
              $qty_total += $item->quantity;
              $cost_total += $item->purchasePrice * $item->exchangeRate * $item->quantity;
              $tax_total += $item->total_tax;
            ?>
          @endforeach
          <?php
            $sales_total += $data->total;
          ?>
        @endforeach
        <?php 
          $profit_amount = $sales_total - $cost_total;
          $profit_total += $profit_amount;
        ?>
        <div class="row">
          <div class="col-md-3 col-xs-6 border-right text-center">
              <h5 class="bold">{{  formatCurrencyAmount($sales_total, $currency_symbol) }}</h5>
              <span class="text-info">{{ __('Sales value')  }} </span>
          </div>
          <div class="col-md-3 col-xs-6 border-right text-center">
              <h5 class="bold">{{ formatCurrencyAmount($cost_total, $currency_symbol)}}</h5>
              <span class="text-info">{{ __('Cost Value')  }}</span>
          </div>

          <div class="col-md-3 col-xs-6 border-right text-center">
              <h5 class="bold">{{ formatCurrencyAmount($tax_total, $currency_symbol)}}</h5>
              <span class="text-info">{{ __('Tax')  }}</span>
          </div>

          <div class="col-md-3 col-xs-6 text-center">
              <h5 class="bold">
                @if($profit_total<0)
                -{{ formatCurrencyAmount(abs($profit_total), $currency_symbol)}}
                @else
               {{ formatCurrencyAmount(abs($profit_total), $currency_symbol)}}
                @endif
              </h5>
              @if($profit_total<0)
              <span class="text-info">{{ __('Profit')  }}</span>
              @else
              <span class="text-info">{{ __('Profit')  }}</span>
              @endif
          </div> 
        </div>
      </div>
      <div class="card-block">
        <div class="table-responsive">
          <table id="salesList" class="table table-bordered table-hover table-striped dt-responsive" width="100%">
            <thead>
              <tr>
                <th class="text-center">{{ __('Order No') }}</th>
                <th class="text-center">{{ __('Customer') }}</th>                  
                <th class="text-center">{{ __('Quantity') }}</th>
                <th class="text-center">{{ __('Sales value') }}({{ $currency_symbol }})</th>
                <th class="text-center">{{ __('Cost Value') }}({{ $currency_symbol }})</th>
                <th class="text-center">{{ __('Tax')  }}({{ $currency_symbol }})</th>
                <th class="text-center">{{ __('Discount') }}({{ $currency_symbol }})</th>
                <th class="text-center">{{ __('Profit') }}({{ $currency_symbol }})</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($saleData as $data)
                  <?php
                    $qty = 0;
                    $sales_price = 0;
                    $purchase_price = 0;
                    $tax = 0;
                    $profit = 0;
                    $profit_amount = 0;
                    $profit_margin = 0;
                    $itemDiscountAmount = 0;
                    $otherDiscountAmount = 0;
                  ?>
                  @foreach ($data->saleOrderDetails as $item)
                    <?php
                      $qty += $item->quantity;
                      $purchase_price += $item->purchasePrice * $item->exchangeRate * $item->quantity;
                      $tax += $item->total_tax;
                      if ($data->has_item_discount == 1) {
                          $itemDiscountAmount += $item->discount_amount;
                      }

                      if ($data->has_other_discount == 1 && $data->other_discount_type != '%') {
                          if ($loop->last) {
                              $otherDiscountAmount += $data->other_discount_amount;
                          }
                      } else {
                          $otherDiscountAmount += ($item->unit_price * $item->quantity) * ($data->other_discount_amount / 100);
                      }
                    ?>
                  @endforeach
                  <?php 
                    $sales_price += $data->total;
                    $profit = $sales_price - $purchase_price;
                    if ($sales_price != 0) {
                      $profit_margin = ($profit/$sales_price) * 100;
                    } else {
                      $profit_margin = 0;
                    }
                  ?>
                <tr>
                  <td class="text-center"><a href="{{URL::to('/')}}/invoice/view-detail-invoice/{{$data->id}}">{{ $data->reference }}</a></td>
                  @if (isset($data->customer->id) && !empty($data->customer->id))
                    <td class="text-center"><a href="{{URL::to('/')}}/customer/edit/{{$data->customer->id}}">{{ $data->customer->name }}</a></td>
                  @else
                    <td class="text-center">{{__('Walking Customer')}}</td>
                  @endif
                  <td class="text-center">{{ $qty }}</td>
                  <td class="text-center">{{ formatCurrencyAmount($sales_price) }}</td>
                  <td class="text-center">{{ formatCurrencyAmount($purchase_price) }}</td>
                  <td class="text-center">{{ formatCurrencyAmount($tax) }}</td>
                  <td class="text-center">
                    @php
                      if ($itemDiscountAmount + $otherDiscountAmount > 0 ) {
                          $discount = formatCurrencyAmount($itemDiscountAmount + $otherDiscountAmount);
                      } else {
                          $discount = '-';
                      } 
                    @endphp
                    {{ $discount }}
                  </td>
                  <td class="text-center">{{ formatCurrencyAmount($profit) }} <br> {{ formatCurrencyAmount($profit_margin).'%' }}</td>
                </tr>
               @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/report.min.js') }}"></script>
@endsection