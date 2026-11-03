<?php

declare(strict_types=1);

namespace App\Oop\Models;

class User extends AbstractEntity
{
    public function __construct(
        string $id,
        string $createdAt,
        private readonly string $name,
        private readonly string $email,
    ) {
        parent::__construct($id, $createdAt);
    }

    public function getDetails(): string
    {
        return 'Користувач '.$this->name.' ('.$this->email.'), id '.$this->id;
    }
}
