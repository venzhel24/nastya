<?php

namespace App\Service\Mapper;

use App\Dto\CatalogItemDto;
use App\Entity\CatalogItem;
use App\Service\UploadService;

readonly class CatalogItemMapper
{
    public function __construct(private UploadService $uploadService) {}

    public function mapDtoToEntity(CatalogItemDto $dto, CatalogItem $item): void
    {
        $item->setName($dto->name);
        $item->setDescription($dto->description);
        $item->setType($dto->type);
        $item->setAdvantages($dto->advantages);
        $item->setPublishedAt($dto->publishedAt);

        if ($dto->manufacturers) {
            $item->setManufacturers($dto->manufacturers);
        }

        if ($dto->country) {
            $item->setCountry($dto->country);
        }

        if ($dto->section) {
            $item->setSection($dto->section);
        }

        if ($dto->photo) {
            $fileName = $this->uploadService->uploadFile($dto->photo);
            $photoUrl = $this->uploadService->getFileUrl($fileName);
            $item->setPhotoPath($photoUrl);
        }
    }

    public function mapEntityToDto(CatalogItem $item, CatalogItemDto $dto): void
    {
        $dto->name = $item->getName();
        $dto->description = $item->getDescription();
        $dto->type = $item->getType();
        $dto->advantages = $item->getAdvantages();
        $dto->publishedAt = $item->getPublishedAt();
        $dto->manufacturers = $item->getManufacturers();
        $dto->country = $item->getCountry();
        $dto->section = $item->getSection();
    }
}
