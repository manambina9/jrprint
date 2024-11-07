<?php

namespace App\Entity;

use App\Repository\PrestationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrestationRepository::class)]
class Prestation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    #[ORM\Column(type: 'json')]
    private ?array $advantages = []; // Modification pour autoriser null

    #[ORM\Column(type: 'json')]
    private ?array $characteristics = []; // Modification pour autoriser null

    #[ORM\Column(type: 'json')]
    private ?array $images3d = []; // Modification pour autoriser null

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $price = null;

    #[ORM\Column(type: 'json')]
    private ?array $locations = []; // Modification pour autoriser null

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getAdvantages(): ?array // Modification pour renvoyer null
    {
        return $this->advantages;
    }

    public function setAdvantages(?array $advantages): static
    {
        $this->advantages = $advantages ?? []; // Assigne un tableau vide si null
        return $this;
    }

    public function getCharacteristics(): ?array // Modification pour renvoyer null
    {
        return $this->characteristics;
    }

    public function setCharacteristics(?array $characteristics): static
    {
        $this->characteristics = $characteristics ?? []; // Assigne un tableau vide si null
        return $this;
    }

    public function getImages3d(): ?array // Modification pour renvoyer null
    {
        return $this->images3d;
    }

    public function setImages3d(?array $images3d): static
    {
        $this->images3d = $images3d ?? []; // Assigne un tableau vide si null
        return $this;
    }

    public function getLocations(): ?array // Modification pour renvoyer null
    {
        return $this->locations;
    }

    public function setLocations(?array $locations): static
    {
        $this->locations = $locations ?? []; // Assigne un tableau vide si null
        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;
        return $this;
    }
}
