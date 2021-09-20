@extends('layouts.app')
@section('css')
    {{-- Select2  --}}
    <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection
@section('content')
    <div class="col-sm-12" id="knowledge-container">
        <div class="card">
            <div class="card-header">
                <h5> <a href="{{url('knowledges')}}">{{ __('Knowledge Base List') }}</a> >> {{ __('New Knowledge Base') }}</h5>
                <div class="card-header-right">

                </div>
            </div>
            <div class="card-body table-border-style">
                <div class="form-tabs">
                    <form action="{{ url('knowledge-base/save') }}" method="post" id="knowledgeAdd" class="form-horizontal">
                        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active text-uppercase" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __('New Information') }}</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="row">
                                    <div class="col-sm-9">
                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label require" for="inputEmail3">{{ __('Subject') }}</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" placeholder="{{ __('Subject') }}"  name="subject" value="{{ old('subject')}}">
                                                <label id="subject-error" class="error display_inline_block" for="subject"></label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label require" for="inputEmail3">{{ __('Group') }}</label>
                                            <div class="col-sm-10">
                                                <select class="form-control select2" name="group_id" id="group_id">
                                                    <option value="">{{ __('Select One') }}</option>
                                                    @foreach ($groups as $value)
                                                        <option value="{{ $value->id }}" >{{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                                <label id="group-error" class="error display_inline_block" for="group_id"></label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">{{ __('Status') }}</label>
                                            <div class="col-sm-10">
                                                <select class="form-control select2" name="status" id="status">
                                                    <option value="publish">{{ __('Publish') }}</option>
                                                    <option value="draft">{{ __('Draft') }}</option>
                                                </select>
                                                <label id="status-error" class="error display_inline_block" for="status"></label>
                                            </div>
                                        </div>
                                        @if($comments == 'enable')
                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label">{{ __('Comments') }}</label>
                                            <div class="col-sm-10">
                                                <select class="form-control select2" name="comments" id="comments">
                                                    <option value="yes">{{ __('Yes') }}</option>
                                                    <option value="no">{{ __('No') }}</option>
                                                </select>
                                                <label id="comments-error" class="error display_inline_block" for="comments"></label>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label" for="inputEmail3">{{ __('Article Details') }}</label>
                                            <div class="col-sm-10">
                                                <textarea class="description form-control" name="description" id="description">{{ old('description') }}</textarea>
                                                <label id="description-error" class="error" for="description"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-9 px-0 pt-2">
                                    <button class="btn btn-primary custom-btn-small" type="submit" id="btnSubmit"><i class="comment_spinner spinner fa fa-spinner fa-spin custom-btn-small display_none"></i><span id="spinnerText">{{ __('Submit') }}</span></button>
                                    <a href="{{ url('knowledge-base') }}" class="btn btn-danger custom-btn-small">{{ __('Cancel') }}</a>
                                </div>
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
    <script src="{{ asset('public/datta-able/plugins/ckeditor/js/ckeditor.js') }}"></script>
    <script src="{{ asset('public/dist/js/moment.min.js') }}"></script>
    <script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
    {!! translateValidationMessages() !!}
    <script src="{{ asset('public/dist/js/custom/knowledge.min.js') }}"></script>
    <script type="text/javascript">
        "use strict";
        var dateFormat = '{!! $date_format_type !!}';
    </script>
@endsection
