<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>{{ __('Payment') }}</title>
<link rel="stylesheet" type="text/css" href="{{ asset('public/dist/css/pdf/receipt-pdf.min.css') }}">
</head>
<body>
  <div class="receipt-container">
  <div class="receipt-type-heading">{{ __('Payment Receipt') }}</div>
  <div>
    <div class="receipt-head-section">
      <div class="r-mt20">
        <div class="r-company-name">{{ $company_name }}</div>
        <div class="r-font-16">{{ $company_street }}</div>
        <div class="r-font-16">{{ $company_city }}{{!empty($company_state) ? ', ' . $company_state : '' }}</div>
        <div class="r-font-16">{{ $company_country_name }}{{ !empty($company_zipCode) ? ', ' . $company_zipCode : ''}}</div>
      </div>
    </div>

    <div class="receipt-head-section">
      <div class="r-mt20">
        <div class="r-font-16"><strong>{{ isset($paymentInfo->customer->name) ? $paymentInfo->customer->name : '' }}</strong></div>
        <div class="r-font-16">{{ isset($paymentInfo->customer->customerBranch) ? $paymentInfo->customer->customerBranch->billing_street : '' }}</div>
        <div class="r-font-16">{{ isset($paymentInfo->customer->customerBranch) ? $paymentInfo->customer->customerBranch->billing_city : '' }}{{ isset($paymentInfo->customer->customerBranch->billing_state) ? ', ' . $paymentInfo->customer->customerBranch->billing_state : '' }}</div>
        <div class="r-font-16">{{ isset($paymentInfo->customer->customerBranch->billingCountry) ? $paymentInfo->customer->customerBranch->billingCountry->name : '' }}{{ isset($paymentInfo->customer->customerBranch->billing_zip_code) ? ', ' . $paymentInfo->customer->customerBranch->billing_zip_code : '' }}</div>
      </div>
      <br/>
    </div>
  
    <div class="receipt-head-section">
      <div class="r-mt20">
        <div class="r-font-16">{{ __('Payment No') . ' # ' . sprintf("%04d", $paymentInfo->id) }}</div>
        <div class="r-font-16">{{ __('Payment Date')  }} : {{ formatDate($paymentInfo->transaction_date) }}</div>
        <div class="r-font-16">{{ __('Payment Method')  }} : {{ isset($paymentInfo->paymentMethod) && isset($paymentInfo->paymentMethod->name) ? $paymentInfo->paymentMethod->name : __('N/A') }}</div>
      </div>
      <br/>
    </div>
  </div>

  <div class="c-both"></div>
   <table class="receipt-table">
      <tr class="receipt-table-head">
      <td class="add-padding-left">{{ __('Invoice No')  }}</td>
      <td>{{ __('Invoice Date')  }}</td>
      <td>{{ __('Invoice Amount')  }}</td>
      <td>{{ __('Paid Amount')  }}</td>
    </tr>
    <tr class="receipt-table-row">
      <td class="add-padding-left">{{ $paymentInfo->saleOrder->reference }}</td>
      <td>{{ formatDate($paymentInfo->saleOrder->order_date) }}</td>
      <td>{{ formatCurrencyAmount($paymentInfo->saleOrder->total, $paymentInfo->saleOrder->currency->symbol) }}</td>
      <td>{{ formatCurrencyAmount($paymentInfo->amount, $paymentInfo->currency->symbol) }}</td>
    </tr>
  </table>
  </div>
    @php 
      if (isset($_GET['type']) && strtolower($_GET['type']) == 'print')
        $print = 'yes';
      else 
        $print = '';
    @endphp
    <input type="hidden" id="printVal" value="{{ $print }}">
  <script type="text/javascript">
    'use strict';
    $(function(){
      var print = $('#printVal').val();
      if (print && print.length > 1) {
        window.onload = function() { window.print(); }
      }
    });
  </script>
</body>
</html>