<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use DB;

class UniqueExpenseReference implements Rule
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
        if(!empty($value)){
            $reference = DB::table('transaction_references')->where(['reference_type' => 'EXPENSE', 'code' => $value])->first();
            if(!empty($reference)){
                return false;
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
        return __('Expense Reference already used');
    }
}
