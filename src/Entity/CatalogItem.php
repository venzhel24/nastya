<?php

namespace App\Entity;

use App\Enum\CatalogType;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\Table(name: 'catalog_items')]
class CatalogItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private string $description;

    #[ORM\Column(enumType: CatalogType::class)]
    private CatalogType $type;

    #[ORM\Column(type: Types::JSON)]
    private array $advantages = [];

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $publishedAt;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $photoPath = null;

    #[ORM\ManyToMany(targetEntity: Manufacturer::class, inversedBy: 'catalogItems')]
    #[ORM\JoinTable(name: 'catalog_item_manufacturer')]
    private ?array $manufacturers = [];

    #[ORM\ManyToOne(targetEntity: Country::class)]
    private ?Country $country = null;

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getSection(): ?CatalogSection
    {
        return $this->section;
    }

    public function setSection(?CatalogSection $section): void
    {
        $this->section = $section;
    }

    #[ORM\ManyToOne(targetEntity: CatalogSection::class)]
    private ?CatalogSection $section = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getType(): CatalogType
    {
        return $this->type;
    }

    public function setType(CatalogType $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getAdvantages(): array
    {
        return $this->advantages;
    }

    public function setAdvantages(array $advantages): self
    {
        $this->advantages = $advantages;
        return $this;
    }

    public function getPublishedAt(): \DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;
        return $this;
    }

    public function getManufacturers(): ?array
    {
        return $this->manufacturers;
    }

    public function setManufacturers(array $manufacturers): self
    {
        $this->manufacturers = $manufacturers;
        return $this;
    }

    public function addManufacturer(Manufacturer $manufacturer): self
    {
        if (!in_array($manufacturer, $this->manufacturers, true)) {
            $this->manufacturers[] = $manufacturer;
        }

        return $this;
    }

    public function removeManufacturer(Manufacturer $manufacturer): self
    {
        $this->manufacturers = array_filter($this->manufacturers, fn($m) => $m !== $manufacturer);
        return $this;
    }

    public function getPhotoPath(): ?string
    {
        return $this->photoPath;
    }

    public function setPhotoPath(string $photoPath): self
    {
        $this->photoPath = $photoPath;
        return $this;
    }
}
