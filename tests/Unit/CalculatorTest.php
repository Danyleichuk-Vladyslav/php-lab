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

        $this->assertSame(5, $calculator->add(2, 3));
    }

    public function test_subtract(): void
    {
        $calculator = new Calculator;

        $this->assertSame(1, $calculator->subtract(3, 2));
    }
}
