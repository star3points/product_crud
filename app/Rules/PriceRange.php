<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class PriceRange implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $prices = explode(',', $value);

        $validator = Validator::make($prices, [
            '0' => ['required', 'numeric', 'min:0'],
            '1' => ['required', 'numeric', 'min:0'],
        ]);

        if ($validator->fails()) {
            $fail("$attribute -> $value is not valid");
        }
    }
}
