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
                <select class="form-control js-example-basic-single2" id="tax-type">
                  <option value="exclusive">{{ __('Exclusive') }}</option>
                  <option value="inclusive">{{ __('Inclusive') }}</option>
                </select>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group row">
              <label for="discount-on" class="col-md-4 col-form-label">{{ __('Tax On') }}</label>
              <div class="inv-discount-on col-md-7 p-md-0">
                <select id="discount-on" class="form-control js-example-basic-single2">
                  <option value="before">{{ __('Before Discount') }}</option>
                  <option value="after">{{ __('After Discount') }}</option>
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
                    <input type="checkbox" id="invItemTax" checked="checked">
                    <label for="invItemTax" class="cr"></label>
                  </div>
                  <label>{{ __('Tax') }}</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <div class="switch switch-primary d-inline">
                    <input type="checkbox" id="invItemDetails" checked="checked">
                    <label for="invItemDetails" class="cr"></label>
                  </div>
                  <label>{{ __('Details description') }}</label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <div class="switch switch-primary d-inline">
                    <input type="checkbox" id="invItemDiscount">
                    <label for="invItemDiscount" class="cr"></label>
                  </div>
                  <label>{{ __('Item discount') }}</label>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <div class="switch switch-primary d-inline">
                    <input type="checkbox" id="invItemHSN">
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
                    <input type="checkbox" id="invOtherDiscount" checked="checked">
                    <label for="invOtherDiscount" class="cr"></label>
                  </div>
                  <label>{{ __('Discount') }}</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <div class="switch switch-primary d-inline">
                    <input type="checkbox" id="invShipping" checked="checked">
                    <label for="invShipping" class="cr"></label>
                  </div>
                  <label>{{ __('Shipping') }}</label>
                </div>
              </div>
              <div class="col-md-4 p-md-0">
                <div class="form-group">
                  <div class="switch switch-primary d-inline">
                    <input type="checkbox" id="invCustomAmount">
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