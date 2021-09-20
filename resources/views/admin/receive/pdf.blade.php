<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>{{ __('Purchase Receive') }}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/css/pdf/receipt-pdf.min.css') }}">
  </head>
  <body>
    <div class="receipt-container">
      <div class="receipt-type-heading">{{ __('Receive')  }}</div>
      <div>
        <div class="receipt-head-section">
          <div>
            <div class="r-font-16"><strong>{{ $company_name }}</strong></div>
            <div class="r-font-16">{{ $company_street }}</div>
            <div class="r-font-16">{{ ! empty($company_city) ? $company_city : '' }}{{ ! empty($company_state) ? ', '.$company_state : ''}}{{!empty($company_zipCode) ? ', '.$company_zipCode : ''}}</div>
            <div class="r-font-16">{{ $company_country_name }}</div>
          </div>
        </div>
        <div class="receipt-head-section">
          <div class="r-font-16"><strong>{{ isset($receivedData->supplier->name) ? $receivedData->supplier->name : '' }}<strong></div>
          <div class="r-font-16">{{ isset($receivedData->supplier->street) ? $receivedData->supplier->street : '' }}</div>
          <div class="r-font-16">{{ isset($receivedData->supplier->city) ? $receivedData->supplier->city : '' }}{{ isset($receivedData->supplier->state) ? ', ' . $receivedData->supplier->state : '' }}</div>
          <div class="r-font-16">{{ isset($receivedData->supplier->country->name) ? $receivedData->supplier->country->name : ''}}{{ isset($receivedData->supplier->zipcode) ? ', ' . $receivedData->supplier->zipcode : '' }}</div>
        </div>
        <div class="receipt-head-section">
          <div class="r-font-16"><strong>{{ __('Purchase No')  . ' # ' . $receivedData->reference }}</strong></div>
          <div class="r-font-16"><strong>{{ __('Receive No') }} # {{ sprintf("%04d", $receivedData->id) }}</strong></div>
          <div class="r-font-16">{{ __('Date')  }} : {{ formatDate($receivedData->receive_date) }}</div>
        </div>
      </div>
      <div class="c-both"></div>
      <div class="r-mt20">
        <table class="receipt-table">
          <tr class="receipt-table-head">
            <td class="add-padding-left">{{ __('SL No')  }}</td>
            <td>{{ __('Description')  }}</td>
            <td>{{ __('Quantity')  }}</td>
          </tr>
          @forelse($receivedData->receivedOrderDetails as $key => $item)
          <tr class="receipt-table-row">
            <td class="add-padding-left">{{ ++$key }}</td>
            <td>{{ $item->item_name }}</td>
            <td>{{ formatCurrencyAmount($item->quantity) }}</td>
          </tr>
          @empty
          @endforelse
        </table>
      </div>
    </div>
  </body>
</html>