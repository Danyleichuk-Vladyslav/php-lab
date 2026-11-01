<?php

declare(strict_types=1);

namespace App\Tasks;

class Rectangle
{
    public function __construct(
        public readonly float $width,
        public readonly float $height,
    ) {}

    public function perimeter(): float
    {
        return 2 * ($this->width + $this->height);
    }

    public function area(): float
    {
        return $this->width * $this->height;
    }
}
