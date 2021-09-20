@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('public/dist/css/project.min.css')}}">

@endsection

@section('content')
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header">
        <h5>
          <div class="top-bar-title padding-bottom">{{ $project->name }} &nbsp;<span class="color_84c529 f-16">{{ $project->status_name }}</span>
          </div>
        </h5>
        <div class="card-header-right">

        </div>
      </div>
      <div class="card-body p-0">
        @include('layouts.includes.project_navbar')
        <div class="card-block m-t-10">
          @foreach($activities as $activity)
            <div class="row">
              <div class="col-md-10">
                <ul class="timeline">
                  <li class="time-label">
                    <span class="bg-red">
                      {{ formatDate($activity->created_at) }}
                    </span>
                  </li>
                  <li>
                    <i class="fa fa-envelope bg-blue"></i>
                    <div class="timeline-item">
                      <span class="time"><i class="feather icon-clock"></i> {{ timeZonegetTime($activity->created_at) }}</span>
                      @if(!empty($activity->user_id))
                        <h3 class="timeline-header">
                          <a href="{{ url('user/team-member-profile/'.$activity->user_id) }}">{{ isset($activity->user->full_name) ? $activity->user->full_name : '' }}</a>
                        </h3>
                      @else
                        <h3 class="timeline-header">
                          <a href="{{ url('customer/edit/'.$activity->customer_id) }}">{{ isset($activity->customer->name) ? $activity->customer->name : '' }}</a>
                        </h3>
                      @endif
                      <div class="timeline-body">
                        {!!  html_entity_decode($activity->description) !!}
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
              <div class="col-md-11 bottom-border m-l-50 m-b-20"></div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
@endsection
