<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>{{ __('Purchase') }}</title>
    <link rel="stylesheet" href="{{ asset('public\bootstrap\css\font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/dist/css/pdf/invoice-pdf.min.css') }}">
  </head>
  <body>
    <header class="clearfix">

      <div class="top-header" id="logo">
        @if (!empty($company_logo))
        <div class="logo_bg"><img src='{{ public_path("/uploads/companyPic/" . $company_logo) }}'></div>
        @endif
        <div class="textbg"><h1>{{ __('Purchase') }}</h1></div>
      </div>
       <div id="company" class="clearfix">
        <table>
          <tbody>
            <tr>
              <td class="company-td p0" align="left">{{ __('Purchase No') }}  :</td>
              <td class="company-td pb10" align="left">{{ $purchaseData->reference }}</td>
            </tr>
            <tr>
              <td class="company-td p0" align="right">{{ __('Date') }}  :</td>
              <td class="company-td pb10" align="left">{{ formatDate($purchaseData->order_date) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
        <div id="project">
          <h2>{{ $company_name }}</h2>
          <p><strong>{{ $company_street }}</strong></p>
          <p><i class="fa fa-phone" aria-hidden="true"></i> {{ ! empty($company_city) ? $company_city : '' }}{{ ! empty($company_state) ? ', '.$company_state : ''}}{{!empty($company_zipCode) ? ', '.$company_zipCode : ''}}</p>
          <p><i class="fa fa-envelope" aria-hidden="true"></i> {{ $company_country_name }}</p>
        </div>
        <div id="project2">
          <h2>{{ __('Bill To') }}</h2>
          <p>{{!empty($purchaseData->supplier->name) ? $purchaseData->supplier->name : ''}}</p>
          <p>{{ !empty($purchaseData->supplier->city) ? $purchaseData->supplier->city : ''}}{{ !empty($purchaseData->supplier->state) ? ', '.$purchaseData->supplier->state : ''}} </p>
          <p>{{ !empty($purchaseData->supplier->country->name) ? $purchaseData->supplier->country->name : '' }}{{ !empty($purchaseData->supplier->zipcode) ? ', '.$purchaseData->supplier->zipcode : '' }}</p>
        </div>
    </header>
    <main>
        <table class="tablebg">
        <thead>
          <tr class="inv-primary-color-bg">
            <th class="service f20">SI</th>
            <th class="desc">{{ __('Description')}}</th>
            @if($purchaseData->invoice_type!='amount')
              @if($purchaseData->invoice_type=='quantity')
                <th class="f20">{{ __('Quantity') }}</th>
              @elseif($purchaseData->invoice_type=='hours')
                <th class="f20">{{ __('Hours') }}</th>
              @endif
              @if($purchaseData->invoice_type=='quantity')
                <th class="f20">{{ __('Price') }}</th>
              @elseif($purchaseData->invoice_type=='hours')
                <th class="f20">{{ __('Rate') }}</th>
              @endif
            @else
              <th class="f20">{{ __('Amount') }}</th>
            @endif

            @if($purchaseData->has_item_discount)
                <th class="f20">{{ __('Discount') }}</th>
            @endif
            @if($purchaseData->has_tax)
                <th class="f20">{{ __('Tax') }}</th>
            @endif
            <th class="f20">{{ __('Amount') }} ({{ isset($purchaseData->currency->symbol) ? $purchaseData->currency->symbol : '' }}) </th>
          </tr>
        </thead>
        @php
            $subTotal = 0;
              $itemsInformation = '';
              $row = 6;
              $currentTaxArray = [];
              if ($purchaseData->invoice_type == 'amount') {
                $row = $row - 2;
              }
              if ($purchaseData->has_item_discount == 0) {
                $row = $row - 1;
              }
              if ($purchaseData->has_hsn == 0) {
                $row = $row - 1;
              }
              if ($purchaseData->has_tax == 0) {
                $row = $row - 1;
              }
        @endphp
        @if ( count($purchaseData->purchaseOrderDetails) > 0)
          @php $subTotal = $totalDiscount = $i = 0; @endphp
          @foreach($purchaseData->purchaseOrderDetails as $result)
            @php
              $priceAmount = ($result->quantity_ordered * $result->unit_price);
              $subTotal += $priceAmount;
            @endphp
            @if($result->quantity_ordered > 0)
              <tr>
                <td class="f20 inv-primary-color">{{ ++$i }}</td>
                <td class="desc wd-400">
                  <span class="main_titlebg f18 inv-primary-color"><strong>{{ $result->item_name }}</strong></span><br/>
                  @if($purchaseData->has_detail_description && $result->descriptionon)
                    <span class="f18 inv-secondary-color">{{ $result->description }}</span>
                  @endif
                </td>
                @if($purchaseData->has_hsn)
                  <td class="qty f18 inv-secondary-color">{{$result->hsn}}</td>
                @endif
                @if($purchaseData->invoice_type!='amount')
                  <td class="qty f18 inv-secondary-color">{{ formatCurrencyAmount($result->quantity_ordered) }}</td>
                @endif
                <td class="unit f18 inv-secondary-color"> {{ formatCurrencyAmount($result->unit_price) }}</td>
                @if($purchaseData->has_item_discount)
                  <td class="qty f18 inv-secondary-color">{{ formatCurrencyAmount($result->discount) }}{{$result->discount_type}}</td>
                @endif
                @if($purchaseData->has_tax)
                  <td class="qty f18 inv-secondary-color">
                    @php
                    foreach(json_decode($result->taxList) as $counter => $tax) {
                      echo formatCurrencyAmount($tax->rate)."%";
                      if( $counter < count(json_decode($result->taxList)) - 1) {
                        echo ", ";
                      }
                    }
                    @endphp
                  </td>
                @endif
                <td class="total f18 inv-secondary-color">{{ formatCurrencyAmount($priceAmount) }}</td>
              </tr>
            @endif
          @endforeach
        @endif
      </table>

      <div id="notices" class='sub_total'>
        <table class="tablebg3">
          <tbody>
            <tr>
              <td colspan="{{$row}}" class="cal-title">{{ __('Sub Total') }} : </td>
              <td class="cal-value">{{ formatCurrencyAmount($subTotal, isset($purchaseData->currency->symbol) ? $purchaseData->currency->symbol : '') }}</td>
            </tr>
            @if($purchaseData->has_item_discount)
              <tr>
                <td colspan="{{$row}}" class="cal-title">{{ __('Discount') }} : </td>
                <td class="cal-value" >{{ formatCurrencyAmount($purchaseData->purchaseOrderDetails->sum('discount_amount'), isset($purchaseData->currency->symbol) ? $purchaseData->currency->symbol : '') }}</td>
              </tr>
            @endif
            @forelse($taxes as $tax)
              <tr>
                <td colspan="{{$row}}" class="cal-title">{{$tax['name']}}  ({{ formatCurrencyAmount($tax['rate']) }})% :</td>
                <td  class="cal-value">{{ formatCurrencyAmount($tax['amount'], isset($purchaseData->currency->symbol) ? $purchaseData->currency->symbol : '') }}</td>
              </tr>
            @empty
            @endforelse

            @if($purchaseData->has_other_discount == 1 && $purchaseData->other_discount_amount > 0)
              <tr>
                @php
                  if($purchaseData->other_discount_type=="$"){
                    $otherDiscount = $purchaseData->other_discount_amount;
                  }else{
                    $otherDiscount = $subTotal * $purchaseData->other_discount_amount / 100;
                  }
                @endphp
                <td colspan="<?= $row ?>" class="cal-title">{{ __('Other Discount') }} ({{ formatCurrencyAmount($purchaseData->other_discount_amount) }}) {{$purchaseData->other_discount_type}} :</td>
                <td class="cal-value">{{ formatCurrencyAmount($otherDiscount, isset($purchaseData->currency->symbol) ? $purchaseData->currency->symbol : '') }} </td>
              </tr>
            @endif

            @if($purchaseData->has_shipping_charge && $purchaseData->shipping_charge)
              <tr>
                <td colspan="{{$row}}" class="cal-title">{{ __('Shipping') }} :</td>
                <td class="cal-value" >{{formatCurrencyAmount($purchaseData->shipping_charge, isset($purchaseData->currency->symbol) ? $purchaseData->currency->symbol : '') }}</td>
              </tr>
            @endif
            @if($purchaseData->has_custom_charge)
              <tr>
                <td colspan="<?= $row ?>" class="cal-title">{{ $purchaseData->custom_charge_title }} :</td>
                <td class="cal-value" >{{ formatCurrencyAmount($purchaseData->custom_charge_amount, isset($purchaseData->currency->symbol) ? $purchaseData->currency->symbol : '') }}</td>
              </tr>
            @endif

          </tbody>
        </table>
        <br/>
        <div class="grand_total">
        <table class="tablebg2">
          <tbody>
            <tr>
              <td colspan="{{$row}}" class="grand-tatal" align="right">
              {{ __('Grand Total') }}</td>
              <td  align="right"> {{ formatCurrencyAmount($purchaseData->total, isset($purchaseData->currency->symbol) ? $purchaseData->currency->symbol : '') }}
              </td>
            </tr>
            <tr>
              <td colspan="{{$row}}" class="final-cal-title">{{ __('Paid') }} :</td>
              <td  class="final-cal-value"> {{ formatCurrencyAmount($purchaseData->paid, isset($purchaseData->currency->symbol) ? $purchaseData->currency->symbol : '') }}</td>
            </tr>
            <tr>
              <td colspan="{{$row}}" class="final-cal-title">{{ __('Due') }} :</td>
              <td  class="final-cal-value">
                @if(($subTotal-$purchaseData->paid_amount)<0)
                -{{ formatCurrencyAmount(abs($purchaseData->total-$purchaseData->paid), isset($purchaseData->currency->symbol) ? $purchaseData->currency->symbol : '') }}
                @else
                {{ formatCurrencyAmount(abs($purchaseData->total-$purchaseData->paid), isset($purchaseData->currency->symbol) ? $purchaseData->currency->symbol : '') }}
                @endif
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      </div>

      @if($purchaseData->has_comment != 0 && $purchaseData->comments)
      <div class="bottomtex">
        <div class="left_text">
          <h1>{{ __('Note') }} : {{ $purchaseData->comments }} </h1>
        </div>
      </div>
      @endif

    </main>
  </body>
</html>
