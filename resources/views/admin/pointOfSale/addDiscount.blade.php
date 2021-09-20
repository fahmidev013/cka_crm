<div class="modal-body-content" id="discountModalBody">
	{{-- Discount Modal --}}
	<div class="form-group row">
		<label for="discount_type" class="col-sm-3 col-form-label">{{ __('Discount Type') }}</label>
		<div class="col-sm-9">
			<select class="form-control" name="discount_type" id="discount_type">
				<option value="$">{{ __('Flat') }}</option>
				<option value="%">{{ __('Percentage') }}</option>
			</select>
		</div>
	</div>
	<div class="form-group row">
		<label for="discount_amount" class="col-sm-3 col-form-label">{{ __('Amount') }}</label>
		<div class="col-sm-6 input-group">
			<div class="input-group-prepend">
				<span class="input-group-text discount-type-prepend">{{ $currency_symbol }}</span>
			</div>
			<input type="text" class="form-control positive-float-number" id="discount_amount" name="discount_amount" value="{{old('name')}}" placeholder="{{ __('Amount') }}">
		</div>
	</div>
<script src="{{ asset('public/dist/js/custom/pos-modals.min.js') }}"></script>
</div>
