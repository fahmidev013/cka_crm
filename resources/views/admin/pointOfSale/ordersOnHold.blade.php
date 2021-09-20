<div class="modal-body-content" id="order-hold-modal">
	<div class="row">
		<div class="col-sm-3">
			<div class="card">
			  <div class="card-body">
	        <div class="hold_item_list">
						<form action="">
							<div class="form-group">
								<input type="text" class="form-control" id="order_hold_search" placeholder="{{ __('Search') }}">
							</div>
						</form>
						<div class="hold_items">
							@foreach($orders as $order)
								<div class="single_hold_items row" data-order_id="{{ $order->id }}">
									<div class="single_hold_item">
										@if(isset($order->pos_order_title))
											{{ $order->pos_order_title }}
										@else
											{{ __('Unknown') }}
										@endif
									</div>
									<div class="delete_hold_item">
										<span class="feather icon-trash-2 text-danger" title="{{ __('Delete') }}"></span>
									</div>
								</div>
							@endforeach
						</div>
					</div>
			  </div>
			</div>
		</div>
		<div class="col-sm-9 order_details">
			<div class="card">
			    <div class="card-body">
					<div class="row">
						<div class="col-sm-12">
							<h4 class="text-center" id="hold_name">{{ __('Order Details')  }}</h4>
							<div class="row hold_order_details mx-2">
								<div class="col-sm-6">
									<p>{{ __('Author')  }}: <b><span class="author"></span></b></p>
									<p>{{ __('Customer')  }}: <b><span class="customer_name"></span></b></p>
								</div>
								<div class="col-sm-6">
									<div class="float-right mr-1">
										<p>{{ __('Date')  }}: <b><span class="orderDate"></span></b></p>
										<p>{{ __('Order No')  }}: <b><span class="orderNo"></span></b></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row mt-3">
						<div class="col-sm-12">
							<div class="hold_order_details">
								<table class="table">
									<thead>
										<tr class="theme-bg2 text-white">
											<th width="60%">{{ __('Item Name')  }}</th>
											<th width="10%">{{ __('Unit Price')  }}</th>
											<th width="10%">{{ __('Quantity')  }}</th>
											<th class="text-right">{{ __('Total')  }}</th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot></tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="{{ asset('public/dist/js/custom/pos-modals.min.js') }}"></script>
