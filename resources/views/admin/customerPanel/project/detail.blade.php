@extends('layouts.customer_panel')
@section('css')

<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('public/dist/css/customer-panel.min.css')}}">
@endsection

@section('content')
<div class="col-sm-12" id="customer-panel-details-container">
  <div class="card mb-2">
    <div class="card-header">
      <h5>
        <div class="top-bar-title padding-bottom">{{ __('Project No') }}{{ '#' . $project->id }} : {{ $project->name }} &nbsp;<span class="color_84c529 f-16">{{ $project->projectStatuses->name }}</span></div>
      </h5>
    </div>
    <div class="card-block bg_f4f7fa">
      <div class="row">
        <div class="col-sm-6" id="border-right-1">
          <div class="row">
            <div class="col-md-12">
              <div class="card card-customer">
                <div class="card-block">
                  <div class="row align-items-center justify-content-center">
                    <div class="col">
                      <h4 class="mb-2 f-w-300"><strong>{{ $dayCount }}</strong></h4>
                      <h6 class="text-muted mb-0">{{ $dayTitle }}</h6>
                    </div>
                    <div class="col-auto">
                      <i class="feather icon-clock f-22 text-white theme-bg w-40p h-40"></i>
                    </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
          <div class="row">
            <div class="col-sm-12 ml-0">
              <div class="card statistial-visit">
                <div class="card-block">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="card theme-bg">
                        <div class="card-block text-center">
                        <i class="feather icon-clock f-30 text-white d-block m-b-25"></i>
                        <h4 class="f-w-300 m-b-30 text-white"><strong>{{ $total_logged_time }}</strong></h4>
                        <h5 class="text-white text-center">{{ __('Logged Times') }}</h5>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="progressbar" data-animate="false">
                        <div class="circle" data-percent="{{ !empty($totalTask) ? (100 * $completedTask) / $totalTask : 100 }}">
                          <div></div>
                          <p class="mt-7">{{ __('Project Progress') }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="media">
                    <div class="photo-table">
                        <h5 class="mb-2 f-16 f-w-300 resDate">{{ timeZonegetTime($project->begin_date) }}</h5>
                        <h6 class="text-muted mb-0">{{ __('Start Date') }}</h6>
                    </div>
                    <div class="media-body">
                      <div class="float-right">
                        <h5 class="mb-2 f-16 f-w-300 resDate">{{ isset($project->due_date) ? timeZoneformatDate( $project->due_date ) : '--' }}</h5>
                        <h6 class="text-muted mb-0">{{ __('End Date') }}</h6>
                      </div>
                    </div>
                  </div>
                  <div class="media b-top-ddd">
                    <div class="photo-table">
                      <h6> {{ __('Charge Type') }}</h6>
                    </div>
                    <div class="media-body">
                      <span class="f-16 f-w-400 ml-2 float-right color_black">
                        @if($project->charge_type == 1)
                          {{ __('Fixed Rate') }}
                        @elseif($project->charge_type == 2)
                          {{ __('Project Hours') }}
                        @elseif($project->charge_type == 3) 
                          {{ __('Task Hours') }}  
                        @endif
                      </span>
                    </div>
                  </div>
                  <div class="media  b-top-ddd b-bottom-ddd">
                    <div class="photo-table">
                      <h6> {{ __('Total Cost') }}</h6>
                    </div>
                    <div class="media-body">
                      <span class="f-16 f-w-400 ml-1 float-right color_black">
                        {{ $project->cost }}
                      </span>
                    </div>
                  </div>
                  @if (!empty($checkTicket))
                  <div class="media b-top-ddd b-bottom-ddd">
                      <div class="photo-table">
                        <h6> {{ __('Related To') }}</h6>
                      </div>
                      <div class="media-body">
                          <a href="{{ url('customer-panel/support/list/?customer=' . $project->customer->id . '&project=' . $project->id) }}"> 
                            <span class="f-16 f-w-700 float-right color_1dc4e7">{{ __('Support Tickets') }}</span>
                          </a>
                      </div>
                  </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="card mb-0">
                <div class="card-header">
                  <h5 class="card-header-text">{{ __('Tags') }}</h5>
                </div>
              </div>
              <div class="card-block task-comment p-15">
                <div class="row">
                  @if(!empty($tags->all_tags))
                  @php
                    $tagList = explode(",", $tags->all_tags);
                  @endphp
                  @for($i = 0; $i < count($tagList); $i++)
                  <span class="tag label label-info">{{ $tagList[$i] }}</span>
                    @if($i < count($tagList) - 1)
                    @endif
                  @endfor
                @else
                  <label class="col-form-label text-danger">{{ __('No tags found') }}</label>
                @endif
                </div>
              </div>
            </div>
        </div>
        </div>
        
      <div class="col-sm-6">
        <div class="row">
          <div class="col-sm-12">
              <h5>{{ __('Invoice Overview') }}</h5>
              <hr>
            </div>
          <div class="col-sm-6 col-md-6 col-xl-6 mb-3">
           <div class="text-white">
            <div class="p-10 status-border color_0000ff">
              <span class="f-w-700 f-20">{{ __('Total Amount') }}</span><br>
              @forelse($amounts['amounts'] as $amount)
                <span class="f-16">{{ isset($amount->totalInvoice) ?  formatCurrencyAmount($amount->totalInvoice, $amount->currency->symbol) : 0 }}</span><br>
              @empty
                <span class="f-16">{{ formatCurrencyAmount(0) }}</span><br>
              @endforelse
            </div>
          </div>
          </div>
          <div class="col-sm-6 col-md-6 col-xl-6">
            <div class="p-10 status-border color_4CAF50">
              <span class="f-w-700 f-20">{{ __('Total Paid') }}</span><br>
              @forelse($amounts['amounts'] as $amount)
                <span class="f-16">{{ isset($amount->totalPaid) ? formatCurrencyAmount($amount->totalPaid, $amount->currency->symbol) : 0 }}</span><br>
              @empty
                <span class="f-16">{{ formatCurrencyAmount(0) }}</span><br>
              @endforelse
            </div>
          </div>
          <div class="col-sm-6 col-md-6 col-xl-6 mb-3">
            <div class="text-white">
              <div class="p-10 status-border color_848484">
                <span class="f-w-700 f-20">{{ __('Total Due') }}</span><br>
                @forelse($amounts['amounts'] as $amount)
                  <span class="f-16">{{ formatCurrencyAmount($amount->totalInvoice - $amount->totalPaid, $amount->currency->symbol) }}</span><br>
                @empty
                  <span class="f-16">{{ formatCurrencyAmount(0) }}</span><br>
                @endforelse
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-6 col-xl-6">
            <div class="text-white">
              <div class="p-10 status-border color_ff2d42">
                <span class="f-w-700 f-20">{{ __('Over Due') }}</span><br>
                @if(count($amounts['overDue']) > 0)
                  @foreach($amounts['overDue'] as $index => $overDue)
                    <span class="f-16">{{ isset($overDue) && !empty($overDue) ? formatCurrencyAmount($overDue['totalAmount'] - $overDue['totalPaid'], $overDue->currency->symbol)  : formatCurrencyAmount(0) }}</span><br>
                  @endforeach
                @elseif (!empty($allCurrency))
                  @foreach($allCurrency as $key => $value)
                    <span class="f-16">{{ formatCurrencyAmount(0, $value) }}</span><br>
                  @endforeach
                @else
                  <span class="f-16">{{ formatCurrencyAmount(0) }}</span><br>
                @endif
              </div>
            </div>
          </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-xl-12">
              <div class="card">
                <h5 class="card-header">{{ __('Project Details') }}</h5>
                @php
                  $projectDetailsMore = !empty($project->detail) ? $project->detail : '';
                  $moreButton = false; 
                  if (mb_strlen($projectDetailsMore) > 230) {
                      $projectDetailsLess = mb_substr($projectDetailsMore, 0, 230);
                      $moreButton = true;
                  }
                @endphp
                <div class="card-body">
                  <div class="card-text" id="projectDetailsLess">{!! !empty($projectDetailsLess) ? $projectDetailsLess : $projectDetailsMore !!}</div>
                  @if($moreButton == true)
                  <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample" id="projectDetailsLess">
                    {!! $projectDetailsMore !!}
                  </div>
                  <a href="#!" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" id="show_more">{{ __('SHOW MORE') }}</a>
                  @endif
                </div>
              </div>
            </div>
        </div>
    </div>
    <hr>
    </div>
      <hr>
      @if( count($projectMembers) > 0) 
      <div class="row">
        <div class="col-md-6">
          <div class="card mb-0">
            <div class="card-header">
              <h5 class="card-header-text">{{ __('Project Member') }}</h5>
             
            </div>
          </div>
          <div class="card-block task-comment p-15">
            <div class="row">
              @foreach($projectMembers as $data)
                @if (!empty($data->imageIcon) && file_exists(public_path('uploads/user/' . $data->imageIcon)))
                <img alt=" " src='{{url("public/uploads/user/" . $data->imageIcon)}}' class="user-img img-radius" data-toggle="tooltip" data-placement="top" title="{{ $data->full_name }}">
                @else
                <img alt=" " src='{{url("public/dist/img/avatar.jpg")}}' class="user-img img-radius" data-toggle="tooltip" data-placement="top" title="{{ $data->full_name }}">
                @endif
              @endforeach
            </div>
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>
</div>
@endsection

@section('js')
{{-- Select2 js --}}
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/progress-bar.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/customerpanel.min.js') }}"></script>
@endsection