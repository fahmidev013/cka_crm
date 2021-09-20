<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use DB;

class ValidateNewItemAdjustment implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

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
        if(!empty($value) && $request->type == 'STOCKOUT'){
            if (isset($request->id)) {
                for ($i = 0; $i < count($request->id); $i++) { 
                    $check = DB::table('stock_moves')
                                ->select(DB::raw('sum(quantity) as total'))
                                ->where(['item_id' => $request->id[$i], 'location_id' => $request->location])
                                ->groupBy('location_id')
                                ->first();

                    $currentQty = validateNumbers($request->quantity[$i]);
                    $currentTotal = isset($check->total) ? $check->total : 0;
                    if($currentQty > $currentTotal) {
                        return false;
                    }
                }
                return true;
            }
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
        return __('Some products do not have sufficient quantity to do this adjustment');
    }
}
