<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DateBetween implements Rule
{
    protected $fromDate;
    protected $endDate;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($fromDate, $endDate)
    {
        $this->fromDate = $fromDate;
        $this->endDate = $endDate;
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
        $date = \Carbon\Carbon::parse($value);
        $from = \Carbon\Carbon::parse($this->fromDate);
        $end = \Carbon\Carbon::parse($this->endDate);

        return $date->between($from, $end);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be between ' . $this->fromDate->format('d/m/Y') . ' and ' . $this->endDate->format('d/m/Y') . '.';
    }
}
