<div class="modal-body-content" id="settingsModalBody">
	{{-- Discount Modal --}}
	<div class="form-group row">
		<label for="name" class="col-sm-3 col-form-label">{{ __('Tax On') }}</label>
		<div class="col-sm-9">
			<select class="form-control" name="discount_on" id="discount_on">
				<option value="before">{{ __('Before Discount') }}</option>
				<option value="after">{{ __('After Discount') }}</option>
			</select>
		</div>
	</div>
	<div class="form-group row">
		<label for="name" class="col-sm-3 col-form-label">{{ __('Tax Type')  }}</label>
		<div class="col-sm-9">
			<select class="form-control" name="tax_type" id="tax_type">
				<option value="exclusive">{{ __('Exclusive') }}</option>
				<option value="inclusive">{{ __('Inclusive') }}</option>
			</select>
		</div>
	</div>
	<script src="{{ asset('public/dist/js/custom/pos-modals.min.js') }}"></script>
</div>
