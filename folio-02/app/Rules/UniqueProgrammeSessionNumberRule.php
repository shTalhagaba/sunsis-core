<?php

namespace App\Rules;

use App\Models\Programmes\ProgrammeDeliveryPlanSession;
use Illuminate\Contracts\Validation\Rule;

class UniqueProgrammeSessionNumberRule implements Rule
{
    protected $programmeId;
    protected $sessionId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($programmeId, $sessionId = null)
    {
        $this->programmeId = $programmeId;
        $this->sessionId = $sessionId;
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
        // Check if session number is unique for the given programme_id
        if(is_null($this->sessionId))
        {
            // create
            return !ProgrammeDeliveryPlanSession::where('programme_id', $this->programmeId)
                ->where('session_number', $value)
                ->exists();
        }
        else
        {
            // update
            return !ProgrammeDeliveryPlanSession::where('programme_id', $this->programmeId)
                ->where('session_number', $value)
                ->where('id', '!=', $this->sessionId)
                ->exists();
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The session number must be unique for each programme.';
    }
}
