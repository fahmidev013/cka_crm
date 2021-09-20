@extends('layouts.customer_panel')
@section('content')
<div class="col-md-12">
    <div class="row justify-content-center">
        <div class="col-md-8 py-4">
            <h5 class="text-center py-4">{{ __('Search Knowledge Base Articles') }}</h5>
             <form action="{{ url("customer-panel/knowledge-base/search") }}" method="GET" id="kb-search-form" accept-charset="utf-8">
                <div class="form-group has-feedback has-feedback-left">
                    <div class="input-group">
                        <input type="search" name="q" placeholder="{{ __('Have a question?') }}" class="form-control kb-search-input" value="">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary kb-search-button">{{ __('Search') }}</button>
                        </span>
                        <i class="glyphicon glyphicon-search form-control-feedback kb-search-icon"></i>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card py-3">
                <div class="card-body">
                    <h4 class="color-6c757d py-3">Showing search results for: {{ isset($searchData) ? $searchData : '' }}</h4>
                    <hr>
                    @if(count($knowledgeData) > 0)
                        @foreach($knowledgeData as $key => $value)
                            <h4 class="bold mbot25 mtop25 color-6c757d py-2"><i class="fa fa-book fa-fw"></i> {{ isset($group[$key]) ? $group[$key] : '' }}</h4>
                            @foreach($value as $innerKey => $innerValue)
                            <div class="pt-2">
                                <a class="text-primary color-04a9f5 f-14" href="{{ url('customer-panel/knowledge-base/article/' . $innerValue['slug']) }}">{{ mb_strlen($innerValue['subject']) > 120 ? mb_substr($innerValue['subject'], 0, 120) . "..." : $innerValue['subject'] }}</a>
                            </div>

                            <div class="text-muted mtop10 f-13 text-justify">
                                {!! mb_strlen($innerValue['description']) > 220 ? mb_substr($innerValue['description'], 0, 220) . "..." : $innerValue['description'] !!}
                            </div>
                            @endforeach
                        @endforeach
                    @else
                        <div class="col-md-12">
                            <h4 class="bold mbot25 mtop25">{{ __('No knowledge base articles found!') }}</h4>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
