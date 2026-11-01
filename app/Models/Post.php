<?php

declare(strict_types=1);

namespace App\Models;

use App\Interfaces\ExportableInterface;
use App\Traits\JsonExportTrait;

class Post extends AbstractEntity implements ExportableInterface
{
    use JsonExportTrait;

    public function __construct(
        string $id,
        string $createdAt,
        private readonly string $title,
        private readonly string $body,
        private readonly string $status,
    ) {
        parent::__construct($id, $createdAt);
    }

    public function getDetails(): string
    {
        return 'Допис «'.$this->title.'», статус '.$this->status.', id '.$this->id;
    }
}
