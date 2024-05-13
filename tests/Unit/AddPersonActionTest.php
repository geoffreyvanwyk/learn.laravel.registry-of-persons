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

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

use App\Actions\AddPersonAction;
use App\DataTransferObjects\AddPersonRequest;
use App\Models\Interest;
use App\Models\Language;
use App\Models\Person;
use App\Notifications\PersonRegistered;
use App\ValueObjects\SouthAfricanId;
use App\ValueObjects\SouthAfricanMobileNumber;
use Tests\TestCase;

/**
 * Unit test for \App\Actions\AddPersonAction.
 *
 * @author     Geoffrey Bernardo van Wyk <geoffrey@vanwyk.biz>
 * @copyright  2024 Geoffrey Bernardo van Wyk {@link https://geoffreyvanwyk.dev}
 * @license    {@link http://www.gnu.org/copyleft/gpl.html} GNU GPL v3 or later
 */
class AddPersonActionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The action can add a person to the registry.
     */
    public function test_can_add_a_person(): void
    {
        // --- Arrange ---------------------------------------------------------
        $southAfricanId = fake()->idNumber();
        $numberOfInterests = 3;

        $request = new AddPersonRequest(
            name: fake()->firstName(),
            surname: fake()->lastName(),
            southAfricanId: $southAfricanId,
            mobileNumber:  fake()->mobileNumber(),
            emailAddress: fake()->safeEmail(),
            birthDate: $this->matchingBirthDate($southAfricanId),
            languageId: (Language::factory()->create())->id,
            interests: (Interest::factory()->count($numberOfInterests)->create())->pluck('id')->all(),
        );

        // --- Act -------------------------------------------------------------
        $action = new AddPersonAction($request);
        $person = $action->execute();

        // --- Assert ----------------------------------------------------------
        $this->assertTrue($person instanceof Person);
        $this->assertEquals(1, $person->id);


        $this->assertDatabaseCount('people', 1);
        $this->assertDatabaseHas('people', [
            'name' => $request->name,
            'surname' => $request->surname,
            'south_african_id' => (new SouthAfricanId($request->southAfricanId))->value(),
            'mobile_number' => (new SouthAfricanMobileNumber($request->mobileNumber))->value(),
            'email' => $request->emailAddress,
            'birth_date' => $request->birthDate,
            'language_id' => $request->languageId,
        ]);

        $this->assertDatabaseCount('languages', 1);

        $this->assertDatabaseCount('interests', $numberOfInterests);
        for ($i = 1; $i <= $numberOfInterests; $i++) {
            $this->assertDatabaseHas('interest_person', [
                'interest_id' => $i,
                'person_id' => 1,
            ]);
        }
    }

    /**
     * An added person must be interested in at least one topic.
     */
    public function test_person_has_at_least_one_interest(): void
    {
        // --- Arrange ---------------------------------------------------------
        $southAfricanId = fake()->idNumber();

        $request = new AddPersonRequest(
            name: fake()->firstName(),
            surname: fake()->lastName(),
            southAfricanId: $southAfricanId,
            mobileNumber:  fake()->mobileNumber(),
            emailAddress: fake()->safeEmail(),
            birthDate: $this->matchingBirthDate($southAfricanId),
            languageId: (Language::factory()->create())->id,
            interests: [],
        );

        try {
            // --- Act -------------------------------------------------------------
            $action = new AddPersonAction($request);
            $action->execute();

            $this->fail(
                'Failed asserting that exception of type \Illuminate\Validation\ValidationException was thrown.'
            );
        } catch (ValidationException $e) {
            // --- Assert ----------------------------------------------------------
            $this->assertTrue($e->errorBag->has('interests'));
            $this->assertEquals('A person must be interested in at least one topic.', $e->errorBag->first('interests'));
            $this->assertDatabaseCount('interests', 0);
            $this->assertDatabaseCount('people', 0);
            $this->assertDatabaseCount('languages', 1);
        }
    }

    /**
     * The topics in which a person is interested must exist.
     */
    public function test_person_is_interested_in_existing_topics(): void
    {
        // --- Arrange ---------------------------------------------------------
        $southAfricanId = fake()->idNumber();

        $request = new AddPersonRequest(
            name: fake()->firstName(),
            surname: fake()->lastName(),
            southAfricanId: $southAfricanId,
            mobileNumber:  fake()->mobileNumber(),
            emailAddress: fake()->safeEmail(),
            birthDate: $this->matchingBirthDate($southAfricanId),
            languageId: (Language::factory()->create())->id,
            interests: [1, 2, 3],
        );

        try {
            // --- Act -------------------------------------------------------------
            $action = new AddPersonAction($request);
            $action->execute();

            $this->fail(
                'Failed asserting that exception of type \Illuminate\Validation\ValidationException was thrown.'
            );
        } catch (ValidationException $e) {
            // --- Assert ----------------------------------------------------------
            $this->assertTrue($e->errorBag->has('interests'));
            $this->assertEquals('The interests do not exist.', $e->errorBag->first('interests'));
            $this->assertDatabaseCount('interests', 0);
            $this->assertDatabaseCount('people', 0);
            $this->assertDatabaseCount('languages', 1);
        }
    }

    /**
     * A person must be notified of their registration.
     */
    public function test_person_is_notified_of_registration(): void
    {
        // --- Arrange ---------------------------------------------------------

        Notification::fake();

        $southAfricanId = fake()->idNumber();
        $numberOfInterests = 3;

        $request = new AddPersonRequest(
            name: fake()->firstName(),
            surname: fake()->lastName(),
            southAfricanId: $southAfricanId,
            mobileNumber:  fake()->mobileNumber(),
            emailAddress: fake()->safeEmail(),
            birthDate: $this->matchingBirthDate($southAfricanId),
            languageId: (Language::factory()->create())->id,
            interests: (Interest::factory()->count($numberOfInterests)->create())->pluck('id')->all(),
        );

        // --- Act -------------------------------------------------------------
        $action = new AddPersonAction($request);
        $person = $action->execute();

        // --- Assert ----------------------------------------------------------
        Notification::assertCount(1);
        Notification::assertSentTo([$person], PersonRegistered::class);
    }
}
