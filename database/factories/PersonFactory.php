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

namespace Database\Factories;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Language;
use App\ValueObjects\SouthAfricanId;
use App\ValueObjects\SouthAfricanMobileNumber;

/**
 * Creates a dummy instance of Person model.
 *
 * @see  {@link https://laravel.com/docs/11.x/eloquent-factories}
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Person>
 * @author     Geoffrey Bernardo van Wyk <geoffrey@vanwyk.biz>
 * @copyright  2024 Geoffrey Bernardo van Wyk {@link https://geoffreyvanwyk.dev}
 * @license    {@link http://www.gnu.org/copyleft/gpl.html} GNU GPL v3 or later
 */
class PersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $southAfricanId = new SouthAfricanId(fake()->idNumber());

        return [
            'name' => fake()->firstName(),
            'surname' => fake()->lastName(),
            'south_african_id' => $southAfricanId,
            'mobile_number' => new SouthAfricanMobileNumber(fake()->mobileNumber()),
            'email' => fake()->email(),
            'birth_date' => '19' . Carbon::createFromFormat(
                'ymd',
                $southAfricanId->dateSegment()->value()
            )->format('y-m-d'),
            'language_id' => (Language::factory()->create())->id,
        ];
    }
}
