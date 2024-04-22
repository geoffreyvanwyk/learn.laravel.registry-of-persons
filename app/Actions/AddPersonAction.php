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

namespace App\Actions;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use App\DataTransferObjects\AddPersonRequest;
use App\Models\Person;
use App\ValueObjects\SouthAfricanId;
use App\ValueObjects\SouthAfricanMobileNumber;

/**
 * Action for adding a new Person to the registory.
 *
 * @see        {@link https://freek.dev/1371-refactoring-to-actions}
 * @author     Geoffrey Bernardo van Wyk <geoffrey@vanwyk.biz>
 * @copyright  2024 Geoffrey Bernardo van Wyk {@link https://geoffreyvanwyk.dev}
 * @license    {@link http://www.gnu.org/copyleft/gpl.html} GNU GPL v3 or later
 */
class AddPersonAction
{
    /**
     * Data transfer object containing all the data required to add a person to the
     * registry.
     */
    private AddPersonRequest $request;

    /**
     * Create new instance of the action.
     */
    public function __construct(AddPersonRequest $request)
    {
        $this->request = $request;
        $this->validateRequest();
    }

    /**
     * Execute the action.
     */
    public function execute(): Person
    {
        $person = new Person();
        $person->name = $this->request->name;
        $person->surname = $this->request->surname;
        $person->south_african_id = new SouthAfricanId($this->request->southAfricanId);
        $person->mobile_number = new SouthAfricanMobileNumber($this->request->mobileNumber);
        $person->email_address = $this->request->emailAddress;
        $person->birth_date = $this->request->birthDate;
        $person->language_id = $this->request->languageId;
        $person->save();
        $person->interests()->attach($this->request->interests);

        return $person;
    }

    /**
     * Validate the request data that are not validated by the Person model.
     */
    private function validateRequest(): void
    {
        $validator = Validator::make(
            ['interests' => $this->request->interests],
            ['interests' => ['array', 'min:1', 'exists:interests,id']],
            [
                'min' => 'A person must be interested in at least one topic.',
                'exists' => 'The interests do not exist.',
            ],
        );

        if ($validator->fails()) {
            throw new ValidationException($validator, null, $validator->errors()) ;
        }
    }
}
