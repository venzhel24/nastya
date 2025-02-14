<?php

namespace App\Service;

use App\Entity\CatalogItemPropertyName;
use Doctrine\ORM\EntityManagerInterface;

class PropertyNameService
{
    private EntityManagerInterface $entityManager;
    private array $cachedNames = [];

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->loadNames();
    }

    private function loadNames(): void
    {
        $repo = $this->entityManager->getRepository(CatalogItemPropertyName::class);
        $this->cachedNames = [];
        foreach ($repo->findAll() as $property) {
            $this->cachedNames[$property->getPropertyKey()] = $property->getDisplayName();
        }
    }

    public function getDisplayName(string $propertyKey): string
    {
        return $this->cachedNames[$propertyKey] ?? ucfirst($propertyKey);
    }

    public function updateDisplayName(string $propertyKey, string $newDisplayName): void
    {
        if (isset($this->cachedNames[$propertyKey])) {
            $this->cachedNames[$propertyKey] = $newDisplayName;
        }

        $repo = $this->entityManager->getRepository(CatalogItemPropertyName::class);
        $property = $repo->findOneBy(['propertyKey' => $propertyKey]);

        if ($property) {
            $property->setDisplayName($newDisplayName);
            $this->entityManager->flush();
        }
    }

    public function getAllLabels(): array
    {
        return $this->cachedNames;
    }
}

