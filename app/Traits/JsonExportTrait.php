<?php

declare(strict_types=1);

namespace App\Traits;

trait JsonExportTrait
{
    public function exportToJson(): string
    {
        return json_encode(
            get_object_vars($this),
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR
        );
    }
}
