<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    public function test_add(): void
    {
        $calculator = new Calculator;

        $this->assertEquals(5, $calculator->add(2, 3));
    }

    public function test_subtract(): void
    {
        $calculator = new Calculator;

        $this->assertEquals(3, $calculator->subtract(5, 2));
    }
}
