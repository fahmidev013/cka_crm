@extends('layouts.customer_panel')
@section('content')

<div class="col-md-12">
    <div class="row justify-content-center">
        <div class="col-md-8 py-4">
            <h5 class="text-center py-4">{{ __('Search Knowledge Base Articles') }}</h5>
             <form action="{{ url('customer-panel/knowledge-base/search') }}" method="GET" id="kb-search-form" accept-charset="utf-8">
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
                    @foreach($groups as $key => $value)
                        <div class="article_group_wrapper py-1">
                            <h4 class="bold color-6c757d py-2"><i class="fa fa-book fa-fw"></i> <a class="color-04a9f5" href="{{ url('customer-panel/knowledge-base/groups/' . $value['name']) }}">{{ $value['name'] }}</a>
                                <small>{{ isset($groupCount[$value['id']]) ? $groupCount[$value['id']] : 0 }}</small>
                            </h4>
                            <p>{!! mb_strlen($value['description']) > 150 ? mb_substr($value['description'], 0, 150) . "..." : $value['description'] !!}</p>
                        </div>
                        <hr>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
