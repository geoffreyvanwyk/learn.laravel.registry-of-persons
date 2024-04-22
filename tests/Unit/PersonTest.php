<?php

namespace Tests\Unit;

use InvalidArgumentException;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;

use App\Models\Person;
use Tests\TestCase;

class PersonTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<int,array<int,mixed>>
     */
    public static function blanksProvider(): array
    {
        $blanks = [[null]];
        foreach (range(0, 255) as $times) {
            $blanks[] = [str_repeat(' ', $times)];
        }
        return $blanks;
    }

    /**
     * A person is required to have a name.
     */
    #[DataProvider(methodName: 'blanksProvider')]
    public function test_person_requires_name(?string $name): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The name field is required.');

        Person::factory()->create(['name' => $name]);
    }

    /**
     * A person is required to have a surname.
     */
    #[DataProvider(methodName: 'blanksProvider')]
    public function test_person_requires_surname(?string $surname): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The surname field is required.');

        Person::factory()->create(['surname' => $surname]);
    }
}
