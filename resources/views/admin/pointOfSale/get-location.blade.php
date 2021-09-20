@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
<div class="col-md-6" id="get-pos-location">
	<div class="card">
		<form method="POST" action="{{url('pos/set-location')}}">
			{{csrf_field()}}
			<div class="card-header">
				<h5>{{ __('Location') }}</h5>	
			</div>
			<div class="card-body p-0">
				<div class="card-block">
					<div class="form-group row mt-2">
						<label for="location" class="col-sm-4 control-label col-form-label">{{ __('Location') }} </label>
					    <div class="col-sm-7 px-0">
					    	<select class="select2 form-control" name="location">
					    		@forelse($locations as $location)
					    			<option value="{{$location->id}}" {{$dflt_location == $location->id ? 'selected':''}} >{{$location->name}}</option>
					    		@empty
					    		@endforelse
					    	</select>
					    </div>
					</div>
				</div>
			</div>
			<div class="card-footer">
				<button class="btn custom-btn-small btn-primary">{{ __('Submit') }}</button>
				<a class="btn custom-btn-small btn-danger" href="{{ url('pos') }}">{{ __('Cancel') }}</a>
			</div>
		</form>
	</div>
</div>
@endsection

@section('js')
<script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/pos-script.min.js') }}"></script>
@endsection