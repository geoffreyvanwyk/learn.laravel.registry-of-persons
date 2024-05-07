<?php

namespace App\Models;

use App\Rules\BirthDateMatchSouthAfricanId;
use InvalidArgumentException;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

use App\Rules\BirthDateMatchSouthAfricanId as BirthDateMatchSouthAfricanIdRule;
use App\Rules\SouthAfricanId as SouthAfricanIdRule;
use App\ValueObjects\SouthAfricanId;

class Person extends BaseModel
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
                'south_african_id' => ['required', new SouthAfricanIdRule(), 'unique:people'],
                'mobile_number' => ['required', 'regex:/^0\d{9}$/'],
                'email_address' => ['required', 'email'],
                'birth_date' => ['required','date_format:Y-m-d', 'before_or_equal:today', new BirthDateMatchSouthAfricanIdRule()],
                'language_id' => ['required', 'exists:languages,id'],
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
            'birth_date' => 'datetime:Y-m-d',
        ];
    }

    /**
     * Get or set the person's South African Identity Number.
     */
    protected function southAfricanId(): Attribute
    {
        $formatSouthAfricanId = function (?string $value) {
            try {
                return strval(new SouthAfricanId($value));
            } catch (InvalidArgumentException $e) {
                return $value;
            }
        };

        return Attribute::make(set: $formatSouthAfricanId, get: $formatSouthAfricanId);
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

    /**
     * Get the language that the person speaks.
    */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
