@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
<div id="purchase-payment-view-details-container">
  <div class="col-sm-12" id="card-with-header-button">
    <div class="card">
      <div class="card-header">
        <h5><a href="{{url('purchase_payment/list')}}">{{ __('Payments') }}</a> >> #{{ sprintf("%04d", $paymentInfo->id) }}</h5>
      </div>
    </div>
  </div>
  <div class="col-sm-12">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <div class="btn-group float-right row mr-2 mt-1">
              <button title="Email" type="button" class="btn custom-btn-small btn-outline-secondary" data-toggle="modal" data-target="#emailReceipt">{{ __('Email')  }}</button>
              <a target="_blank" href="{{URL::to('/')}}/purchase_payment/print-receipt/{{$paymentInfo->id}}" title="Print" class="btn custom-btn-small btn-outline-secondary">{{ __('Print')  }}</a>
              <a target="_blank" href="{{URL::to('/')}}/purchase_payment/create-receipt-pdf/{{$paymentInfo->id}}" title="PDF" class="btn custom-btn-small btn-outline-secondary">{{ __('PDF')  }}</a>
              <a href='{{URL::to("/")}}/purchase_payment/edit/{{base64_encode($paymentInfo->id)}}{{$menu == "purchase" ? "" : "?$sub_menu"}}' title="Edit" class="btn custom-btn-small btn-outline-secondary">{{ __('Edit')  }}</a>
              <form method="POST" action='{{url("purchase_payment/delete")}}' accept-charset="UTF-8" class="display_inline" id="delete-purchase-payment">
                {{csrf_field()}}
                <input type="hidden" name="id" value="{{$paymentInfo->id}}">
                <input type="hidden" name="menu" value="{{$menu}}">
                <input type="hidden" name="sub_menu" value="{{$sub_menu}}">
                <button title="{{ __('Delete') }}" class="btn custom-btn-small btn-outline-danger" type="button" data-toggle="modal" data-id="{{$paymentInfo->id}}" data-target="#theModal" data-label="Delete" data-title="{{ __('Delete Payment') }}" data-message="{{ __('Are you sure to delete this?') }}">{{ __('Delete')  }}</button>
              </form>
            </div>
          </div>
          <div class="card-body">
            <div class="m-t-10">
              <div class="row m-t-10 ml-2">
                <div class="col-md-4 m-b-15">
                  <strong class="f-13 color-2d2f30">{{ $company_name }}</strong><br>
                  <strong class="f-13">{{ $company_street }}</strong><br>
                  <strong class="f-13">{{ ! empty($company_city) ? $company_city : '' }}{{ ! empty($company_state) ? ', '.$company_state : ''}}{{!empty($company_zipCode) ? ', '.$company_zipCode : ''}}</strong><br>
                  <strong class="f-13">{{ $company_country_name }}</strong>
                </div>
                <div class="col-md-4 m-b-15">
                  <strong class="f-13 color-2d2f30">{{ !empty($paymentInfo->supplier->name) ? $paymentInfo->supplier->name : '' }}</strong><br>
                  <strong class="f-13">{{ !empty($paymentInfo->supplier->street) ? $paymentInfo->supplier->street : '' }}</strong><br>
                  <strong class="f-13">{{ isset($paymentInfo->supplier->city) ? $paymentInfo->supplier->city : '' }}{{ isset($paymentInfo->supplier->state) ? ', '.$paymentInfo->supplier->state : '' }}</strong><br>
                  <strong class="f-13">{{ isset($paymentInfo->supplier->Country->name) ? $paymentInfo->supplier->Country->name : '' }}{{ isset($paymentInfo->supplier->zipcode) ? ', '.$paymentInfo->supplier->zipcode: '' }}</strong>
                </div>
                <div class="col-md-4 m-b-15">
                  <strong class="f-13">{{ __('Payment Date') }} : {{ formatDate($paymentInfo->transaction_date)}}</strong><br>
                  <strong class="f-13">{{ __('Payment Method') }} : {{ isset($paymentInfo->paymentMethod->name) ? $paymentInfo->paymentMethod->name : 'N/A' }}</strong>
                </div>
              </div>
              <div class="row m-t-20">
                <div class="col-md-12 table-responsive">
                  <table class="table table-bordered" id="salesInvoice">
                    <tbody>
                      <tr class="tbl_header_color dynamicRows">
                        <th width="20%" class="text-center">{{ __('Purchase No')  }}</th>
                        <th width="20%" class="text-center">{{ __('Purchase Date') }}</th>
                        <th width="20%" class="text-center">{{ __('Purchase Amount')  }}</th>
                        <th width="20%" class="text-center">{{ __('Paid Amount')  }}</th>
                      </tr>
                      <tr>
                        <td width="20%" class="text-center">
                          <a href='{{url("purchase/view-purchase-details")}}/{{$paymentInfo->purchaseOrder->id}}{{$menu == "purchase" ? "" : "?$sub_menu"}}'>{{ $paymentInfo->purchaseOrder->reference }}</a>
                        </td>
                        <td width="20%" class="text-center">{{$paymentInfo->purchaseOrder ? formatDate($paymentInfo->purchaseOrder->order_date) : '-'}}</td>
                        <td width="20%" class="text-center">{{ formatCurrencyAmount($paymentInfo->purchaseOrder->total, $paymentInfo->purchaseOrder->currency->symbol) }}</td><td width="20%" class="text-center">{{ formatCurrencyAmount($paymentInfo->amount, $paymentInfo->currency->symbol) }}</td>
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

                    $customFileName = explode("_",$file->file_name)
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
  <div id="emailReceipt" class="modal fade" role="dialog">
    @inject('purchaseOrder', 'App\Models\PurchaseOrder')
    <div class="modal-dialog">
      <form id="sendPaymentReceipt" method="POST" action="{{url('purchase_payment/email-payment-info')}}">
      <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
      <input type="hidden" value="{{$paymentInfo->id}}" name="id" id="token">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{__('Send payment receipt to client') }}</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="email">{{ __('Send To') }}:</label>
            <input type="email" value="{{ isset($paymentInfo->supplier->email) ? $paymentInfo->supplier->email : '' }}" class="form-control" name="email" id="email">
          </div>
          <?php
              $subjectText = str_replace('{purchase_reference_no}', isset($paymentInfo->purchaseOrder->reference) ? $paymentInfo->purchaseOrder->reference : null, $emailInfo->subject);
              $subjectText = str_replace('{company_name}', $company_name, $subjectText);
           ?>
          <div class="form-group">
            <label for="subject">{{ __('Subject') }}:</label>
            <input type="text" class="form-control" name="subject" id="subject" value="{{$subjectText}}">
          </div>
          <div class="form-group">
            @php
              $bodyInfo = str_replace('{supplier_name}', isset($paymentInfo->supplier->name) ? $paymentInfo->supplier->name : '', $emailInfo->body);
              $bodyInfo = str_replace('{payment_id}', sprintf("%04d", $paymentInfo->id), $bodyInfo);
              $bodyInfo = str_replace('{payment_method}', isset($paymentInfo->paymentMethod->name) ? $paymentInfo->paymentMethod->name : '', $bodyInfo);
              $bodyInfo = str_replace('{payment_date}', formatDate($paymentInfo->transaction_date), $bodyInfo);
              $bodyInfo = str_replace('{total_amount}', $paymentInfo->currency->symbol.number_format($paymentInfo->amount,2,'.',','), $bodyInfo);
              $bodyInfo = str_replace('{purchase_reference_no}', isset($paymentInfo->purchaseOrder->reference) ? $paymentInfo->purchaseOrder->reference : '', $bodyInfo);
              $bodyInfo = str_replace('{company_name}', $company_name, $bodyInfo);
              $bodyInfo = str_replace('{billing_state}', isset($paymentInfo->supplier->city) ? $paymentInfo->supplier->city : '', $bodyInfo);
              $bodyInfo = str_replace('{billing_street}', isset($paymentInfo->supplier->street) ? $paymentInfo->supplier->street : '', $bodyInfo);
              $bodyInfo = str_replace('{billing_city}', isset($paymentInfo->supplier->state) ? $paymentInfo->supplier->state : '', $bodyInfo);
              $bodyInfo = str_replace('{billing_zip_code}', isset($paymentInfo->supplier->zipcode) ? $paymentInfo->supplier->zipcode : '', $bodyInfo);
              $bodyInfo = str_replace('{billing_country}', isset($paymentInfo->supplier->Country->name) ? $paymentInfo->supplier->Country->name : '', $bodyInfo);
            @endphp
            <textarea id="compose-textarea" name="message" id='message' class="form-control editor h-200">{{$bodyInfo}}</textarea>
          </div>

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
            <a href="{{ url('purchase_payment/list') }}" class="btn btn-secondary custom-btn-small" data-dismiss="modal">{{ __('Close')  }}</a>
            <button type="submit" class="btn btn-primary custom-btn-small">{{ __('Send')  }}</button>
        </div>
      </div>
      </form>
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
<!--Modal end -->
@endsection
@section('js')
<script type="text/javascript" src="{{ asset('public/dist/js/html5lightbox/html5lightbox.js?v=1.0') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/datta-able/plugins/ckeditor/js/ckeditor.js') }}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/sales-purchase.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/purchases.min.js') }}"></script>
@endsection
