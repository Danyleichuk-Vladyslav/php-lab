<?php

declare(strict_types=1);

namespace App\Tasks;

class Car extends Vehicle
{
    public function calculateTravelTime(float $distance): float
    {
        return $distance / $this->speedKmh;
    }
}
