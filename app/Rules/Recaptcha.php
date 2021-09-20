<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Recaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($captcha = null)
    {
        $this->captcha = $captcha;
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
        if (isset($this->captcha->site_verify_url) && isset($this->captcha->secret_key)) {
            $verifyResponse = file_get_contents($this->captcha->site_verify_url . '?secret='. $this->captcha->secret_key .'&response='. $value);

            $responseData = json_decode($verifyResponse);

            // Captcha checks
            return $responseData->success === true ? true : false;
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
        return __('Please enter correct captcha.');
    }
}
