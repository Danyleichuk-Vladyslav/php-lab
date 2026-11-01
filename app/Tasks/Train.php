<?php

declare(strict_types=1);

namespace App\Tasks;

class Train extends Car
{
    public function calculateTravelTime(float $distance): float
    {
        return parent::calculateTravelTime($distance);
    }
}
