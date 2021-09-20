@extends('layouts.app')
@section('css')
{{-- Select2  --}}
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
  <!-- Main content -->
  <div class="col-sm-12" id="deposit-edit-container">
    <div class="card">
      <div class="card-header">
        <h5> <a href="{{ url('deposit/list') }}">{{ __('Bank Account Deposits')  }}</a> >> {{ __('Edit Deposit') }}</h5>
        <div class="card-header-right">

        </div>
      </div>
      <div class="card-body table-border-style">
        <div class="form-tabs">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active text-uppercase" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __('Edit Deposit') }}</a>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
              <form action="{{ url('deposit/update') }}" method="post" id="deposit" class="form-horizontal" enctype="multipart/form-data">
                <input type="hidden" value="{{ csrf_token() }}" name="_token" id="token">
                <input type="hidden" value="{{ $depositInfo->id }}" name="id" >
                <input type="hidden" value="{{ $depositInfo->transaction_reference_id }}" name="reference_id">
                <input type="hidden" value="{{ $depositInfo->account_id }}" name="account_no">
                <div class="row">
                  <div class="col-sm-9">
                    <div class="form-group row">
                      <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Account')  }}</label>
                      <div class="col-sm-9">
                         <select class="form-control select2"  id="account_no" disabled>
                          @foreach($accounts as $account)
                            <option value="{{ $account->id }}" <?= ($account->id == $depositInfo->account_id) ? 'selected' : '' ?>>{{ $account->name }} ({{ $account->currency->name }}) </option>
                          @endforeach
                          </select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Amount')  }}</label>
                      <div class="col-sm-9">
                        <input type="text" placeholder="{{ __('Amount')  }}" class="form-control positive-float-number" id="amount" name="amount" value="{{ formatCurrencyAmount($depositInfo->amount) }}">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-3 control-label" for="inputEmail3">{{ __('Category')  }}</label>
                      <div class="col-sm-9">
                         <select class="form-control select2" name="category_id" id="category_id" >
                          @foreach($incomeCategories as $cat_id=>$cat_name)
                            <option value="{{ $cat_id }}" <?= ($cat_id == $depositInfo->income_expense_category_id) ? 'selected' : '' ?>>{{ $cat_name }}</option>
                          @endforeach
                          </select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-3 control-label" for="inputEmail3">{{ __('Payment Method')  }}</label>
                      <div class="col-sm-9">
                         <select class="form-control select2" name="payment_method" id="payment_method">
                          @foreach($payment_methods as $method_id => $method_name)
                            <option value="{{ $method_id }}" <?= ($method_id == $depositInfo->payment_method_id) ? 'selected' : '' ?> >{{ $method_name }}</option>
                          @endforeach
                          </select>
                      </div>
                    </div>

                     <div class="form-group row">
                      <label class="col-sm-3 control-label" for="description">{{ __('Description')  }}</label>
                      <div class="col-sm-9">
                        <textarea class="form-control" id="description" placeholder="{{ __('Description')  }}" name="description" rows="3">{{ $depositInfo->description }}</textarea>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Date')  }}</label>
                      <div class="col-sm-9">
                      <input type="text" class="form-control" id="trans_date" readonly="true" name="transaction_date" value="{{ formatDate($depositInfo->transaction_date )}}">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-3 control-label" for="inputEmail3">{{ __('Reference')  }}</label>
                      <div class="col-sm-9">
                        <input type="text" placeholder="{{ __('Reference')  }}" class="form-control" id="reference" name="reference" value="{{ $depositInfo->transactionReference->code }}" readonly="true">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-3 control-label">{{ __('Attachment') }}</label>
                      <div class="col-sm-9">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" name="attachment" id="validatedCustomFile">
                          <label class="custom-file-label overflow_hidden" for="validatedCustomFile">{{ __('Upload file...') }}</label>
                          <label id="validatedCustomFile-error" class="error" for="validatedCustomFile"></label>
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-3 control-label"></label>
                      <div class="col-sm-9" id='note_txt_1'>
                        <span class="badge badge-danger">{{ __('Note') }}!</span> {{ __('Allowed File Extensions: jpg, png, gif, doc, docx, pdf') }}
                      </div>
                    </div>
                  </div>
                  @if(isset($files) && count($files) > 0)
                    <div class="col-sm-3">
                      @php
                        $extension = strtolower(pathinfo($files[0]->file_name, PATHINFO_EXTENSION));
                        $icon = getFileIcon($files[0]->file_name);
                        $url = 'public/dist/js/html5lightbox/no_preview.png?v' . $files[0]->id;
                        $extra = '';
                        $div = '';
                        $fileName = !empty($files[0]->original_file_name) ? $files[0]->original_file_name : $files[0]->file_name;
                        if (in_array($extension, array('jpg', 'png', 'jpeg', 'gif', 'pdf', 'flv', 'webm', 'mp4', 'ogv', 'swf', 'm4v', 'ogg'))) {
                          $url = url($filePath) . '/' . $files[0]->file_name;
                        } elseif (in_array($extension, array('csv', 'xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx', 'txt'))) {
                          $url = '#pdiv-' . $files[0]->id;
                          $extra = 'data-width=900 data-height=600';
                          $div = '<div id="pdiv-' . $files[0]->id . '" class="display_none">
                                    <div class="lightboxcontainer">
                                      <iframe width="100%" height="100%" src="//docs.google.com/gview?url=' . url($filePath) . '/' . $files[0]->file . '&embedded=true" frameborder="0" allowfullscreen></iframe>
                                      <div class="clear_both"></div>
                                    </div>
                                  </div>';
                        }
                      @endphp
                      <a href="#" >
                        <i class="fas fa-times-circle attachment-delete pl-5 ml-5 f-20 color_red delete-icon" data-depositId="<?= $depositInfo->id; ?>" data-attachment="<?= $files[0]->id; ?>" aria-hidden="true"></i><br>
                      </a>
                      <a <?= $extra ?> href="{{ $url }}" data-attachment="<?= $files[0]->id; ?>" class="html5lightbox attachment" title="{{ $fileName }}">
                        <div class="previewer-file-total-div">
                          <div class="previewer-file-thumbnail-div">
                            @if (in_array($extension, array('jpg', 'png', 'jpeg', 'gif')))
                              <img class="previewer-thumbnail-size" src="{{ $url }}">
                            @else
                              <i class="{{ $icon }} center f-50 previewer-icon-position" style="color:{{ setColor($extension) }};" aria-hidden="true"></i>
                            @endif
                          </div>
                          <div class="previewer-file-name-div">
                            <div>
                              <i class="{{ $icon }} f-20" style="color:{{ setColor($extension) }};" aria-hidden="true"></i>
                              <span class="f-12 previewer-file-name">{{ strlen($fileName) > 15 ? substr_replace($fileName, "..", 15) : $files[0]->original_file_name }}
                              </span>
                            </div>
                          </div>
                        </div>
                      </a>
                      <?= $div ?>
                    </div>
                  @endif
                </div>
                <div class="col-sm-9 px-0 pt-2">
                  <button class="btn btn-primary custom-btn-small" type="submit" id="btnSubmit">{{ __('Update') }}</button>
                  <a href="{{ url('deposit/list') }}" class="btn btn-info custom-btn-small">{{ __('Cancel') }}</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
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


@endsection

@section('js')
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/html5lightbox/html5lightbox.js?v=1.0') }}"></script>
<script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/deposit.min.js') }}"></script>
@endsection
