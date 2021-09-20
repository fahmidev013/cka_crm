<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckQuantity implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $request = app(\Illuminate\Http\Request::class);
        if (isset($request->quantity) || isset($request->new_item_quantity) || isset($request->item_quantity)) {
            if ( isset($request->quantity) ) {
                for ($i = 0; $i < count($request->quantity); $i++) {
                    if (validateNumbers($request->quantity[$i]) <= 0) {
                        return false;
                    }
                }
            }
            if ( isset($request->new_item_quantity) ) {       
                for ($i = 0; $i < count($request->new_item_quantity); $i++) {
                    if (validateNumbers($request->new_item_quantity[$i]) <= 0) {
                        return false;
                    }
                }
            }
            if ( isset($request->item_quantity) ) {            
                for ($i = 0; $i < count($request->item_quantity); $i++) {
                    if (validateNumbers($request->item_quantity[$i]) <= 0) {
                        return false;
                    }
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Item Quantity Should Not be Less Than One!');
    }
}
