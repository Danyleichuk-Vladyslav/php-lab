<?php

declare(strict_types=1);

function countViews(int $views, int $added): int
{
    return $views + $added;
}

function applyDiscount(float $price, float $discount): float
{
    if ($discount < 0 || $discount > 100) {
        throw new Exception('Неприпустиме значення знижки!');
    }

    return $price - ($price * ($discount / 100));
}
