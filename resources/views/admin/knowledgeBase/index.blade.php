@extends('layouts.list-layout')
@section('list-title')
    <h5>{{ __('Knowledge Base') }}</h5>
@endsection
@section('list-add-button')
    @if(Helpers::has_permission(Auth::user()->id, 'add_knowledge_base'))
        <a href="{{ url('knowledge-base/create') }}" class="btn btn-outline-primary custom-btn-small"><span class="feather icon-plus"> &nbsp;</span>{{ __('New Knowledge base') }}</a>
    @endif
@endsection
@section('list-form-content')
    <form class="form-horizontal" enctype='multipart/form-data' action="{{ url('knowledge-base') }}" method="GET" accept-charset="UTF-8" id="knowledge-container-index">
        <input class="form-control" id="startfrom" type="hidden" name="from">
        <input class="form-control" id="endto" type="hidden" name="to">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 m-l-10">
            <div class="row mt-3">
                <div class="col-md-12 col-xl-4 col-lg-5 col-sm-12 col-xs-12 mb-2">

                    <div class="input-group">
                        <button type="button" class="form-control" id="daterange-btn">
              <span class="float-left">
                <i class="fa fa-calendar"></i>
                <span>{{ __('Pick a date range') }}</span>
              </span>
                            <i class="fa fa-caret-down float-right pt-1"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-12 col-xl-2 col-lg-2 col-sm-12 col-xs-12 mb-2">
                    <select class="form-control select2" name="group_id" id="group_id">
                        <option value="" >{{ __('All Groups') }}</option>
                        @foreach($groups as $value)
                         <option value="{{$value->id}}" {{ $value->id == $allgroups ? ' selected' : '' }}>{{ $value->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 col-xl-2 col-lg-2 col-sm-12 col-xs-12 mb-2">
                    <select class="form-control select2" name="status" id="status">
                        <option value="" >{{ __('All Status') }}</option>
                        <option value="publish" {{ $allstatus == "publish" ? ' selected' : '' }}>{{ __('Publish') }}</option>
                        <option value="draft" {{ $allstatus == "draft" ? ' selected' : '' }}>{{ __('Draft') }}</option>
                    </select>
                </div>
                <div class="col-md-1 col-lg-1 col-sm-12 col-xs-12 mb-2">
                    <button type="submit" name="btn" title="Click to filter" class="btn btn-primary custom-btn-small mt-0 mr-0">{{ __('Go') }}</button>
                </div>
            </div>
        </div>
    </form>
    <!--Filtering Box End -->
    <!-- Top Box-->
@endsection
@section('list-js')
    <script src="{{ asset('public/dist/js/custom/knowledge.min.js') }}"></script>
@endsection
