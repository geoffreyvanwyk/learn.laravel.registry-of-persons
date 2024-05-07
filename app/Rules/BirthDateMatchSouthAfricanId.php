<?php

namespace App\Rules;

use Closure;
use Exception;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

use App\ValueObjects\SouthAfricanId;

class BirthDateMatchSouthAfricanId implements ValidationRule, DataAwareRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $southAfricanId = new SouthAfricanId($this->data['south_african_id'] ?? '');
            $birthDate = new Carbon($value);

            if ($birthDate->format('ymd') !== $southAfricanId->dateSegment()->value()) {
                $fail('The :attribute field does not match the South African ID field.');
            }
        } catch (Exception $e) {
            return;
        }
    }

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
