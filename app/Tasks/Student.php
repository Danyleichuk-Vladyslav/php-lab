<?php

declare(strict_types=1);

namespace App\Tasks;

class Student
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $surname,
        public readonly string $birthday,
    ) {}
}
