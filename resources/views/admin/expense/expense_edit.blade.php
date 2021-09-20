@extends('layouts.app')
@section('css')
{{-- Select2  --}}
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection
@section('content')
  <div class="col-sm-12" id="edit-expense-container">
    <div class="card">
      <div class="card-header">
        <h5> <a href="{{ url('expense/list') }}">{{ __('Expense') }}</a> >> {{ __('Edit Expense') }}</h5>
        <div class="card-header-right">
            
        </div>
      </div>
      <div class="card-body table-border-style">
        <div class="form-tabs">
          <form action="{{ url('expense/update') }}" method="post" id="expense" class="form-horizontal" enctype="multipart/form-data">
            <input type="hidden" value="{{ csrf_token() }}" name="_token" id="token">
            <input type="hidden" value="{{ $expenseInfo->id }}" name="id" id="id">
            <input type="hidden" value="{{ isset($expenseInfo->transaction_id) ? $expenseInfo->transaction->account_id : '' }}" name="account_no" id="account_no">
            <input type="hidden" value="{{ isset($expenseInfo->transaction_id) ? $expenseInfo->transaction_id : '' }}" name="bank_transaction_id" id="bank_transaction_id">
            <input type="hidden" value="{{ $expenseInfo->transaction_reference_id }}" name="reference_id" id="reference_id">
            <input type="hidden" value="{{ $expenseInfo->currency_id }}" name="currency_id" id="currency_id">

            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item">
                  <a class="nav-link active text-uppercase" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __('Expense Information') }}</a>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="row">
                  <div class="col-sm-9">
                    <div class="form-group row">
                      <label class="col-sm-3 control-label" for="inputEmail3">{{ __('Payment Method') }}</label>
                      <div class="col-sm-9">
                        <select class="form-control select" name="payment_method" id="payment_method">
                          <option value="">{{ __('Select One') }}</option>
                          @foreach($paymentMethods as $method)
                            <option value="{{ $method->name }}" data-methodId="{{ $method->id }}" <?= ($method->id == $expenseInfo->payment_method_id) ? 'selected' : '' ?>> {{ $method->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <input type="hidden" name="payment_method_id" id="payment_method_id" value="{{ $expenseInfo->payment_method_id }}">
                    <input type="hidden" name="homeCurrency" id="homeCurrency" value="{{ $homeCurrency[0] }}">
                    <input type="hidden" name="totalBalance" id="totalBalance" value= "{{ !empty($balance) ? $balance : 0 }}">

                    @if (!empty($expenseInfo->paymentMethod) && $expenseInfo->paymentMethod->name == "Bank")
                     <div class="form-group row" id="previousAccount">
                      <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Account') }}</label>
                        <div class="col-sm-9">
                           <select class="form-control select" name="account_number" id="account_number">
                            @foreach($account as $data)
                              <option value="{{ $data->id }}" <?= ($data->id == $expenseInfo->transaction->account_id) ? 'selected' : '' ?> currency-id="{{ $data->currency->id }}" currency-code="{{ $data->currency->name }}">{{ $data->name }} ({{ $data->currency->name }})</option>
                            @endforeach
                            </select>
                            <span class="message"></span>
                        </div>
                      </div>
                      @endif

                      <div class="form-group row display_none" id="account">
                        <label class="col-sm-3 control-label require" for="acc_no">{{ __('Account') }}</label>
                        <div class="col-sm-9">
                          <select class="form-control select" name="acc_no" id="acc_no">
                            <option value="">{{ __('Select One') }}</option>
                            @foreach($accounts as $account)
                              <option value="{{ $account->id }}" currency-id="{{ $account->currency_id }}" currency-code="{{ $account->currency }}" >{{ $account->name }} ({{ $account->currency }})</option>
                            @endforeach
                          </select>
                          <span class="message"></span>
                          <input type="hidden" name="curr_id" id="curr_id" value="">
                        </div>
                        <div class="col-md-3 ml-3"></div>
                        <label id="acc_no-error" class="error" for="acc_no">{{ __('This field is required.') }}</label>
                      </div>

                      <div class="form-group row" id="currency-div">
                        <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Currency') }}</label>
                        <div class="col-sm-9">
                          <select class="form-control select" name="currency" id="currency">
                            <option value="">{{ __('Select One') }}</option>
                            @foreach($currencies as $key => $value)
                              <option value="{{ $key }}" {{ $key == $expenseInfo->currency_id ? 'selected' : ''}}>{{ $value }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="offset-sm-3 pl-3 mt-0 pt-0 custom_error_show margin-top-neg-15">
                          <label id="currency-error" class="error" for="currency"></label>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Amount') }}</label>
                        <div class="col-sm-9">
                          <input type="text" placeholder="{{ __('Amount') }}" class="form-control positive-float-number" id="amount" name="amount" value="{{ formatCurrencyAmount($expenseInfo->amount) }}" >
                          <span id="errorMessage" class="color_red"></span>
                        </div>
                        <input type="hidden" id="lastAmount" value="{{formatCurrencyAmount($expenseInfo->amount)}}">
                      </div>

                     <div class="form-group row">
                        <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Category') }}</label>
                        <div class="col-sm-9">
                           <select class="form-control select" name="category_id" id="category_id">
                            <option value="">{{ __('Select One') }}</option>
                            @foreach($incomeCategories as $cat_id => $cat_name)
                              <option value="{{ $cat_id }}" <?= ($cat_id == $expenseInfo->income_expense_category_id) ? 'selected' : '' ?>>{{ $cat_name }}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="offset-sm-3 pl-3 mt-0 pt-0 custom_error_show margin-top-neg-15">
                          <label id="category_id-error" class="error" for="category_id"></label>
                        </div>
                     </div>

                      <div class="form-group row">
                        <label class="col-sm-3 control-label" for="inputEmail3">{{ __('Description') }}</label>
                        <div class="col-sm-9">
                          <textarea class="form-control overflow_hidden" placeholder="{{ __('Description') }}" id="description" name="description" rows="3"> {{ $expenseInfo->note }}                     
                          </textarea>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Date') }}</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="trans_date" name="trans_date" value="{{ formatDate($expenseInfo->transaction_date) }}" readonly="true">
                        </div>
                      </div>


                      <div class="form-group row">
                        <label class="col-sm-3 control-label" for="inputEmail3">{{ __('Reference') }}</label>
                        <div class="col-sm-9">
                          <input type="text" placeholder="{{ __('Reference') }}" class="form-control" id="reference" name="reference" value="{{ $expenseInfo->transactionReference->code }}" readonly="true">
                        </div>
                      </div>


                    <div class="form-group row">
                      <label class="col-sm-3 control-label">{{ __('Attachment') }}</label>
                      <div class="col-sm-9">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" name="attachment" id="validatedCustomFile">
                          <label class="custom-file-label overflow_hidden" for="validatedCustomFile">{{ __('Upload file...') }}</label>
                        </div>                    
                      </div>
                    </div>

                    <div class="form-group row" id="noteTxtDiv">
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
                                      <iframe width="100%" height="100%" src="//docs.google.com/gview?url=' . url($filePath) . '/' . $files->file . '&embedded=true" frameborder="0" allowfullscreen></iframe>
                                      <div class="clear_both"></div>
                                    </div>
                                  </div>';
                        }
                      @endphp
                      <a href="#" >
                        <i class="fas fa-times-circle attachment-delete pl-5 ml-5 f-20 color_red delete-icon expense-delete-icon" data-attachment="<?= $files[0]->id; ?>" aria-hidden="true"></i><br>
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
                              <span class="f-12 previewer-file-name">{{ strlen($fileName) > 15 ? substr_replace($fileName, "..", 15) : $fileName }}
                              </span>
                            </div>
                          </div>
                        </div>
                      </a>
                      <?= $div ?>
                    </div>
                  @endif
                  <div class="col-sm-9 px-0 m-l-15 mt-2">
                    <button class="btn btn-primary custom-btn-small" type="submit" id="btnSubmit"><i class="comment_spinner spinner fa fa-spinner fa-spin custom-btn-small display_none"></i><span id="spinnerText">{{ __('Update') }} </span></button>   
                    <a href="{{ url('expense/list') }}" class="btn btn-info custom-btn-small">{{ __('Cancel') }}</a>
                  </div>
                </div>
              </div>
            </div>
          </form>
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
          <button type="button" id="theModalSubmitBtn" data-task="" class="btn btn-danger custom-btn-small">{{ __('Update') }}</button>
          <span class="ajax-loading"></span>
        </div>
    </div>
  </div>
</div>

@endsection

@section('js')
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/dist/js/html5lightbox/html5lightbox.js?v=1.0') }}"></script>
<script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script type="text/javascript">
  "use strict";
  var paymentMethod = '{{ !empty($expenseInfo->paymentMethod->name) ?  $expenseInfo->paymentMethod->name : '' }}';
</script>
<script src="{{ asset('public/dist/js/custom/expense.min.js') }}"></script>
@endsection