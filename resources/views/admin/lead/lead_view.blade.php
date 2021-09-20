@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <div class="col-md-12">
        <div class="card Recent-Users">
            <div class="card-header">
                <h5> <a href="{{ url('lead/list') }}"> {{ __('Lead List') }} </a> >> {{ __('New Information') }}</h5>
                <div class="card-header-right">
                    <form method="get" action={{ url('convert-to-customer/' . $leadData->id) }}
                        {{ $leadData->status_id == 1 ? 'hidden' : '' }}>
                        <button class="btn btn-outline-primary custom-btn-small"><span class="fa fa-user"> &nbsp;</span>
                            {{ __('Convert To Customer') }}
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body px-3 py-3 table-border-style">
                <div class="form-tabs">
                    <input type="hidden" value="{{ $leadData->id }}" name="lead_id" id="lead_id">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active text-uppercase" id="home-tab" data-toggle="tab" href="#home"
                                role="tab" aria-controls="home" aria-selected="true">{{ __('Basic Information') }}</a>
                        </li>
                        @if (!empty($leadData->description))
                            <li class="nav-item">
                                <a class="nav-link text-uppercase" id="profile-tab" data-toggle="tab" href="#profile"
                                    role="tab" aria-controls="profile" aria-selected="false">{{ __('Description') }}</a>
                            </li>
                        @endif
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active pt-4" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="row">
                                <div class="col-md-6 col-xs-12 lead-information-col">

                                    <table class="w-100 table">
                                        <thead>
                                            <tr>
                                                <th colspan="3" class="font-weight-700 text-dark text-18">Lead Information
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="3" class="font-bold text-left">{{ __('Name') }}:&nbsp<br />
                                                    <span class="font-weight-400 text-dark">
                                                      {{ $leadData->first_name }} {{ $leadData->last_name }}
                                                      </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="font-bold text-left">{{ __('Email') }}:&nbsp<br />
                                                    <span class="font-weight-400 text-dark">
                                                        {{ !empty($leadData->email) ? $leadData->email : '' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="font-bold text-left">{{ __('Phone') }}:&nbsp<br />
                                                    <span class="font-weight-400 text-dark">
                                                        {{ !empty($leadData->phone) ? $leadData->phone : '' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="font-bold text-left">{{ __('Website') }}:&nbsp<br />
                                                    <span class="font-weight-400 text-dark">
                                                        <a href="https://{{ !empty($leadData->website) ? $leadData->website : '#' }}" target="_blank" class="color_4293c2">
                                                            {{ !empty($leadData->website) ? $leadData->website : '' }}
                                                        </a>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="font-bold text-left">{{ __('Company') }}:&nbsp<br />
                                                    <span class="font-weight-400 text-dark">
                                                        {{ !empty($leadData->company) ? $leadData->company : '' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="font-bold text-left">{{ __('Street') }}:&nbsp<br />
                                                    <span class="font-weight-400 text-dark">
                                                        {{ !empty($leadData->street) ? $leadData->street : '' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="font-bold text-left">{{ __('City') }}:&nbsp<br />
                                                    <span class="font-weight-400 text-dark">
                                                        {{ !empty($leadData->city) ? $leadData->city : '' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="font-bold text-left">{{ __('State') }}:&nbsp<br />
                                                    <span class="font-weight-400 text-dark">
                                                        {{ !empty($leadData->state) ? $leadData->state : '' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="font-bold text-left">{{ __('Country') }}:&nbsp<br />
                                                    <span class="font-weight-400 text-dark">
                                                        {{ !empty($leadData->country) ? $leadData->country->country : '' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="font-bold text-left">{{ __('Zip Code') }}:&nbsp<br />
                                                    <span class="font-weight-400 text-dark">
                                                        {{ !empty($leadData->zip_code) ? $leadData->zip_code : '' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-6 col-xs-12 lead-information-col">
                                    <table class="w-100 table">
                                        <thead>
                                            <tr>
                                                <th colspan="3" class="font-weight-700 text-dark text-18">General
                                                    Information</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="3" class="font-bold text-left">{{ __('Status') }}:&nbsp<br />
                                                    <span class="font-weight-400 text-dark">
                                                        {{ $leadData->leadStatus->name }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="font-bold text-left">{{ __('Source') }}:&nbsp<br />
                                                    <span class="font-weight-400 text-dark">
                                                        {{ $leadData->leadSource->name }}
                                                    </span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="3" class="font-bold text-left">{{ __('Assigned') }}:&nbsp<br />
                                                    <span class="font-weight-400 text-dark">
                                                        {{ !empty($leadData->user) ? $leadData->user->full_name : '' }}
                                                    </span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="3" class="font-bold text-left">{{ __('Created') }}:&nbsp<br />
                                                    <span class="font-weight-400 text-dark">
                                                        {{ date('M j, Y', strtotime(timeZoneformatDate($leadData->created_at))) }}
                                                        <span class="f-12" class="color_4293c2">
                                                            {{ timeZonegetTime($leadData->created_at) }}
                                                        </span>
                                                    </span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="3" class="font-bold text-left">
                                                    {{ __('Last Contact') }}:&nbsp<br />
                                                    <span class="font-weight-400 text-dark">
                                                        {{ isset($leadData->last_contact) && !empty($leadData->last_contact) && $leadData->last_contact != '0000-00-00 00:00:00' ? date('M j, Y', strtotime(timeZoneformatDate($leadData->last_contact))) : '' }}
                                                    </span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="3" class="font-bold text-left">{{ __('Public') }}:&nbsp<br />
                                                    <span class="font-weight-400 text-dark">
                                                        {{ $leadData->is_public }}
                                                    </span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="3" class="font-bold text-left">{{ __('Tags') }}:&nbsp<br />
                                                  <p class="bold font-medium-xs">
                                                    <div class="w-60p">
                                                      <div class="tags-labels-lead-view">
                                                          @php
                                                            if (!empty($oldTags->all_tags)) {
                                                                $oldTags = $oldTags->all_tags;
                                                            }
                                                          @endphp
                                                          {!!$oldTags !!}
                                                      </div>
                                                    </div>
                                                  </p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @if (!empty($leadData->description))
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="row p-9">
                                            <strong>
                                                <p class="font-bold">{{ __('Description') }}:&nbsp </p>
                                            </strong>
                                            <p class="bold font-medium-xs">
                                                {{ !empty($leadData->description) ? $leadData->description : '' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
