@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('public/dist/css/customer-panel.min.css')}}">
@endsection

@section('content')
<div class="col-sm-12">
  <div class="card mb-2">
    <div class="card-header">
      <h5>
        <div class="top-bar-title padding-bottom">{{ __('Project No') }}{{ '#' . $project->id }} : {{ $project->name }} &nbsp;<span class="color_84c529 f-16">{{ $project->status_name }}</span></div>
      </h5>
    </div>
    <div class="card-body p-0">
      @include('layouts.includes.project_navbar')
    </div>
    <div class="card-block m-t-10 bg_f4f7fa" id="project-overview-container">
      <div class="row" id="removeStrong">
        <div class="col-sm-6">
          <div class="row">
            <div class="col-md-6">
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
            <div class="col-md-6">
              <div class="card card-customer">
                <div class="card-block">
                  <div class="row align-items-center justify-content-center">
                    <div class="col">
                      <h4 class="mb-2 f-w-300"><strong><a href="{{url('project/tasks/' .$project->id)}}?from=&to=&project={{ $project->id }}&assignee=&status=&priority=&btn=">{{ $totalTask.' / '.$completedTask}}</a></strong></h4>
                      <h6 class="text-muted mb-0">{{ __('Total task') }}</h6>
                    </div>
                    <div class="col-auto">
                      <i class="feather icon-list f-22 text-white theme-bg w-40p h-40"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div> 
          </div> 
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
                    <h5 class="mb-2 f-w-300">{{ timeZoneformatDate($project->begin_date) }}</h5>
                    <h6 class="text-muted mb-0">{{ __('Start Date') }}</h6>
                </div>
                <div class="media-body">
                  <div class="float-right">
                    <h5 class="mb-2 f-w-300">{{timeZoneformatDate( $project->due_date )}}</h5>
                    <h6 class="text-muted float-right mb-0">{{ __('End Date') }}</h6>
                  </div>
                </div>
              </div>
              <div class="media">
                <div class="photo-table">
                  <h6> {{ __('Customers') }}</h6>
                </div>
                <div class="media-body">
                  @if (isset($project->first_name) && !empty($project->first_name))
                    <a href="{{ url('/customer/edit'). '/'. $project->customer_id  }}"><span class="f-16 f-w-700 float-right color_1dc4e7">{{ $project->first_name ? $project->first_name.' '.$project->last_name : __('N/A')}}</span></a>
                  @else 
                    <span class="f-16 f-w-700 float-right color_1dc4e7"> {{ __('N/A') }}</span>
                  @endif
                </div>
              </div>
              <div class="media">
                <div class="photo-table">
                  <h6> {{ __('Project Creator') }}</h6>
                </div>
                <div class="media-body">
                  @if (isset($project->userName) && !empty($project->userName))
                    <a href="{{ url('user/team-member-profile'). '/'. $project->user_id  }}"><span class="f-16 f-w-700 float-right color_1dc4e7">{{ $project->userName }}</span></a>
                  @else 
                    <span class="f-16 f-w-700 float-right color_1dc4e7"> {{ __('N/A') }}</span>
                  @endif
                </div>
              </div>
              <div class="media">
                <div class="photo-table">
                  <h6> {{ __('Charge Type') }}</h6>
                </div>
                <div class="media-body">
                  <span class="f-16 f-w-400 float-right color_black">
                    @if($project->charge_type==1)
                      {{ __('Fixed Rate') }}
                    @elseif($project->charge_type==2)
                      {{ __('Project Hours') }}
                    @elseif($project->charge_type==3) 
                      {{ __('Task Hours') }}  
                    @endif
                  </span>
                </div>
              </div>
              <div class="media">
                <div class="photo-table">
                  <h6> {{ __('Total Cost') }}</h6>
                </div>
                <div class="media-body">
                  <span class="f-16 f-w-400 float-right color_black">
                    {{ isset($project->cost) ? $project->cost : __('N/A') }}
                  </span>
                </div>
              </div>
              </div>
          </div>

          @if (!empty($project->detail))
            <div class="card">
              <h5 class="card-header">{{ __('Project Details') }}</h5>
              @php
                $project->detail = str_replace('</p>', '<br></p>', $project->detail);
                if (mb_strlen(strip_tags($project->detail)) > 230) {
                    $projectDetailsLess = mb_substr(strip_tags($project->detail, '<br>'), 0, 230) . '...';
                }
              @endphp
              <div class="card-body">
                <div class="card-text mb-3" id="initial">{!! !empty($projectDetailsLess) ? strip_tags($projectDetailsLess, '<br>') : strip_tags($project->detail, '<br>') !!}</div>
                <div class="collapse mb-3 display_none" id="projectDetailsLess">
                  {!! strip_tags($project->detail, '<br>') !!}
                </div>
                <a href="javascript: void(0)" id="show-more" data-title="show-more">{{ __('SHOW MORE') }}</a>
              </div>
            </div>
          @endif
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
              <div class="text-white">
                <div class="p-10 status-border color_4CAF50">
                  <span class="f-w-700 f-20">{{ __('Total Paid') }}</span><br>
                  @forelse($amounts['amounts'] as $amount)
                    <span class="f-16">{{ isset($amount->totalPaid) ? formatCurrencyAmount($amount->totalPaid, $amount->currency->symbol) : 0 }}</span><br>
                  @empty
                    <span class="f-16">{{ formatCurrencyAmount(0) }}</span><br>
                  @endforelse
                </div>
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
            <div class="col-md-12 p-0">
              <div class="col-md-12">
                <h5>{{ __('Project Activities') }}</h5>
                <hr>
              </div>
              <div class="col-xl-12 col-md-12 m-b-30">
              <ul class="nav nav-tabs" id="myTab1" role="tablist">
                <li class="nav-item">
                  <a class="nav-link show active" id="user-tab" data-toggle="tab" href="#user" role="tab" aria-selected="true">Today</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link show" id="designer-tab" data-toggle="tab" href="#designer" role="tab" aria-selected="false">This Week</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link show" href="{{url('project/activities/'.$project->id)}}">{{ __('All') }}</a>
                </li>
              </ul>
              <div class="tab-content User-Lists" id="myTabContent1">
                <div class="tab-pane fade active show" id="user" role="tabpanel">
                  <div class="activity-project project-activity">
                    @if(!$todayActivities->isEmpty())
                    @foreach($todayActivities as $todayActivity)
                      <div class="media friendlist-box justify-content-center m-b-20">
                        <div class="photo-table">
                          <i class="fa fa-envelope bg-blue"></i>
                        </div>
                        <div class="media-body ml-2">
                          <h6 class="m-0 d-inline">
                            @if(!empty($todayActivity->user_id))
                                <a href="{{ url('user/team-member-profile/'.$todayActivity->user_id) }}">{{ isset($activity->user->full_name) ? $activity->user->full_name : '' }}</a>
                            @else
                                <a href="{{ url('customer/edit/'.$todayActivity->customer_id) }}">{{ isset($activity->customer->name) ? $activity->customer->name : '' }}</a>
                            @endif
                            {!! $todayActivity->description !!}
                          </h6>
                            <span class="float-right d-flex f-10 time">{{ \Carbon::parse($todayActivity->created_at)->diffForHumans() }}</span>
                        </div>
                      </div>
                      @endforeach
                      @else
                        <h5 class="no-record-found">{{ __('No records found') }}</h5>
                      @endif
                  </div>
                </div>
                <div class="tab-pane fade" id="designer" role="tabpanel">
                  <div class="activity-project">
                    @if(!$thisWeekActivities->isEmpty())
                    @foreach($thisWeekActivities as $thisWeekActivity)
                      <div class="media friendlist-box justify-content-center m-b-20">
                        <div class="m-r-10 photo-table">
                          <i class="fa fa-envelope bg-blue"></i>
                        </div>
                        <div class="media-body">
                          <h6 class="m-0 d-inline">
                            @if(!empty($thisWeekActivity->user_id))
                                <a href="{{ url('user/team-member-profile/'.$thisWeekActivity->user_id) }}">{{ isset($thisWeekActivity->user->full_name) ? $thisWeekActivity->user->full_name : '' }}</a>
                            @else
                                <a href="{{ url('customer/edit/'.$thisWeekActivity->customer_id) }}">{{ isset($thisWeekActivity->customer->name) ? $thisWeekActivity->customer->name : '' }}</a>
                            @endif
                            {!! $thisWeekActivity->description !!}
                          </h6>
                            <span class="float-right d-flex f-10 time">{{ \Carbon::parse($thisWeekActivity->created_at)->diffForHumans() }}</span>
                        </div>
                      </div>
                      @endforeach
                      @else
                        <h5 class="no-record-found">{{ __('No records found') }}</h5>
                      @endif
                  </div>
                </div>
              </div>
              </div>
            </div>
          </div>
        </div> 
      </div>
      <hr>
      @if( count($projectMembers) > 0) 
      <div class="row">
        <div class="col-md-6">
          <div class="card mb-0">
            <div class="card-header">
              <h5 class="card-header-text">{{ __('Project Member') }}</h5>
              <span id="memberSetting" data-toggle="modal" data-target="#memberSettingModal" class="float-right cursor_pointer">
                <button class="btn btn-primary" id="btn-add-member"><i class="fas fa-plus f-14 mr-0"></i></button>
              </span>
            </div>
          </div>
          <div class="card-block task-comment p-15">
            <div class="row">
              @foreach($projectMembers as $data)
              <a href="{{ url('user/team-member-profile/'.$data->id) }}" class="mb-3">
                @if (!empty($data->imageIcon) && file_exists(public_path('uploads/user/' . $data->imageIcon)))
                <img alt=" "  src='{{url("public/uploads/user/".$data->imageIcon)}}' class="user-img img-radius" data-toggle="tooltip" data-placement="top" title="{{ $data->full_name }}">
                @else
                <img alt=" " src='{{url("public/dist/img/avatar.jpg")}}' class="user-img img-radius" data-toggle="tooltip" data-placement="top" title="{{ $data->full_name }}">
                @endif
              </a>
              @endforeach
            </div>
          </div>
        </div>
        <div class="col-md-6">
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
              @for($i=0; $i<count($tagList); $i++)
              <span class="tag label label-info">{{$tagList[$i]}}</span>
              @endfor
            @else
              <label class="col-form-label text-danger">{{ __('No tags found') }}</label>
            @endif
            </div>
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>
</div>

