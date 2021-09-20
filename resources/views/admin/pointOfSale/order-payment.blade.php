<div class="modal-body-content" id="payment-modal">
	<div class="form-group row">
		<label for="payment_type" class="col-sm-4 control-label require">{{ __('Payment Type')  }} </label>
		<div class="col-sm-7">
			<select class="form-control select2 payment_type" name="payment_type" id="payment_type">
				<option value="cash">{{ __('Cash') }}</option>
				<option value="card">{{ __('Card') }}</option>
				<option value="cheque">{{ __('Cheque') }}</option>
			</select>
		</div>
	</div>
	<input type="hidden" name="payment_date" class="form-control" id="payment_date" placeholder="{{ __('Paid On') }}">
  	<input type="hidden" name="reference_no" class="form-control" id="reference_no" value="{{$reference_no}}" readonly>
	<div class="form-group row card-section">
    	<label for="card_number" class="col-sm-4 control-label require">{{ __('Card Number') }}</label>
	    <div class="col-sm-7">
	       <input type="text" name="card_number" class="form-control" id="card_number">
	    </div>
  	</div>
	<div class="form-group row cheque-section">
	    <label for="cheque_number" class="col-sm-4 control-label require">{{ __('Cheque Number') }}</label>
	    <div class="col-sm-7">
	       <input type="text" name="cheque_number" class="form-control" id="cheque_number">
	    </div>
  	</div>
	<div class="form-group row card-section cheque-section">
	    <label for="additional_data" class="col-sm-4 control-label require">{{ __('Additional Data') }} </label>
	    <div class="col-sm-7">
	       <input type="text" name="additional_data" class="form-control" id="additional_data">
	    </div>
  	</div>
	<div class="form-group row">
		<label for="orderAmount" class="col-sm-4 col-form-label">{{ __('Order Amount')  }}</label>
		<div class="col-sm-7">
			<input type="text" class="form-control order_amount positive-float-number" readonly>
		</div>
	</div>
	<div class="form-group row">
		<label for="orderAmount" class="col-sm-4 col-form-label">{{ __('Paid')  }}</label>
		<div class="col-sm-7">
			<input type="text" class="form-control positive-float-number" id="amount_received">
		</div>
	</div>
	<div class="form-group row">
		<label for="orderAmount" class="col-sm-4 col-form-label">{{ __('Return')  }}</label>
		<div class="col-sm-7">
			<input type="text" class="form-control return_amount positive-float-number" readonly>
		</div>
	</div>
	
	<script src="{{ asset('public/dist/js/custom/pos-modals.min.js') }}"></script>
</div>