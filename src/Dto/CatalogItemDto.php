<?php

namespace App\Dto;

use DateTimeInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CatalogItemDto
{
    public function __construct(
        public ?string            $description = null,
        public ?string            $type = null,
        public ?array             $advantages = null,
        public ?UploadedFile      $photo = null,
        public ?string            $manufacturer = null,
        public ?string            $country = null,
        public ?string $section = null,
        public ?DateTimeInterface $publishedAt = null
    ) {}
}
