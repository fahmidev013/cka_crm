@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('public/dist/css/invoice-style.min.css') }}">
@endsection

@section('content')
<div class="col-sm-12" id="card-with-header-button">
  <div class="card">
    <div class="card-header">
      <h5><a href="{{ url('order/list') }}">{{ __('Quotation') }}</a> >> #{{ $saleOrderData->reference }}</h5>
      <div class="card-header-right">
        @if(Helpers::has_permission(Auth::user()->id, 'add_quotation'))
          <a href="{{ url('order/add') }}{{ isset($url_extension) ? '?' . $url_extension : ''}}" class="btn btn-outline-primary custom-btn-small"><span class="feather icon-plus"> &nbsp;</span>{{ __('New Quotation') }}</a>
        @endif
      </div>
    </div>
  </div>
</div>
<div class="col-sm-12" id="sales-quotation-view-details-container">
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <div class="btn-group float-right row mr-2 mt-1">
            @if(! empty($smsInformation))
            <button title="SMS" type="button" class="btn custom-btn-small btn-outline-secondary" data-toggle="modal" data-target="#smsOrder">{{ __('SMS') }}</button>
            @endif

            @if(! empty($emailInfo))
            <button title="Email" type="button" class="btn custom-btn-small btn-outline-secondary" data-toggle="modal" data-target="#emailOrder">{{ __('Email') }}</button>
            @endif

            <a target="_blank" href="{{ URL::to('/') }}/order/print-pdf/{{ $saleOrderData->id }}?type=print" title="Print" class="btn custom-btn-small btn-outline-secondary">{{ __('Print') }}</a>
            <a target="_blank" href="{{ URL::to('/') }}/order/print-pdf/{{ $saleOrderData->id }}?type=pdf" title="PDF" class="btn custom-btn-small btn-outline-secondary">{{ __('PDF') }}</a>
            @if(Helpers::has_permission(Auth::user()->id, 'edit_quotation'))
              <a href='{{ URL::to("/") }}/order/edit/{{ $saleOrderData->id }}{{ $menu == "sales" ? "" : "?" . $sub_menu }}' title="Edit" class="btn custom-btn-small btn-outline-secondary">{{ __('Edit') }}</a>
            @endif
            <a target="_self" href='{{ URL::to("/") }}/order/copy/{{ $saleOrderData->id }}{{ $menu == "sales" ? "" : "?" . $sub_menu }}'title="Copy" class="btn custom-btn-small btn-outline-secondary">{{ __('Copy Quotation') }}</a>
            @if(Helpers::has_permission(Auth::user()->id, 'delete_quotation'))
              <form method="POST" action='{{ url("order/delete/" . $saleOrderData->id) }}' accept-charset="UTF-8" class="display_inline" id="delete-quotation">
                {{csrf_field()}}
                <input type="hidden" name="menu" value="{{ $menu }}">
                <input type="hidden" name="sub_menu" value="{{ $sub_menu }}">
                @if($sub_menu == 'customer')
                  <input type="hidden" name="customer" value="{{ $saleOrderData->customer_id }}">
                @endif
                <button title="{{ __('Delete') }}" class="btn custom-btn-small btn-outline-danger" type="button" data-toggle="modal" data-id="{{ $saleOrderData->id }}" data-target="#theModal" data-label="Delete" data-title="{{ __('Delete Quotation') }}" data-message="{{ __('Are you sure to delete this quotation? This will delete quotation related all information.') }}">{{ __('Delete') }}</button>
              </form>
            @endif
          </div>
        </div>
        <div class="card-body">
          <div class="m-t-10">
            <div class="row m-t-10 ml-2">
              <div class="col-md-4 m-b-15">
                <strong class="text-black">{{ $company_name }}</strong><br>
                <strong>{{ $company_street }}</strong><br>
                <strong>{{ $company_city }}, {{ $company_state }}</strong><br>
                <strong>{{ $company_country_name }}, {{ $company_zipCode }}</strong><br>
              </div>
              <div class="col-md-4 m-b-15">
                <strong class="text-black">{{ __('Bill To') }}</strong><br>
                <strong class="text-black">{{ isset($saleOrderData->customer->first_name) ? $saleOrderData->customer->first_name : '' }} {{ isset($saleOrderData->customer->last_name) ? $saleOrderData->customer->last_name : '' }}</strong><br>
                <strong>{{ isset($saleOrderData->customerBranch->billing_street) ? $saleOrderData->customerBranch->billing_street : '' }} </strong><br>
                <strong>{{ isset($saleOrderData->customerBranch->billing_state) ? $saleOrderData->customerBranch->billing_state : '' }}{{ isset($saleOrderData->customerBranch->billing_city) ? ', ' . $saleOrderData->customerBranch->billing_city : '' }}</strong><br>
                  <strong>{{ isset($saleOrderData->customerBranch->billingCountry) ? $saleOrderData->customerBranch->billingCountry->name : '' }} {{ isset($saleOrderData->customerBranch->billing_zip_code) ? ', ' . $saleOrderData->customerBranch->billing_zip_code : '' }}</strong><br>
              </div>
              <div class="col-md-4 m-b-15">
                <strong>{{ __('Quotation Date') }} : {{ formatDate($saleOrderData->order_date) }}</strong><br>
                <strong>{{ __('Location') }} : {{ isset($saleOrderData->location->name) ? $saleOrderData->location->name : '' }}</strong><br class="mb-1">
                @if ( isset($invoiced_status) and $invoiced_status == 'no' )
                  <strong class="text-black"> {{ __('To convert in invoice') }} <a class="text-white label theme-bg f-12 customer-quotation" href="{{ URL::to('/') }}/order/auto-invoice-create/{{ $saleOrderData->id }}"> {{ __('Click Here') }} </a></strong>
                @else
                  @if(isset($ref_invoice))
                    <strong>{{ __('Quotation invoice on') }} (<a href="{{ URL::to('/') }}/invoice/view-detail-invoice/{{ $order_no }}" class="text-success">{{ $ref_invoice }}</a>)  {{ __('Accounting on') }}  {{ formatDate($invoiced_date) }} </strong>
                  @else
                    {{ __('Quotation invoice on') }} {{ __('Accounting on') }} {{ formatDate($invoiced_date) }}<br>
                  @endif
                @endif
              </div>
            </div>
            <div class="row mt-0">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordered" id="salesInvoice">
                    <tbody>
                      <tr class="tbl_header_color dynamicRows">
                        <th>{{ __('Items') }}</th>
                        @if($saleOrderData->has_hsn)
                          <th width="5%" class="text-center">{{ __('HSN') }}</th>
                        @endif
                        @if($saleOrderData->invoice_type=='hours')
                          <th width="5%" class="text-center">{{ __('Hrs') }}</th>
                          <th width="8%" class="text-center">{{ __('Rate') }}</th>
                        @else
                          <th width="5%" class="text-center">{{ __('Qnt') }}</th>
                          <th width="8%" class="text-center">{{ __('Price') }}({{ isset($saleOrderData->currency->symbol) ? $saleOrderData->currency->symbol : ''}})</th>
                        @endif
                        @if($saleOrderData->has_item_discount)
                          <th class="text-center" width="5%">{{ __('Discount') }}</th>
                        @endif
                        @if($saleOrderData->has_tax)
                          <th width="10%" class="text-center">{{ __('Tax') }} (%) </th>
                        @endif
                        <th width="10%" class="text-center" id="order-total">
                          Total ({{ isset($saleOrderData->currency->symbol) ? $saleOrderData->currency->symbol : '' }})
                        </th>
                      </tr>
                      @php
                        $itemsInformation = '';
                        $row = 6;
                        $currentTaxArray = [];
                        if ($saleOrderData->invoice_type == 'amount') {
                          $row = $row - 2;
                        }
                        if (!$saleOrderData->has_item_discount) {
                          $row = $row - 1;
                        }
                        if (!$saleOrderData->has_hsn) {
                          $row = $row - 1;
                        }
                        if (!$saleOrderData->has_tax) {
                          $row = $row - 1;
                        }
                      @endphp
                      @if ( count($saleOrderData->saleOrderDetails) > 0 )
                        @php $subTotal = $totalDiscount = 0; @endphp
                        @foreach ($saleOrderData->saleOrderDetails as $result)
                          @php
                            $priceAmount = ($result->quantity * $result->unit_price);
                            $subTotal += $priceAmount;
                          @endphp
                          @if ($result->quantity > 0 )
                            <tr>
                              <td class="white-space-unset">
                                <span class="break-all f-16">
                                  {{$result['item_name']}}
                                </span> <br/>
                                @if($saleOrderData->has_description && $result->description)
                                  <span class="break-all f-13">
                                    {{ $result->description }}
                                  </span>
                                @endif
                              </td>
                              @if( $saleOrderData->has_hsn )
                                <td width="5%" class="white-space-unset text-center"> {{ $result->hsn }}</td>
                              @endif
                              @if( $saleOrderData->invoice_type != 'amount' )
                                <td width="5%" class="white-space-unset text-center">{{ formatCurrencyAmount($result->quantity) }} </td>
                              @endif
                              <td width="8%" class="white-space-unset text-center">{{ formatCurrencyAmount($result->unit_price) }}</td>
                              @if( $saleOrderData->has_item_discount )
                                <td width="5%" class="white-space-unset text-center">{{ formatCurrencyAmount($result->discount) }}{{ $result->discount_type }}</td>
                              @endif
                              @if( $saleOrderData->has_tax )
                                <td width="10%" class="white-space-unset text-center">
                                  @forelse(json_decode($result->taxList) as $counter => $tax)
                                    {{ formatCurrencyAmount($tax->rate) }}%
                                  @empty
                                  @endforelse
                                </td>
                              @endif
                              <td width="10%" align="right" class="white-space-unset">{{ formatCurrencyAmount($priceAmount) }}</td>
                            </tr>
                          @endif
                        @endforeach
                        <tr class="tableInfos">
                          <td colspan="{{ $row }}" align="right">{{ __('Sub Total') }}</td>
                          <td align="right" colspan="1">{{ formatCurrencyAmount($subTotal, isset($saleOrderData->currency->symbol) ? $saleOrderData->currency->symbol : '') }}</td>
                        </tr>
                        @if($saleOrderData->has_item_discount)
                          <tr class="tableInfos">
                            <td colspan="{{ $row }}" align="right">{{ __('Discount') }}</td>
                            <td align="right" colspan="1">{{ formatCurrencyAmount( $saleOrderData->saleOrderDetails->sum('discount_amount'), isset($saleOrderData->currency->symbol) ? $saleOrderData->currency->symbol : '') }}</td>
                          </tr>
                        @endif

                        @forelse($taxes as $tax)
                          <tr>
                              <td colspan="{{ $row }}" align="right">{{$saleOrderData->tax_type == 'inclusive' ? 'Including ' : ''}}  {{ $tax['name'] }} : {{ formatCurrencyAmount($tax['rate']) }}% </td>
                              <td colspan="1" class="text-right">{{ formatCurrencyAmount(($tax['amount']), isset($saleOrderData->currency->symbol) ? $saleOrderData->currency->symbol : '') }}</td>
                          </tr>
                        @empty
                        @endforelse

                        @if($saleOrderData->has_other_discount)
                          <tr class="tableInfos">
                            @php
                              if ($saleOrderData->other_discount_type == "$") {
                                $otherDiscount = $saleOrderData->other_discount_amount;
                              } else {
                                $otherDiscount = $subTotal * $saleOrderData->other_discount_amount / 100;
                              }
                            @endphp
                            <td colspan="{{ $row }}" align="right">
                              {{ __('Other Discount') }} : {{ formatCurrencyAmount($saleOrderData->other_discount_amount) }}{{ $saleOrderData->other_discount_type == '$' && isset($saleOrderData->currency->symbol) ? $saleOrderData->currency->symbol : '%' }}
                            </td>
                            <td align="right">{{ formatCurrencyAmount($otherDiscount, isset($saleOrderData->currency->symbol) ? $saleOrderData->currency->symbol : '') }}</td>
                          </tr>
                        @endif

                        @if($saleOrderData->has_shipping_charge && $saleOrderData->shipping_charge)
                          <tr class="tableInfos">
                            <td colspan="{{ $row }}" align="right">{{ __('Shipping') }}</td>
                            <td align="right" colspan="1">{{ formatCurrencyAmount($saleOrderData->shipping_charge, isset($saleOrderData->currency->symbol) ? $saleOrderData->currency->symbol : '') }}</td>
                          </tr>
                        @endif

                        @if($saleOrderData->has_custom_charge)
                          <tr class="tableInfos">
                            <td colspan="{{ $row }}" align="right">{{ $saleOrderData->custom_charge_title }}</td>
                            <td align="right" colspan="1">{{ formatCurrencyAmount($saleOrderData->custom_charge_amount, isset($saleOrderData->currency->symbol) ? $saleOrderData->currency->symbol : '') }}</td>
                          </tr>
                        @endif

                        <tr class="tableInfos">
                          <td colspan="{{ $row }}" align="right">
                            <strong>{{ __('Grand Total') }}</strong></td>
                          <td class="text-right">
                            <strong>{{ formatCurrencyAmount($saleOrderData->total, isset($saleOrderData->currency->symbol) ? $saleOrderData->currency->symbol : '') }}</strong>
                          </td>
                        </tr>

                        <tr class="tableInfos">
                          <td colspan="{{ $row }}" align="right">{{ __('Paid') }}</td>
                          <td class="text-right">
                            {{ formatCurrencyAmount($saleOrderData->paid, isset($saleOrderData->currency->symbol) ? $saleOrderData->currency->symbol : '') }}
                          </td>
                        </tr>
                        
                        <tr class="tableInfos">
                          <td colspan="{{ $row }}" align="right">
                            <strong>{{ __('Due') }}</strong>
                          </td>
                          <td class="text-right">
                            <strong>
                              @if($saleOrderData->total < $saleOrderData->paid)
                                -
                              @endif
                              {{ formatCurrencyAmount(abs($saleOrderData->total-$saleOrderData->paid), isset($saleOrderData->currency->symbol) ? $saleOrderData->currency->symbol : '') }}
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
    @if (count($files) > 0)
      <div class="{{ $saleOrderData->comment ? 'col-sm-7' : 'col-sm-12'}}">
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
                @if(file_exists($filePath .'/'. $file->file_name))
                <a <?= $extra ?> href="{{ $url }}" data-attachment="<?= $file->id; ?>" class="html5lightbox" title="{{ $fileName }}" data-group="{{ $saleOrderData->reference }}">
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
                @endif
              @endforeach
            </div>
          </div>
        </div>
      </div>
    @endif
    @if($saleOrderData->has_comment == 1 && $saleOrderData->comment)
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
                    {{ $saleOrderData->comment }}
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

