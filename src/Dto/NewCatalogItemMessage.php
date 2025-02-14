<?php

namespace App\Dto;

readonly class NewCatalogItemMessage
{
    public function __construct(
        private int $itemId
    ) {}

    public function getItemId(): int
    {
        return $this->itemId;
    }
}
