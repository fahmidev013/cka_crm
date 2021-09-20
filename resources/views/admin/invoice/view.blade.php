@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/dist/css/invoice-style.min.css') }}">
@endsection

@section('content')

<div class="col-sm-12" id="card-with-header-button">
  <div class="card">
    <div class="card-header">
      <h5><a href="{{url('invoice/list')}}">{{ __('Invoice') }}</a> >>  #{{ $saleInvoiceData->reference }}</h5>
      <div class="card-header-right">
        @if(Helpers::has_permission(Auth::user()->id, 'add_invoice'))
          <a href='{{ url("invoice/add") }}{{ $menu == "sales" ? "" : "?" . $changeSubMenu }}' class="btn btn-outline-primary custom-btn-small"><span class="feather icon-plus"> &nbsp;</span>{{ __('New Invoice') }}</a>
        @endif
      </div>
    </div>
  </div>
</div>

<div class="col-sm-12" id="sales-invoice-view-details-container">
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
        	<div class="float-left">
      		@if($saleInvoiceData->total > 0)
              @if($saleInvoiceData->paid == 0)
                 <div class="btn-unpaid">{{ __('Unpaid') }}</div>
              @elseif($saleInvoiceData->paid > 0 && $saleInvoiceData->total > $saleInvoiceData->paid)
                 <div class="btn-paid-partial">{{ __('Partially Paid') }}</div>
              @elseif($saleInvoiceData->total <= $saleInvoiceData->paid)
                <div class="btn-paid">{{ __('Paid') }}</div>
              @endif
            @else
              <div class="btn-paid">{{ __('Paid')}}</div>
            @endif
        	</div>
          <div class="btn-group float-right row mr-2 mt-1 pl-3">
            @if(!empty($smsInformation))
            <button title="SMS" type="button" class="btn custom-btn-small btn-outline-secondary" data-toggle="modal" data-target="#smsOrder">{{ __('SMS') }}</button>
            @endif
            <button title="Email" type="button" class="btn custom-btn-small btn-outline-secondary" data-toggle="modal" data-target="#emailInvoice">{{ __('Email') }}</button>
            <a target="_blank" href="{{ URL::to('/') }}/invoice/print-pdf/{{ $invoice_no }}?type=print" title="Print" class="btn custom-btn-small btn-outline-secondary">{{ __('Print') }}</a>
            <a target="_blank" href="{{ URL::to('/') }}/invoice/print-pdf/{{ $invoice_no }}?type=pdf" title="PDF" class="btn custom-btn-small btn-outline-secondary">{{ __('PDF') }}</a>
            @if($saleInvoiceData->transaction_type != "POSINVOICE" &&  Helpers::has_permission(Auth::user()->id, 'edit_invoice'))
              <a href='{{ URL::to("/") }}/invoice/edit/{{ $saleInvoiceData->id }}{{ $menu == "sales" ? "" : "?" . $sub_menu }}' title="Edit" class="btn custom-btn-small btn-outline-secondary">{{ __('Edit') }}</a>
            @endif
            @if($saleInvoiceData->transaction_type != "POSINVOICE")
              <a target="_self" href='{{ URL::to("/invoice/copy") }}/{{ $saleInvoiceData->id }}{{ $menu == "sales" ? "" : "?" . $sub_menu }}' title="Copy" class="btn custom-btn-small btn-outline-secondary">{{  __('Copy Invoice') }}</a>
            @endif
            @if($saleInvoiceData->total > $saleInvoiceData->paid)
							<button title="{{ __('Pay Now') }}" type="button" class="btn custom-btn-small btn-outline-primary" data-toggle="modal" data-target="#payModal">{{ __('Pay Now') }}</button>
            @endif
            @if(Helpers::has_permission(Auth::user()->id, 'delete_invoice'))
              <form method="POST" action='{{ url("invoice/delete/" . $saleInvoiceData->id) }}' accept-charset="UTF-8" class="display_inline" id="delete-invoice">
                {{csrf_field()}}
                <input type="hidden" name="menu" value="{{ $menu }}">
                <input type="hidden" name="sub_menu" value="{{ $sub_menu }}">
                <input type="hidden" name="customer" value="{{ $saleInvoiceData->customer_id }}">
                <button title="{{ __('Delete') }}" class="btn custom-btn-small btn-outline-danger" type="button" data-toggle="modal" data-id="{{ $saleInvoiceData->id }}" data-target="#theModal" data-label="Delete" data-title="{{ __('Delete Invoice') }}" data-message="{{ __('Are you sure to delete this invoice? This will delete all related transaction data.') }}">{{ __('Delete') }}</button>
              </form>
            @endif
          </div>
        </div>
        <div class="card-body">
          <div class="m-t-10">
            <div class="row m-t-10 ml-2">
              <div class="col-md-{{ $saleInvoiceData->pos_shipping ? 3 : 4 }} m-b-15">
                <strong class="text-black">{{ $company_name }}</strong><br>
                <strong>{{ $company_street }}</strong><br>
                <strong>{{ $company_city }}, {{ $company_state }}</strong><br>
                <strong>{{ $company_country_name }}, {{ $company_zipCode }}</strong><br>
              </div>
              <div class="col-md-{{ $saleInvoiceData->pos_shipping ? 3 : 4 }} m-b-15">
                <strong class="text-black">{{ __('Bill To') }}</strong><br>
                <strong class="text-black">{{ isset($saleInvoiceData->customer->first_name) ? $saleInvoiceData->customer->first_name : '' }} {{ isset($saleInvoiceData->customer->last_name) ? $saleInvoiceData->customer->last_name : __('Walking customer') }}</strong><br>
                <strong>{{ isset($saleInvoiceData->customerBranch->billing_street) ? $saleInvoiceData->customerBranch->billing_street : '' }} </strong><br>
                <strong>{{ isset($saleInvoiceData->customerBranch->billing_state) ? $saleInvoiceData->customerBranch->billing_state : '' }}{{ isset($saleInvoiceData->customerBranch->billing_city) ? ', ' . $saleInvoiceData->customerBranch->billing_city : '' }}</strong><br>
                  <strong>{{ isset($saleInvoiceData->customerBranch->billingCountry) ? $saleInvoiceData->customerBranch->billingCountry->name : '' }} {{ isset($saleInvoiceData->customerBranch->billing_zip_code) ? ', ' . $saleInvoiceData->customerBranch->billing_zip_code : '' }}</strong><br>
              </div>
              @if($saleInvoiceData->pos_shipping)
                <div class="col-md-3 m-b-15">
                  <strong>{{ __('Shipment') }}</strong><br>
                  @if(isset($saleInvoiceData->shipping_address->name))
                    <strong>{{ $saleInvoiceData->shipping_address->name }}</strong><br>
                  @endif
                  @if(isset($saleInvoiceData->shipping_address->ship_email))
                    <strong>{{ $saleInvoiceData->shipping_address->ship_email }}</strong><br>
                  @endif
                  <strong>{{ isset($saleInvoiceData->shipping_address->ship_city) ? $saleInvoiceData->shipping_address->ship_city : "" }} {{ isset($saleInvoiceData->shipping_address->ship_city) && isset($saleInvoiceData->shipping_address->ship_state) ? ', ' : '' }} {{ isset($saleInvoiceData->shipping_address->ship_state) ? $saleInvoiceData->shipping_address->ship_state : '' }}</strong><br>
                  <strong>{{ isset($saleInvoiceData->shipping_address->ship_country) ? $saleInvoiceData->shipping_address->ship_country : "" }} {{ isset($saleInvoiceData->shipping_address->ship_country) && isset($saleInvoiceData->shipping_address->ship_zipCode) ? ' - ': '' }} {{ isset($saleInvoiceData->shipping_address->ship_zipCode) ? $saleInvoiceData->shipping_address->ship_zipCode : '' }}</strong><br>
                </div>
              @endif
              <div class="col-md-{{ $saleInvoiceData->pos_shipping ? 3 : 4 }} m-b-15">
                @if($saleInvoiceData->transaction_type != "POSINVOICE" && !empty($saleInvoiceData->order_reference_id))
                  <strong>{{ __('Quotation No') }} # <a href='{{ url("order/view-order-details/" . $saleInvoiceData->order_reference_id) }}'>{{ $saleOrderData->reference }}</a></strong><br>
                @endif
                <strong>{{ __('Location') }} : {{ isset($saleInvoiceData->location->name) ? $saleInvoiceData->location->name : '' }}</strong><br>
                <strong>{{  __('Invoice Date') }} : {{ formatDate($saleInvoiceData->order_date) }}</strong><br>
                @if($saleInvoiceData->due_date)
                  <strong>{{ __('Due Date') }} : {{ formatDate($saleInvoiceData->due_date) }}</strong><br>
                @endif
              </div>
            </div>
            <div class="row mt-0">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordered" id="salesInvoice">
                    <thead>
                      <tr class="tbl_header_color dynamicRows">
                        @if($saleInvoiceData->invoice_type == 'hours')
                          <th>{{  __('Service') }}</th>
                        @else
                          <th>{{ __('Items') }}</th>
                        @endif
                        @if($saleInvoiceData->has_hsn)
                          <th width="5%" class="text-center">{{ __('HSN') }}</th>
                        @endif
                        @if($saleInvoiceData->invoice_type=='hours')
                          <th width="5%" class="text-center">{{ __('Hours') }}</th>
                          <th width="8%" class="text-center">{{ __('Rate') }}</th>
                        @else
                          <th width="5%" class="text-center">{{ __('Quantity') }}</th>
                          <th width="8%" class="text-center">{{ __('Price') }}({{ isset($saleInvoiceData->currency->symbol) ? $saleInvoiceData->currency->symbol : '' }})</th>
                        @endif
                        @if($saleInvoiceData->has_item_discount)
                          <th class="text-center" width="5%">{{ __('Discount') }}</th>
                        @endif
                        @if($saleInvoiceData->has_tax)
                          <th width="10%" class="text-center">{{ __('Tax') }} (%) </th>
                        @endif
                        <th width="10%" class="text-center">
                          {{ __('Total') }} ({{ isset($saleInvoiceData->currency->symbol) ? $saleInvoiceData->currency->symbol : '' }})
                        </th>
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
                        @php $subTotal = $totalDiscount = 0; @endphp
                        @foreach ($saleInvoiceData->saleOrderDetails as $result)
                          @php
                            $priceAmount = ($result->quantity * $result['unit_price']);
                            $subTotal += $priceAmount;
                          @endphp
                          @if($result->quantity > 0)
                          	<tr>
                              <td class="white-space-unset">
                                <span class="break-all f-16">
                                  {{ $result->item_name }}
                                </span> <br/>
                                @if($saleInvoiceData->has_description && $result->description)
                                  <span class="break-all f-13">
                                    {{ $result->description }}
                                  </span>
                                @endif
                              </td>
                              @if($saleInvoiceData->has_hsn)
                                <td width="5%" class="white-space-unset text-center">{{ $result->hsn }}</td>
                              @endif
                              @if($saleInvoiceData->invoice_type != 'amount')
                                <td width="5%" class="white-space-unset text-center">{{ formatCurrencyAmount($result->quantity) }}</td>
                              @endif
                              <td width="8%" class="white-space-unset text-center">{{ formatCurrencyAmount($result->unit_price) }}</td>
                              @if($saleInvoiceData->has_item_discount)
                                <td width="5%" class="white-space-unset text-center">{{ formatCurrencyAmount($result->discount) }}{{ $result->discount_type }}</td>
                              @endif
                              @if($saleInvoiceData->has_tax)
                                <td width="10%" class="white-space-unset text-center">
                                  @foreach ( json_decode($result->taxList) as $counter => $tax)
                                    {{ formatCurrencyAmount($tax->rate) }}%
                                    @if( $counter < count( json_decode($result->taxList) ) - 1 )
                                      ,
                                    @endif
                                  @endforeach
                                </td>
                              @endif
                              <td align="right" class="white-space-unset">{{ formatCurrencyAmount($priceAmount, isset($saleInvoiceData->currency->symbol) ? $saleInvoiceData->currency->symbol : '') }}</td>
                            </tr>
                          @endif
                        @endforeach
                        <tr class="tableInfos">
                          <td colspan="{{ $row }}" align="right">{{ __('Sub Total') }}</td>
                          <td align="right" colspan="1">{{ formatCurrencyAmount($subTotal, $saleInvoiceData->currency->symbol) }}</td>
                        </tr>
                        @if($saleInvoiceData->has_item_discount)
                          <tr class="tableInfos">
                            <td colspan="{{ $row }}" align="right">{{ __('Discount') }}</td>
                            <td align="right" colspan="1">{{ formatCurrencyAmount($saleInvoiceData->saleOrderDetails->sum('discount_amount'), isset($saleInvoiceData->currency->symbol) ? $saleInvoiceData->currency->symbol : '') }}</td>
                          </tr>
                        @endif
                        @forelse($taxes as $tax)
                          <tr>
                              <td colspan="{{$row}}" align="right">{{$tax['name']}} : {{ formatCurrencyAmount($tax['rate']) }}% </td>
                              <td colspan="1" class="text-right">{{ formatCurrencyAmount($tax['amount'], isset($saleInvoiceData->currency->symbol) ? $saleInvoiceData->currency->symbol : '') }}</td>
                          </tr>
                        @empty
                        @endforelse

                        @if($saleInvoiceData->has_other_discount == 1)
                          <tr class="tableInfos">
                            @php
                              if ($saleInvoiceData->has_item_discount == 1) {
                                if($saleInvoiceData->other_discount_type == "$"){
                                  $otherDiscount = $saleInvoiceData->other_discount_amount;
                                } else {
                                  $otherDiscount = ($subTotal - $saleInvoiceData->saleOrderDetails->sum('discount_amount')) * $saleInvoiceData->other_discount_amount / 100;
                                }
                              } else {
                                if($saleInvoiceData->other_discount_type == "$"){
                                  $otherDiscount = $saleInvoiceData->other_discount_amount;
                                } else {
                                  $otherDiscount = $subTotal * $saleInvoiceData->other_discount_amount / 100;
                                }
                            }
                            @endphp
                            <td colspan="{{ $row }}" align="right">
                              {{ __('Other discount') }} : {{   formatCurrencyAmount($saleInvoiceData->other_discount_amount) }}{{ $saleInvoiceData->other_discount_type == '$' ? $saleInvoiceData->currency->symbol : '%' }}
                            </td>
                            <td align="right">{{ formatCurrencyAmount($otherDiscount, isset($saleInvoiceData->currency->symbol) ? $saleInvoiceData->currency->symbol : '') }}</td>
                          </tr>
                        @endif

                        @if($saleInvoiceData->has_shipping_charge && $saleInvoiceData->shipping_charge)
                          <tr class="tableInfos">
                            <td colspan="{{ $row }}" align="right">{{ __('Shipping') }}</td>
                              <td align="right" colspan="1">{{ formatCurrencyAmount($saleInvoiceData->shipping_charge, isset($saleInvoiceData->currency->symbol) ? $saleInvoiceData->currency->symbol : '') }}</td>
                          </tr>
                        @endif

                        @if($saleInvoiceData->has_custom_charge)
                          <tr class="tableInfos">
                            <td colspan="{{ $row }}" align="right">{{ $saleInvoiceData->custom_charge_title }}</td>
                            <td align="right" colspan="1">{{ formatCurrencyAmount($saleInvoiceData->custom_charge_amount, isset($saleInvoiceData->currency->symbol) ? $saleInvoiceData->currency->symbol : '') }}</td>
                          </tr>
                        @endif

                        <tr class="tableInfos">
                          <td colspan="{{ $row }}" align="right">
                            <strong>{{ __('Grand Total') }}</strong></td>
                          <td colspan="1" class="text-right">
                            <strong>{{ formatCurrencyAmount($saleInvoiceData->total, isset($saleInvoiceData->currency->symbol) ? $saleInvoiceData->currency->symbol : '') }}</strong>
                          </td>
                        </tr>
                        <tr class="tableInfos">
                          <td colspan="{{ $row }}" align="right">{{ __('Paid') }}</td>
                          <td colspan="1" class="text-right">
                            {{ formatCurrencyAmount($saleInvoiceData->paid, isset($saleInvoiceData->currency->symbol) ? $saleInvoiceData->currency->symbol : '') }}
                          </td>
                        </tr>
                        <tr class="tableInfos">
                          <td colspan="{{ $row }}" align="right">
                            <strong>{{ __('Due') }}</strong>
                          </td>

                          <td colspan="1" class="text-right">
                            <strong>
                              @if($saleInvoiceData->total >= $saleInvoiceData->paid)
                                {{ formatCurrencyAmount(abs($saleInvoiceData->total - $saleInvoiceData->paid), isset($saleInvoiceData->currency->symbol) ? $saleInvoiceData->currency->symbol : '') }}
                              @else
                                -{{ formatCurrencyAmount(abs($saleInvoiceData->total - $saleInvoiceData->paid), isset($saleInvoiceData->currency->symbol) ? $saleInvoiceData->currency->symbol : '') }}
                              @endif
                            </strong>
                          </td>
                        </tr>
                      @endif
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-12">
      <div class="card">
      	<div class="card-header">
      		<h5>{{ __('Payments') }}</h5>
      	</div>
        <div class="card-body">
          @if(isset($paymentsList) && count($paymentsList) > 0)
		        <table class="table table-bordered" id="paymentTable">
		          <thead>
		            <tr>
		              <th>{{ __('Payment No') }}</th>
		              <th>{{ __('Method') }}</th>
                  <th>{{  __('Date')  }}</th>
                  <th>{{ __('Status') }}</th>
		              <th align="right">{{ __('Amount') }}</th>
		            </tr>
		          </thead>
		          <tbody>
		            @php $sumInvoice = 0; @endphp
		            @foreach($paymentsList as $payment)
		              <tr>
		                <td>
		                  <a href="{{ url("payment/view-receipt/$payment->id") }}">
		                    <i class="fa fa-chevron-right" aria-hidden="true"></i>&nbsp;{{ sprintf("%04d", $payment->id) }}
		                  </a>
		                </td>
		                <td>{{ isset($payment->paymentMethod->name) ? $payment->paymentMethod->name : '-' }}</td>
                    <td>{{ formatDate($payment->created_at,'Y-m-d') }}</td>
                    <td>
                      @if ($payment->status == 'Approved')
                        <span class="label label-success badge text-white f-12 task-priority color_4CAF50">{{ __('Approved') }}</span>
                      @elseif($payment->status == 'Pending')
                        <div class="btn-group">
                          <button type="button" class="badge text-white f-12 dropdown-toggle task-priority color-04a9f5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ __('Pending') }} &nbsp;<span class="caret"></span><span class="sr-only">{{ __('Toggle Dropdown') }}</span>
                          </button>
                          <ul class="dropdown-menu scrollable-menu status_change task-priority-name w-100p" role="menu">
                            <li class="properties"><a class="status f-14 color_black" data-id="1" data-trans_id="{{ json_encode($payment) }}">{{ __('Approve') }}</a>
                            </li>
                            <li class="properties"><a class="status f-14 cursor_pointer color_black" data-id="2" data-trans_id="{{ $payment->id }}">{{ __('Declined') }}</a></li>

                            </ul>
                          </div>
                      @elseif($payment->status == 'Declined')
                        <span class="label label-danger badge text-white f-12 task-priority color-f44236">{{ __('Declined') }}</span>
                      @endif
                    </td>
                    <td align="right">{{ formatCurrencyAmount($payment->amount, isset($saleInvoiceData->currency->symbol) ? $saleInvoiceData->currency->symbol : '') }}</td>
		              </tr>
		              @php $sumInvoice += $payment->amount; @endphp
		            @endforeach
		            <td colspan="4" align="right">
		            	<strong> {{ __('Total')}} </strong>
		            </td>
		            <td align="right">
		              <strong>{{ formatCurrencyAmount($sumInvoice, isset($saleInvoiceData->currency->symbol) ? $saleInvoiceData->currency->symbol : '') }}</strong>
		            </td>
		          </tbody>
		        </table>
		      @else
		        <h5 class="text-center">{{ __('No payment found') }}</h5>
		      @endif
        </div>
      </div>
    </div>

    @if (count($files) > 0)
      <div class="{{ $saleInvoiceData->comment ? 'col-sm-7' : 'col-sm-12' }}">
        <div class="card">
          <div class="card-header">
            <h5>Files</h5>
          </div>
          <div class="card-body">
            <div class="row pt-4 pb-4 px-3">
              @foreach ($files as $file)
                @php
                  $url = url('public/dist/js/html5lightbox/no_preview.png?v'). $file->id;
                  $extra = '';
                  $div = '';
                  $fileName = !empty($file->original_file_name) ? $file->original_file_name : $file->file_name;
                  if (in_array($file->extension, array('jpg', 'png', 'jpeg', 'gif', 'pdf', 'flv', 'webm', 'mp4', 'ogv', 'swf', 'm4v', 'ogg'))) {
                    $url = url($filePath) .'/'. $file->file_name;
                  } elseif (in_array($file->extension, array('csv', 'xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx', 'txt'))) {
                    $url = '#pdiv-'. $file->id;
                    $extra = 'data-width=900 data-height=600';
                    $div = '<div id="pdiv-'. $file->id .'" class="display_none">
                              <div class="lightboxcontainer">
                                <iframe width="100%" height="100%" src="//docs.google.com/gview?url='. url($filePath) .'/'. $file->file_name .'&embedded=true" frameborder="0" allowfullscreen></iframe>
                                <div class="clear_both"></div>
                              </div>
                            </div>';
                  }
                @endphp
                <a <?= $extra ?> href="{{ $url }}" data-attachment="<?= $file->id; ?>" class="html5lightbox" title="{{ $fileName }}" data-group="{{ $saleInvoiceData->reference }}">
                  <div class="previewer-file-total-div">
                    <div class="previewer-file-thumbnail-div">
                      @if (in_array($file->extension, array('jpg', 'png', 'jpeg', 'gif')))
                        <img class="previewer-thumbnail-size" src="{{ $url }}">
                      @else
                        <i class="{{ $file->icon }} center f-50 previewer-icon-position" style="color:{{ setColor($file->extension) }};" aria-hidden="true"></i>
                      @endif
                    </div>
                    <div class="previewer-file-name-div">
                      <div>
                        <i class="{{ $file->icon }} f-20" style="color:{{ setColor($file->extension) }};" aria-hidden="true"></i>
                        <span class="f-12 previewer-file-name">{{ strlen($fileName) > 15 ? substr_replace($fileName, "..", 15) : $file->original_file_name }}
                        </span>
                      </div>
                    </div>
                  </div>
                </a>
                <?= $div ?>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    @endif
    @if($saleInvoiceData->has_comment == 1 && !empty($saleInvoiceData->comment))
      <div class="{{ count($files) > 0 ? 'col-sm-5' : 'col-sm-12' }}">
        <div class="card">
          <div class="card-header">
            <h5>{{ __('Note') }}</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="text-justify">
                    {{ $saleInvoiceData->comment }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif
  </div>
</div>

<div class="modal fade" id="theModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="theModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary custom-btn-small" data-dismiss="modal">{{ __('Close') }}</button>
        <button type="button" id="theModalSubmitBtn" data-task="" class="btn btn-danger custom-btn-small">{{ __('Submit') }}</button>
        <span class="ajax-loading"></span>
      </div>
    </div>
  </div>
</div>

<!--Pay Modal Start-->
<div class="modal fade" id="payModal" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ __('New Payment') }}</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body mx-5">
	      <form class="form-horizontal" id="payForm" action="{{ url('payment/save') }}" method="POST" enctype="multipart/form-data">
          <input type="hidden" value="{{ csrf_token() }}" name="_token">
          <input type="hidden" name="invoice_reference" value="{{ $saleInvoiceData->reference }}">
          <input type="hidden" name="order_reference" value="{{ $saleInvoiceData->reference }}">
          <input type="hidden" name="customer_id" id="customers" value="{{ $saleInvoiceData->customer_id }}">
          <input type="hidden" name="customerCurrency" id="customerCurrency" value="{{ $saleInvoiceData->currency_id }}">
          <input type="hidden" name="project_id" value="{{ $saleInvoiceData->project_id }}">

          <input type="hidden" name="order_no" value="{{ $saleInvoiceData->order_reference_id }}">
          <input type="hidden" name="invoice_no" value="{{ $invoice_no }}">
          <input type="hidden" name="payment_type" value="invoice">
          <input type="hidden" name="invoice_exchange_rate" value="{{ $saleInvoiceData->exchange_rate }}">
          <input type="hidden" name="invoice_currency_id" value="{{ $saleInvoiceData->currency->id }}">

          <div class="form-group row">
            <label for="payment_type_id" class="col-sm-4 control-label">{{ __('Payment Method') }} </label>
            <div class="col-sm-6">
              <select class="form-control js-example-basic w-100" name="payment_type_id" id="payment_method">
                <option value="">{{ __('Select One') }}</option>
                @foreach($paymentMethods as $payment)
                  <option value="{{ $payment['id'] }}" data-methodName="{{ $payment['name'] }}">{{ $payment['name'] }}</option>
                @endforeach
              </select>
              <input type="hidden" name="setPaymentMethodName" id="setPaymentMethodName">
            </div>
          </div>

          <div class="form-group row display_none" id="account_div">
            <label class="col-sm-4 col-form-label">{{ __('Account') }}</label>
            <div class="col-sm-6">
              <select class="form-control js-example-basic w-100" name="account_no" id="account_no">
                <option value="">{{ __('Select One') }}</option>
                @foreach($accounts as $data)
                  <option value="{{ $data->id }}" data-currency="{{ $data->currency_id }}" currency-symbol="{{ $data->currency->symbol }}" data-code="{{ $data->currency->name }}"> {{ $data->name }}({{ $data->currency->name }})</option>
                @endforeach
              </select>
              <div class="message"></div>
            </div>
            <label id="account_no-error" class="error col-sm-6" for="account_no"></label>
          </div>

          <div class="form-group row" id="currency_div">
            <label for="currency" class="col-sm-4 control-label require">{{ __('Currency') }}  </label>
            <div class="col-sm-6">
              <select class="form-control js-example-basic" name="currency" id="currency">
                @foreach($currencies as $key => $value)
                  <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
              </select>
            </div>
            <input type="hidden" name="setCurrency" id="setCurrency">
            <label id="currency-error" class="error offset-sm-4 currency-error-css" for="currency"></label>
          </div>

          <div class="form-group row display_none" id="account_currency_div">
            <label for="currency" class="col-sm-4 control-label require">{{ __('Currency') }}  </label>
            <div class="col-sm-6">
              <select class="form-control js-example-basic" name="account_currency" id="account_currency">
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label for="amount" class="col-sm-4 control-label require">{{ __('Amount') }}  </label>
            <div class="col-sm-6">
              <div class="input-group">
                <input type="text" placeholder="Amount" class="form-control positive-float-number" id="amount" name="amount" value="{{ $saleInvoiceData->total - $saleInvoiceData->paid }}">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="amount_currency_code">{{ isset( $saleInvoiceData->currency->name) ? $saleInvoiceData->currency->name : '' }}</span>
                  <input type="hidden" id="currency-symbol" value="{{ isset($saleInvoiceData->currency->name) ? $saleInvoiceData->currency->name : ''}}">
                </div>
              </div>
            </div>
            <div class="col-sm-4"></div>
            <label id="amount-error" class="error col-sm-6 display_none" for="amount"></label>
          </div>

          <div class="form-group row display_none" id="exchange_rate_div">
            <label for="amount" class="col-sm-4 control-label require">{{ __('Exchange Rate') }}  </label>
            <div class="col-sm-6">
              <div class="input-group">
                <input type="text" placeholder="Exchange rate" class="form-control positive-float-number" id="exchange_rate" name="exchange_rate" value="">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="exchange_rate_currency_code"></span>
                </div>
              </div>
              <label id="exchange_rate-error" class="error display_none" for="exchange_rate"></label>
            </div>
            <div class="col-sm-4"></div>
          </div>

          <div class="form-group row display_none" id="incoming_amount_div">
            <label for="incoming_amount" class="col-sm-4 control-label require">{{ __('Equivalent Amount') }}  </label>
            <div class="col-sm-6">
              <div class="col-sm-12 p-0">
                <div class="row">
                  <div class="col-sm-11 pr-0">
                    <div class="input-group">
                      <input type="text" placeholder="Incoming Amount" class="form-control positive-float-number" id="incoming_amount" name="incoming_amount" value="" readonly="true">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="incoming_currency"></span>
                      </div>
                    </div>
                    <div id="incoming_amount_message"></div>
                  </div>
                  <div class="col-sm-1 p-0">
                    <sup class="p-1"><span data-toggle="tooltip" title="Successful = Quotation Converted to Invoice."><i class="mdi mdi-information-variant f-18"></i></span></sup>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-4"></div>
            <label id="incoming_amount-error" class="error col-sm-6" for="incoming_amount display_none"></label>
          </div>

          <div class="form-group row">
            <label for="payment_date" class="col-sm-4 control-label require">{{  __('Date') }}</label>
            <div class="col-sm-6">
              <div class="input-group date p-md-0">
                <div class="input-group-prepend">
                  <i class="fas fa-calendar-alt input-group-text"></i>
                </div>
                <input type="text" name="payment_date" class="form-control" id="payment_date" placeholder="{{ __('Paid On') }}">
              </div>
            </div>
          </div>

          <div class="form-group row">
            <label for="reference" class="col-sm-4 control-label require">{{ __('Description') }} </label>
            <div class="col-sm-6">
              <input type="text" name="description" class="form-control" id="description" placeholder="{{ __('Description') }}" value="Payment for {{ $saleInvoiceData->reference }} " readonly>
            </div>
          </div>

          <div class="form-group row">
            <label for="reference" class="col-sm-4 control-label">{{ __('Reference') }} </label>
            <div class="col-sm-6">
               <input type="text" name="reference" class="form-control" id="reference" value="{{ $reference }}"  placeholder="{{ __('Reference') }}" readonly>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-4 control-label" for="attachment">{{ __('Attachment') }}</label>
            <div class="dropzone-attachments col-sm-7 ml-3">
              <div class="event-attachments">
                <div class="add-attachments"><i class="fa fa-plus"></i></div>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-4"></div>
            <div class="col-sm-6" id="uploader-text"></div>
          </div>

          <div class="form-group row">
            <div class="col-sm-4"></div>
            <div class="col-sm-6 mt-5">
              <span class="badge badge-danger">{{ __('Note') }}!</span> {{ __('Allowed File Extensions: jpg, png, gif, docx, xls, xlsx, csv and pdf') }}
            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm-offset-3 col-sm-6">
              <button class="btn btn-primary btn-flat custom-btn-small" type="submit" id="pay-button"><i class="comment_spinner spinner fa fa-spinner fa-spin custom-btn-small"></i><span id="spinnerText">{{ __('Pay Now') }} </span></button>  
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--Pay Modal End-->

