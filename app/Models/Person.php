<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
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
                'mobile_number' => ['required', 'regex:/^0\d{9}$/'],
                'email_address' => ['required', 'email'],
                'birth_date' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
                'language_id' => ['required'],
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
            'birth_date' => 'datetime:Y-m-d',
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

            if($person->birth_date->format('ymd') !== $person->south_african_id->dateSegment()->value()) {
                throw new InvalidArgumentException('The birth date field does not match the south african id field.');
            }
        });
    }

    /**
     * Get or set the person's mobile number.
     */
    protected function mobileNumber(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => Str::of($value)
                ->replace(' ', '')
                ->replace('-', '')
                ->replace('+27', '0')
                ->replaceMatches('/^27/', '0')
                ->value(),
        );
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Language::class);
    }
}
