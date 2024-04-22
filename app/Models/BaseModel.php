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

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Parent class of all models in the app.
 *
 * @author     Geoffrey Bernardo van Wyk <geoffrey@vanwyk.biz>
 * @copyright  2024 Geoffrey Bernardo van Wyk {@link https://geoffreyvanwyk.dev}
 * @license    {@link http://www.gnu.org/copyleft/gpl.html} GNU GPL v3 or later
 */
abstract class BaseModel extends Model
{
    use HasFactory;

    /**
     * Rules for validating the model's attributes.
     *
     * @see     {@link https://laravel.com/docs/11.x/validation#available-validation-rules}
     * @return  array<string,array<string,mixed>>
     */
    abstract protected static function validationRules(): array;

    /**
    * Observe and react to events in the model's lifecycle.
    *
    * @see  {@link https://laravel.com/docs/11.x/eloquent#events-using-closures}
    */
    protected static function booted(): void
    {
        static::saving(function ($model) {
            static::validate($model);
        });
    }

    /**
     * Validate the model's attributes.
     */
    protected static function validate(BaseModel $model): void
    {
        $attributeRules = static::validationRules();

        $validator = Validator::make(
            $model->getAttributes(),
            $attributeRules['rules'] ?? [],
            $attributeRules['messages'] ?? []
        );


        if ($validator->fails()) {
            throw new ValidationException($validator, null, $validator->errors()) ;
        }
    }
}
