<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Validator;

class Barcode implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = (string)$value;
        $correctLength = strlen($value) >= 6 && strlen($value) <= 13;

        if (Validator::make(['ean' => $value], ['ean' => ['numeric']])->fails() || !$correctLength) {
            $fail('Geen geldige barcode');
        }
    }
}
