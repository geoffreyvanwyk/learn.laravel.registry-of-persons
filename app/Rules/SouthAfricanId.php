<?php

namespace App\Rules;

use Closure;
use Exception;

use Illuminate\Contracts\Validation\ValidationRule;

use App\ValueObjects\SouthAfricanId as SouthAfricanIdValueObject;

class SouthAfricanId implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            new SouthAfricanIdValueObject($value);
        } catch (Exception $e) {
            $fail($e->getMessage());
        }
    }
}
