<div class="modal-body-content" id="put-order-on-hold">
	<div class="form-group row">
		<label for="order_title" class="col-sm-4 col-form-label">{{ __('Order Title')  }}</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" id="order_title" placeholder="{{ __('Order Title')  }}">
		</div>
	</div>
	<div class="form-group row">
		<label for="orderAmount" class="col-sm-4 col-form-label">{{ __('Order Amount')  }}</label>
		<div class="col-sm-4">
			<input type="text" class="form-control order_amount" readonly>
		</div>
	</div>
</div>
<script src="{{ asset('public/dist/js/custom/pos-modals.min.js') }}"></script>