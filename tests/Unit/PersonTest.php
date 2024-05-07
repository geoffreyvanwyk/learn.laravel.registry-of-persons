<?php

namespace Tests\Unit;

use InvalidArgumentException;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\DataProvider;

use App\Models\Language;
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
        try {
            Person::factory()->create(['name' => null]);

            $this->fail(
                'Failed asserting that exception of type \Illuminate\Validation\ValidationException was thrown.'
            );
        } catch (ValidationException $e) {
            $this->assertTrue($e->errorBag->has('name'));
            $this->assertEquals('The name field is required.', $e->errorBag->first('name'));
        }
    }

    /**
     * A person is required to have a surname.
     */
    public function test_person_requires_surname(): void
    {
        try {
            Person::factory()->create(['surname' => null]);

            $this->fail(
                'Failed asserting that exception of type \Illuminate\Validation\ValidationException was thrown.'
            );
        } catch (ValidationException $e) {
            $this->assertTrue($e->errorBag->has('surname'));
            $this->assertEquals('The surname field is required.', $e->errorBag->first('surname'));
        }
    }

    /**
     * A person is required to have a South African Identity Number.
     */
    public function test_person_requires_south_african_id(): void
    {
        try {
            Person::factory()->create(['south_african_id' => null]);

            $this->fail(
                'Failed asserting that exception of type \Illuminate\Validation\ValidationException was thrown.'
            );
        } catch (ValidationException $e) {
            $this->assertTrue($e->errorBag->has('south_african_id'));
            $this->assertEquals('The south african id field is required.', $e->errorBag->first('south_african_id'));
        }
    }

    /**
     * A person's South African Identity Number must be unique.
     */
    public function test_person_requires_unique_south_african_id(): void
    {
        $southAfricanId = fake()->idNumber();
        $birthDate = $this->matchingBirthDate($southAfricanId);
        Person::factory()->create([
            'south_african_id' => $southAfricanId,
            'birth_date' => $birthDate,
        ]);


        try {
            Person::factory()->create([
                'south_african_id' => $southAfricanId,
                'birth_date' => $birthDate,
            ]);

            $this->fail(
                'Failed asserting that exception of type \Illuminate\Validation\ValidationException was thrown.'
            );
        } catch (ValidationException $e) {
            $this->assertTrue($e->errorBag->has('south_african_id'));
            $this->assertEquals('The south african id has already been taken.', $e->errorBag->first('south_african_id'));

        }
    }

    /**
     * A person is required to have a mobile number.
     */
    public function test_person_requires_mobile_number(): void
    {
        try {
            Person::factory()->create(['mobile_number' => null]);

            $this->fail(
                'Failed asserting that exception of type \Illuminate\Validation\ValidationException was thrown.'
            );
        } catch (ValidationException $e) {
            $this->assertTrue($e->errorBag->has('mobile_number'));
            $this->assertEquals('The mobile number field is required.', $e->errorBag->first('mobile_number'));
        }
    }

    /**
     * A person's mobile number must be valid.
     */
    #[DataProvider(methodName: 'invalidMobileNumbers')]
    public function test_person_has_valid_mobile_number(string $mobileNumber): void
    {
        try {
            Person::factory()->create(['mobile_number' => $mobileNumber]);

            $this->fail(
                'Failed asserting that exception of type \Illuminate\Validation\ValidationException was thrown.'
            );
        } catch (ValidationException $e) {
            $this->assertTrue($e->errorBag->has('mobile_number'));
            $this->assertEquals('The mobile number field format is invalid.', $e->errorBag->first('mobile_number'));
        }
    }

    /**
     * A person is required to have an email address.
     */
    public function test_person_requires_email_address(): void
    {
        try {
            Person::factory()->create(['email_address' => null]);

            $this->fail(
                'Failed asserting that exception of type \Illuminate\Validation\ValidationException was thrown.'
            );
        } catch (ValidationException $e) {
            $this->assertTrue($e->errorBag->has('email_address'));
            $this->assertEquals('The email address field is required.', $e->errorBag->first('email_address'));
        }
    }

    /**
     * A person's email address must be valid.
     */
    public function test_person_has_valid_email_address(): void
    {
        try {
            Person::factory()->create(['email_address' => 'not-valid-email-address']);

            $this->fail(
                'Failed asserting that exception of type \Illuminate\Validation\ValidationException was thrown.'
            );
        } catch (ValidationException $e) {
            $this->assertTrue($e->errorBag->has('email_address'));
            $this->assertEquals(
                'The email address field must be a valid email address.',
                $e->errorBag->first('email_address')
            );
        }
    }

    /**
     * A person is required to have a birthdate.
     */
    public function test_person_requires_birth_date(): void
    {
        try {
            Person::factory()->create(['birth_date' => null]);

            $this->fail(
                'Failed asserting that exception of type \Illuminate\Validation\ValidationException was thrown.'
            );
        } catch (ValidationException $e) {
            $this->assertTrue($e->errorBag->has('birth_date'));
            $this->assertEquals('The birth date field is required.', $e->errorBag->first('birth_date'));

        }
    }

    /**
     * A person's birth date cannot be in the future.
     */
    public function test_person_birth_date_not_in_future(): void
    {
        try {
            Person::factory()->create(['birth_date' => Carbon::now()->addDay()->format('Y-m-d')]);

            $this->fail(
                'Failed asserting that exception of type \Illuminate\Validation\ValidationException was thrown.'
            );
        } catch (ValidationException $e) {
            $this->assertTrue($e->errorBag->has('birth_date'));
            $this->assertEquals(
                'The birth date field must be a date before or equal to today.',
                $e->errorBag->first('birth_date')
            );

        }
    }

    /**
     * A person's birth date must match the date segment of his South African Identity Number.
     */
    public function test_person_birthdate_match_south_african_id(): void
    {
        try {
            Person::factory()->create([
                'birth_date' => '1984-03-13',
                'south_african_id' => '6812060311083',
            ]);

            $this->fail(
                'Failed asserting that exception of type \Illuminate\Validation\ValidationException was thrown.'
            );
        } catch (ValidationException $e) {
            $this->assertTrue($e->errorBag->has('birth_date'));
            $this->assertEquals(
                'The birth date field does not match the South African ID field.',
                $e->errorBag->first('birth_date')
            );

        }
    }

    /**
     * A person is required to have a language.
     */
    public function test_person_requires_a_language(): void
    {
        try {
            Person::factory()->create(['language_id' => null]);

            $this->fail(
                'Failed asserting that exception of type \Illuminate\Validation\ValidationException was thrown.'
            );
        } catch (ValidationException $e) {
            $this->assertTrue($e->errorBag->has('language_id'));
            $this->assertEquals('The language id field is required.', $e->errorBag->first('language_id'));
        }
    }

    public function test_person_has_valid_language(): void
    {
        Language::factory()->create(['name' => 'English']);


        try {
            Person::factory()->create(['language_id' => 3]);

            $this->fail(
                'Failed asserting that exception of type \Illuminate\Validation\ValidationException was thrown.'
            );
        } catch (ValidationException $e) {
            $this->assertTrue($e->errorBag->has('language_id'));
            $this->assertEquals('The selected language id is invalid.', $e->errorBag->first('language_id'));
        }
    }

    /**
     * Data provider.
     *
     * @return array<array<string>>
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
     * Returns a full date that matches the given South African Identity Number.
     */
    private function matchingBirthDate(string $southAfricanId): string
    {
        return '19' . Carbon::createFromFormat(
            'ymd',
            (new SouthAfricanId($southAfricanId))->dateSegment()->value()
        )->format('y-m-d');
    }

}
