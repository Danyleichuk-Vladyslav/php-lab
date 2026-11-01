<?php

declare(strict_types=1);

namespace App\Tasks;

class ExpressTrain extends Train
{
    public function calculateTravelTime(float $distance): float
    {
        return parent::calculateTravelTime($distance);
    }
}
