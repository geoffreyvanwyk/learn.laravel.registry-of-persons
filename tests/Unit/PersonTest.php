<?php

namespace Tests\Unit;

use InvalidArgumentException;

use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Person;
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
}
