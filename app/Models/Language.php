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

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model of the Language entity.
 *
 * @see        {@link https://laravel.com/docs/11.x/eloquent#eloquent-model-conventions}
 * @author     Geoffrey Bernardo van Wyk <geoffrey@vanwyk.biz>
 * @copyright  2024 Geoffrey Bernardo van Wyk {@link https://geoffreyvanwyk.dev}
 * @license    {@link http://www.gnu.org/copyleft/gpl.html} GNU GPL v3 or later
 */
class Language extends BaseModel
{
    /**
     * @inheritDoc
     */
    public static function validationRules(): array
    {
        return [
            'rules' => [
                'code' => ['required', 'string', 'size:2', 'unique:languages'],
            ],
            'messages' => [],
        ];
    }

    /**
     * All the people who speak this language.
    */
    public function persons(): HasMany
    {
        return $this->hasMany(Person::class);
    }
}
