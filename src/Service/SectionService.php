<?php

namespace App\Service;

use App\Entity\CatalogSection;
use Doctrine\ORM\EntityManagerInterface;

class SectionService
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getAll(): array
    {
        return $this->entityManager->getRepository(CatalogSection::class)->findAll();
    }

    public function getById(int $id): ?CatalogSection
    {
        return $this->entityManager->getRepository(CatalogSection::class)->find($id);
    }

    public function create(CatalogSection $section): void
    {
        $this->entityManager->persist($section);
        $this->entityManager->flush();
    }

    public function delete(CatalogSection $section): void
    {
        $this->entityManager->remove($section);
        $this->entityManager->flush();
    }
}