@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
@endsection
@section('content')
  <div class="col-sm-12" id="purchase-report-datewise">
    <div class="card">
      <div class="card-header" id="headerDiv">
        @php
          if (strtotime($reportDate)) {
            $reportDate = formatDate($reportDate);
          } else {
          $str = explode("-", $reportDate);
          $filterMonth = (string) $str[0];
          $months = getMonthList();
          foreach ($months as $key => $monthName) {
            if ($key == $filterMonth) {
              $filterMonth = $monthName;
            }
          }
          $reportDate = $filterMonth.'-'.$str[1];
        }
       @endphp
       <h5>{{ __('Purchase Report On')  }} {{ $reportDate }}</h5>
     </div>
     <div class="card-body p-0">
       <div class="col-sm-12 mt-3 px-4">
          <div class="row mb-4">
           	<div class="col-md-4 col-xs-6 border-right text-center">
              <h5 class="bold">{{ formatCurrencyAmount($purchData->sum('total'), $filterCurrency->symbol) }}</h5>
              <span class="text-info">{{ __('Purchases Value')  }}</span>
            </div>

           	<div class="col-md-4 col-xs-6 border-right text-center">
              <h5 class="bold" id="grand-total-qty"></h5>
              <span class="text-info">{{ __('Quantity')  }}</span>
            </div>

           	<div class="col-md-4 col-xs-6 border-right text-center">
              <h5 class="bold" id="grand-total-tax"></h5>
              <span class="text-info">{{ __('Tax')  }}</span>
            </div>
          </div>
          <div class="card-block p-0">
            <div class="table-responsive">
              <table id="purchaseList" class="table table-bordered table-hover table-striped dt-responsive" width="800%">
                <thead>
                  <tr>
                    <th>{{ __('Purchase No')  }} #</th>
                    <th>{{ __('Supplier')  }}</th>
                    <th>{{ __('Quantity')  }}</th>
                    <th>{{ __('Total')  }}</th>
                    <th>{{ __('Tax')  }}</th>
                    <th>{{ __('Discount')  }}</th>
                  </tr>
                </thead>
                <tbody>
                    <?php
                      $grandTotalTax = 0;
                      $grandTotalQuantity = 0;
                    ?>
                    @foreach($purchData as $data)
                      <?php
                        $itemDiscountAmount = 0;
                        $otherDiscountAmount = 0;
                      ?>

                      @foreach ($data->purchaseOrderDetails as $item)
                        <?php
                          if ($data->has_item_discount == 1) {
                              $itemDiscountAmount += $item->discount_amount;
                          }

                          if ($data->has_other_discount == 1 && $data->other_discount_type != '%') {
                              if ($loop->last) {
                                  $otherDiscountAmount += $data->other_discount_amount;
                              }
                          } else {
                              $otherDiscountAmount += ($item->unit_price * $item->quantity_ordered) * ($data->other_discount_amount / 100);
                          }
                        ?>
                      @endforeach

                    <tr>
                      <td><a href="{{URL::to('/')}}/purchase/view-purchase-details/{{$data->id}}" >{{ $data->reference }}</a></td>
                     <td><a href="{{ url("edit-supplier/$data->supplier_id") }}">{{ $data->supplier->name }}</a></td>
                      <td>{{  formatCurrencyAmount($data->purchaseOrderDetails->sum('quantity_ordered')) }}</td>
                      <td>{{  formatCurrencyAmount($data->total, $filterCurrency->symbol) }}</td>
                      <td>{{  formatCurrencyAmount($data->purchaseOrderDetails->sum('total_tax'), $filterCurrency->symbol) }}</td>
                      <td>
                        @php
                          if ($itemDiscountAmount + $otherDiscountAmount > 0 ) {
                              $discount = $itemDiscountAmount + $otherDiscountAmount;
                          } else {
                              $discount = '-';
                          } 
                        @endphp
                        {{ formatCurrencyAmount($discount, $filterCurrency->symbol) }}
                      </td>
                      @php
                        $grandTotalTax      += $data->purchaseOrderDetails->sum('total_tax');
                        $grandTotalQuantity += $data->purchaseOrderDetails->sum('quantity_ordered');
                      @endphp
                    </tr>
                    @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" id="totalTax" value="{{ formatCurrencyAmount($grandTotalTax) }}">
  <input type="hidden" id="totalQuantity" value="{{ formatCurrencyAmount($grandTotalQuantity) }}">
@endsection

@section('js')
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script type="text/javascript">
  'use strict';
  var grandTotalTax = $('#totalTax').val();
  var grandTotalQuantity = $('#totalQuantity').val();
</script>
<script src="{{ asset('public/dist/js/custom/report.min.js') }}"></script>
@endsection