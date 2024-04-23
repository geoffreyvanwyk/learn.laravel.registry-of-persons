<?php

namespace Tests\Unit;

use App\Exceptions\InvalidChecksumDigitException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use App\Exceptions\ArgumentNotDateException;
use App\Exceptions\ArgumentNotNumericException;
use App\Exceptions\ArgumentTooLongException;
use App\Exceptions\ArgumentTooShortException;
use App\Exceptions\InvalidCitizenshipClassificationException;
use App\ValueObjects\SouthAfricanId;

class SouthAfricanIdTest extends TestCase
{
    /**
     * @return array<arrray<string>>
    */
    public static function nonnumericStrings(): array
    {
        return [
            ['123a567'],
            ['1b345678'],
            ['12345c7890'],
            ['123d5678901'],
            ['123456789e12'],
            ['1234f678901234'],
            ['1g3456789012345'],
            ['1234h67890123456'],
            ['12345678i01234567'],
            ['12345678901j345678'],
            ['1234567890k23456789'],
        ];
    }

    /**
     * @return array<array<string>>
    */
    public static function fewerThan13Digits(): array
    {
        return [
            ['1'],
            ['12'],
            ['123'],
            ['1234'],
            ['12345'],
            ['123456'],
            ['1234567'],
            ['12345678'],
            ['1234567890'],
            ['12345678901'],
            ['123456789012'],
        ];
    }

    /**
     * @return array<array<string>>
     */
    public static function moreThan13Digits(): array
    {
        return [
            ['12345678901234'],
            ['123456789012345'],
            ['1234567890123456'],
            ['12345678901234567'],
            ['123456789012345678'],
            ['1234567890123456789'],
            ['12345678901234567890'],
            ['123456789012345678901'],
            ['1234567890123456789012'],
            ['12345678901234567890123'],
            ['123456789012345678901234'],
            ['1234567890123456789012345'],
            ['12345678901234567890123456'],
        ];
    }

    /**
     * @return array<array<string>>
     */
    public static function doesNotStartWithDate(): array
    {
        return [
            ['971305 2879 088'],
            ['841183 1148 083'],
            ['800638 2539 096'],
            ['733329 1928 084'],
            ['710991 8954 099'],
            ['584201 5865 085'],
            ['591068 1661 089'],
            ['286629 7495 093'],
            ['550348 2681 097'],
            ['479104 6415 096'],
            ['221034 3900 084'],
            ['677727 2870 092'],
            ['910389 4750 099'],
        ];
    }

    /**
     * @return array<array<string>>
     */
    public static function invalidCitizenshipClassification(): array
    {
        return [
            ['971205 2879 388'],
            ['841113 1148 283'],
            ['800628 2539 596'],
            ['730329 1928 684'],
            ['710921 8954 799'],
            ['581201 5865 885'],
            ['591008 1661 989'],
            ['280629 7495 293'],
            ['550308 2681 497'],
            ['470104 6415 596'],
            ['221024 3900 684'],
            ['670727 2870 792'],
            ['910329 4750 899'],
        ];
    }

    /**
     * @return array<array<string>>
     */
    public static function invalidChecksumDigit(): array
    {
        return [
            ['971205 2879 087'],
            ['841113 1148 082'],
            ['800628 2539 095'],
            ['730329 1928 083'],
            ['710921 8954 098'],
            ['581201 5865 084'],
            ['591008 1661 088'],
            ['280629 7495 092'],
            ['550308 2681 096'],
            ['470104 6415 095'],
            ['221024 3900 083'],
            ['670727 2870 091'],
            ['910329 4750 098'],
        ];
    }

    /**
     * A South African Identity Number is numeric.
     */
    #[DataProvider(methodName: 'nonnumericStrings')]
    public function test_south_african_id_is_numeric(string $southAfricanId): void
    {
        $this->expectException(ArgumentNotNumericException::class);

        new SouthAfricanId($southAfricanId);
    }

    /**
     * A South African Identity Number contains at least 13 digits.
     */
    #[DataProvider(methodName: 'fewerThan13Digits')]
    public function test_south_african_id_contains_at_least_13_digits(string $southAfricanId): void
    {
        $this->expectException(ArgumentTooShortException::class);

        new SouthAfricanId($southAfricanId);

    }

    /**
    * A South African Identity Number contains at most 13 digits.
    */
    #[DataProvider(methodName: 'moreThan13Digits')]
    public function test_south_african_id_contains_at_most_13_digits(string $southAfricanId): void
    {
        $this->expectException(ArgumentTooLongException::class);

        new SouthAfricanId($southAfricanId);
    }

    /**
     * A South African Identity Number starts with a date in 'yymmdd' format.
     */
    #[DataProvider(methodName: 'doesNotStartWithDate')]
    public function test_south_african_id_starts_with_date(string $southAfricanId): void
    {
        $this->expectException(ArgumentNotDateException::class);

        new SouthAfricanId($southAfricanId);
    }

    /**
     * A South African Identity Number must correctly classify citizenship.
     */
    #[DataProvider(methodName: 'invalidCitizenshipClassification')]
    public function test_south_african_id_correctly_classifies_citizenship(string $southAfricanId): void
    {
        $this->expectException(InvalidCitizenshipClassificationException::class);

        new SouthAfricanId($southAfricanId);
    }

    /**
     * A South African Identity Number must end with a valid checksum digit.
     */
    #[DataProvider(methodName: 'invalidChecksumDigit')]
    public function test_south_african_id_has_correct_checksum_digit(string $southAfricanId): void
    {
        $this->expectException(InvalidChecksumDigitException::class);

        new SouthAfricanId($southAfricanId);
    }
}