<!-- Send Email Modal -->
<div id="emailInvoice" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <form id="sendVoiceInfo" method="POST" action="{{ url('invoice/email-invoice-info') }}">
      <input type="hidden" value="{{ csrf_token() }}" name="_token">
      <input type="hidden" value="{{ $saleInvoiceData->order_reference_id }}" name="order_id" id="order_id_email">
      <input type="hidden" value="{{ $invoice_no }}" name="invoice_id" id="invoice_id">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('Send invoice information to client') }}</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="email">{{ __('Send To') }}:</label>
            <input type="email" value="{{ isset($saleInvoiceData->customer->email) ? $saleInvoiceData->customer->email : '' }}" class="form-control" name="email" id="email">
          </div>
          @php
            $subjectInfo = !empty ($emailInfo->subject) ? $emailInfo->subject : '';
          	$subjectInfo = str_replace('{invoice_reference_no}', $saleInvoiceData->reference, $subjectInfo);
          	$subjectInfo = str_replace('{company_name}', $company_name, $subjectInfo);
          @endphp
          <div class="form-group">
            <label for="subject">{{ __('Subject')}}:</label>
            <input type="text" class="form-control" name="subject" id="subject-email" value="{{ $subjectInfo }}">
          </div>
          <div class="form-group">
            @php
              $firstName = isset($saleInvoiceData->customer->first_name) ? $saleInvoiceData->customer->first_name : "";
              $lastName = isset($saleInvoiceData->customer->last_name) ? $saleInvoiceData->customer->last_name : "";
              $bodyInfo = str_replace('{customer_name}', $firstName .' '. $lastName, !empty($emailInfo) ? $emailInfo->body : '');
              $bodyInfo = str_replace('{order_reference_no}', $saleInvoiceData->reference, $bodyInfo);
              $bodyInfo = str_replace('{invoice_reference_no}',$saleInvoiceData->reference, $bodyInfo);
              $bodyInfo = str_replace('{due_date}',formatDate($saleInvoiceData->due_date), $bodyInfo);
              $bodyInfo = str_replace('{billing_street}', isset($saleInvoiceData->customer->customerBranch->billing_street) ? $saleInvoiceData->customer->customerBranch->billing_street : "", $bodyInfo);
              $bodyInfo = str_replace('{billing_city}', isset($saleInvoiceData->customer->customerBranch->billing_city) ? $saleInvoiceData->customer->customerBranch->billing_city : "", $bodyInfo);
              $bodyInfo = str_replace('{billing_state}', isset($saleInvoiceData->customer->customerBranch->billing_state) ? $saleInvoiceData->customer->customerBranch->billing_state : "", $bodyInfo);
              $bodyInfo = str_replace('{billing_zip_code}', isset($saleInvoiceData->customer->customerBranch->billing_zip_code) ? $saleInvoiceData->customer->customerBranch->billing_zip_code : "", $bodyInfo);
              $bodyInfo = str_replace('{billing_country}', $billingCountry, $bodyInfo);
              $bodyInfo = str_replace('{company_name}', $company_name, $bodyInfo);
              $bodyInfo = str_replace('{invoice_summery}', $itemsInformation, $bodyInfo);
              $bodyInfo = str_replace('{currency}',  isset($saleInvoiceData->currency->symbol) ? $saleInvoiceData->currency->symbol : '' , $bodyInfo);
              $bodyInfo = str_replace('{total_amount}', $saleInvoiceData->total, $bodyInfo);
              $bodyInfo = html_entity_decode($bodyInfo);
            @endphp
            <input type="hidden" name="message" id='messageTxt' value="{{ $bodyInfo }}">
          </div>
          <div id="previewTxt"></div>
          <div class="form-group">
            <div class="checkbox">
              <label>{{ __('Attach pdf with Email') }}</label><br>
              <div class="form-group">
                <div class="checkbox checkbox-primary checkbox-fill d-inline">
                  <input type="checkbox" name="invoice_pdf" id="invoice_pdf" checked="">
                  <label for="invoice_pdf" class="cr"><strong>{{ $saleInvoiceData->reference }}</strong></label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary custom-btn-small" data-dismiss="modal">{{ __('Close') }}</button>
          <button type="submit" class="btn btn-primary custom-btn-small">{{ __('Send') }}</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- Send Email Modal end -->

