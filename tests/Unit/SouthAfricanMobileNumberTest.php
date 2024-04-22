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

use App\Exceptions\ArgumentNotNumericException;
use App\Exceptions\ArgumentTooLongException;
use App\Exceptions\ArgumentTooShortException;
use App\ValueObjects\SouthAfricanMobileNumber;
use Exception;
use PHPUnit\Framework\Attributes\DataProvider;

use Tests\TestCase;

/**
 * Unit test for \App\ValueObjects\SouthAfricanMobileNumber.
 *
 * @author     Geoffrey Bernardo van Wyk <geoffrey@vanwyk.biz>
 * @copyright  2024 Geoffrey Bernardo van Wyk {@link https://geoffreyvanwyk.dev}
 * @license    {@link http://www.gnu.org/copyleft/gpl.html} GNU GPL v3 or later
 */
class SouthAfricanMobileNumberTest extends TestCase
{
    /**
     * The mobile number must start with a 0.
     */
    #[DataProvider(methodName: 'withoutLeadingZero')]
    public function test_mobile_number_must_start_with_zero(string $mobileNumber): void
    {
        $this->expectException(\App\Exceptions\InvalidLeadingCharactersException::class);

        new SouthAfricanMobileNumber($mobileNumber);
    }

    /**
     * The mobile number accepts country codes, with or without leading +.
     */
    #[DataProvider(methodName: 'withCountryCodes')]
    public function test_mobile_number_accepts_country_codes(string $mobileNumber): void
    {
        $this->expectNotToPerformAssertions();

        new SouthAfricanMobileNumber($mobileNumber);
    }

    /**
     * The mobile number accepts spaces and dashes.
     */
    #[DataProvider(methodName: 'withSpacesAndDashes')]
    public function test_mobile_number_accepts_spaces_and_dashes(string $mobileNumber): void
    {
        $this->expectNotToPerformAssertions();

        new SouthAfricanMobileNumber($mobileNumber);
    }

    /**
     * The mobile number must not be too short.
     */
    #[DataProvider(methodName: 'fewerThan10Digits')]
    public function test_mobile_number_must_not_be_too_short(string $mobileNumber): void
    {
        $this->expectException(ArgumentTooShortException::class);

        new SouthAfricanMobileNumber($mobileNumber);
    }

    /**
     * The mobile number must not be too long.
     */
    #[DataProvider(methodName: 'moreThan10Digits')]
    public function test_mobile_number_must_not_be_too_long(string $mobileNumber): void
    {
        $this->expectException(ArgumentTooLongException::class);

        new SouthAfricanMobileNumber($mobileNumber);
    }

    /**
     * The mobile number must contain only digits.
     */
    #[DataProvider(methodName: 'nonnumbericMobileNumbers')]
    public function test_mobile_number_must_be_numeric(string $mobileNumber): void
    {
        $this->expectException(ArgumentNotNumericException::class);

        new SouthAfricanMobileNumber($mobileNumber);
    }

    /**
     * Data provider of mobile numbers without leading zeros or country codes.
     *
     * @return array<array<string>>
     */
    public static function withoutLeadingZero(): array
    {
        return [
            ['1854609177'],
            ['2864656247'],
            ['3873396967'],
            ['4628496691'],
            ['5852397579'],
        ];
    }

    /**
     * Data provider of mobile numbers with country codes, with or without
     * leading +.
     *
     * @return array<array<string>>
     */
    public static function withCountryCodes(): array
    {
        return [
            ['+27785824485'],
            ['+27883456599'],
            ['+27797932929'],
            ['27790175937'],
            ['27627722658'],
        ];
    }

    /**
     * Data provider of mobile numbers with spaces and dashes.
     *
     * @return array<array<string>>
     */
    public static function withSpacesAndDashes(): array
    {
        return [
            ['+27 78 582 4485'],
            ['+27-88-345-6599'],
            [' +27 79-793-2929'],
            [' 2 7 7-9 0-1   75 9 3 7 '],
            ['2--7--6   2--7722658'],
        ];
    }

    /**
     * Data provider of mobile numbers shorter than 10 digits.
     *
     * @return array<array<string>>
     */
    public static function fewerThan10Digits(): array
    {
        return [
            ['0'],
            ['08'],
            ['087'],
            ['0628'],
            ['08523'],
            ['085460'],
            ['0864656'],
            ['08733969'],
            ['062849669'],
        ];
    }

    /**
     * Data provider of mobile numbers longer than 10 digits.
     *
     * @return array<array<string>>
     */
    public static function moreThan10Digits(): array
    {
        return [
            ['08546091771'],
            ['086465624712'],
            ['0873396967123'],
            ['06284966911234'],
            ['085239757912345'],
        ];
    }

    /**
     * Data provider of mobile numbers containing nonnumeric characters.
     *
     * @return array<array<string>>
     */
    public static function nonnumbericMobileNumbers(): array
    {
        return [
            ['085*609177'],
            ['0864656e47'],
            ['08a3396,67'],
            ['0628496z91'],
            ['085x397579'],
        ];
    }
}
