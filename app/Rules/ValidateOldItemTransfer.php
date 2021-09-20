<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use DB;

class ValidateOldItemTransfer implements Rule
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
        if(!empty($value)){
            for ($i = 0; $i < count($request->item_id); $i++) { 
                $check = DB::table('stock_moves')
                            ->select(DB::raw('sum(quantity) as total'))
                            ->where(['item_id' => $request->item_id[$i], 'location_id' => $request->source])
                            ->groupBy('location_id')
                            ->first();

                $data = DB::table('stock_moves')
                          ->where(['transaction_type_id' => $request->transfer_id, 'item_id' => $request->item_id[$i], 'transaction_type' => 'STOCKMOVEIN'])
                          ->orderBy('quantity', 'DESC')
                          ->first();
                $maxTransferable = $data->quantity + $check->total ;
                if(validateNumbers($request->item_quantity[$i]) > $maxTransferable) {
                    return false;
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
        return __('Old Item Available');
    }
}
