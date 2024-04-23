<?php

namespace Database\Factories;

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
        return [
            'name' => fake()->firstName(),
            'surname' => fake()->lastName(),
            'south_african_id' => new SouthAfricanId(fake()->idNumber()),
            'email_address' => fake()->email(),
        ];
    }
}