<!--Modal start-->
@if(!empty($emailInfo))
<div id="emailOrder" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <form id="sendOrderInfo" method="POST" action="{{ url('order/email-order-info') }}">
      <input type="hidden" value="{{ csrf_token() }}" name="_token" id="token">
      <input type="hidden" value="{{ $saleOrderData->id }}" name="order_id" id="order_id">
      <input type="hidden" value="{{ $saleOrderData->reference }}" name="reference" id="reference">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('Send quotation information to client') }}</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="email">{{ __('Send To') }}:</label>
            <input type="email" value="{{ isset($saleOrderData->customer->email) ? $saleOrderData->customer->email : '' }}" class="form-control" name="email" id="email">
          </div>
          @php
            $subjectInfo = str_replace('{order_reference_no}', $saleOrderData->reference, !empty ($emailInfo->subject) ? $emailInfo->subject : '');
            $subjectInfo = str_replace('{invoice_reference_no}', $saleOrderData->reference, $subjectInfo);
            $subjectInfo = str_replace('{company_name}', $company_name, $subjectInfo);
          @endphp
          <div class="form-group">
            <label for="subject">{{ __('Subject')}}:</label>
            <input type="text" class="form-control" name="subject" id="subject-email" value="{{ $subjectInfo }}">
          </div>
          <div class="form-group">
            @php
              $firstName = isset($saleOrderData->customer->first_name) ? $saleOrderData->customer->first_name : "";
              $lastName = isset($saleOrderData->customer->last_name) ? $saleOrderData->customer->last_name : "";
              $bodyInfo = str_replace('{customer_name}', $firstName .' '. $lastName, !empty($emailInfo) ? $emailInfo->body : '');
              $bodyInfo = str_replace('{order_reference_no}', $saleOrderData->reference, $bodyInfo);
              $bodyInfo = str_replace('{order_date}',formatDate($saleOrderData->order_date), $bodyInfo);
              $bodyInfo = str_replace('{billing_street}', isset($saleOrderData->customer->customerBranch->billing_street) ? $saleOrderData->customer->customerBranch->billing_street : "", $bodyInfo);
              $bodyInfo = str_replace('{billing_city}', isset($saleOrderData->customer->customerBranch->billing_city) ? $saleOrderData->customer->customerBranch->billing_city : "", $bodyInfo);
              $bodyInfo = str_replace('{billing_state}', isset($saleOrderData->customer->customerBranch->billing_state) ? $saleOrderData->customer->customerBranch->billing_state : "", $bodyInfo);
              $bodyInfo = str_replace('{billing_zip_code}', isset($saleOrderData->customer->customerBranch->billing_zip_code) ? $saleOrderData->customer->customerBranch->billing_zip_code : "", $bodyInfo);
              $bodyInfo = str_replace('{billing_country}', $billingCountry, $bodyInfo);
              $bodyInfo = str_replace('{company_name}', $company_name, $bodyInfo);
              $bodyInfo = str_replace('{currency}',  isset($saleOrderData->currency->symbol) ? $saleOrderData->currency->symbol : '' , $bodyInfo);
              $bodyInfo = str_replace('{total_amount}', $saleOrderData->total, $bodyInfo);
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
                  <input type="checkbox" name="quotation_pdf" id="quotation_pdf" checked="">
                  <label for="quotation_pdf" class="cr"><strong>{{ $saleOrderData->reference }}</strong></label>
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
@endif
@if(!empty($smsInformation))
<div id="smsOrder" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <form id="sendOrderInfoSMS" method="POST" action="{{ url('sales/send-sms') }}">
      <input type="hidden" value="{{ csrf_token() }}" name="_token" id="token">
      <input type="hidden" value="{{ $saleOrderData->id }}" name="order_id" id="order_id">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('Send quotation information to client') }}</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="phoneno">{{ __('Phone') }}:</label>
            <input type="text" value="{{ isset($saleOrderData->customer->phone) ? $saleOrderData->customer->phone : '' }}" class="form-control" name="phoneno" id="phoneno">
          </div>
          <div class="form-group">
            <label for="message">{{ __('Message') }}:</label>
            <textarea id="compose-textarea" name="message" id='message' class="form-control h-200">{{ $smsInformation }}</textarea>
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

<!--Modal end -->
@include('layouts.includes.message_boxes')
@endsection
@section('js')
<script type="text/javascript" src="{{ asset('public/dist/js/html5lightbox/html5lightbox.js?v=1.0') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script type="text/javascript">
'use strict';
var phoneNumber = "{{ isset($saleOrderData->customer->phone) ? $saleOrderData->customer->phone : '' }}";
</script>
<script src="{{ asset('public/dist/js/custom/sales-purchase.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/sales.min.js') }}"></script>
@endsection