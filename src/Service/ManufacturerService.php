<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Manufacturer;
use App\Entity\Country;
use App\Entity\CatalogSection;

class ManufacturerService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function getAll(): array
    {
        return $this->entityManager->getRepository(Manufacturer::class)->findAll();
    }

    public function getById(int $id): ?Manufacturer
    {
        return $this->entityManager->getRepository(Manufacturer::class)->find($id);
    }

    public function create(Manufacturer $manufacturer): void
    {
        $this->entityManager->persist($manufacturer);
        $this->entityManager->flush();
    }

    public function update(): void
    {
        $this->entityManager->flush();
    }

    public function delete(Manufacturer $manufacturer): void
    {
        $this->entityManager->remove($manufacturer);
        $this->entityManager->flush();
    }
}