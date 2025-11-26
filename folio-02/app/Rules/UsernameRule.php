<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UsernameRule implements Rule
{
    protected $message;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Check if the username is between 8 and 50 characters
        if( strlen($value) < 8 || strlen($value) > 50 )
        {
            $this->message = 'The :attribute must be between 8 and 50 characters.';
            return false;
        }

        // Check if the username is unique in the users table
        if( DB::table('users')->where('username', $value)->exists() )
        {
            $this->message = 'The :attribute has already been taken.';
            return false;
        }

        // Check if the username contains only allowed characters
        if ( !preg_match('/^[a-zA-Z0-9.-]+$/', $value) ) 
        {
            $this->message = 'The :attribute may only contain letters, digits, hyphens, and dots.';
            return false;
        }

        // Check if the username contains at least one letter
        if ( !preg_match('/\pL/u', $value) ) 
        {
            $this->message = 'The :attribute must contain at least one letter.';
            return false;
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
        return $this->message;
    }
}
