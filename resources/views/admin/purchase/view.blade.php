@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('public/dist/css/invoice-style.min.css')}}">
@endsection

@section('content')
<div id="purchase-view-details-container">
  <div class="col-sm-12" id="card-with-header-button">
    <div class="card">
      <div class="card-header">
        <h5> <a href="{{url('purchase/list')}}">{{ __('Purchases') }}</a> >> #{{ $purchaseData->reference }}</h5>
        <div class="card-header-right">
          @if(Helpers::has_permission(Auth::user()->id, 'add_purchase'))
            <a href='{{ url("purchase/add") }}{{$menu == "purchase" ? "":"?$changeSubMenu"}}' class="btn btn-outline-primary custom-btn-small"><span class="feather icon-plus"> &nbsp;</span>{{ __('Purchase Order')  }}</a>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-12">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
          	<div class="float-left">
  	        @if($purchaseData->total > 0)
                @if($purchaseData->paid == 0)
                  <div class="btn-unpaid">{{ __('Unpaid') }}</div>
                @elseif($purchaseData->paid > 0 && $purchaseData->total > $purchaseData->paid )
                  <div class="btn-paid-partial">{{ __('Partially Paid')}}</div>
                @elseif($purchaseData->total <= $purchaseData->paid)
                  <div class="btn-paid">{{ __('Paid')}}</div>
                @endif
              @else
                <div class="btn-paid">{{ __('Paid')}}</div>
            @endif
          	</div>
          	<div class="btn-group float-right row mr-2 mt-1">
              <button title="Email" type="button" class="btn custom-btn-small btn-outline-secondary" data-toggle="modal" data-target="#emailPurchase">{{ __('Email') }}</button>
              <a target="_blank" href="{{URL::to('/')}}/purchase/print-pdf/{{$purchaseData->id}}?type=print" title="Print" class="btn custom-btn-small btn-outline-secondary">{{ __('Print') }}</a>
              <a target="_blank" href="{{URL::to('/')}}/purchase/print-pdf/{{$purchaseData->id}}?type=pdf" title="PDF" class="btn custom-btn-small btn-outline-secondary">{{ __('PDF') }}</a>
              @if(Helpers::has_permission(Auth::user()->id, 'edit_invoice'))
                <a href='{{URL::to("/")}}/purchase/edit/{{ $purchaseData->id }}{{$menu == "purchase" ? "": "?$sub_menu"}}' title="{{ __('Edit') }}" class="btn custom-btn-small btn-outline-secondary">{{ __('Edit') }}</a>
              @endif
              <a target="_self" href='{{URL::to("/")}}/purchase/copy/{{$purchaseData->id}}{{$menu == "purchase" ? "" : "?$sub_menu"}}' title="Copy" class="btn custom-btn-small btn-outline-secondary">{{ __('Copy Purchase') }}</a>
              @if($purchaseData->total > $purchaseData->paid)
  				      <button title="{{ __('Record Pay')}}" type="button" class="btn custom-btn-small btn-outline-primary" data-toggle="modal" data-target="#payModal" id="modalBtn">{{ __('Record Pay')}}</button>
              @endif
              @if(Helpers::has_permission(Auth::user()->id, 'delete_purchase'))
                <form method="POST" action='{{url("purchase/delete/$purchaseData->id")}}' accept-charset="UTF-8" class="display_inline" id="delete-purchase">
                  {{csrf_field()}}
                  <input type="hidden" name="menu" value="{{ $menu }}">
                  <input type="hidden" name="sub_menu" value="{{ $sub_menu }}">
                  <input type="hidden" name="supplier" value="{{ $purchaseData->supplier_id }}">
                  <input type="hidden" name="user" value="{{ $purchaseData->user_id }}">
                  <button title="{{ __('Delete') }}" class="btn custom-btn-small btn-outline-danger" type="button" data-toggle="modal" data-id="{{ $purchaseData->id }}" data-target="#theModal" data-label="Delete" data-title="{{ __('Delete Purchase') }}" data-message="{{ __('Are you sure to delete this purchase? This will delete all related transaction data.') }}">{{ __('Delete') }}</button>
                </form>
              @endif
            </div>
          </div>
          <div class="card-body">
            <div class="m-t-10">
              <div class="row m-t-10 m-l-15">
                <div class="col-md-4 m-b-15">
                  <strong class="text-black">{{ $company_name }}</strong><br>
                  <strong>{{ $company_street }}</strong><br>
                  <strong>{{ $company_city }}, {{ $company_state }}</strong><br>
                  <strong>{{ $company_country_name }}, {{ $company_zipCode }}</strong><br>
                </div>
                <div class="col-md-4 m-b-15">
                  <strong class="text-black">{{ isset($purchaseData->supplier->name) ? $purchaseData->supplier->name : ''}}</strong><br>
                  <strong>{{ isset($purchaseData->supplier->street) ? $purchaseData->supplier->street : ''}}</strong><br>
                  <strong>{{ isset($purchaseData->supplier->city) ? $purchaseData->supplier->city : ''}}{{ isset($purchaseData->supplier->state) ? ', '.$purchaseData->supplier->state : ''}}</strong><br>
                  <strong>{{ isset($purchaseData->supplier->country->name) ? $purchaseData->supplier->country->name : '' }}{{ isset($purchaseData->supplier->zipcode) ? ', '.$purchaseData->supplier->zipcode : '' }}</strong><br>
                </div>
                <div class="col-md-4 m-b-15">
                  <strong>{{ __('Purchase No') .' # '.$purchaseData->reference }}</strong><br>
                  <strong>{{ __('Location')}} : {{ isset($purchaseData->location->name) ? $purchaseData->location->name : '' }}</strong><br>
                  <strong>{{ __('Date')}} : {{ formatDate($purchaseData->order_date) }}</strong><br>
                </div>
              </div>
              <div class="row mt-0">
                <div class="col-md-12">
                  <div class="table-responsive">
                    <table class="table table-bordered" id="salesInvoice">
                      <tbody>
                        <tr class="tbl_header_color dynamicRows">
                          @if($purchaseData->invoice_type == 'hours')
                            <th>{{ __('Service') }}</th>
                          @else
                            <th>{{ __('Items') }}</th>
                          @endif
                          @if($purchaseData->has_hsn)
                            <th width="5%" class="text-center">{{ __('HSN') }}</th>
                          @endif
                          @if($purchaseData->invoice_type == 'quantity')
                            <th width="5%" class="text-center"> {{ __('Quantity') }} </th>
                            <th width="8%" class="text-center">{{ __('Price') }}({{ isset($purchaseData->currency->symbol) ? $purchaseData->currency->symbol : '' }})</th>
                          @elseif($purchaseData->invoice_type == 'hours')
                            <th width="5%" class="text-center"> {{ __('Hours') }} </th>
                            <th width="8%" class="text-center">{{ __('Rate') }}</th>
                          @endif
                          @if($purchaseData->has_item_discount)
                            <th class="text-center" width="5%">{{ __('Discount') }}</th>
                          @endif
                          @if($purchaseData->has_tax)
                            <th width="10%" class="text-center">{{ __('Tax') }} (%) </th>
                          @endif
                          <th width="10%" class="text-center"> {{ __('Total') }} ({{ isset($purchaseData->currency->symbol) ? $purchaseData->currency->symbol : '' }})
                          </th>
                        </tr>
                        @php
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
                        @if( count( $purchaseData->purchaseOrderDetails ) > 0 )
                          @php $subTotal = $totalDiscount = 0; @endphp
                          @foreach( $purchaseData->purchaseOrderDetails as $result )
                            @php
                              $priceAmount = ($result->quantity_ordered * $result->unit_price);
                              $subTotal += $priceAmount;
                            @endphp
                            @if($result->quantity_ordered > 0)
  	                          <tr class="">
  	                            <td class="white-space-unset">
  	                              <span class="break-all f-16">{{$result->item_name}}</span><br/>
  	                              @if($purchaseData->has_description && $result->description)
  	                                <span class="break-all f-13">{{$result->description}}</span>
  	                              @endif
  	                            </td>
  	                            @if($purchaseData->has_hsn)
  	                              <td class="white-space-unset text-center">{{$result->hsn}}</td>
  	                            @endif
  	                            @if($purchaseData->invoice_type!='amount')
  	                              <td class="white-space-unset text-center">{{ formatCurrencyAmount($result->quantity_ordered)}}</td>
  	                            @endif
  	                            <td class="white-space-unset text-center">{{formatCurrencyAmount($result->unit_price) }}</td>
  	                            @if($purchaseData->has_item_discount)
  	                              <td class="white-space-unset text-center">{{formatCurrencyAmount($result->discount)}}{{$result->discount_type}}</td>
  	                            @endif

  	                            @if($purchaseData->has_tax)
  	                              <td class="white-space-unset text-center">
  	                                @foreach( json_decode( $result->taxList ) as $counter => $tax )
  	                                  {{ formatCurrencyAmount($tax->rate) }}%
                                      @if( $counter < count( json_decode( $result->taxList ) ) - 1 )
                                      ,
                                      @endif
  	                                @endforeach
  	                              </td>
  	                            @endif
  	                            <td align="right" class="white-space-unset">{{ formatCurrencyAmount($priceAmount) }}</td>
  	                          </tr>
                            @endif
                        	@endforeach
  	                    <tr class="tableInfos bt-2-gray">
  	                        <td colspan="{{$row}}" align="right">{{ __('Subtotal') }}</td>
  	                        <td align="right" colspan="1">{{ formatCurrencyAmount($subTotal, isset($purchaseData->currency->symbol) ? $purchaseData->currency->symbol : '') }}</td>
  	                    </tr>
  	                    @if($purchaseData->has_item_discount)
  	                        <tr class="tableInfos">
  	                          <td colspan="{{$row}}" align="right">{{ __('Discount') }}</td>
  	                          <td align="right" colspan="1">{{ formatCurrencyAmount($purchaseData->purchaseOrderDetails->sum('discount_amount'), isset($purchaseData->currency->symbol) ? $purchaseData->currency->symbol : '') }}</td>
  	                        </tr>
  	                    @endif
  	                    @forelse($taxes as $tax)
  	                        <tr>
  	                            <td colspan="{{$row}}" align="right">{{ $tax['name'] }} : {{ formatCurrencyAmount($tax['rate']) }}% </td>
  	                            <td colspan="1" class="text-right">{{ formatCurrencyAmount($tax['amount'], isset($purchaseData->currency->symbol) ? $purchaseData->currency->symbol : '') }}</td>
  	                        </tr>
  	                    @empty
  	                    @endforelse
  	                      @if($purchaseData->has_other_discount == 1 && $purchaseData->other_discount_amount > 0)
  		                    <tr class="tableInfos">
  		                        @php
                              if ($purchaseData->has_item_discount == 1) {
  		                          if($purchaseData->other_discount_type=="$"){
  		                            $otherDiscount = $purchaseData->other_discount_amount;
  		                          } else {
  		                            $otherDiscount = ($subTotal - $purchaseData->purchaseOrderDetails->sum('discount_amount')) * $purchaseData->other_discount_amount / 100;
  		                          }
                              } else {
                                if($purchaseData->other_discount_type=="$"){
                                  $otherDiscount = $purchaseData->other_discount_amount;
                                } else {
                                  $otherDiscount = $subTotal * $purchaseData->other_discount_amount / 100;
                                }
                              }
  		                        @endphp
  		                        <td colspan="<?= $row ?>"
  		                        align="right">{{ __('Other Discount') }} : {{ formatCurrencyAmount($purchaseData->other_discount_amount, $purchaseData->other_discount_type != "%" && isset($purchaseData->currency->symbol) ? $purchaseData->currency->symbol : '%') }} </td>
  		                        <td align="right">{{ formatCurrencyAmount($otherDiscount, isset($purchaseData->currency->symbol) ? $purchaseData->currency->symbol : '') }} </td>
  		                    </tr>
  	                      @endif
  	                      @if($purchaseData->has_shipping_charge && $purchaseData->shipping_charge > 0)
  	                        <tr class="tableInfos">
  	                          <td colspan="{{$row}}"
  	                          align="right">{{ __('Shipping') }}</td>
  	                          <td align="right" colspan="1">{{ formatCurrencyAmount($purchaseData->shipping_charge, isset($purchaseData->currency->symbol) ? $purchaseData->currency->symbol : '') }}</td>
  	                        </tr>
  	                      @endif
  	                      @if($purchaseData->has_custom_charge && $purchaseData->custom_charge_amount > 0)
  	                        <tr class="tableInfos">
  	                          <td colspan="<?= $row ?>"
  	                          align="right">{{ $purchaseData->custom_charge_title }}</td>
  	                          <td align="right" colspan="1">{{ formatCurrencyAmount($purchaseData->custom_charge_amount, isset($purchaseData->currency->symbol) ? $purchaseData->currency->symbol : '') }}</td>
  	                        </tr>
  	                      @endif
  	                      <tr class="tableInfos">
  	                        <td colspan="{{$row}}" align="right">
  	                        <strong>{{ __('Grand Total') }}</strong></td>
  	                        <td colspan="1" class="text-right">
  	                          <strong>{{ formatCurrencyAmount($purchaseData->total, isset($purchaseData->currency->symbol) ? $purchaseData->currency->symbol : '') }}</strong>
  	                        </td>
  	                      </tr>
  	                      <tr class="tableInfos">
  	                        <td colspan="{{$row}}"
  	                        align="right">{{ __('Paid') }}</td>
  	                        <td colspan="1"
  	                        class="text-right"> {{ formatCurrencyAmount($purchaseData->paid, isset($purchaseData->currency->symbol) ? $purchaseData->currency->symbol : '') }}</td>
  	                      </tr>
  	                      <tr class="tableInfos">
  	                        <td colspan="{{$row}}" align="right">
  	                        <strong>{{ __('Due') }}</strong></td>
  	                        <td colspan="1" class="text-right"><strong>
  	                          @if(($purchaseData->total - $purchaseData->paid) < 0)
                                -{{ formatCurrencyAmount(abs($purchaseData->total - $purchaseData->paid), isset($purchaseData->currency->symbol) ? $purchaseData->currency->symbol : '') }}
  	                          @else
                                {{ formatCurrencyAmount(abs($purchaseData->total - $purchaseData->paid), isset($purchaseData->currency->symbol) ? $purchaseData->currency->symbol : '') }}
  	                          @endif
  	                        </strong></td>
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
            @if(isset($purchasePaymentsList) && count($purchasePaymentsList) > 0)
  		        <table class="table table-bordered">
  		          <thead>
  		            <tr>
  		              <th>{{ __('Payment No') }}</th>
  		              <th>{{ __('Payment Term') }}</th>
  	                <th>{{ __('Date') }}</th>
  		              <th class="text-right">{{ __('Amount') }}</th>
  		            </tr>
  		          </thead>
  		          <tbody>
  		            @php $sumInvoice = 0; @endphp
  		            @foreach($purchasePaymentsList as $payment)
  		              <tr>
  		                <td>
  		                  <a href='{{ url("purchase_payment/view_receipt/$payment->id") }}'>
  		                    <i class="fa fa-chevron-right" aria-hidden="true"></i>&nbsp;{{sprintf("%04d", $payment->id)}}
  		                  </a>
  		                </td>
  		                <td>{{ isset($payment->paymentMethod) ? $payment->paymentMethod->name : __('N/A')}}</td>
                      <td>{{formatDate($payment->created_at,'Y-m-d')}}</td>
                      <td align="right">{{ formatCurrencyAmount($payment->amount, isset($payment->currency->symbol) ? $payment->currency->symbol : '')}}</td>
  		              </tr>
  		              @php $sumInvoice += $payment->amount; @endphp
  		            @endforeach
  		            <td colspan="3" align="right">
  		            	<strong> {{__('Total') }} </strong>
  		            </td>
  		            <td align="right">
  		              <strong>{{ formatCurrencyAmount($sumInvoice, isset($payment->currency->symbol) ? $payment->currency->symbol : '')}}</strong>
  		            </td>
  		          </tbody>
  		        </table>
  		      @else
  		        <h5 class="text-center">{{ __('No payment found.') }}</h5>
  		      @endif
          </div>
        </div>
      </div>

      @if ( count ($purchaseData->receivedOrderDetails) > 0)
      	<div class="col-sm-12">
        	<div class="card">
        		<div class="card-header">
        			<h5>{{ __('Purchase Receive') }}</h5>
        		</div>
          	<div class="card-body">
  		      	<table class="table table-bordered">
  		          <thead>
  		            <tr>
  		              <th>{{ __('Receive No') }}</th>
                    <th>{{ __('Date') }}</th>
  		              <th>{{ __('Qty') }}</th>
  		            </tr>
  		          </thead>
  		          <tbody>
  		            @foreach($purchaseData->receivedOrders as $receive)
  		              <tr>
  		                <td>
  		                  <a href='{{ url("purchase_receive/details/$receive->id") }}'>
  		                    <i class="fa fa-chevron-right" aria-hidden="true"></i>&nbsp;{{sprintf("%04d", $receive->id)}}
  		                  </a>
  		                </td>
                      <td>{{formatDate($receive->receive_date,'Y-m-d')}}</td>
                      <td>{{ formatCurrencyAmount($receive->receivedOrderDetails->sum('quantity')) }}</td>
  		              </tr>
  		            @endforeach
  		          </tbody>
  		        </table>
          	</div>
        	</div>
      	</div>
      @endif

      @if ($purchaseData->purchaseOrderDetails->sum('quantity_ordered') > $purchaseData->purchaseOrderDetails->sum('quantity_received') )
  		<div class="col-sm-12">
  	      <div class="card">
  	      	<div class="card-header">
  	      		<h5>{{ __('Receive information') }}</h5>
  	      	</div>
  	      	<div class="card-body">
  	      		<div class="row">
  	      			<div class="col-md-6 col-xl-4 col-12  p-r-5">
  			          <a href="{{URL::to('/')}}/purchase/receive/manual/{{$purchaseData->id}}"
  			            title="{{ __('Manually receive purchase item') }}"
  			          class="btn btn-primary btn-flat btn-block ">{{ __('Receive Manually') }}</a>
  			        </div>
  			        <div class="col-md-6 col-xl-4 col-12 btn-block-right-padding">
  			          <a href="{{URL::to('/')}}/purchase/receive/all/{{$purchaseData->id}}"
  			            title="{{ __('Receive all purchase item') }}"
  			          class="btn btn-secondary btn-flat btn-block">{{ __('Receive All') }}</a>
  			        </div>
  	      		</div>
  	      	</div>
  	      </div>
  	    </div>
  	@endif

      @if(isset($files) && count($files) > 0)
  	    <div class="{{ !empty($purchaseData->comments) ? 'col-sm-7' : 'col-sm-12' }}">
  	      <div class="card">
  	      	<div class="card-header">
  	      		<h5>{{  __('Files')  }}</h5>
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
                                  <iframe width="100%" height="100%" src="//docs.google.com/gview?url='. url($filePath) .'/'. $file->file .'&embedded=true" frameborder="0" allowfullscreen></iframe>
                                  <div class="clear_both"></div>
                                </div>
                              </div>';
                    }
                  @endphp
                  <a <?= $extra ?> href="{{ $url }}" data-attachment="<?= $file->id; ?>" class="html5lightbox" title="{{ $fileName }}" data-group="{{ $purchaseData->reference }}">
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

  	  @if(isset($purchaseData->comments) && !empty($purchaseData->comments))
  		  <div class="{{ count($files) > 0 ? 'col-sm-5' : 'col-sm-12' }}">
  	      <div class="card">
  	        <div class="card-header">
  	          <h5>{{ __('Note') }}</h5>
  	        </div>
  	        <div class="card-body">
  	          @if($purchaseData->comments)
  	            <div class="row">
  	              <div class="col-md-12">
  	                <div class="form-group">
  	                  <div class="text-justify">
  	                    {{$purchaseData->comments}}
  	                  </div>
  	                </div>
  	              </div>
  	            </div>
  	          @else
  	            <div class="text-justify">
  	              {{ __('No comment for this invoice') }}
  	            </div>
  	          @endif
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
          <h4 class="modal-title">{{  __('New Payment')  }}</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body mx-5">
  	      <form class="form-horizontal" id="payForm" action="{{url('payment/save')}}" method="POST" enctype="multipart/form-data">
            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
            <input type="hidden" name="supplier_id" value="{{$purchaseData->supplier_id}}">
            <input type="hidden" name="order_no" value="{{$purchaseData->id}}">
            <input type="hidden" name="payment_type" value="purchase">
            <input type="hidden" name="invoice_currency_id" value="{{ $purchaseData->currency_id }}" id="pay_currency">
            <input type="hidden" name="set_account_balance" id="set_account_balance">
            <div class="form-group row">
              <label for="payment_type_id" class="col-sm-4 control-label">{{  __('Payment Method')  }} </label>
              <div class="col-sm-6">
                <select class="form-control js-example-basic w-100" name="payment_type_id" id="payment_method">
                  <option value="">{{ __('Select One')  }}</option>
                  @foreach($paymentMethods as $payment)
                    <option value="{{ $payment['id'] }}" data-methodName="{{ $payment['name'] }}">{{ $payment['name'] }}</option>
                  @endforeach
                </select>
                <input type="hidden" name="setPaymentMethodName" id="setPaymentMethodName">
              </div>
            </div>
            <div class="form-group row display_none" id="account_div">
              <label class="col-sm-4 col-form-label">{{  __('Account')  }}</label>
              <div class="col-sm-6">
                <select class="form-control js-example-basic w-100" name="account_no" id="account_no">
                  <option value="">{{ __('Select One')  }}</option>
                  @foreach($accounts as $data)
                    <option value="{{$data->id}}" data-currency="{{ $data->currency_id }}" currency-symbol="{{ isset($data->currency->symbol) ? $data->currency->symbol : '' }}" data-code="{{ isset($data->currency->name) ? $data->currency->name : '' }}"> {{ $data->name }}({{ isset($data->currency->name) ? $data->currency->name : '' }})</option>
                  @endforeach
                </select>
                <div class="message"></div>
              </div>
              <label id="account_no-error" class="error col-sm-6" for="account_no"></label>
            </div>
            <div class="form-group row mb-0 display_none" id="account_balance_div">
              <div class="col-sm-4"></div>
              <div class="col-sm-6">
                <label class="text-success" id="account_balance"></label>
              </div>
            </div>

            <div class="form-group row" id="currency_div">
              <label for="currency" class="col-sm-4 control-label require">{{  __('Currency')  }}  </label>
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
                  <input type="text" placeholder="Amount" class="form-control positive-float-number" id="amount" name="amount" value="{{ formatCurrencyAmount($purchaseData->total-$purchaseData->paid) }}">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="amount_currency_code">{{ isset($purchaseData->currency->name) ? $purchaseData->currency->name : '' }}</span>
                    <input type="hidden" id="currency-symbol" value="{{ $purchaseData->currency->name }}">
                  </div>
                </div>
                <div id="amount_message"></div>
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
              <label for="incoming_amount" class="col-sm-4 control-label require">{{  __('Equivalent Amount')  }}  </label>
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
              <label id="incoming_amount-error" class="error col-sm-6 display_none" for="incoming_amount"></label>
            </div>

            <div class="form-group row">
              <label for="payment_date" class="col-sm-4 control-label require">{{ __('Date')}}</label>
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
              <label for="reference" class="col-sm-4 control-label require">{{  __('Description')  }} </label>
              <div class="col-sm-6">
                <input type="text" name="description" class="form-control" id="description" placeholder="{{  __('Description')  }}" value="Payment for {{ $purchaseData->reference }} " readonly>
                <input type="hidden" name="reference_no" value="{{ $purchaseData->reference }}">
              </div>
            </div>

            <div class="form-group row">
              <label for="reference" class="col-sm-4 control-label">{{ __('Reference') }} </label>
              <div class="col-sm-6">
                 <input type="text" name="reference" class="form-control" id="reference" value="{{ $reference }}"  placeholder="{{ __('Reference') }}" readonly>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-4 control-label" for="attachment">{{  __('Attachment')  }}</label>
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

  <!--Modal start-->
  <div id="emailPurchase" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <form id="sendOrderInfo" method="POST" action="{{url('purchase/email-puchase-details')}}">
        @csrf
        <input type="hidden" value="{{$purchaseData->id}}" name="order_id" id="order_id">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">{{  __('Send purchase information to client') }}</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="email">{{ __('Send To') }}:</label>
              <input type="email" value="{{  isset($purchaseData->supplier->email) ? $purchaseData->supplier->email : '' }}" class="form-control" name="email" id="email">
            </div>
            <?php
            $subjectInfo = str_replace('{invoice_reference_no}', $purchaseData->reference, $emailInfo->subject);
            $subjectInfo = str_replace('{company_name}', $company_name, $subjectInfo);
            ?>
            <div class="form-group">
              <label for="subject">{{ __('Subject') }}:</label>
              <input type="text" class="form-control" name="subject" id="subject" value="{{$subjectInfo}}">
            </div>
            <div class="form-groupa">
              <?php
              $bodyInfo = str_replace('{supplier_name}', !empty($purchaseData->supplier->name) ? $purchaseData->supplier->name : '', $emailInfo->body);
              $bodyInfo = str_replace('{invoice_reference_no}', $purchaseData->reference, $bodyInfo);
              $bodyInfo = str_replace('{billing_street}', isset($purchaseData->supplier->address) ? $purchaseData->supplier->address : '' , $bodyInfo);
              $bodyInfo = str_replace('{billing_city}', isset($purchaseData->supplier->city) ? $purchaseData->supplier->city : '', $bodyInfo);
              $bodyInfo = str_replace('{billing_state}', isset($purchaseData->supplier->state) ? $purchaseData->supplier->state : '', $bodyInfo);
              $bodyInfo = str_replace('{billing_zip_code}', isset($purchaseData->supplier->zipcode) ? $purchaseData->supplier->zipcode : '', $bodyInfo);
              $bodyInfo = str_replace('{billing_country}', isset($purchaseData->supplier->country->name) ? $purchaseData->supplier->country->name : '', $bodyInfo);
              $bodyInfo = str_replace('{company_name}', $company_name, $bodyInfo);
              $bodyInfo = str_replace('{invoice_summery}', $itemSummery, $bodyInfo);
              $bodyInfo = str_replace('{currency}',  isset($purchaseData->currency->symbol) ? $purchaseData->currency->symbol : '', $bodyInfo);
              $bodyInfo = str_replace('{total_amount}', formatCurrencyAmount($purchaseData->total), $bodyInfo);
              $bodyInfo = str_replace('{due_date}', formatDate($purchaseData->order_date), $bodyInfo);
              ?>
              <textarea id="compose-textarea" name="message" id='message' class="form-control editor h-200">{{$bodyInfo}}</textarea>
            </div>
            <div class="form-group">
              <div class="checkbox">
                <label><input type="checkbox" name="purchase_pdf" checked><strong>{{$purchaseData->reference}}</strong></label>
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
  <!--Modal end -->
</div>
@endsection

@section('js')

<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/ckeditor/js/ckeditor.js') }}"></script>
<script src="{{url('public/dist/plugins/dropzone/dropzone.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('public/dist/js/html5lightbox/html5lightbox.js?v=1.0') }}"></script>
{!! translateValidationMessages() !!}
<script type="text/javascript">
  'use strict';
  var dflt_currency_id = {!! $purchaseData->currency_id !!};
  var invoiceCurrency = {!! $purchaseData->currency->id !!};
  var invoiceCurrencyName = '{!! $purchaseData->currency->name !!}';
  var due = '{!! $purchaseData->total-$purchaseData->paid !!}';
  var exchange_rate_decimal_digits = "{{ $exchange_rate_decimal_digits }}";
  var currencySymbol = '{!! $currency_symbol !!}';
  var amountBill = 0;
  var exhangeRateBill = 0;
</script>
<script src="{{ asset('public/dist/js/custom/sales-purchase.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/purchases.min.js') }}"></script>
@endsection
