@extends('errors.layout')
@section('content')
    <div class="row">
        <div class="col-sm-12" id="cus-panel-container">
            <div class="card cardMinWidthCustomer">
                <div class="card-header">
                    <h5>{{ __('Error 500') }}</a></h5>
                </div>
                <div class="card-body text-center mid_error" id="no_shadow_on_card">
                    <div class="error_main mt-3">
                        <h1 class="f-100">{{ __('500') }}</h1>
                        <h2 class="f-60">{{ __('Internal Server Error') }}</h2>
                        <p class="f-18">{{ __('Sorry, but the page you are looking for might have been removed, or unavailable.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection