<?php

namespace App\Service;

use App\Entity\Country;
use Doctrine\ORM\EntityManagerInterface;

class CountryService
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    public function getAll(): array
    {
        return $this->entityManager->getRepository(Country::class)->findAll();
    }

    public function getById(int $id): ?Country
    {
        return $this->entityManager->getRepository(Country::class)->find($id);
    }

    public function create(Country $country): void
    {
        $this->entityManager->persist($country);
        $this->entityManager->flush();
    }

    public function delete(Country $country): void
    {
        $this->entityManager->remove($country);
        $this->entityManager->flush();
    }
}