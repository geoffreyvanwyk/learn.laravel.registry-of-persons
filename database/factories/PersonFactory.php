<?php

namespace Database\Factories;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\ValueObjects\SouthAfricanId;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Person>
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
            'mobile_number' => fake()->mobileNumber(),
            'email_address' => fake()->email(),
            'birth_date' => '19' . Carbon::createFromFormat(
                'ymd',
                $southAfricanId->dateSegment()->value()
            )->format('y-m-d')
        ];
    }
}
