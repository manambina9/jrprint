<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PrestationRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PrestationRepository::class)]
#[Vich\Uploadable]
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
    private ?array $advantages = [];

    #[ORM\Column(type: 'boolean')]
    private bool $available;

    #[ORM\Column(type: 'json')]
    private ?array $characteristics = [];

    #[ORM\Column(type: 'json')]
    private ?array $images3d = [];

    #[ORM\Column(type: 'json')]
    private ?array $locations = [];

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageUrl = null;

    #[Vich\UploadableField(mapping: 'prestations_images', fileNameProperty: 'imageUrl')]
    #[Assert\File(
        maxSize: '50M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
        maxSizeMessage: 'Le fichier est trop volumineux. La taille maximale autorisée est de 5 Mo.',
        mimeTypesMessage: 'Seuls les fichiers JPEG, PNG ou WebP sont autorisés.'
    )]
    private ?File $imageFile = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?float $price = null;

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

    public function getAdvantages(): ?array
    {
        return $this->advantages;
    }

    public function setAdvantages(?array $advantages): static
    {
        $this->advantages = $advantages ?? [];
        return $this;
    }

    public function getCharacteristics(): ?array
    {
        return $this->characteristics;
    }

    public function setCharacteristics(?array $characteristics): static
    {
        $this->characteristics = $characteristics ?? [];
        return $this;
    }

    public function getImages3d(): ?array
    {
        return $this->images3d;
    }

    public function setImages3d(?array $images3d): static
    {
        $this->images3d = $images3d ?? [];
        return $this;
    }

    public function getLocations(): ?array
    {
        return $this->locations;
    }

    public function setLocations(?array $locations): static
    {
        $this->locations = $locations ?? [];
        return $this;
    }

    public function isAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): self
    {
        $this->available = $available;
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

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): self
    {
        $this->imageFile = $imageFile;

        if ($imageFile !== null) {
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;
        return $this;
    }

    #[Callback]
    public function validateLocation(ExecutionContextInterface $context): void
    {
        if ($this->category === 'Panneau' && empty($this->locations)) {
            $context->buildViolation("Le champ de localisation est obligatoire pour les panneaux.")
                ->atPath('locations')
                ->addViolation();
        }
    }
    public function __toString(): string
    {
        return $this->name ?: 'Prestation';
    }
}