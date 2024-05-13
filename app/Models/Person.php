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

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;

use App\Casts\AsSouthAfricanId;
use App\Casts\AsSouthAfricanMobileNumber;
use App\Observers\PersonObserver;
use App\Rules\BirthDateMatchSouthAfricanId;
use App\Rules\SouthAfricanId;
use App\Rules\SouthAfricanMobileNumber;

/**
 * Model of the Person entity.
 *
 * @see        {@link https://laravel.com/docs/11.x/eloquent#eloquent-model-conventions}
 * @author     Geoffrey Bernardo van Wyk <geoffrey@vanwyk.biz>
 * @copyright  2024 Geoffrey Bernardo van Wyk {@link https://geoffreyvanwyk.dev}
 * @license    {@link http://www.gnu.org/copyleft/gpl.html} GNU GPL v3 or later
 */
#[ObservedBy([PersonObserver::class])]
class Person extends BaseModel
{
    use Notifiable;

    /**
     * @inheritDoc
     */
    public static function validationRules(): array
    {
        return [
            'rules' => [
                'name' => ['required', 'string'],
                'surname' => ['required', 'string'],
                'south_african_id' => ['required', new SouthAfricanId(), 'unique:people'],
                'mobile_number' => ['required', new SouthAfricanMobileNumber()],
                'email' => ['required', 'email'],
                'birth_date' => ['required','date_format:Y-m-d', 'before_or_equal:today', new BirthDateMatchSouthAfricanId()],
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
