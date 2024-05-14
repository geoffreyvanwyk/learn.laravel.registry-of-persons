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

namespace App\DataTransferObjects;

/**
 * Data transfer object used when adding a new person to the registry.
 *
 * @see        {@link https://matthiasnoback.nl/2022/09/is-it-a-dto-or-a-value-object/#what%27s-a-dto-and-how-do-you-recognize-it%3F}
 * @see        {@link https://en.wikipedia.org/wiki/Data_transfer_object}
 * @author     Geoffrey Bernardo van Wyk <geoffrey@vanwyk.biz>
 * @copyright  2024 Geoffrey Bernardo van Wyk {@link https://geoffreyvanwyk.dev}
 * @license    {@link http://www.gnu.org/copyleft/gpl.html} GNU GPL v3 or later
 */
readonly class RegisterPersonRequest
{
    /**
     * Create new instance of the data transfer object.
     *
     * @param  array<int> $interests
     */
    public function __construct(
        public string $name,
        public string $surname,
        public string $southAfricanId,
        public string $mobileNumber,
        public string $emailAddress,
        public string $birthDate,
        public int $languageId,
        public array $interests,
    ) {
    }
}
