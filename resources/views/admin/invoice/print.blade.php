<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>{{ __('Invoice') }}</title>
    <link rel="stylesheet" href="{{ asset('public/bootstrap/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/dist/css/pdf/invoice-pdf.min.css') }}">
  </head>
  <body>
    <header class="clearfix">
      <div class="top-header" id="logo">
        @if (isset($company_logo) && !empty($company_logo))
          <div class="logo_bg"><img src='{{ public_path("/uploads/companyPic/".$company_logo) }}'></div>
        @endif
        <div class="textbg"><h1>{{ __('Invoice') }}</h1></div>
      </div>
       <div id="company" class="clearfix">
        <table>
          <tbody>
            <tr>
              <td class="company-td p0" align="left">{{ __('Invoice No') }}  :</td>
              <td class="company-td pb10" align="left">{{ $saleInvoiceData->reference }}</td>
            </tr>
            <tr>
              <td class="company-td p0" align="right">{{ __('Date') }}  :</td>
              <td class="company-td pb10" align="left">{{ formatDate($saleInvoiceData->order_date) }}</td>
            </tr>
            <tr>
              <td class="company-td p0" align="right">{{ __('Due Date') }}  :</td>
              <td class="company-td pb10" align="left">{{ formatDate($saleInvoiceData->due_date) }}</td>
            </tr>
            <tr>
              <td class="company-td p0" align="right">{{ __('Status') }}  :</td>
              @if($saleInvoiceData->total > 0)
                @if($saleInvoiceData->paid == 0)
                  <td class="company-td pb10" align="left">{{ __('Unpaid') }}</td>
                @elseif($saleInvoiceData->paid > 0 && $saleInvoiceData->total > $saleInvoiceData->paid )
                  <td class="company-td pb10" align="left">{{ __('Partially Paid') }}</td>
                @elseif($saleInvoiceData->total <= $saleInvoiceData->paid)
                @endif
                @else
                  <td class="company-td pb10" align="left">{{ __('Paid') }}</td>
                @endif
            </tr>
          </tbody>
        </table>
      </div>
        <div id="project">
          @if (isset($company_name) && !empty($company_name))
            <h2>{{ $company_name }}</h2>
          @endif
          <p><strong>{{ $company_street }}</strong></p>
          <p><i class="fa fa-phone" aria-hidden="true"></i> {{ ! empty($company_city) ? $company_city : '' }}{{ ! empty($company_state) ? ', '.$company_state : ''}}{{!empty($company_zipCode) ? ', '.$company_zipCode : ''}}</p>
          <p><i class="fa fa-envelope" aria-hidden="true"></i> {{ $company_country_name }}</p>
        </div>
        <div id="project2">
          <h2>{{ __('Bill To') }}</h2>
          <p>{{ isset($saleInvoiceData->customer->first_name) ? $saleInvoiceData->customer->first_name : '' }} {{ isset($saleInvoiceData->customer->last_name) ? $saleInvoiceData->customer->last_name : '' }}</p>
          <p>{{ isset($saleInvoiceData->customerBranch->billing_street) ? $saleInvoiceData->customerBranch->billing_street : '' }}</p>
          <p>{{ isset($saleInvoiceData->customerBranch->billing_state) ? $saleInvoiceData->customerBranch->billing_state : '' }}{{ isset($saleInvoiceData->customerBranch->billing_city) ? ', '. $saleInvoiceData->customerBranch->billing_city : '' }}</p>
          <p>{{ isset($saleInvoiceData->customerBranch->billingCountry) ? $saleInvoiceData->customerBranch->billingCountry->name : '' }} {{ isset($saleInvoiceData->customerBranch->billing_zip_code) ? ', '.$saleInvoiceData->customerBranch->billing_zip_code : '' }}</p>
        </div>
    </header>
    <main>
      <table class="tablebg">
        <thead>
          <tr class="inv-primary-color-bg">
            <th class="service f20">{{ __('SI') }}</th>
            <th class="desc">{{ __('Description')}}</th>
            @if($saleInvoiceData->has_hsn)
                <th class="f20">{{ __('HSN') }}</th>
            @endif
            @if($saleInvoiceData->invoice_type == 'hours')
                <th class="f20">{{ __('Hours') }}</th>
                <th class="f20">{{ __('Rate') }}</th>
            @else
                <th class="f20">{{ __('Quantity') }}</th>
                <th class="f20">{{ __('Price') }}</th>
            @endif

            @if($saleInvoiceData->has_item_discount)
                <th class="f20">{{ __('Discount') }}</th>
            @endif
            @if($saleInvoiceData->has_tax)
                <th class="f20">{{ __('Tax') }}(%)</th>
            @endif
            <th class="f20">{{ __('Amount') }} ({{ $saleInvoiceData->currency->symbol }}) </th>
          </tr>
        </thead>
        <tbody>
          @php
            $itemsInformation = '';
            $row = 6;
            $currentTaxArray = [];
            if ($saleInvoiceData->invoice_type == 'amount') {
              $row = $row - 2;
            }
            if (!$saleInvoiceData->has_item_discount) {
              $row = $row - 1;
            }
            if (!$saleInvoiceData->has_hsn) {
              $row = $row - 1;
            }
            if (!$saleInvoiceData->has_tax) {
              $row = $row - 1;
            }
          @endphp
          @if ( count ($saleInvoiceData->saleOrderDetails) > 0 )
            @php $subTotal = $totalDiscount = $i = 0; @endphp
            @foreach ($saleInvoiceData->saleOrderDetails as $result)
              @php
              $priceAmount = ($result->quantity * $result['unit_price']);
              $subTotal += $priceAmount;
              @endphp
              @if($result->quantity > 0)
                <tr>
                  <td class="f20 inv-primary-color">{{ ++$i }}</td>
                  <td class="desc wd-400">
                    <span class="main_titlebg f18 inv-primary-color"><strong>{{ $result->item_name }}</strong></span><br/>
                    @if($saleInvoiceData->has_description && $result->description)
                      <span class="f18 inv-secondary-color">{{ $result->description }}</span>
                    @endif
                  </td>
                  @if($saleInvoiceData->has_hsn == 1)
                    <td class="qty f18 inv-secondary-color">{{ $result->hsn }}</td>
                  @endif
                  @if($saleInvoiceData->invoice_type != 'amount')
                    <td  class="qty f18 inv-secondary-color">{{ formatCurrencyAmount($result->quantity) }}</td>
                  @endif
                  <td class="unit f18 inv-secondary-color">{{ formatCurrencyAmount($result->unit_price) }}</td>
                  @if($saleInvoiceData->has_item_discount == 1)
                    <td class="qty f18 inv-secondary-color">{{ formatCurrencyAmount($result->discount) }}{{ $result->discount_type }}</td>
                  @endif
                  @if($saleInvoiceData->has_tax == 1)
                  
                  <td class="qty f18 inv-secondary-color">
                    @foreach ( json_decode($result->taxList) as $counter => $taxItem)
                      {{ formatCurrencyAmount($taxItem->rate) }}%
                      @if ($counter < count(json_decode($result->taxList)) - 1 )
                        <br>
                      @endif
                    @endforeach
                  </td>
                  @endif
                  <td class="total f18 inv-secondary-color">{{ formatCurrencyAmount($priceAmount) }}</td>
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
              <td colspan="{{ $row }}" class="cal-title">{{ __('Sub Total') }} :</td>
              <td class="cal-value">{{ formatCurrencyAmount($subTotal, isset($saleInvoiceData->currency->symbol) ? $saleInvoiceData->currency->symbol : '') }}</td>
            </tr>
            @if($saleInvoiceData->has_item_discount)
            <tr>
              <td colspan="{{$row}}" class="cal-title">Discount :</td>
              <td class="cal-value">{{ formatCurrencyAmount($saleInvoiceData->saleOrderDetails->sum('discount_amount'), isset($saleInvoiceData->currency->symbol) ? $saleInvoiceData->currency->symbol : '') }}</td>
            </tr>
            @endif
            </tr>
            @forelse($taxes as $tax)
              <tr>
                <td colspan="{{$row}}" class="cal-title">{{ $saleInvoiceData->tax_type == 'inclusive' ? __('Included') : '' }}{{ $tax['name'] }} ({{ formatCurrencyAmount($tax['rate']) }})% :</td>
                <td class="cal-value">{{ formatCurrencyAmount($tax['amount'], isset($saleInvoiceData->currency->symbol) ? $saleInvoiceData->currency->symbol : '') }}</td>
              </tr>
            @empty
            @endforelse
            @if($saleInvoiceData->has_other_discount == 1)
              <tr>
                @php
                  if($saleInvoiceData->other_discount_type=="$"){
                    $otherDiscount = $saleInvoiceData->other_discount_amount;
                  }else{
                    $otherDiscount = $subTotal * $saleInvoiceData->other_discount_amount / 100;
                  }
                @endphp
                <td colspan="<?= $row ?>" class="cal-title">{{ __('Other Discount') }} ({{ formatCurrencyAmount($saleInvoiceData->other_discount_amount) }}) {{ $saleInvoiceData->other_discount_type=='$' ? $saleInvoiceData->currency->symbol : '%' }} :</td>
                <td class="cal-value">{{ formatCurrencyAmount($otherDiscount, $saleInvoiceData->currency->symbol )}}</td>
              </tr>
              @endif
              @if($saleInvoiceData->has_shipping_charge && $saleInvoiceData->shipping_charge)
                <tr>
                  <td colspan="{{$row}}" class="cal-title">Shipping :</td>
                  <td class="cal-value">{{ formatCurrencyAmount($saleInvoiceData->shipping_charge, $saleInvoiceData->currency->symbol) }}</td>
                </tr>
              @endif
              @if($saleInvoiceData->has_custom_charge)
                <tr>
                  <td colspan="<?= $row ?>" class="cal-title">{{ $saleInvoiceData->custom_charge_title }} :</td>
                  <td class="cal-value">{{ formatCurrencyAmount($saleInvoiceData->custom_charge_amount, isset($saleInvoiceData->currency->symbol) ? $saleInvoiceData->currency->symbol : '') }}</td>
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
              <td align="right">{{ formatCurrencyAmount($saleInvoiceData->total, isset($saleInvoiceData->currency->symbol) ? $saleInvoiceData->currency->symbol : '') }}</td>
            </tr>
            <tr>
              <td colspan="{{$row}}" class="final-cal-title">{{ __('Paid') }} :</td>
              <td class="final-cal-value">{{ formatCurrencyAmount($saleInvoiceData->paid, isset($saleInvoiceData->currency->symbol) ? $saleInvoiceData->currency->symbol : '')  }}</td>
            </tr>
            <tr>
              <td colspan="{{$row}}" class="final-cal-title">{{ __('Due') }} :</td>
              <td class="final-cal-value">
                @if($saleInvoiceData->total > $saleInvoiceData->paid)
                  {{ formatCurrencyAmount(abs($saleInvoiceData->total - $saleInvoiceData->paid), isset($saleInvoiceData->currency->symbol) ? $saleInvoiceData->currency->symbol : '') }}
                @else
                  {{ formatCurrencyAmount(abs($saleInvoiceData->total - $saleInvoiceData->paid), isset($saleInvoiceData->currency->symbol) ? $saleInvoiceData->currency->symbol : '') }}
                @endif
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      </div>
      
      @if($saleInvoiceData->has_comment == 1 && !empty($saleInvoiceData->comment))
      <div class="bottomtex">
        <div class="left_text">
          <h1>{{ __('Note') }} : {{ $saleInvoiceData->comment }} </h1>         
        </div>
      </div>
      @endif
    </main>
  </body>
</html>