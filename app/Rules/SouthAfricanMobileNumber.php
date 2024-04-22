<?php

// This file is part of registry-of-persons.
//
// registry-of-persons is free software: you can redistribute it and/or modify it under the
// terms of the GNU General Public License as published by the Free Software
// Foundation, either version 3 of the License, or (at your option) any later
// version.
//
// registry-of-persons is distributed in the hope that it will be useful, but WITHOUT ANY
// WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
// PARTICULAR PURPOSE. See the GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License along with
// registry-of-persons. If not, see <https://www.gnu.org/licenses/>.

namespace App\Rules;

use Closure;
use InvalidArgumentException;

use Illuminate\Contracts\Validation\ValidationRule;

use App\ValueObjects\SouthAfricanMobileNumber as SouthAfricanMobileNumberValueObject;

/**
 * Rule that validates that a string is a South African mobile telephone number.
 *
 * @see        {@link https://laravel.com/docs/11.x/validation#using-rule-objects}
 * @author     Geoffrey Bernardo van Wyk <geoffrey@vanwyk.biz>
 * @copyright  2024 Geoffrey Bernardo van Wyk {@link https://geoffreyvanwyk.dev}
 * @license    {@link http://www.gnu.org/copyleft/gpl.html} GNU GPL v3 or later
 */
class SouthAfricanMobileNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            new SouthAfricanMobileNumberValueObject($value);
        } catch (InvalidArgumentException $e) {
            $fail($e->getMessage());
        }
    }
}
