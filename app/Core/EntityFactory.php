<?php

declare(strict_types=1);

namespace App\Core;

use App\Oop\Models\AbstractEntity;
use App\Oop\Models\Post;
use App\Oop\Models\User;

class EntityFactory
{
    public function createEntity(string $type, array $data): AbstractEntity
    {
        return match ($type) {
            'user' => new User(
                $data['id'],
                $data['createdAt'],
                $data['name'],
                $data['email'],
            ),
            'post' => new Post(
                $data['id'],
                $data['createdAt'],
                $data['title'],
                $data['body'],
                $data['status'],
            ),
            default => throw new \InvalidArgumentException('Невідомий тип сутності: '.$type),
        };
    }
}
