<?php

declare(strict_types=1);

namespace App\Tasks;

abstract class Vehicle
{
    public function __construct(
        protected readonly string $name,
        protected readonly float $speedKmh,
    ) {}

    abstract public function calculateTravelTime(float $distance): float;

    public function name(): string
    {
        return $this->name;
    }
}