<div id="memberSettingModal" class="modal fade" role="dialog display_none">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('Edit Member') }}</h4>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        <div class="modal-body" id="parent">
          <form class="form-horizontal" action='{{url("update/project_member")}}' method="post" id="editMember">
            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token-rm">
            <input type="hidden" name="project_id" value="{{ $project->id }}">
            <div class="form-group row">
              <label class="col-md-3 control-label require">{{ __('Members') }}</label>
              <div class="col-md-8">
                <select name="members[]" multiple="multiple" class="member_class form-control" id="members">
                  @foreach($users as $user)
                    <option value="{{$user->id}}">{{$user->full_name}}</option>
                  @endforeach
                </select>
                <label for="members" id="members_error display_inline_block" generated="true" class="error"></label>
              </div>

            </div>

            <div class="form-group row">
              <div class="col-sm-3"></div>
              <div class="col-sm-6">
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
{{-- Select2 js --}}
<script src="{{ asset('public/dist/js/jquery.validate.min.js')}}"></script>
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{url('public/dist/js/progress-bar.min.js')}}"></script>
<script type="text/javascript">
  'use strict';
  var oldMembers = JSON.parse("{!! $oldMembers !!}");
  var from = "";
  var to = "";
  var projectId = "";
</script>
<script src="{{ asset('public/dist/js/custom/project.min.js') }}"></script>
@endsection