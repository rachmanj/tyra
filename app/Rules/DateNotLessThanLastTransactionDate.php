<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DateNotLessThanLastTransactionDate implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // get date of last transaction of the tyre
        $lastTransactionDate = app(ToolController::class)->getLastTransaction($value)->date;

        // convert the input value to carbon object
        $inputDate = Carbon::parse($value);

        // Compare the input date to the last transaction date
        return $inputDate->greaterThanOrEqualTo($lastTransactionDate);
    }

    public function message()
    {
        return 'The :attribute must be greater than or equal to the last transaction date.';
    }
}
