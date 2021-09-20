@extends('errors.layout')
@section('content')
    <div class="row">
        <div class="col-sm-12" id="cus-panel-container">
            <div class="card cardMinWidthCustomer">
                <div class="card-header">
                    <h5>{{ __('Error 403') }}</a></h5>
                </div>
                <div class="card-body text-center mid_error" id="no_shadow_on_card">
                    <div class="error_main mt-3">
                        <h1 class="f-100">{{ __('403') }}</h1>
                        <h2 class="f-60">{{ __('Forbidden') }}</h2>
                        <p class="f-18">{{ __('Access to this resource is denied.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection