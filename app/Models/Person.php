<?php

namespace App\Models;

use InvalidArgumentException;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

use App\Casts\AsSouthAfricanId;

class Person extends Model
{
    use HasFactory;

    /**
    * Rules for validating the model's attributes.
    *
    * @return array<string,array<string,mixed>>
    */
    public static function validationRules(): array
    {
        return [
            'rules' => [
                'name' => ['required', 'string'],
                'surname' => ['required', 'string'],
                'south_african_id' => ['unique:people'],
                'email_address' => ['required', 'email'],
            ],
            'messages' => [],
        ];
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'south_african_id' => AsSouthAfricanId::class,
        ];
    }

    /**
    * Observe and react to events in the model's lifecycle.
    */
    protected static function booted(): void
    {
        static::saving(function (Person $person) {
            $attributeRules = static::validationRules();

            $validator = Validator::make(
                $person->getAttributes(),
                $attributeRules['rules'],
                $attributeRules['messages']
            );

            if ($validator->stopOnFirstFailure()->fails()) {
                throw new InvalidArgumentException($validator->messages()->first());
            }
        });
    }
}
