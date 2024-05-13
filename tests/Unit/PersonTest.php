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

namespace Tests\Unit;

use Carbon\Carbon;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

use App\Models\Language;
use App\Models\Person;
use App\ValueObjects\SouthAfricanId;
use Tests\TestCase;

/**
 * Unit test for \App\Models\Person.
 *
 * @author     Geoffrey Bernardo van Wyk <geoffrey@vanwyk.biz>
 * @copyright  2024 Geoffrey Bernardo van Wyk {@link https://geoffreyvanwyk.dev}
 * @license    {@link http://www.gnu.org/copyleft/gpl.html} GNU GPL v3 or later
 */
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
            'south_african_id' => new SouthAfricanId($southAfricanId),
            'birth_date' => $birthDate
        ]);

        try {
            Person::factory()->create([
                'south_african_id' => new SouthAfricanId($southAfricanId),
                'birth_date' => $birthDate
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
     * A person is required to have an email address.
     */
    public function test_person_requires_email_address(): void
    {
        try {
            Person::factory()->create(['email' => null]);

            $this->fail(
                'Failed asserting that exception of type \Illuminate\Validation\ValidationException was thrown.'
            );
        } catch (ValidationException $e) {
            $this->assertTrue($e->errorBag->has('email'));
            $this->assertEquals('The email field is required.', $e->errorBag->first('email'));
        }
    }

    /**
     * A person's email address must be valid.
     */
    public function test_person_has_valid_email_address(): void
    {
        try {
            Person::factory()->create(['email' => 'not-valid-email-address']);

            $this->fail(
                'Failed asserting that exception of type \Illuminate\Validation\ValidationException was thrown.'
            );
        } catch (ValidationException $e) {
            $this->assertTrue($e->errorBag->has('email'));
            $this->assertEquals(
                'The email field must be a valid email address.',
                $e->errorBag->first('email')
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
        Language::factory()->create(['code' => 'en']);

        try {
            // The factory has already inserted a Language while making the
            // Person, before the language_id is overridden here. That means 2
            // Lanuages in the database before the Person is created here
            // (counting the one above). Before create is called on the factory
            // here, Language::count() will still be 1.
            Person::factory()->create(['language_id' => Language::count() + 2]);

            $this->fail(
                'Failed asserting that exception of type \Illuminate\Validation\ValidationException was thrown.'
            );
        } catch (ValidationException $e) {
            $this->assertTrue($e->errorBag->has('language_id'));
            $this->assertEquals('The selected language id is invalid.', $e->errorBag->first('language_id'));
        }
    }
}
