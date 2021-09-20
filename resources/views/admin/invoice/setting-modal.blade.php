<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('Add custom fields') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row" id="myModal-body">
          <div class="col-md-4">
            <div class="form-group row">
              <label class="col-md-4 col-form-label">{{ __('Tax Type') }}</label>
              <div class="inv-tax-type col-md-6 p-md-0">
                <select class="form-control js-example-basic-single" id="tax-type">
                  <option value="exclusive" {{ $invoiceData->tax_type == 'exclusive'  ? 'selected' : '' }} >{{ __('Exclusive') }}</option>
                  <option value="inclusive" {{ $invoiceData->tax_type == 'inclusive'  ? 'selected' : '' }} >{{ __('Inclusive') }}</option>
                </select>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group row">
              <label for="discount-on" class="col-md-4 col-form-label">{{ __('Tax On') }}</label>
              <div class="inv-discount-on col-md-7 p-md-0">
                <select id="discount-on" class="form-control js-example-basic-single">
                  <option value="before" {{ $invoiceData->discount_on == 'before' ? 'selected' : '' }} >{{ __('Before Discount') }}</option>
                  <option value="after" {{ $invoiceData->discount_on == 'after'  ? 'selected' : '' }} >{{ __('After Discount') }}</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <h5>{{ __('Add/Remove details') }}</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                  <div class="switch switch-primary d-inline">
                    <input type="checkbox" id="invItemTax" {{ $invoiceData->has_tax == 1 ? 'checked' : '' }}>
                    <label for="invItemTax" class="cr"></label>
                  </div>
                  <label>{{ __('Tax') }}</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <div class="switch switch-primary d-inline">
                    <input type="checkbox" id="invItemDetails" {{ $invoiceData->has_description == 1 ? 'checked' : '' }}>
                    <label for="invItemDetails" class="cr"></label>
                  </div>
                  <label>{{ __('Details description') }}</label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <div class="switch switch-primary d-inline">
                    <input type="checkbox" id="invItemDiscount" {{ $invoiceData->has_item_discount == 1 ? 'checked' : '' }}>
                    <label for="invItemDiscount" class="cr"></label>
                  </div>
                  <label>{{ __('Item discount') }}</label>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <div class="switch switch-primary d-inline">
                    <input type="checkbox" id="invItemHSN" {{ $invoiceData->has_hsn == 1 ? 'checked' : '' }} >
                    <label for="invItemHSN" class="cr"></label>
                  </div>
                  <label>{{ __('HSN')  }}</label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <h5>{{ __('Add to sub total') }}</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <div class="switch switch-primary d-inline">
                    <input type="checkbox" id="invOtherDiscount" {{ $invoiceData->has_other_discount == 1 ? 'checked' : '' }} >
                    <label for="invOtherDiscount" class="cr"></label>
                  </div>
                  <label>{{ __('Discount') }}</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <div class="switch switch-primary d-inline">
                    <input type="checkbox" id="invShipping" {{ $invoiceData->has_shipping_charge == 1 ? 'checked' : '' }}>
                    <label for="invShipping" class="cr"></label>
                  </div>
                  <label>{{ __('Shipping') }}</label>
                </div>
              </div>
              <div class="col-md-4 p-md-0">
                <div class="form-group">
                  <div class="switch switch-primary d-inline">
                    <input type="checkbox" id="invCustomAmount" {{ $invoiceData->has_custom_charge == 1 ? 'checked' : '' }} >
                    <label for="invCustomAmount" class="cr"></label>
                  </div>
                  <label>{{ __('Custom amount') }}</label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>