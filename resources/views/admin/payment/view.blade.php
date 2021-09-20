@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
<div class="col-sm-12" id="sales-payment-view-details-container">
  <div class="col-sm-12" id="card-with-header-button">
    <div class="card">
      <div class="card-header">
        <h5><a href="#">{{ __('Payments') }}</a> >> #{{ sprintf("%04d", $paymentInfo->id) }}</h5>
      </div>
    </div>
  </div>
  <div class="col-sm-12">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
          	<div class="float-left">
              @if($paymentInfo->status == "Approved")
                 <div id="payment-status" class="btn-paid">{{$paymentInfo->status}}</div>
              @elseif($paymentInfo->status == "Pending" )
                <div id="payment-status" class="btn-group">
                  <button type="button btn-paid-partial" class="badge text-white dropdown-toggle customer-payment-status" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  {{ __('Pending') }} &nbsp;<span class="caret"></span>
                    <span class="sr-only">{{ __('Toggle Dropdown') }}</span>
                  </button>
                  <ul class="dropdown-menu scrollable-menu status_change task-priority-name w-100p" role="menu">
                    <li class="properties">
                      <a class="status f-14 cursor_pointer" class="status" data-status="Approved" data-id="{{$paymentInfo->id}}">{{ __('Approved') }}</a>
                    </li>
                    <li class="properties">
                      <a class="status f-14 cursor_pointer" class="status" data-status="Declined" data-id="{{$paymentInfo->id}}">{{ __('Declined') }}</a>
                    </li>
                  </ul>
                </div>
              @elseif($paymentInfo->status == "Declined")
                <div id="payment-status" class="btn-unpaid">{{ $paymentInfo->status }}</div>
              @endif
          	</div>
          	<div class="btn-group float-right row mr-2 mt-1">
          		<button title="Email" type="button" class="btn custom-btn-small btn-outline-secondary" data-toggle="modal" data-target="#emailReceipt">{{ __('Email')  }}</button>
          		<a target="_blank" href="{{URL::to('/')}}/payment/create-receipt/{{$paymentInfo->id}}?type=Print" title="Print" class="btn custom-btn-small btn-outline-secondary">{{ __('Print')  }}</a>
              <a target="_blank" href="{{URL::to('/')}}/payment/create-receipt/{{$paymentInfo->id}}?type=Pdf" title="PDF" class="btn custom-btn-small btn-outline-secondary">{{ __('PDF')  }}</a>
              <a href='{{URL::to("/")}}/payment/edit/{{base64_encode($paymentInfo->id)}}{{$menu == "sales" ? "":"?$sub_menu"}}' title="Edit" class="btn custom-btn-small btn-outline-secondary">{{ __('Edit')  }}</a>
              @if(Helpers::has_permission(Auth::user()->id, 'delete_payment'))
                <form method="POST" action='{{url("payment/delete")}}' accept-charset="UTF-8" class="display_inline" id="delete-payment">
                  {{csrf_field()}}
                  <input type="hidden" name="id" value="{{$paymentInfo->id}}">
                  <button title="{{ __('Delete') }}" class="btn custom-btn-small btn-outline-danger" type="button" data-toggle="modal" data-id="{{$paymentInfo->id}}" data-target="#theModal" data-label="Delete" data-title="{{ __('Delete payment') }}" data-message="{{ __('Are you sure to delete this?') }}">{{ __('Delete')  }}</button>
                </form>
              @endif
            </div>
          </div>
          <div class="card-body">
            <div class="m-t-10">
              <div class="row m-t-10 ml-2">
                <div class="col-md-4 m-b-15">
                  <strong class="f-13 color-2d2f30">{{ $company_name }}</strong><br>
                  <strong class="f-13">{{ $company_street }}</strong><br>
                  <strong class="f-13">{{ $company_city }}, {{ $company_state }}</strong><br>
                  <strong class="f-13">{{ $company_country_name }}, {{ $company_zipCode }}</strong><br>
                </div>
                <div class="col-md-4 m-b-15">
                  <strong class="text-black">{{ isset($paymentInfo->customer->first_name) ? $paymentInfo->customer->first_name : '' }} {{ isset($paymentInfo->customer->last_name) ? $paymentInfo->customer->last_name : '' }}</strong><br>
                  <strong>{{ isset($paymentInfo->customer->customerBranch->billing_street) ? $paymentInfo->customer->customerBranch->billing_street : '' }} </strong><br>
                  <strong>{{ isset($paymentInfo->customer->customerBranch->billing_state) ? $paymentInfo->customer->customerBranch->billing_state : '' }}{{ isset($paymentInfo->customer->customerBranch->billing_city) ? ', ' . $paymentInfo->customer->customerBranch->billing_city : '' }}</strong><br>
                    <strong>{{ isset($paymentInfo->customer->customerBranch->billingCountry) ? $paymentInfo->customer->customerBranch->billingCountry->name : '' }} {{ isset($paymentInfo->customer->customerBranch->billing_zip_code) ? ', ' . $paymentInfo->customer->customerBranch->billing_zip_code : '' }}</strong><br>
                </div>
                <div class="col-md-4 m-b-15">
                  <strong class="f-13">{{ __('Payment Date') }} : {{ formatDate($paymentInfo->transaction_date)}}</strong><br>
                  <strong class="f-13">{{ __('Payment Method') }} : {{ isset($paymentInfo->paymentMethod) && isset($paymentInfo->paymentMethod->name)? $paymentInfo->paymentMethod->name : __('N/A') }}</strong>
                </div>
              </div>
              <div class="row m-t-20">
                <div class="col-md-12 table-responsive">
                  <table class="table table-bordered" id="salesInvoice">
                    <tbody>
                      <tr class="tbl_header_color dynamicRows">
                        <th width="20%" class="text-center">{{ __('Invoice No')  }}</th>
                        <th width="20%" class="text-center">{{ __('Invoice Date') }}</th>
                        <th width="20%" class="text-center">{{ __('Invoice Amount')  }}</th>
                        <th width="20%" class="text-center">{{ __('Paid Amount')  }}</th>
                      </tr>
                      <tr>
                        <td width="20%" class="text-center">
                          <a href="{{ url('invoice/view-detail-invoice/' . $paymentInfo->saleOrder->id) }}?{{$sub_menu}}">{{ $paymentInfo->saleOrder->reference }}</a>
                        </td>
                        <td width="20%" class="text-center">{{ formatDate($paymentInfo->saleOrder->order_date) }}</td>
                        <td width="20%" class="text-center">{{ formatCurrencyAmount($paymentInfo->saleOrder->total, $paymentInfo->saleOrder->currency->symbol) }}</td><td width="20%" class="text-center">{{ formatCurrencyAmount($paymentInfo->amount, $paymentInfo->currency->symbol) }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @if (! empty($files) && count($files) > 0)
        <div class="col-sm-12">
          <div class="card">
            <div class="card-header">
              <h5>Files</h5>
            </div>
            <div class="card-body">
              <div class="row pt-4 pb-4 px-3">
              @php
                $groupName = 'pay-'.(string) $paymentInfo->id;
              @endphp
                @foreach ($files as $file)
                  @php
                    $url = 'public/dist/js/html5lightbox/no_preview.png?v'. $file->id;
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
                  <a <?= $extra ?> href="{{ $url }}" data-attachment="<?= $file->id; ?>" class="html5lightbox" title="{{ $fileName }}" data-group="{{ $groupName }}">
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
</div>


@inject('saleOrder', 'App\Models\SaleOrder')
  @inject('country', 'App\Models\Country')
  @php
    if(isset($paymentInfo->customer->customerBranch->billing_country_id) && $paymentInfo->customer->customerBranch->billing_country_id != 0 ) {
      $billingCountry = $country->getCountry($paymentInfo->customer->customerBranch->billing_country_id);
    } else {
      $billingCountry = '';
    }
  @endphp

<div id="emailReceipt" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    @inject('saleOrder', 'App\Models\SaleOrder')
    <form id="sendPaymentReceipt" method="POST" action="{{url('payment/email-payment-info')}}">
		  <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
		  <input type="hidden" value="{{$paymentInfo->id}}" name="id" id="token">
      <input type="hidden" value="{{isset($saleOrder->getQuotationId($paymentInfo->saleOrder->order_reference_id)->pluck('reference')[0]) ? $saleOrder->getQuotationId($paymentInfo->saleOrder->order_reference_id)->pluck('reference')[0] : ''}}" name="quotationRef" id="quotationRef">
      <input type="hidden" value="{{$paymentInfo->saleOrder->reference}}" name="invoiceRef" id="invoiceRef">
		  <div class="modal-content">
		    <div class="modal-header">
		      <h4 class="modal-title">{{__('Send payment receipt to client') }}</h4>
		      <button type="button" class="close" data-dismiss="modal">&times;</button>
		    </div>
		    <div class="modal-body">
		      <div class="form-group">
		        <label for="email">{{ __('Send To') }}:</label>
		        <input type="email" value="{{ old('email') ? old('email') : $paymentInfo->customer->email }}" class="form-control" name="email" id="email">
		      </div>
		      @php

		        $subjectText = str_replace('{invoice_reference_no}', $paymentInfo->saleOrder->reference, $emailInfo->subject);
		        $subjectText = str_replace('{company_name}', $company_name, $subjectText);
		      @endphp
		      <div class="form-group">
		        <label for="subject">{{ __('Subject') }}:</label>
		        <input type="text" class="form-control" name="subject" id="subject" value="{{$subjectText}}">
		      </div>
		      <div class="form-group">
		        @php
		          $bodyInfo = str_replace('{customer_name}', $paymentInfo->customer->name, $emailInfo->body);
              $bodyInfo = str_replace('{billing_street}', isset($paymentInfo->customer->customerBranch->billing_street) ? $paymentInfo->customer->customerBranch->billing_street : "", $bodyInfo);
              $bodyInfo = str_replace('{billing_city}', isset($paymentInfo->customer->customerBranch->billing_city) ? $paymentInfo->customer->customerBranch->billing_city : "", $bodyInfo);
              $bodyInfo = str_replace('{billing_state}', isset($paymentInfo->customer->customerBranch->billing_state) ? $paymentInfo->customer->customerBranch->billing_state : "", $bodyInfo);
              $bodyInfo = str_replace('{billing_zip_code}', isset($paymentInfo->customer->customerBranch->billing_zip_code) ? $paymentInfo->customer->customerBranch->billing_zip_code : "", $bodyInfo);
              $bodyInfo = str_replace('{billing_country}', $billingCountry, $bodyInfo);
              if(isset($saleOrder->getQuotationId($paymentInfo->saleOrder->order_reference_id)->pluck('reference')[0])) {
                $order_reference_id = $saleOrder->getQuotationId($paymentInfo->saleOrder->order_reference_id)->pluck('reference')[0];
              } else {
                $order_reference_id = "";
              }
              $bodyInfo = str_replace('{payment_id}', sprintf("%04d", $paymentInfo->id), $bodyInfo);
              $bodyInfo = str_replace('{payment_date}', formatDate($paymentInfo->transaction_date), $bodyInfo);
              $bodyInfo = str_replace('{total_amount}', formatCurrencyAmount($paymentInfo->amount, $paymentInfo->currency->symbol), $bodyInfo);
              $bodyInfo = str_replace('{order_reference_no}', $order_reference_id, $bodyInfo);
              $bodyInfo = str_replace('{invoice_reference_no}', $paymentInfo->saleOrder->reference, $bodyInfo);
              $bodyInfo = str_replace('{company_name}', $company_name, $bodyInfo);
              $bodyInfo = html_entity_decode($bodyInfo);
		        @endphp
            <input type="hidden" name="message" id='messageTxt' value="{{$bodyInfo}}">
		      </div>
          <div id="previewTxt"></div>
		      <div class="form-group">
		      	<div class="checkbox">
              <label>{{ __('Attach pdf with Email') }}</label><br>
              <div class="form-group">
                <div class="checkbox checkbox-primary checkbox-fill d-inline">
                  <input type="checkbox" name="payment_pdf" id="payment_pdf" checked="">
                  <label for="payment_pdf" class="cr"><strong>{{'PM-'. sprintf("%04d", $paymentInfo->id)}}</strong></label>
                </div>
              </div>
		      	</div>
		  		</div>
		    </div>
		    <div class="modal-footer">
            <a href="{{ url('payment/list') }}" class="btn btn-secondary custom-btn-small" data-dismiss="modal">{{ __('Close')  }}</a>
            <button type="submit" class="btn btn-primary custom-btn-small">{{ __('Send')  }}</button>
		    </div>
		  </div>
    </form>
  </div>
</div>
<!--Modal end -->

@endsection

@section('js')

<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/ckeditor/js/ckeditor.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/dist/js/html5lightbox/html5lightbox.js?v=1.0') }}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/sales-purchase.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/sales.min.js') }}"></script>
@endsection
