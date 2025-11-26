<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueLearnerNumberRule implements Rule
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
        $uln = trim($value);
        if($uln)
        {
            // Validate the entered ULN
            $valid_pattern = "/^[1-9]{1}[0-9]{9}$/";
            $valid_pattern = preg_match($valid_pattern, $uln);
            if($valid_pattern)
            {
                $remainder = ((10 * $uln[0])
                        + (9 * $uln[1])
                        + (8 * $uln[2])
                        + (7 * $uln[3])
                        + (6 * $uln[4])
                        + (5 * $uln[5])
                        + (4 * $uln[6])
                        + (3 * $uln[7])
                        + (2 * $uln[8])) % 11;

                if($remainder == 0)
                {
                    return false;
                }

                $check_digit = 10 - $remainder;
                if($check_digit != $uln[9])
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid ULN (Unique Learner Number).';
    }
}
