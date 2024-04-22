<?php

namespace Tests;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

use App\ValueObjects\SouthAfricanId;

abstract class TestCase extends BaseTestCase
{
    /**
     * Returns a full date that matches the given South African Identity Number.
     */
    protected function matchingBirthDate(string $southAfricanId): string
    {
        return '19' . Carbon::createFromFormat(
            'ymd',
            (new SouthAfricanId($southAfricanId))->dateSegment()->value()
        )->format('y-m-d');
    }
}
