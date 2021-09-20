@extends('layouts.customer_panel')
@section('css')
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('public/dist/css/invoice-style.min.css') }}">
@endsection
@section('content')
<div class="col-sm-12" id="card-with-header-button">
  <div class="card mb-0">
    <div class="card-header">
      <h5><a href="{{ url('customer-panel/order') }}">{{ __('Quotation') }}</a> >> #{{ $saleOrderData->reference }}</h5>
    </div>
  </div>
</div>
<div class="col-sm-12">
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header quotation-pdf-header">
          <div class="btn-group float-right form-group row mr-2">
            <a target="_blank" href="{{URL::to('/')}}/customer-panel/order/print-pdf/{{ $saleOrderData->id }}?type=print" title="Print" class="btn custom-btn-small btn-outline-secondary">{{ __('Print')  }}</a>
            <a target="_blank" href="{{URL::to('/')}}/customer-panel/order/print-pdf/{{ $saleOrderData->id }}?type=pdf" title="PDF" class="btn custom-btn-small btn-outline-secondary">{{ __('PDF')  }}</a>
          </div>
        </div>
        <div class="card-body">
          <div class="m-t-5">
            <div class="row m-t-10 m-l-15">
              <div class="col-md-4 m-b-15">
                <strong class="text-black">{{ $company_name }}</strong><br>
                <strong>{{ $company_street }}</strong><br>
                <strong>{{ $company_city }}{{ !empty($company_state) ? ', ' . $company_state : '' }}</strong><br>
                <strong>{{ $company_country_name }}{{ !empty($company_zipCode) ? ', ' . $company_zipCode : '' }}</strong><br>
              </div>
              <div class="col-md-4 m-b-15">
                <strong class="text-black">{{ __('Bill To')  }}</strong><br>
                <strong class="text-black">{{ isset($saleOrderData->customer->name) ? $saleOrderData->customer->name : '' }}</strong><br>
                <strong>{{ isset($saleOrderData->customerBranch->billing_street) ? $saleOrderData->customerBranch->billing_street : '' }} </strong><br>
                <strong>{{ isset($saleOrderData->customerBranch->billing_state) ? $saleOrderData->customerBranch->billing_state : '' }}{{ isset($saleOrderData->customerBranch->billing_city) ? ', ' . $saleOrderData->customerBranch->billing_city : '' }}</strong><br>
                <strong>{{ isset($saleOrderData->CustomerBranch->billingCountry) ? $saleOrderData->CustomerBranch->billingCountry->name : '' }} {{ isset($saleOrderData->CustomerBranch->billing_zip_code) ? ', ' . $saleOrderData->CustomerBranch->billing_zip_code : ''  }}</strong>
              </div>
              <div class="col-md-4 m-b-15">
                <strong>{{ __('Quotation Date') }} : {{ formatDate($saleOrderData->order_date) }}</strong><br>
                <strong>{{  __('Location') }} : {{ $saleOrderData->location->name }}</strong><br>
                @if(isset($invoiced_status) and $invoiced_status == 'no')
                  <label class="badge theme-bg2 text-white f-12 customer-quotation">{{ __('Not Yet Invoiced!') }}</label>
                @else
                  @if(isset($ref_invoice))
                    <strong>{{ __('Quotation Invoiced')  }} (<a href="{{URL::to('customer-panel/view-detail-invoice/' . $order_no) }}" class="text-success">{{ $ref_invoice }}</a>)  {{ __('on')  }}  {{ formatDate($invoiced_date) }} </strong><br>
                  @else
                    {{ __('Quotation Invoiced on') }} {{ formatDate($invoiced_date) }}<br>
                  @endif
                @endif
              </div>
            </div>
            <div class="row m-t-20">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table id="dataTableBuilder" class="table table-bordered dt-responsive quotation-view" width="100%">
                    <tbody>
                      <tr class="tbl_header_color dynamicRows">
                        <th>{{  __('Items')  }}</th>
                        @if($saleOrderData->has_hsn)
                          <th width="5%" class="text-center">{{ __('HSN')  }}</th>
                        @endif
                        @if($saleOrderData->invoice_type == 'hours')
                          <th width="5%" class="text-center">{{ __('Hours') }}</th>
                          <th width="8%" class="text-center">{{ __('Rate')  }}</th>
                        @else
                          <th width="5%" class="text-center">{{ ('Quantity') }}</th>
                          <th width="8%" class="text-center">{{ __('Price')  }}({{ !empty($saleOrderData->currency) ? $saleOrderData->currency->symbol : '' }})</th>
                        @endif
                        @if($saleOrderData->has_item_discount)
                          <th class="text-center" width="5%">{{ __('Discount')  }}</th>
                        @endif
                        @if($saleOrderData->has_tax)
                          <th width="10%" class="text-center">{{ __('Tax')  }} (%) </th>
                        @endif
                        <th width="10%" class="text-center">
                          Total ({{ $saleOrderData->currency->symbol }})
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
                                  {{ $result['item_name'] }}
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
                              <td width="8%" class="white-space-unset text-center">{{ formatCurrencyAmount( $result->unit_price) }}</td>
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
                              <td width="10%" align="right" class="white-space-unset">{{ formatCurrencyAmount( $priceAmount) }}</td>
                            </tr>
                          @endif
                        @endforeach
                        <tr class="tableInfos">
                          <td colspan="{{ $row }}" align="right">{{ __('Sub Total')  }}</td>
                          <td align="right" colspan="1">{{ formatCurrencyAmount($subTotal) }}</td>
                        </tr>
                        @if($saleOrderData->has_item_discount)
                          <tr class="tableInfos">
                            <td colspan="{{ $row }}" align="right">{{ __('Discount')  }}</td>
                            <td align="right" colspan="1">{{ formatCurrencyAmount( $saleOrderData->saleOrderDetails->sum('discount_amount')) }}</td>
                          </tr>
                        @endif

                        @forelse($taxes as $tax)
                          <tr>
                              <td colspan="{{$row}}" align="right">{{$tax['name']}} : {{ formatCurrencyAmount($tax['rate']) }}% </td>
                              <td colspan="1" class="text-right">{{ formatCurrencyAmount(($tax['amount'])) }}</td>
                          </tr>
                        @empty
                        @endforelse

                        @if( $saleOrderData->has_other_discount )
                          <tr class="tableInfos">
                            @php
                              if( $saleOrderData->other_discount_type == "$" ){
                                $otherDiscount = $saleOrderData->other_discount_amount;
                              } else {
                                $otherDiscount = $subTotal * $saleOrderData->other_discount_amount / 100;
                              }
                            @endphp
                            <td colspan="{{ $row }}" align="right">
                              {{ __('Other Discount') }} : {{ formatCurrencyAmount($saleOrderData->other_discount_amount) }}{{ $saleOrderData->other_discount_type == '$' ? $saleOrderData->currency->symbol : '%' }}
                            </td>
                            <td align="right">{{ formatCurrencyAmount($otherDiscount) }}</td>
                          </tr>
                        @endif

                        @if($saleOrderData->has_shipping_charge && $saleOrderData->shipping_charge)
                          <tr class="tableInfos">
                            <td colspan="{{$row}}" align="right">{{ __('Shipping')  }}</td>
                            <td align="right" colspan="1">{{ $saleOrderData->shipping_charge }}</td>
                          </tr>
                        @endif

                        @if( $saleOrderData->has_custom_charge )
                          <tr class="tableInfos">
                            <td colspan="{{$row}}" align="right">{{ $saleOrderData->custom_charge_title }}</td>
                            <td align="right" colspan="1">{{ $saleOrderData->custom_charge_amount }}</td>
                          </tr>
                        @endif

                        <tr class="tableInfos">
                          <td colspan="{{ $row }}" align="right">
                            <strong>{{ __('Grand Total')  }}</strong></td>
                          <td class="text-right">
                            <strong>{{ formatCurrencyAmount($saleOrderData->total, $saleOrderData->currency->symbol) }}</strong>
                          </td>
                        </tr>

                        <tr class="tableInfos">
                          <td colspan="{{ $row }}" align="right">{{ __('Paid')  }}</td>
                          <td class="text-right">
                            {{ formatCurrencyAmount($saleOrderData->paid, $saleOrderData->currency->symbol) }}
                          </td>
                        </tr>

                        <tr class="tableInfos">
                          <td colspan="{{ $row }}" align="right">
                            <strong>{{ __('Due')  }}</strong>
                          </td>
                          <td colspan="1" class="text-right">
                            <strong>
                               @if($saleOrderData->total < $saleOrderData->paid)
                                -{{ formatCurrencyAmount(abs($grandTotal-$saleOrderData->paid_amount), $saleOrderData->currency->symbol) }}
                              @else
                                {{ formatCurrencyAmount(abs($saleOrderData->total-$saleOrderData->paid), $saleOrderData->currency->symbol) }}
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
    @if (count($files) > 0)
      <div class="{{ $saleOrderData->comment ? 'col-sm-7' : 'col-sm-12' }}">
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
@endsection
@section('js')
<script type="text/javascript" src="{{ asset('public/dist/js/html5lightbox/html5lightbox.js?v=1.0') }}"></script>

@endsection