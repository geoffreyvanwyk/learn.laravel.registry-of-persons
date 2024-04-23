<?php

namespace Tests\Unit;

use InvalidArgumentException;

use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Person;
use App\ValueObjects\SouthAfricanId;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PersonTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<array<strings>>
     */
    public static function invalidMobileNumbers(): array
    {
        return [
            ['9825566344'],
            ['+278140582333'],
            ['061-713*4950'],
            ['060 423 935'],
            ['076 014 349911111'],
            ['276-598-0143'],
            ['+17609611676'],
            ['2760579039'],
        ];
    }

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

    /**
     * A person is required to have a mobile number.
     */
    public function test_person_requires_mobile_number(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The mobile number field is required.');

        Person::factory()->create(['mobile_number' => null]);
    }

    /**
     * A person's mobile number must be valid.
     */
    #[DataProvider(methodName: 'invalidMobileNumbers')]
    public function test_person_has_valid_mobile_number(string $mobileNumber): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The mobile number field format is invalid.');

        Person::factory()->create(['mobile_number' => $mobileNumber]);
    }

    /**
     * A person is required to have an email address.
     */
    public function test_person_requires_email_address(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The email address field is required.');

        Person::factory()->create(['email_address' => null]);
    }

    /**
     * A person's email address must be valid.
     */
    public function test_person_has_valid_email_address(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The email address field must be a valid email address.');

        Person::factory()->create(['email_address' => 'not-valid-email-address']);
    }
}
