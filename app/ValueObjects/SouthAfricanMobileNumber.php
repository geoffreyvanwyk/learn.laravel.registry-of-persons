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

namespace App\ValueObjects;

use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

use App\Exceptions\ArgumentNotNumericException;
use App\Exceptions\ArgumentTooLongException;
use App\Exceptions\ArgumentTooShortException;
use App\Exceptions\InvalidLeadingCharactersException;

/**
 * A value object encapsulating South African mobile telephone numbers.
 *
 * @see        {@link https://en.wikipedia.org/wiki/Telephone_numbers_in_South_Africa}
 * @see        {@link https://en.wikipedia.org/wiki/Value_object}
 * @see        {@link https://matthiasnoback.nl/2022/09/is-it-a-dto-or-a-value-object/#what%27s-a-value-object-and-how-do-you-recognize-it%3F}
 * @author     Geoffrey Bernardo van Wyk <geoffrey@vanwyk.biz>
 * @copyright  2024 Geoffrey Bernardo van Wyk {@link https://geoffreyvanwyk.dev}
 * @license    {@link http://www.gnu.org/copyleft/gpl.html} GNU GPL v3 or later
 */
class SouthAfricanMobileNumber
{
    /**
     * Underlying value encapsulated by the value object.
     */
    private Stringable $value;

    /**
     * Creates a new instance of the value object.
     */
    public function __construct(?string $value)
    {
        $this->value = Str::of($value)
            ->replace(' ', '')
            ->replace('-', '')
            ->replace('+27', '0')
            ->replaceMatches('/^27/', '0');

        $this->assertsStartsWithZero();
        $this->assertsCorrectLength();
        $this->assertIsNumeric();
    }

    /**
     * Casts the value object to a string.
     */
    public function __toString(): string
    {
        return $this->value()->value();
    }

    /**
     * Formatted version of underlying value encapsulated by the value object.
     */
    public function value(): Stringable
    {
        return $this->value->substr(0, 3)
            ->append(' ')
            ->append($this->value->substr(3, 3))
            ->append(' ')
            ->append($this->value->substr(4, 4));
    }

    /**
     * The mobile number must start with a 0.
     */
    private function assertsStartsWithZero(): void
    {
        if (! $this->value->startsWith('0')) {
            throw new InvalidLeadingCharactersException('The mobile number must start with a zero.');
        }
    }

    /**
     * The mobile number must consist of the correct number of characters.
     */
    private function assertsCorrectLength(): void
    {
        if ($this->value->length() < 10) {
            throw new ArgumentTooShortException("The value '{$this->value}' is not exactly 10 digits long.");
        }

        if ($this->value->length() > 10) {
            throw new ArgumentTooLongException("The value '{$this->value}' is not exactly 10 digits long.");
        }
    }

    /**
     * The mobile number must contain only digits.
     */
    private function assertIsNumeric(): void
    {
        if (! $this->value->isMatch('/^\d+$/')) {
            throw new ArgumentNotNumericException("The value '{$this->value}' is not numeric.");
        }
    }
}
