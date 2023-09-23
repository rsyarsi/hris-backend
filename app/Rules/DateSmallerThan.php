<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class DateSmallerThan implements Rule
{
    protected $otherField;
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public function __construct($otherField)
    {
        $this->otherField = $otherField;
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
        $otherDate = request($this->otherField);

        // Convert both dates to Carbon objects
        $fromDate = Carbon::parse($value);
        $toDate = Carbon::parse($otherDate);

        // Check if from_date is less than or equal to to_date
        return $fromDate->lte($toDate) || ($fromDate->eq($toDate) && $fromDate->addDay()->eq($toDate));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The from date must be smaller than or equal to the to date.';
    }
}
