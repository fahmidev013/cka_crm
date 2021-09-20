<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use DB;

class ValidateNewItemTransfer implements Rule
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
            if(isset($request->new_item_id)){
                for ($i = 0; $i < count($request->new_item_id); $i++) { 
                    $check = DB::table('stock_moves')
                                ->select(DB::raw('sum(quantity) as total'))
                                ->where(['item_id' => $request->new_item_id[$i], 'location_id' => $request->source])
                                ->groupBy('location_id')
                                ->first();
                    $currentQty = validateNumbers($request->new_item_quantity[$i]);

                    $data = DB::table('stock_moves')
                          ->where(['transaction_type_id' => $request->transfer_id, 'item_id' => $request->new_item_id[$i], 'transaction_type' => 'STOCKMOVEIN'])
                          ->first();
                    $currentTotal = isset($check->total) ? $check->total + (!empty($data->quantity) ? $data->quantity : 0) : 0;
                    if($currentQty > $currentTotal) {
                        return false;
                    }
                }
                return true;
            } elseif (isset($request->id)) {
                for ($i = 0; $i < count($request->id); $i++) { 
                    $check = DB::table('stock_moves')
                                ->select(DB::raw('sum(quantity) as total'))
                                ->where(['item_id' => $request->id[$i], 'location_id' => $request->source])
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
        return __('Some newly added products do not have sufficient quantity');
    }
}
