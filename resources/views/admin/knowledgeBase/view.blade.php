@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5> <a href="{{ url('knowledges') }}"> {{ __('Knowledge Base List') }} </a> >> {{ __('View Information') }}</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-md-{{ count($relatedArticle) > 0 ? '8' : '12' }}">
                <div class="card py-3">
                    <div class="card-body">
                        <h4 class="font-weight-bold text-left color-6c757d">{{ $knowledgeData->subject }}</h4>
                        <hr class="no-mtop">
                        <div class="mtop10 tc-content kb-article-content text-justify">
                            {!! $knowledgeData->description !!}
                        </div>
                        <hr>
                        @if($preferenceComments == 'enable' && $knowledgeData->comments == 'yes')
                            <h4 class="mtop20 color-04a9f5">Did you find this article useful?</h4>
                            <div class="answer_response"></div>
                            <div class="btn-group mtop15 article_useful_buttons" role="group">
                                <div class="fb-comments" data-href="{{ url('knowledge-base/comments/' . $knowledgeData->id) }}" data-width="600" data-numposts="5"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if( count($relatedArticle) > 0 )
            <div class="col-md-4">
                <div class="card py-3">
                    <div class="card-body ">
                        <h4 class="font-weight-bold color-6c757d">{{ __('Related Articles') }}</h4>
                        <hr class="no-mtop">
                        @foreach($relatedArticle as $value)
                        <div class="article-heading article-related-heading text-justify f-14">
                            <a class="color-04a9f5" href="{{ url('knowledge-base/view/' . $value->slug) }}">{{mb_strlen($value->subject) > 30 ? mb_substr($value->subject, 0, 30) . "..." : $value->subject}}</a>
                        </div>
                        <div class="text-justify f-13 mt-2">
                            {!! mb_strlen($value->description) > 120 ? mb_substr($value->description, 0, 120) . "..." : $value->description !!}
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
@endsection
@section('js')
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v11.0" nonce="gNOBF8S2"></script>
@endsection
