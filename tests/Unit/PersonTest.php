<?php

namespace Tests\Unit;

use Exception;
use InvalidArgumentException;

use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Person;
use App\ValueObjects\SouthAfricanId;
use Tests\TestCase;

class PersonTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A person is required to have a name.
     */
    public function test_person_requires_name(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The name field is required.');

        Person::factory()->create(['name' => null]);
    }

    /**
     * A person is required to have a surname.
     */
    public function test_person_requires_surname(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The surname field is required.');

        Person::factory()->create(['surname' => null]);
    }

    /**
     * A person is required to have a South African Identity Number.
     */
    public function test_person_requires_south_african_id(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The value is not an instance of ' . SouthAfricanId::class . '.');

        Person::factory()->create(['south_african_id' => null]);
    }

    /**
     * A person is required to have a unique South African Identity Number.
     */
    public function test_person_requires_unique_south_african_id(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The south african id has already been taken.');

        $southAfricanId = fake()->idNumber();
        Person::factory()->create(['south_african_id' => new SouthAfricanId($southAfricanId)]);

        Person::factory()->create(['south_african_id' => new SouthAfricanId($southAfricanId)]);
    }
}
