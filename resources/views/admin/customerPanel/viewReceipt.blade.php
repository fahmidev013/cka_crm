@extends('layouts.customer_panel')

@section('css')
<link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/css/customer-panel.min.css') }}">
@endsection

@section('content')

<div class="col-sm-12" id="card-with-header-button">
  <div class="card mb-0">
    <div class="card-header">
      <h5><a href="{{ url('customer-panel/payment') }}">{{ __('Payments') }}</a> >> #{{ sprintf("%04d", $paymentInfo->id) }}</h5>
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
              <div id="payment-status" class="btn-paid">{{ $paymentInfo->status }}</div>
            @elseif($paymentInfo->status == "Pending" )
              <div id="payment-status" class="btn-paid-partial customer-payment-status">{{ $paymentInfo->status }}</div>
            @elseif($paymentInfo->status == "Declined")
              <div id="payment-status" class="btn-unpaid">{{ $paymentInfo->status }}</div>
            @endif
          </div>
          <div class="btn-group float-right row mr-2 mt-1">
            <a target="_blank" href="{{ URL::to('/') }}/customer-panel/payment/create-receipt/{{ $paymentInfo->id }}?type=Print" title="Print" class="btn custom-btn-small btn-outline-secondary">{{ __('Print')  }}</a>
            <a target="_blank" href="{{ URL::to('/') }}/customer-panel/payment/create-receipt/{{ $paymentInfo->id }}?type=Pdf" title="PDF" class="btn custom-btn-small btn-outline-secondary">{{ __('PDF')  }}</a>
          </div>
        </div>
        <div class="card-body">
          <div class="m-t-10">
            <div class="row m-t-10 m-l-15">
              <div class="col-md-4 m-b-15">
                <strong class="text-black">{{ __('Bill To')  }}</strong><br>
                <strong class="text-black">{{ $company_name }}</strong><br>
                <strong>{{ $company_street }}</strong><br>
                <strong>{{ $company_city }}{{ !empty($company_state) ? ', ' . $company_state : '' }}</strong><br>
                <strong>{{ $company_country_name }}{{ !empty($company_zipCode) ? ', ' . $company_zipCode : '' }}</strong>
              </div>
              <div class="col-md-4 m-b-15">
                <strong class="text-black">{{ __('Customer') }}</strong><br>
                <strong class="text-black">{{ $paymentInfo->customer->name }}</strong><br>
                <strong>{{ isset($paymentInfo->customer->customerBranch) ? $paymentInfo->customer->customerBranch->billing_street : '' }}{{ isset($paymentInfo->customer->customerBranch->billing_city) ? ", " : '' }} {{ isset($paymentInfo->customer->customerBranch->billing_city) ? $paymentInfo->customer->customerBranch->billing_city : '' }}</strong><br>

                <strong>{{ isset($paymentInfo->customer->customerBranch) ? $paymentInfo->customer->customerBranch->billing_state : '' }} {{ isset($paymentInfo->customer->customerBranch->billing_zip_code) ? " - ":'' }} {{ isset($paymentInfo->customer->customerBranch->billing_zip_code) ? $paymentInfo->customer->customerBranch->billing_zip_code : '' }} {{ isset($paymentInfo->customer->customerBranch->billingCountry) ? ',': ''}} {{ isset($paymentInfo->customer->customerBranch->billingCountry) ? $paymentInfo->customer->customerBranch->billingCountry->name : '' }}</strong>
              </div>
              <div class="col-md-4 m-b-15">
                <strong>{{  __('Payment Date') }} : {{ formatDate($paymentInfo->transaction_date)}}</strong><br>
                <strong>{{ __('Payment Method') }} : {{ !empty($paymentInfo->paymentMethod) ? $paymentInfo->paymentMethod->name : '' }}</strong>
              </div>
            </div>
            <div class="row m-t-20">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordered dt-responsive" id="summery" width="100%">
                    <thead>
                      <tr class="tbl_header_color dynamicRows">
                        <th width="20%" class="text-center">{{ __('Invoice No')  }}</th>
                        <th width="20%" class="text-center">{{  __('Invoice Date')  }}</th>
                        <th width="20%" class="text-center">{{ __('Invoice Amount')  }}</th>
                        <th width="20%" class="text-center">{{ __('Paid Amount')  }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td width="20%" class="text-center">
                          <a href="{{ url('customer-panel/view-detail-invoice/' . $paymentInfo->saleOrder->id) }}">{{ $paymentInfo->saleOrder->reference }}</a>
                        </td>
                        <td width="20%" class="text-center">{{ formatDate($paymentInfo->saleOrder->order_date) }}</td>
                        <td width="20%" class="text-center">{{ formatCurrencyAmount($paymentInfo->saleOrder->total, $paymentInfo->currency->symbol) }}</td>
                        <td width="20%" class="text-center">{{ formatCurrencyAmount($paymentInfo->amount, $paymentInfo->currency->symbol) }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
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
            <h5>{{ __('Files')  }}</h5>
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
@endsection

@section('js')
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/dist/js/html5lightbox/html5lightbox.js?v=1.0') }}"></script>
@endsection
