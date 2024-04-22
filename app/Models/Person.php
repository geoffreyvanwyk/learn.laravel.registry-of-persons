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

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Casts\AsSouthAfricanId;
use App\Casts\AsSouthAfricanMobileNumber;
use App\Rules\BirthDateMatchSouthAfricanId as BirthDateMatchSouthAfricanIdRule;
use App\Rules\SouthAfricanId as SouthAfricanIdRule;
use App\Rules\SouthAfricanMobileNumber as SouthAfricanMobileNumberRule;

/**
 * Model of the Person entity.
 *
 * @see        {@link https://laravel.com/docs/11.x/eloquent#eloquent-model-conventions}
 * @author     Geoffrey Bernardo van Wyk <geoffrey@vanwyk.biz>
 * @copyright  2024 Geoffrey Bernardo van Wyk {@link https://geoffreyvanwyk.dev}
 * @license    {@link http://www.gnu.org/copyleft/gpl.html} GNU GPL v3 or later
 */
class Person extends BaseModel
{
    /**
     * @inheritDoc
     */
    public static function validationRules(): array
    {
        return [
            'rules' => [
                'name' => ['required', 'string'],
                'surname' => ['required', 'string'],
                'south_african_id' => ['required', new SouthAfricanIdRule(), 'unique:people'],
                'mobile_number' => ['required', new SouthAfricanMobileNumberRule()],
                'email_address' => ['required', 'email'],
                'birth_date' => ['required','date_format:Y-m-d', 'before_or_equal:today', new BirthDateMatchSouthAfricanIdRule()],
                'language_id' => ['required', 'exists:languages,id'],
            ],
            'messages' => [],
        ];
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'south_african_id' => AsSouthAfricanId::class,
            'mobile_number' => AsSouthAfricanMobileNumber::class,
            'birth_date' => 'datetime:Y-m-d',
        ];
    }

    /**
     * The language that the person speaks.
    */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * Things in which the person is interested.
     */
    public function interests(): BelongsToMany
    {
        return $this->belongsToMany(Interest::class);
    }
}
