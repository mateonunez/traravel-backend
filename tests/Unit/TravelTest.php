<?php

namespace Tests\Unit;

use App\Models\Travel;
use PHPUnit\Framework\TestCase;

class TravelTest extends TestCase
{
    /** @group travel_test */
    public function test_compute_number_of_days_returns_the_right_difference()
    {
        $startingDate = now();
        $endingDate = now()->addDays(10);

        $numberOfDays = 10;

        $computedNumberOfDays = Travel::computeNumberOfDays($startingDate, $endingDate);

        $this->assertEquals($numberOfDays, $computedNumberOfDays);
    }
}
