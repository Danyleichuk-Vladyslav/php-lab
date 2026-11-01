<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\LoggerInterface;
use App\Models\AbstractEntity;

class EntityService
{
    public function __construct(private LoggerInterface $logger) {}

    public function countViews(int $views, int $added): int
    {
        $result = $views + $added;
        $this->logger->log('Перегляди: було '.$views.', додалося '.$added.', стало '.$result);

        return $result;
    }

    public function applyDiscount(float $price, float $discount): float
    {
        if ($discount < 0 || $discount > 100) {
            $this->logger->log('Знижку '.$discount.' відхилено');
            throw new \Exception('Неприпустиме значення знижки!');
        }

        $result = $price - ($price * ($discount / 100));
        $this->logger->log('Нова ціна після знижки: '.$result);

        return $result;
    }

    public function publish(AbstractEntity $entity): string
    {
        $details = $entity->getDetails();
        $this->logger->log('Сервіс обробив сутність: '.$details);

        return $details;
    }
}
