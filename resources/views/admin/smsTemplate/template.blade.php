@extends('layouts.app')
@section('css')
 @endsection
 @section('content')
  <div class="col-sm-12" id="smsTemplate-settings-container">
   <div class="row">
     <div class="col-sm-3">
       @include('layouts.includes.sms_menu')
     </div>
     <div class="col-sm-9">
       <div class="card card-info">
         <div class="card-header">
           <h3 class="card-title f-18">
            @if ($tempId == 4)
              {{ __('Invoice') }}
            @elseif ($tempId == 5) 
              {{ __('Quotation') }}
            @elseif ($tempId == 19)
              {{ __('POS Invoice') }}
             @endif
           </h3>
           <div class="card-header-right inline-block">
             <a href="#collapseExample" class="btn btn-outline-primary custom-btn-small" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="true" aria-controls="collapseExample">{{ __('Available Variables') }}</a>
           </div>
         </div>
         <div class="card-body">
           <div class="collapse" id="collapseExample">
            <div>
              @if($tempId == 4 || $tempId == 19)
               <div class="row">
                 <div class="col-6 variable">
                   <div>
                     <span>{{ __('Invoice Referance Number') }}: <span class="copyButton">{invoice_reference_no}</span></span>
                   </div>
                   <div>
                     <span>: <span class="copyButton">{order_reference_no}</span></span>
                   </div>
                   <div>
                     <span>{{ __('Payment ID') }}: <span class="copyButton">{payment_id}</span></span>
                   </div>
                   <div>
                     <span>{{ __('Customer Name') }}: <span class="copyButton">{customer_name}</span></span>
                   </div>
                   <div>
                     <span>{{ __('Payment Date') }}: <span class="copyButton">{payment_date}</span></span>
                   </div>
                   <div>
                     <span>{{ __('Payment Method') }}: <span class="copyButton">{payment_method}</span></span>
                   </div>
                   <div>
                     <span>{{ __('Total Amount') }}: <span class="copyButton">{total_amount}</span></span>
                   </div>
                 </div>
                 <div class="col-6 variable">
                   <div>
                     <span>{{ __('Due Date') }}: <span class="copyButton">{due_date}</span></span>
                   </div>
                   <div>
                     <span>{{ __('Order Date') }}: <span class="copyButton">{order_date}</span></span>
                   </div>
                   <div>
                     <span>{{ __('Billing City') }}: <span class="copyButton">{billing_city}</span></span>
                   </div>
                   <div>
                     <span>{{ __('Billing State') }}: <span class="copyButton">{billing_state}</span></span>
                   </div>
                   <div>
                     <span>{{ __('Billing Zip Code') }}: <span class="copyButton">{billing_zip_code}</span></span>
                   </div>
                   <div>
                     <span>{{ __('Billing Country') }}: <span class="copyButton">{billing_country}</span></span>
                   </div>
                   <div>
                     <span>{{ __('Company Name') }}: <span class="copyButton">{company_name}</span></span>
                   </div>
                 </div>
               </div>
              @elseif($tempId == 5)
               <div class="row">
                 <div class="col-6 variable">
                   <div>
                     <span>{{ __('Customer Name') }}: <span class="copyButton">{customer_name}</span></span>
                   </div>
                   <div>
                     <span>{{ __('Company Name') }}: <span class="copyButton">{company_name}</span></span>
                   </div>
                   <div>
                     <span>{{ __('Order Referance Name') }}: <span class="copyButton">{order_reference_no}</span></span>
                   </div>
                   <div>
                     <span>{{ __('Billing Street') }}: <span class="copyButton">{billing_street}</span></span>
                   </div>
                   <div>
                     <span>{{ __('Billing City') }}: <span class="copyButton">{billing_city}</span></span>
                   </div>
                   <div>
                     <span>{{ __('Billing State') }}: <span class="copyButton">{billing_state}</span></span>
                   </div>
                 </div>
                 <div class="col-6 variable">
                   <div>
                     <span>{{ __('Billing Zip Code') }}: <span class="copyButton">{billing_zip_code}</span></span>
                   </div>
                   <div>
                     <span>{{ __('Billing Country') }}: <span class="copyButton">{billing_country}</span></span>
                   </div>
                   <div>
                     <span>{{ __('Billing State') }}: <span class="copyButton">{order_summery}</span></span>
                   </div>
                   <div>
                     <span>{{ __('Order Summary') }}: <span class="copyButton">{quotation_short_url}</span></span>
                   </div>
                   <div>
                     <span>{{ __('Currency') }}: <span class="copyButton">{currency}</span></span>
                   </div>
                   <div>
                     <span>{{ __('Total Amount') }}: <span class="copyButton">{total_amount}</span></span>
                   </div>
                   <div>
                     <span>{{ __('Order Date') }}: <span class="copyButton">{order_date}</span></span>
                   </div>
                 </div>
               </div>
              @endif
            </div>
            <hr>
           </div>
           <form action='{{url("customer-sms-temp/$tempId")}}' method="post" id="myform">
             {!! csrf_field() !!}
             <div class="form-group">
               <input type="hidden" name="en[id]" value="1">
             </div>              
             <div class="form-group">
               <label for="exampleInputEmail1">{{ __('Message') }}</label>
               <textarea id="compose-textarea" name="en[body]" class="form-control editor h-300">{{$temp_Data[0]->body}}</textarea>
             </div>
             <div class="accordion" id="accordion">
              

               @php $i = 1 @endphp
                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                @foreach($languages as $key => $language)
                
                <!-- Escape the english details -->
                @php if($language->short_name == 'en'){continue;} @endphp 
               
               <div class="panel card card-primary">
                 <div class="card-header with-border pl-0 pt-0">
                   <h4 class="card-title h-20">
                     <a data-toggle="collapse" class="btn btn-link text-btn p-b-10 collapsed" data-parent="#accordion" onclick="card('{{$language->short_name }}')"  href="#collapse{{ $language->short_name }}" aria-expanded="false">
                       {{ $language->name }}
                     </a>    

                   </h4>
                 </div>
                 <div id="collapse{{ $language->short_name }}" class="panel-collapse collapse p-l-30" aria-expanded="false">
                   <div class="card-body">
                    
                      <div class="form-group">
                       <input type="hidden" name="{{ $language->short_name }}[id]" value="{{$language->id}}">
                      </div>              
                     <div class="form-group ml-4">
                       <label for="exampleInputEmail1">{{ __('Message') }}</label>
                       <textarea id="compose-textarea" name="{{ $language->short_name }}[body]" class="form-control editor h-300">{{isset($temp_Data[$key]->body)?$temp_Data[$key]->body:'Body'}}</textarea>
                     </div>
                   </div>
                 </div>
               </div>
                @php $i++ @endphp
               @endforeach
             </div>            
           </div>
           <div class="card-footer">
             <div class="float-left">
               <button type="submit" class="btn btn-primary custom-btn-small">{{ __('Submit') }}</button>
             </div>              
           </div>
         </form>
       </div>
     </div>
   </div>
 </div>
@endsection
@section('js')
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/finance.min.js') }}"></script>
@endsection
