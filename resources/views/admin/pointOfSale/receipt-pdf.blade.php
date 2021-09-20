<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="{{ asset('public/dist/css/pdf/pos-receipt-pdf.min.css') }}">
</head>
<body class="width-40">
        @if(!empty($company_logo) && file_exists(public_path("/uploads/companyPic/".$company_logo)))
            <div class="text-center">
                <img src='{{ public_path("/uploads/companyPic/".$company_logo) }}' alt="Logo"
                     height="50" weight="50"/>
            </div>
        @endif
        <div class="clear-both"></div>
        <div class="mb10 text-center">
            <div class="company-info">
                <div><strong>{{ $company_name }}</strong></div>
                <div>{{ $company_street }}</div>
                <div>{{ $company_city }} {{ !empty($company_city) && !empty($company_state) ? "," : "" }} {{ $company_state }} {{ !empty($company_city) && !empty($company_zipCode) ? "-" : "" }} {{ $company_zipCode }}</div>
                <div>{{ $company_country_name }}</div>
            </div>
        </div>
        <div class="f10 mb10">
            -------------------------------------- {{ __('RETAIL INVOICE') }} --------------------------------------
        </div>
        <div class="mb10">
            <table class="width100_per">
                <tr>
                    <td>{{ __('Cashier') }} : {{$saleInvoiceData->user->full_name}}</td>
                    <td class="text-right">{{ __('Customer') }} : {{! empty($saleInvoiceData->customer) ? $saleInvoiceData->customer->first_name." ".$saleInvoiceData->customer->last_name : __('Walking customer') }}</td>
                </tr>
                <tr>
                    <td>{{ __('Invoice') }} : {{ $saleInvoiceData->reference }}</td>
                    <td class="text-right">Date : {{ formatDate($saleInvoiceData->order_date) }} </td>
                </tr>
            </table>
        </div>
        <div class="clear-both"></div>
        <table class="width100_per border-none">
            <thead class="dashed-border-bottom dashed-border-top">
                <tr>
                    <th>{{ __('SL') }}</th>
                    <th>{{ __('Item') }}</th>
                    <th class="text-right">{{ __('Quantity') }}</th>
                    <th class="text-right">{{ __('Price') }}</th>
                    <th class="text-right">{{ __('Total') }}</th>
                </tr>
            </thead>
            <tbody class="dashed-border-bottom">
                @php $subtotal = 0; @endphp
                @foreach($saleInvoiceData->saleOrderDetails as $key => $order)
                <tr>
                    <td>{{$key + 1}}</td>
                    <td>{{ $order->item_name }}</td>
                    <td class="text-right">{{ formatCurrencyAmount($order->quantity) }}</td>
                    <td class="text-right"> {{ formatCurrencyAmount($order->unit_price) }}</td>
                    <td class="text-right"> {{ formatCurrencyAmount($order->quantity * $order->unit_price) }}</td>
                    @php $subtotal += $order->quantity * $order->unit_price; @endphp
                </tr>
                @endforeach
            </tbody>
            <tbody class="dashed-border-bottom pb10">
                <tr>
                    <td colspan="4" class="text-right">{{ __('Sub Total') }}: </td>
                    <td class="text-right">{{ formatCurrencyAmount($subtotal, $saleInvoiceData->currency->symbol) }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right">(-) {{ __('Discount') }}: </td>
                    <td class="text-right">
                        @php
                            $discountAmount = $saleInvoiceData->saleOrderDetails->sum('discount_amount');
                            if($saleInvoiceData->other_discount_type == "%") {
                                $discountAmount += ($subtotal * $saleInvoiceData->other_discount_amount / 100);
                            } else {
                                $discountAmount += $saleInvoiceData->other_discount_amount;
                            }
                        @endphp
                        {{ formatCurrencyAmount($discountAmount, $saleInvoiceData->currency->symbol) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right"> {{ $saleInvoiceData->tax_type == 'inclusive' ? __('Included') : '(+) ' }} {{ __('Tax') }}: </td>
                    <td class="text-right"> {{ formatCurrencyAmount($totalTaxAmount, $saleInvoiceData->currency->symbol) }} </td>
                </tr>
                @if($saleInvoiceData->shipping_charge > 0)
                    <tr>
                        <td colspan="4" class="text-right"> (+) {{ __('Shipping') }}: </td>
                        <td class="text-right"> {{ formatCurrencyAmount($saleInvoiceData->shipping_charge, $saleInvoiceData->currency->symbol) }} </td>
                    </tr>
                @endif
                <tr>
                    <td colspan="4" class="text-right">{{ __('Net payable') }} : </td>
                    <td class="text-right"> {{ formatCurrencyAmount($saleInvoiceData->total, $saleInvoiceData->currency->symbol) }} </td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="dashed-border-bottom"></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right">{{ __('Cash Paid') }} : </td>
                    <td class="text-right"> {{ formatCurrencyAmount($saleInvoiceData->amount_received, $saleInvoiceData->currency->symbol) }} </td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right">{{ __('Change Amount') }} : </td>
                    <td class="text-right"> {{ formatCurrencyAmount(abs($saleInvoiceData->amount_received - $saleInvoiceData->total), $saleInvoiceData->currency->symbol) }}</td>
                </tr>
            </tbody>
        </table>
        <div class="clear-both"></div>
        <div class="thank-note">
            {{ __('Thank you') }}
        </div>
</body>
</html>
