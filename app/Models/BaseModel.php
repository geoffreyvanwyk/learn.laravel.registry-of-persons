<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class BaseModel extends Model
{
    /**
     * @return array<string,array>
     */
    abstract protected static function validationRules(): array;

    /**
    * Observe and react to events in the model's lifecycle.
    */
    protected static function booted(): void
    {
        static::saving(function ($model) {
            $attributeRules = static::validationRules();

            $validator = Validator::make(
                $model->getAttributes(),
                $attributeRules['rules'] ?? [],
                $attributeRules['messages'] ?? []
            );


            if ($validator->fails()) {
                throw new ValidationException($validator, null, $validator->errors()) ;
            }
        });
    }
}
