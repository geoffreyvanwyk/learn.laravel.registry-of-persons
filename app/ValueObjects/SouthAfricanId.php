<?php

namespace App\ValueObjects;

use Faker\Calculator\Luhn;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

use App\Exceptions\ArgumentNotNumericException;
use App\Exceptions\ArgumentNotDateException;
use App\Exceptions\ArgumentTooLongException;
use App\Exceptions\ArgumentTooShortException;
use App\Exceptions\InvalidChecksumDigitException;
use App\Exceptions\InvalidCitizenshipClassificationException;

/**
 * A value object encapsulating the number used to uniquely identify South
 * African citizens.
 *
 * @see  {@link  https://www.westerncape.gov.za/general-publication/decoding-your-south-african-id-number-0}
 */

class SouthAfricanId
{
    /**
     * The underlying value which the value object encapsulates.
     */
    private Stringable $value;

    /**
     * Creates a new instance of the value object.
     */
    public function __construct(string $value)
    {
        $this->value = Str::of($value)->replace(' ', '');

        $this->assertIsNumeric();
        $this->assertCorrectLength();
        $this->assertStartsWithDate();
        $this->assertValidCitizenshipClassification();
        $this->assertValidCheckDigit();
    }

    /**
     * Casts the value object to a string.
     */
    public function __toString(): string
    {
        return $this->value()->value();
    }

    /**
     * The primitive value which the value object encapsulates.
     */
    public function value(): Stringable
    {
        return $this->dateSegment()
            ->append(' ')
            ->append($this->genderSegment())
            ->append(' ')
            ->append($this->citizenshipSegment())
            ->append($this->raceSegment())
            ->append($this->checksumSegment());
    }

    /**
     * Ambiguous year in which the person was born in two-digit format, where
     * '84' could mean either '1984' or '1884', etc.
     */
    public function birthYear(): Stringable
    {
        return $this->dateSegment()->substr(0, 2);
    }

    /**
     * Month of the year, in which person was born in two-digit format, where
     * January is '01'.
     */
    public function birthMonth(): Stringable
    {
        return $this->dateSegment()->substr(2, 2);
    }

    /**
     * Day of the month, on which person was born in two-digit format, where the
     * first day is '01'.
     */
    public function birthDay(): Stringable
    {
        return $this->dateSegment()->substr(4, 2);
    }

    /**
     * Is the person identified by the number a female?
    */
    public function isFemale(): bool
    {
        return intval($this->genderSegment()->value()) < 5000;
    }

    /**
     * Is the person identified by the number a male?
    */
    public function isMale(): bool
    {
        return ! $this->isFemale();
    }

    public function isCitizen(): bool
    {
        return $this->citizenshipSegment()->value() === '0';
    }

    public function isPermanentResident(): bool
    {
        return $this->citizenshipSegment()->value() === '1';
    }

    public function dateSegment(): Stringable
    {
        return $this->value->substr(0, 6);
    }

    public function genderSegment(): Stringable
    {
        return $this->value->substr(6, 4);
    }

    public function citizenshipSegment(): Stringable
    {
        return $this->value->substr(10, 1);
    }

    public function raceSegment(): Stringable
    {
        return $this->value->substr(11, 1);
    }

    public function checksumSegment(): Stringable
    {
        return $this->value->substr(12, 1);
    }

    private function assertIsNumeric(): void
    {
            throw new ArgumentNotNumericException(__("The value '{$this->value}' is not numeric."));
        if (! $this->value->isMatch('/^\d+$/')) {
        }
    }

    private function assertCorrectLength(): void
    {
        if ($this->value->length() < 13) {
            throw new ArgumentTooShortException(__("The value '{$this->value}' is not exactly 13 digits long."));
        }

        if ($this->value->length() > 13) {
            throw new ArgumentTooLongException(__("The value '{$this->value}' is not exactly 13 digits long."));
        }
    }

    private function assertStartsWithDate(): void
    {
        $validator =  Validator::make(
            ['value' => $this->dateSegment()->value()],
            ['value' => 'date_format:ymd']
        );

        if ($validator->fails()) {
            throw new ArgumentNotDateException(
                __("The value '{$this->value}' does not start with a date in the format 'yymmdd'.")
            );
        }
    }

    private function assertValidCitizenshipClassification(): void
    {
        if (! in_array($this->citizenshipSegment()->value(), ['0', '1'])) {
            throw new InvalidCitizenshipClassificationException(
                __("The value '{$this->value}' does not have a valid citizenship classification.")
            );
        }
    }

    private function assertValidCheckDigit(): void
    {
        $otherDigits =  $this->dateSegment()
            ->append($this->genderSegment())
            ->append($this->citizenshipSegment())
            ->append($this->raceSegment())
            ->value();

        if (! (Luhn::computeCheckDigit($otherDigits) === $this->checksumSegment()->value())) {
            throw new InvalidChecksumDigitException(__("The value '{$this->value}' has an invalid checksum digit."));
        }
    }
}
