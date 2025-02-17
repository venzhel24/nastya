<?php

namespace App\Service;

readonly class CatalogLabelService
{
    private PropertyNameService $propertyNameService;

    public function __construct(PropertyNameService $propertyNameService)
    {
        $this->propertyNameService = $propertyNameService;
    }

    public function getCatalogLabels(): array
    {
        return [
            'description' => $this->propertyNameService->getDisplayName('description'),
            'type' => $this->propertyNameService->getDisplayName('type'),
            'publishedAt' => $this->propertyNameService->getDisplayName('publishedAt'),
        ];
    }

    public function getCatalogItemLabels(): array
    {
        return [
            'description' => $this->propertyNameService->getDisplayName('description'),
            'type' => $this->propertyNameService->getDisplayName('type'),
            'advantages' => $this->propertyNameService->getDisplayName('advantages'),
            'photo' => $this->propertyNameService->getDisplayName('photoPath'),
            'manufacturers' => $this->propertyNameService->getDisplayName('manufacturers'),
            'country' => $this->propertyNameService->getDisplayName('country'),
            'section' => $this->propertyNameService->getDisplayName('section'),
            'publishedAt' => $this->propertyNameService->getDisplayName('publishedAt'),
        ];
    }
}
