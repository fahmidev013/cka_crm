<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use DB;

class ValidateEditItemAdjustment implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $request = app(\Illuminate\Http\Request::class);
        if(!empty($value) && $request->type == 'STOCKOUT'){
            if (isset($request->id)){
                for ($i = 0; $i < count($request->id); $i++) { 
                    $check = DB::table('stock_moves')
                                ->select(DB::raw('sum(quantity) as total'))
                                ->where(['item_id' => $request->item_id[$i], 'location_id' => $request->location])
                                ->groupBy('location_id')
                                ->first();
                    $lastData = DB::table('stock_moves')
                          ->where(['transaction_reference_id' => $request->adjustment_id, 'item_id' => $request->item_id[$i], 'transaction_date'=>'STOCKOUT'])
                          ->orderBy('quantity', 'DESC')
                          ->first();
                    $maxAdjustableQuantity = abs(validateNumbers($lastData->quantity)) + $check->total ;
                    if(validateNumbers($request->item_quantity[$i]) > $maxAdjustableQuantity) {
                        return false;
                    }
                }
            }

            if (isset($request->new_item_id)) {
                for ($i = 0; $i < count($request->new_item_id); $i++) { 
                    $check = DB::table('stock_moves')
                                ->select(DB::raw('sum(quantity) as total'))
                                ->where(['item_id' => $request->new_item_id[$i], 'location_id' => $request->location])
                                ->groupBy('location_id')
                                ->first();
                    if (isset($check)) {
                        if($check->total  < validateNumbers($request->new_item_quantity[$i])) {
                            return false;
                        }
                    } else {
                        return false;
                    }
                }
            }
            return true;
        }
        return false;
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
        //
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
