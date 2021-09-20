@extends('layouts.app')

@section('css')
{{-- select2 css --}}
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css')}}">
{{-- DataTable --}}
<link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
{{--Lightbox--}}
<link rel="stylesheet" href="{{asset('public/dist/plugins/lightbox/css/lightbox.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/dist/css/item.min.css') }}">
@endsection

@section('content')
<!-- Main content -->
<div class="col-sm-12" id="edit-item-container">
  <div class="card user-list">
    <div class="card-header">
      <h5><a href="{{ url('item') }}">{{ __('Items')  }}</a> >> 
        @if (isset($parentItem->name) && !empty($parentItem->name))
          <a href='{{ url("edit-item/variant/" . $parentItem->id)}}'> {{ $parentItem->name . '>>' }} <a>
        @endif
        <a href='{{ url("edit-item/variant/" . $primaryItem->id) }}'> {{ $primaryItem->name }}</a> </h5>
      <div class="card-header-right">
        @if (count($variants) > 0)
          @if ($tab == 'variant' && empty($parentItem)) 
            <a href="{{url('add-variant/'.$itemInfo->id)}}" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{  __('Add Variant')  }}</a>
          @endif
        @endif
        
      </div>
    </div>
    <div class="card-block">
      <div class="form-tabs">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link {{ $tab != 'item-info' ? 'active':'no-display' }} text-uppercase" data-toggle="tab" href="#variant" role="tab" aria-controls="variant" aria-expanded="false">{{  __('Variant')  }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ $tab == 'item-info' ? 'active':'no-display' }}  text-uppercase" data-toggle="tab" href="#setting" role="tab" aria-controls="setting" aria-expanded="false">{{ __('General Settings')}}</a>
          </li>
          <li class="nav-item display_none" id="transactions-tab">
            <a class="nav-link {{ $tab == 'item-info' ? '':'no-display' }} text-uppercase" data-toggle="tab" href="#transaction" role="tab" aria-controls="transaction" aria-expanded="false">{{  __('Transactions')  }}</a>
          </li>
          <li class="nav-item display_none" id="status-tab">
            <a class="nav-link {{ $tab == 'item-info' ? '':'no-display' }} text-uppercase" data-toggle="tab" href="#status" role="tab" aria-controls="status" aria-expanded="false">{{  __('Status')  }}</a>
          </li>
          <li class="nav-item"></li>
        </ul>
      </div>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show {{ $tab == 'variant' ? 'active' : '' }}" id="variant" role="tabpanel" aria-labelledby="variant-tab">
              @if(count($variants) == 0)
                <div class="text-center">{{ __('No variant available yet') }}!
                  <a href="{{url('add-variant/'.$itemInfo->id)}}" class="btn btn-default custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('Enter first one') }}</a>
                </div>
              @else
                <div id="col-md-12 col-sm-12">
                  {!! $dataTable->table(['class' => 'table table-striped table-hover dt-responsive', 'width' => '100%', 'id' => 'variant-list-info', 'cellspacing' => '0']) !!}
                </div>
              @endif
          </div>
          <div class="tab-pane fade show {{ $tab == 'item-info' ? 'active' : '' }}" id="setting" role="tabpanel" aria-labelledby="setting-tab">
            <form action="{{ url('update-item-info') }}" method="POST" id="itemEditForm" class="form-horizontal" enctype="multipart/form-data">
              <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
            <div class="row">
              <div class="col-md-8">
                <input type="hidden" value="{{$itemInfo->id}}" name="id">
                <div class="box-body">
                  <div class="form-group row">
                    <label class="col-sm-3 control-label require">{{ __('Item ID')  }}</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="stock_id" value="{{$itemInfo->stock_id}}" readonly="true">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 control-label require">{{ __('Item Name')  }}</label>
                    <div class="col-sm-9">
                      <input type="hidden" id="old_item_name" value="{{$itemInfo->name}}">
                      <input type="text" class="form-control" placeholder="{{ __('Item Name')  }}" name="item_name" id="item_name" value="{{$itemInfo->name}}">
                      <label for="item_name" generated="true" id="item-name-error" class="error"></label>
                      <span id="checkMsg" class="text-danger"></span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 control-label">{{__('HSN/SAC') }}</label>
                    <div class="col-sm-9">
                      <input type="text" placeholder="{{__('HSN/SAC') }}" class="form-control" name="hsn" value="{{$itemInfo->hsn}}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 control-label">{{ __('Item Type') }}</label>
                    <div class="col-sm-9">
                      <select name="item_type" class="form-control select2" id="itemType" disabled="">
                        <option {{ $itemInfo->item_type == 'product' ? 'selected' : ''}} value="product"> {{ __('Product') }} </option>
                        <option {{$itemInfo->item_type == 'service' ? 'selected' : ''}} value="service"> {{ __('Service') }} </option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 control-label">{{ __('Category')  }}</label>
                    <div class="col-sm-9">
                      <select class="form-control select2" name="category_id">
                        @foreach ($categoryData as $data)
                          <option value="{{$data->id}}" {{($data->id == $itemInfo->stock_category_id) ? 'selected' : ''}}>  {{$data->name}} </option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 control-label">{{ __('Units')  }}</label>
                    <div class="col-sm-9">
                      <select class="form-control select2" name="units">
                        @foreach ($unitData as $data)
                          <option value="{{$data->id}}" {{($data->id == $itemInfo->item_unit_id) ? 'selected' : ''}}>{{$data->name}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 control-label">{{ __('Tax Type')  }}</label>
                    <div class="col-sm-9">
                      <select class="form-control select2" name="tax_type_id">
                        @foreach ($taxTypes as $taxType)
                          <option value="{{$taxType->id}}" {{($taxType->id == $itemInfo->tax_type_id) ? 'selected' : ''}}>{{$taxType->name}} ({{ formatCurrencyAmount($taxType->tax_rate) }}%)</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 control-label">{{ __('Item Description')  }}</label>
                    <div class="col-sm-9">
                      <textarea class="form-control" placeholder="{{ __('Item Description')  }}" name="description" id="description">{{$itemInfo->description}}</textarea>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 control-label require">{{ __('Purchase Price')  }}</label>
                    <div class="col-sm-9 input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">{{ $currency_symbol }}</span>
                      </div>
                      <input type="text" class="form-control positive-float-number" placeholder="{{ __('Purchase Price')  }}" name="purchase_price" id="purchase_price" value="{{isset($itemInfo->purchase_price) ? $itemInfo->purchase_price : 0}}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 control-label">{{ __('Retail Price')  }}</label>
                    <div class="col-sm-9">
                      <div class="row">
                        <div class="col-sm-12 input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">{{ $currency_symbol }}</span>
                          </div>
                          <input type="text" class="form-control positive-float-number" placeholder="{{ __('Retail Price')  }}" id="retail_price" name="retail_price" value="{{ isset($itemInfo->retail_price) ? $itemInfo->retail_price : 0 }}">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 control-label">{{  __('Status')  }}</label>
                    <div class="col-sm-9">
                      <select class="form-control validation_select select2" name="inactive">
                        <option value="1" {{isset($itemInfo->is_active) && $itemInfo->is_active == 1 ? 'selected' : ""}} > Active </option>
                        <option value="0" {{isset($itemInfo->is_active) && $itemInfo->is_active == 0 ? 'selected' : ""}} > Inactive </option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 control-label">{{ __('Picture')  }}</label>
                    <div class="col-sm-9">
                      <div class="custom-file">
                        <input type="file"  class="custom-file-input" name="item_image" id="validatedCustomFile">
                        <label class="custom-file-label overflow_hidden" for="validatedCustomFile">Upload photo...</label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 control-label"></label>
                    <div class="col-sm-9" id='note_txt_1'>
                      <span class="badge badge-danger">{{ __('Note') }}!</span> {{ __('Allowed File Extensions: jpg, png, gif')  }}
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <div class="col-md-12 m-l-80 pl-small-0 offset-medium-custom-3">
                      @if (!empty($files[0]->file_name) && file_exists("public/uploads/items/".$files[0]->file_name))
                        <a class="cursor_pointer" href='{{ url("public/uploads/items/".$files[0]->file_name) }}'  data-lightbox="image-1" data-title="{{$itemInfo->name}}"><img src='{{ url("public/uploads/items/".$files[0]->file_name) }}' alt="Item Image" class="img-fluid img-thumbnail img-width-minimize-medium" sizes="(max-width: 200px) 100vw, 200px" id="blah_1"></a>
                      @else
                        <img src='{{ url("public/dist/img/default_product.jpg") }}' alt="Item Image" class="img-fluid img-thumbnail img-width-minimize-medium" sizes="(max-width: 200px) 100vw, 200px" id="blah_2">
                      @endif
                  </div>
                </div>
                  <div class="form-group display_none" id="stock_management">
                    <div class="col-md-12 m-l-80 pl-small-0">
                      <div class="card custom-gray-card">
                        <div class="card-header">                          
                          <h6 class="font-weight-bold" id="stock-management">{{ __('Manage Stock Level') }}</h6>
                        </div>
                        <div class="card-block">
                          <label id="rdoStock" class="m-l-5 swtich-label-status"> {{ __('Status') }} </label>
                          <div class="switch switch-primary d-inline ml-3">
                            <input class="minimal" type="checkbox" id="statusManage" value="{{ $itemInfo->is_stock_managed == 1 ? 1 : 0 }}" name="statusManage" {{ $itemInfo->is_stock_managed == 1 ? 'checked':''}} >
                            <label for="statusManage" class="cr"></label>
                          </div>
                      </div>
                    </div>
                    <label id="msgStatus"></label>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div id="fullVariantBlock" class="row m-t-5">
                  @if($checkVariantArray)
                    <div class="col-md-8">
                      <div class="form-group row">
                          <label class="col-sm-11 control-label text-left">
                            <span id="item-attribute">{{ __('Attributes') }}</span>
                          </label>
                      </div>
                      <div id="multiVariantsBlock">
                        @if(! empty($itemInfo->size))
                          <div class="form-group row">
                            <label class="col-sm-3 control-label require">{{ __('Size') }}</label>
                            <div class="col-sm-9">
                              <input value="{{$itemInfo->size}}" type="text" name="size" id="sizeInput" class="form-control">
                            </div>
                          </div>
                        @endif
                        @if(! empty($itemInfo->color))
                          <div class="form-group row">
                            <label class="col-sm-3 control-label require">{{ __('Color') }}</label>
                            <div class="col-sm-9">
                              <input value="{{$itemInfo->color}}" type="text" name="color" id="colorInput" class="form-control">
                            </div>
                          </div>
                        @endif
                        @if(! empty($itemInfo->weight))
                          <div class="form-group row">
                            <label class="col-sm-3 control-label require">{{ __('Weight') }}</label>
                            <div class="col-sm-9">
                              <div class="row">                                
                                <div class="col-sm-6 margin-bottom-2">
                                  <input value="{{$itemInfo->weight}}" type="text" name="weight" id="weightInput" class="form-control positive-float-number">
                                </div>
                                <div class="col-sm-6">
                                  <select name="weight_unit" class="form-control select2">
                                    @foreach($unitData as $unit)
                                      <option {{$unit->id == $itemInfo->weight_unit_id ? 'selected' : ''}} value="{{$unit->id}}">{{$unit->name}}</option>
                                    @endforeach
                                  </select>
                                </div>
                              </div>
                              <label for="weightInput" generated="true" class="error"></label>
                            </div>
                          </div>
                        @endif
                        <div id="customVariantBlock">
                          @php
                            $i = 1;
                          @endphp
                          @foreach($customVariants as $cusVariant)
                            <div class="form-group row" id="rowid-{{$cusVariant->id}}">
                              <div class="col-sm-3">
                                <input type="hidden" name="custom_variant_id[]" value="{{$cusVariant->id}}">
                                <input type="hidden" name="variant_title[]" value="{{$cusVariant->variant_title}}">
                                <label class="control-label require">{{$cusVariant->variant_title}}</label>
                              </div>
                              <div class="col-sm-9 text_box">
                                <input type="text" data-rel="{{$cusVariant->id}}" data-text="extra-variant-value" value="{{$cusVariant->variant_value}}" name="variant_value[]" class="form-control custom-variant-value" id="variant-value-{{$i}}">
                                <span id="extra-variant-value-{{ $i }}" class="validationMsg"></span>
                              </div>
                            </div>
                            @php $i++; @endphp
                          @endforeach
                        </div>
                      </div>
                    </div>
                  @endif
                </div>
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box-footer -->
            <div class="col-sm-8 px-0 mobile-margin">
              <button class="btn btn-primary custom-btn-small" type="submit" id="submit">{{ __('Update')  }}</button>   
              <?php 
                $parentId = isset($parentItem->name) && !empty($parentItem->name) ? $parentItem->id : $primaryItem->id;
              ?>
              <a href="javascript: window.history.go(-1)" class="btn btn-danger custom-btn-small" >{{  __('Cancel')  }}</a>
            </div>
          </div>
          <div class="tab-pane fade show {{ $tab == 'transaction-info' ? 'active' : '' }}" id="transaction" role="tab-pane" aria-lanelledby="transaction-tab">
            <div class="row">
              <div class="col-md-12">
                @if(count($transations)>0)
                <div class="table-responsive">
                  <table id="transaction-variant-list-info" class="table table-bordered table-hover table-striped dt-responsive" width="100%">
                    <thead>
                      <tr>
                        <th class="text-center">{{ __('Transaction Type') }}</th>
                        <th class="text-center">{{ __('Date') }}</th>
                        <th class="text-center">{{ __('Location') }}</th>
                        <th class="text-center">{{ __('Quantity In') }}</th>
                        <th class="text-center">{{ __('Quantity Out') }}</th>
                        <th class="text-center">{{ __('Quantity On Hand') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $sum = [];
                      $StockIn = 0;
                      $StockOut = 0;
                      ?>
                      @foreach($transations as $result)
                      <?php if (!array_key_exists($result->location_id, $sum)) {
                        $sum[$result->location_id] = 0;
                      } ?>
                        <tr>
                          <td align="center">
                            @if($result->transaction_type == 'PURCHINVOICE')
                              <a href="{{URL::to('/purchase/view-purchase-details/'.$result->transaction_type_id)}}">{{ __('Purchase') }}</a>
                            @elseif($result->transaction_type == 'SALESINVOICE')
                                <a href="{{URL::to('/invoice/view-detail-invoice/'.$result->transaction_type_id)}}">{{ __('Sale') }}</a>
                            @elseif($result->transaction_type == 'STOCKMOVEIN')
                              <a href="{{URL::to('/stock_transfer/view-details/'.$result->transaction_type_id)}}">{{ __('Transfer') }}</a>
                            @elseif($result->transaction_type == 'POSINVOICE')
                              <a href="{{URL::to('/invoice/view-detail-invoice/'.$result->transaction_type_id)}}">{{ __('Pos Invoice') }}</a>
                            @elseif($result->transaction_type == 'STOCKMOVEOUT')
                              <a href="{{URL::to('/stock_transfer/view-details/'.$result->transaction_type_id)}}">{{ __('Transfer') }}</a>
                            @elseif($result->transaction_type == 'INITIALSTOCKIN')
                              <a href="#">Initial Stock</a>
                            @elseif($result->transaction_type == 'STOCKIN')
                              <a href="{{URL::to('/adjustment/view-details/'.$result->transaction_type_id)}}">{{ __('Adjustment') }}</a>
                            @elseif($result->transaction_type == 'STOCKOUT')
                              <a href="{{URL::to('/adjustment/view-details/'.$result->transaction_type_id)}}">{{ __('Adjustment') }}</a>
                            @endif
                          </td>
                          <td align="center">{{formatDate($result->transaction_date)}}</td>
                          <td align="center">{{$result->location->name}}</td>
                          <td align="center">
                            @if($result->quantity >0 )
                              {{formatCurrencyAmount($result->quantity)}}
                              <?php
                                $StockIn += $result->quantity;
                              ?>
                            @else
                                -
                            @endif
                          </td>
                          <td align="center">
                            @if($result->quantity <0 )
                              {{formatCurrencyAmount(str_ireplace('-','',$result->quantity))}}
                              <?php
                                $StockOut += $result->quantity;
                              ?>
                            @else
                              -
                            @endif
                          </td>
                          <td align="center">{{formatCurrencyAmount($sum[$result->location_id] += $result->quantity)}}</td>
                        </tr>
                      @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="3" align="right">{{ __('Total') }}</td>
                        <td align="center">{{formatCurrencyAmount($StockIn)}}</td>
                        <td align="center">{{formatCurrencyAmount(str_ireplace('-','',$StockOut))}}</td>
                        <td align="center">{{formatCurrencyAmount(floatval($StockIn) + floatval($StockOut))}}</td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                @else
                  <br>
                  {{ __('Transaction not available') }}
                @endif
              </div>
            </div>
          </div>
          <div class="tab-pane fade show {{ $tab == 'status-info' ? 'active' : '' }}" id="status" area-labelledby="status-tab">
            <div class="row">
              <div class="col-md-12">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>{{ __('Location') }}</th>
                      <th>{{ __('Available(Qty)') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if(!empty($locData))
                      <?php
                      $sum = 0;
                      ?>
                      @foreach ($locData as $data)
                        <tr>
                          <td>{{$data->name}}</td>
                          <td>{{ formatCurrencyAmount( (new App\Models\StockMove)->getItemQtyByLocationName($data->id, $itemInfo->id) )}}</td>
                        </tr>
                        <?php
                          $sum += (new App\Models\StockMove)->getItemQtyByLocationName($data->id, $itemInfo->id);
                        ?>
                      @endforeach
                    @endif
                    <tr>
                      <td align="right">{{ __('Total')  }}</td>
                      <td>{{formatCurrencyAmount($sum)}}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="confirmDeleteLabel"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
            <button type="button" id="confirmDeleteSubmitBtn" data-task="" class="btn btn-primary">{{ __('Submit') }}</button>
            <span class="ajax-loading"></span>
          </div>
        </div>
      </div>
    </div>
  </div>
@include('layouts.includes.message_boxes')
@endsection

@section('js')
<script src="{{ asset('public/dist/js/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
<script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
<script src="{{asset('public/dist/plugins/lightbox/js/lightbox.min.js')}}"></script>
{!! translateValidationMessages() !!}
{!! $dataTable->scripts() !!}
<script type="text/javascript">
    'use strict';
    var currentItemId = "{{ $itemInfo->id }}";
    var itemID =  '{!! $itemInfo->id !!}';
</script>
<script src="{{ asset('public/dist/js/custom/item.min.js') }}"></script>
@endsection