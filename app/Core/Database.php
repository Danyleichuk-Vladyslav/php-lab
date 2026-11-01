<?php

declare(strict_types=1);

namespace App\Core;

class Database
{
    private static ?self $instance = null;

    private function __construct()
    {
        echo "Підключення до БД створено\n";
    }

    private function __clone() {}

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }

        return self::$instance;
    }
}
