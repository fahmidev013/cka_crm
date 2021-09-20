<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{{ __('Payment') }}</title>
<link rel="stylesheet" type="text/css" href="{{ asset('public/dist/css/pdf/receipt-pdf.min.css') }}">
</head>
  <body>
    <div class="receipt-container">
      <div class="receipt-type-heading">{{ __('Payment Receipt') }}</div>
      <div class="receipt-head-container">
        <div class="receipt-head-section-left">
          <div class="r-mt20">
            <div class="r-company-name">{{ $company_name }}</div>
            <div class="r-font-16">{{ $company_street }}</div>
            <div class="r-font-16">{{ ! empty($company_city) ? $company_city : '' }}{{ ! empty($company_state) ? ', ' . $company_state : ''}}{{!empty($company_zipCode) ? ', ' . $company_zipCode : ''}}</div>
            <div class="r-font-16">{{ $company_country_name }}</div>
          </div>
        </div>
        <div class="receipt-head-section">
          <div class="r-mt20">
            <div class="r-font-16"><strong>{{ isset($paymentInfo->supplier->name) ? $paymentInfo->supplier->name : '' }}</strong></div>
            <div class="r-font-16">{{ isset($paymentInfo->supplier->street) ? $paymentInfo->supplier->street : '' }}</div>
            <div class="r-font-16">{{ isset($paymentInfo->supplier->city) ? $paymentInfo->supplier->city : '' }}{{ isset($paymentInfo->supplier->state) ? ', ' . $paymentInfo->supplier->state : '' }}</div>
            <div class="r-font-16">{{ isset($paymentInfo->supplier->Country->name) ? $paymentInfo->supplier->Country->name : '' }}{{ isset($paymentInfo->supplier->zipcode) ? ', ' . $paymentInfo->supplier->zipcode: '' }}</div>
          </div>
          <br/>
        </div>
        <div class="receipt-head-section-right">
          <div class="r-mt20">
            <div class="r-font-16">{{ __('Payment No') . ' # ' . sprintf("%04d", $paymentInfo->id) }}</div>
            <div>{{ __('Payment Date')  }} : {{ formatDate($paymentInfo->transaction_date) }}</div>
            <div>{{ __('Payment Method')  }} : {{ isset($paymentInfo->payment_method) ? $paymentInfo->payment_method->name : 'N/A' }}</div>
          </div>
          <br/>
        </div>
      </div>

      <div class="c-both"></div>
      <table class="receipt-table">
        <tr class="receipt-table-head">
        <td class="add-padding-left">{{ __('Purchase No')  }}</td>
        <td>{{ __('Purchase Date') }}</td>
        <td>{{ __('Purchase Amount')  }}</td>
        <td>{{ __('Paid Amount')  }}</td>
      </tr>
        <tr class="receipt-table-row">
          <td class="add-padding-left">{{ $paymentInfo->purchaseOrder->reference }}</td>
          <td>{{ formatDate($paymentInfo->transaction_date) }}</td>
          <td>{{ formatCurrencyAmount($paymentInfo->purchaseOrder->total, $paymentInfo->purchaseOrder->currency->symbol) }}</td>
          <td>{{ formatCurrencyAmount($paymentInfo->amount, $paymentInfo->currency->symbol) }}</td>
        </tr>
      </table>
    </div>
    <script type="text/javascript">
        'use strict';
        $(function(){
          window.onload = function() { window.print(); }
        });
    </script>
  </body>
</html>