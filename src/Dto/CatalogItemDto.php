<?php

namespace App\Dto;

use App\Enum\CatalogType;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CatalogItemDto
{
    public function __construct(
        public ?int               $id = null,
        public ?string            $name = null,
        public ?string            $description = null,
        public ?CatalogType       $type = null,
        public ?array             $advantages = null,
        public ?UploadedFile      $photo = null,
        public ?Collection        $manufacturers = null,
        public ?string            $country = null,
        public ?string            $section = null,
        public ?DateTimeInterface $publishedAt = null
    )
    {
    }
}
