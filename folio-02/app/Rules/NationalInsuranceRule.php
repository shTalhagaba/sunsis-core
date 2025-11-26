<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NationalInsuranceRule implements Rule
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
        return (bool) preg_match('/^[a-zA-Z]{2}[0-9]{6}[a-zA-Z]$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid NI (National Insurance) Number.';
    }
}
