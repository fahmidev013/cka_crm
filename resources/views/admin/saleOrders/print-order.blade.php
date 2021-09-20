<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>{{ __('Quotation') }}</title>
    <link rel="stylesheet" href="{{ asset('public\bootstrap\css\font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/dist/css/pdf/invoice-pdf.min.css') }}">
  </head>
  <body>
    <header class="clearfix">
      <div class="top-header" id="logo">
        @if (isset($company_logo) && !empty($company_logo))
        <div class="logo_bg"><img src='{{ public_path("/uploads/companyPic/".$company_logo) }}'></div>
        @endif
        <div class="textbg"><h1>{{ __('Quotation') }}</h1></div>
      </div>
       <div id="company" class="clearfix">
        <table>
          <tbody>
            <tr>
              <td class="company-td p0" align="left">{{ __('Quotation No') }}  :</td>
              <td class="company-td pb10" align="left">{{ $invoiceData->reference }}</td>
            </tr>
            <tr>
              <td class="company-td p0" align="right">{{ __('Date') }}  :</td>
              <td class="company-td pb10" align="left">{{ formatDate($invoiceData->order_date) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
        <div id="project">
          @if(isset($company_name) && !empty($company_name))
            <h2>{{ $company_name }}</h2>
          @endif
          <p><strong>{{ $company_street }}</strong></p>
          <p><i class="fa fa-phone" aria-hidden="true"></i> {{ ! empty($company_city) ? $company_city : '' }}{{ ! empty($company_state) ? ', '.$company_state : ''}}{{!empty($company_zipCode) ? ', '.$company_zipCode : ''}}</p>
          <p><i class="fa fa-envelope" aria-hidden="true"></i> {{ $company_country_name }}</p>
        </div>
        <div id="project2">
          <h2>{{ __('Bill To') }}</h2>
           <p>{{ isset($invoiceData->customer->first_name) ? $invoiceData->customer->first_name : '' }} {{ isset($invoiceData->customer->last_name) ? $invoiceData->customer->last_name : '' }}</p>
          <p>{{ isset($invoiceData->customerBranch->billing_street) ? $invoiceData->customerBranch->billing_street : '' }}</p>
          <p>{{ isset($invoiceData->customerBranch->billing_state) ? $invoiceData->customerBranch->billing_state : '' }}{{ isset($invoiceData->customerBranch->billing_city) ? ', '. $invoiceData->customerBranch->billing_city : '' }}</p>
          <p>{{ isset($invoiceData->customerBranch->billingCountry) ? $invoiceData->customerBranch->billingCountry->name : '' }} {{ isset($invoiceData->customerBranch->billing_zip_code) ? ', '.$invoiceData->customerBranch->billing_zip_code : '' }}</p>
        </div>
    </header>
    <main>
      <table class="tablebg">
        <thead>
          <tr class="inv-primary-color-bg">
            <th class="service" class="f20">{{ __('SI') }}</th>
            <th class="desc">{{ __('Description')}}</th>
            @if($invoiceData->has_hsn)
                <th class="f20">{{ __('HSN') }}</th>
            @endif
            @if($invoiceData->invoice_type == 'hours')
                <th class="f20">{{ __('Hours') }}</th>
                <th class="f20">{{ __('Rate') }}</th>
            @else
                <th class="f20">{{ __('Quantity') }}</th>
                <th class="f20">{{ __('Price') }}</th>
            @endif

            @if($invoiceData->has_item_discount)
                <th class="f20">{{ __('Discount') }}</th>
            @endif
            @if($invoiceData->has_tax)
                <th class="f20">{{ __('Tax') }}(%)</th>
            @endif
            <th class="f20">{{ __('Amount') }} ({{ isset($invoiceData->currency->symbol) ? $invoiceData->currency->symbol : '' }}) </th>
          </tr>
        </thead>
        <tbody>
          @php
            $itemsInformation = '';
            $row = 7;
            $currentTaxArray = [];
            if ($invoiceData->invoice_type == 'amount') {
              $row = $row - 2;
            }
            if (!$invoiceData->has_item_discount) {
              $row = $row - 1;
            }
            if (!$invoiceData->has_hsn) {
              $row = $row - 1;
            }
            if (!$invoiceData->has_tax) {
              $row = $row - 1;
            }
          @endphp
          @if ( count($invoiceData->saleOrderDetails) > 0 )
            @php $subTotal = $totalDiscount = $i = 0; @endphp
            @foreach ($invoiceData->saleOrderDetails as $result)
              @php
                $priceAmount = ($result->quantity * $result->unit_price);
                $subTotal += $priceAmount;
              @endphp
              @if ($result->quantity > 0 )
                <tr>
                  <td class="f20 inv-primary-color">{{ ++$i }}</td>
                  <td class="desc  wd-400">
                    <span class="main_titlebg f18 inv-primary-color"><strong>{{ $result->item_name }}</strong></span><br/>
                    @if($invoiceData->has_description && $result->description)
                      <span class="f18 inv-secondary-color">{{ $result->description }}</span>
                    @endif
                  </td>
                  @if($invoiceData->has_hsn)
                    <td class="qty f18 inv-secondary-color">{{ $result->hsn }}</td>
                  @endif
                  @if($invoiceData->invoice_type != 'amount')
                    <td  class="qty f18 inv-secondary-color">{{ formatCurrencyAmount($result->quantity) }}</td>
                  @endif
                  <td class="unit f18 inv-secondary-color">{{ formatCurrencyAmount( $result->unit_price) }}</td>
                  @if($invoiceData->has_item_discount)
                    <td class="qty f18 inv-secondary-color">{{ formatCurrencyAmount($result->discount) }}{{ $result->discount_type }}</td>
                  @endif

                  @if($invoiceData->has_tax)
                  <td class="qty f18 inv-secondary-color">
                    @forelse(json_decode($result->taxList) as $counter => $tax)
                      {{ formatCurrencyAmount($tax->rate) }}
                      @if($counter < count(json_decode($result->taxList)) - 1)
                        <br>
                      @endif
                    @empty
                    @endforelse
                  </td>
                  @endif

                  <td class="total f18 inv-secondary-color">{{  formatCurrencyAmount($priceAmount) }}</td>
                </tr>
              @endif
            @endforeach
          @endif 
        </tbody>
      </table>
      <div id="notices" class="sub_total">
        <table class="tablebg3">
          <tbody>
            <tr>
              <td colspan="{{$row}}" class="cal-title">{{ __('Sub Total') }} :</td>
              <td class="cal-value">{{ formatCurrencyAmount($subTotal, isset($invoiceData->currency->symbol) ? $invoiceData->currency->symbol : '') }}</td>
            </tr>

            @if($invoiceData->has_item_discount)
            <tr>
              <td colspan="{{$row}}" class="cal-title">Discount :</td>
              <td class="cal-value">{{ formatCurrencyAmount(!empty($invoiceData->saleOrderDetails) ?  $invoiceData->saleOrderDetails->sum('discount_amount') : '', isset($invoiceData->currency->symbol) ? $invoiceData->currency->symbol : '') }}</td>
            </tr>
            @endif

            @forelse($taxes as $tax)
              <tr>
                <td colspan="{{$row}}" class="cal-title">{{$tax['name']}} ({{ formatCurrencyAmount($tax['rate']) }})% :</td>
                <td class="cal-value">{{ formatCurrencyAmount($tax['amount'], isset($invoiceData->currency->symbol) ? $invoiceData->currency->symbol : '') }}</td>
              </tr>
            @empty
            @endforelse

            @if($invoiceData->has_other_discount)
              <tr>
                @php
                  if( $invoiceData->other_discount_type == "$" ) {
                    $otherDiscount = $invoiceData->other_discount_amount;
                  } else {
                    $otherDiscount = $subTotal * $invoiceData->other_discount_amount / 100;
                  }
                @endphp
                <td colspan="<?= $row ?>" class="cal-title">{{ __('Other Discount') }} ({{ formatCurrencyAmount($invoiceData->other_discount_amount) }}) {{ $invoiceData->other_discount_type == '$' && isset($invoiceData->currency->symbol) ? $invoiceData->currency->symbol : '%' }} :</td>
                <td class="cal-value">{{ formatCurrencyAmount($otherDiscount, isset($invoiceData->currency->symbol) ? $invoiceData->currency->symbol : '') }}</td>
              </tr>
            @endif

            @if($invoiceData->has_shipping_charge && $invoiceData->shipping_charge)
              <tr>
                <td colspan="{{$row}}" class="cal-title">{{ __('Shipping') }}</td>
                <td class="cal-value">{{ formatCurrencyAmount($invoiceData->shipping_charge, isset($invoiceData->currency->symbol) ? $invoiceData->currency->symbol : '') }}</td>
              </tr>
            @endif

            @if($invoiceData->has_custom_charge)
                <tr>
                    <td colspan="<?= $row ?>" class="cal-title">{{ $invoiceData->custom_charge_title }}</td>
                    <td class="cal-value">{{ formatCurrencyAmount($invoiceData->custom_charge_amount, isset($invoiceData->currency->symbol) ? $invoiceData->currency->symbol : '') }}</td>
                </tr>
            @endif

          </tbody>
        </table>
        <br/>
        <div class="grand_total">
        <table class="tablebg2">
          <tbody>
            <tr>
              <td colspan="{{$row}}" class="grand-tatal" align="right">{{ __('Grand Total') }}</td>
              <td align="right">{{ formatCurrencyAmount($invoiceData->total, isset($invoiceData->currency->symbol) ? $invoiceData->currency->symbol : '') }}</td>
            </tr>
            <tr>
              <td colspan="{{$row}}"  class="final-cal-title">{{ __('Paid') }} :</td>
              <td class="final-cal-value">{{ formatCurrencyAmount($invoiceData->paid, isset($invoiceData->currency->symbol) ? $invoiceData->currency->symbol : '') }}</td>
            </tr>
            <tr>
              <td colspan="{{$row}}"  class="final-cal-title">{{ __('Due') }} :</td>
              <td class="final-cal-value">
                @if ($invoiceData->total < $invoiceData->paid) 
                  -{{ formatCurrencyAmount( abs($invoiceData->total-$invoiceData->paid), isset($invoiceData->currency->symbol) ? $invoiceData->currency->symbol : '') }}
                @else
                  {{ formatCurrencyAmount( abs($invoiceData->total-$invoiceData->paid), isset($invoiceData->currency->symbol) ? $invoiceData->currency->symbol : '') }}
                @endif
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      </div>
      @if($invoiceData->has_comment == 1 && $invoiceData->comment)
        <div class="bottomtex">
          <div class="left_text">
            <h1>{{ __('Note') }} : {{ $invoiceData->comment }} </h1>         
          </div>
        </div>
      @endif

    </main>
  </body>
</html>