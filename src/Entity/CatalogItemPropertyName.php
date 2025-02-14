<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'catalog_item_property_names')]
class CatalogItemPropertyName
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private string $propertyKey;

    #[ORM\Column(length: 255)]
    private string $displayName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPropertyKey(): string
    {
        return $this->propertyKey;
    }

    public function setPropertyKey(string $propertyKey): self
    {
        $this->propertyKey = $propertyKey;
        return $this;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;
        return $this;
    }
}