<!-- Send SMS Modal -->
@if(!empty($smsInformation))
<div id="smsOrder" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <form id="sendOrderInfoSMS" method="POST" action="{{ url('sales/send-sms') }}">
      <input type="hidden" value="{{csrf_token()}}" name="_token">
      <input type="hidden" value="{{$saleInvoiceData->id}}" name="order_id" id="order_id_sms">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('Send quotation information to client') }}</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="email">{{ __('Phone') }}:</label>
            <input type="text" value="{{ isset($saleInvoiceData->customer->phone) ? $saleInvoiceData->customer->phone : '' }}" class="form-control" name="phoneno" id="phoneno">
          </div>
          <div class="form-groupa">
            <label for="message">{{ __('Messages') }}:</label>
            <textarea id="compose-textarea-sms" name="message" id='message' class="form-control h-200">{{ $smsInformation }}</textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary custom-btn-small" data-dismiss="modal">{{ __('Close') }}</button>
          <button type="submit" class="btn btn-primary custom-btn-small">{{ __('Send') }}</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endif
<!-- Send SMS Modal end -->
@endsection
@section('js')
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/moment.min.js')}}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/ckeditor/js/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/dist/js/html5lightbox/html5lightbox.js?v=1.0') }}"></script>
<script src="{{ asset('public/dist/plugins/dropzone/dropzone.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script type="text/javascript">
  'use strict';
  var dflt_currency_id = {!! $saleInvoiceData->currency_id !!};
  var invoiceCurrency = {!! $saleInvoiceData->currency_id !!};
  var invoiceCurrencyName = '{!! $saleInvoiceData->currency->name !!}';
  var due = {!! $saleInvoiceData->total-$saleInvoiceData->paid !!};
  var accountNo = '';
  var paymentMethod = '';
  var paymentMethodName = '';
  var currencyId = '';
  var currencyName = '';
  var currencyOption = '';
  var exchange_rate_decimal_digits = "{{ $exchange_rate_decimal_digits }}";
  var phoneNo = "{{ isset($saleInvoiceData->customer->phone) ? $saleInvoiceData->customer->phone : '' }}" ;
</script>
<script src="{{ asset('public/dist/js/custom/sales-purchase.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/sales.min.js') }}"></script>
@endsection