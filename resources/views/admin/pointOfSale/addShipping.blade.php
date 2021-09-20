<div class="modal-body-content" id="add-shipping-modal">
	<form id="addPosShipping">
		<div class="form-group row">
			<label for="name" class="col-sm-3 col-form-label">{{ __('Name')}}</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" id="name" name="name" value="{{old('name')}}" placeholder="{{ __('Name') }}" readonly>
				<label class="error no-display" id="name-error">{{ __('Name required') }}</label>
			</div>
		</div>
		<div class="form-group row">
			<label for="email" class="col-sm-3 col-form-label">{{ __('Email')  }}</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" id="ship_email" name="ship_email" value="{{old('email')}}" placeholder="{{ __('Email') }}" readonly>
				<label class="error no-display" id="ship_email-error">{{ __('Email required') }}</label>
			</div>
		</div>
		<div class="form-group row">
			<label for="ship_street" class="col-sm-3 col-form-label">{{ __('Street')  }}</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" id="ship_street" name="ship_street" value="{{old('ship_street')}}" placeholder="{{ __('Street') }}" readonly>
				<label class="error no-display" id="ship_street-error">{{ __('Street field required') }}</label>
			</div>
		</div>
		<div class="form-group row">
			<label for="ship_city" class="col-sm-3 col-form-label">{{ __('City')  }}</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" id="ship_city" name="ship_city" value="{{old('ship_city')}}" placeholder="{{ __('City') }}" readonly>
				<label class="error no-display" id="ship_city-error">{{ __('City name required') }}</label>
			</div>
		</div>
		<div class="form-group row">
			<label for="ship_state" class="col-sm-3 col-form-label">{{ __('State')  }}</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" id="ship_state" name="ship_state" value="{{old('ship_state')}}" placeholder="{{ __('State') }}" readonly>
				<label class="error no-display" id="ship_state-error">{{ __('State name required') }}</label>
			</div>
		</div>
		<div class="form-group row">
			<label for="ship_zipCode" class="col-sm-3 col-form-label">{{ __('Zip Code')  }}</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" id="ship_zipCode" name="ship_zipCode" value="{{old('ship_zipCode')}}" placeholder="{{ __('Zip code') }}" readonly>
				<label class="error no-display" id="ship_zipCode-error">{{ __('Zipcode required') }}</label>
			</div>
		</div>
		<div class="form-group row">
			<label for="ship_country_id" class="col-sm-3 col-form-label">{{ __('Country')  }}</label>
			<div class="col-sm-8">
				<select class="form-control" id="ship_country_id" name="ship_country_id">
					@foreach ($countries as $country)
						<option value="{{ $country->id }}" readonly>{{ $country->name }}</option>
					@endforeach
				</select>
			</div>
		</div>
	</form>
</div>
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
{!! translateValidationMessages() !!}
<script src="{{ asset('public/dist/js/custom/pos-modals.min.js') }}"></script>