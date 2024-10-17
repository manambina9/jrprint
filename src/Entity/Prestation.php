<?php

namespace App\Entity;

use App\Repository\PrestationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: PrestationRepository::class)]
#[Vich\Uploadable]
class Prestation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le titre ne peut pas être vide.")]
    private ?string $title = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: "La description ne peut pas être vide.")]
    private ?string $description = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Assert\NotNull(message: "Le prix ne peut pas être nul.")]
    #[Assert\Positive(message: "Le prix doit être positif.")]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La catégorie ne peut pas être vide.")]
    private ?string $category = null;

    #[ORM\Column(type: 'boolean')]
    #[Assert\NotNull(message: "La disponibilité doit être spécifiée.")]
    private bool $available = true;

    #[Vich\UploadableField(mapping: 'prestation_images', fileNameProperty: 'imageUrl')]
    private ?File $imageFile = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $imageUrl = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotNull(message: "La quantité doit être spécifiée.")]
    #[Assert\PositiveOrZero(message: "La quantité doit être zéro ou positive.")]
    private ?int $quantityAvailable = 0;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: "prestation", targetEntity: Promotion::class, cascade: ["persist", "remove"])]
    private Collection $promotions;

    public function __construct()
    {
        $this->promotions = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    // Getters et setters...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

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

    public function isAvailable(): bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): static
    {
        $this->available = $available;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if ($imageFile !== null) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
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

    public function getQuantityAvailable(): ?int
    {
        return $this->quantityAvailable;
    }

    public function setQuantityAvailable(int $quantityAvailable): static
    {
        $this->quantityAvailable = $quantityAvailable;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Promotion>
     */
    public function getPromotions(): Collection
    {
        return $this->promotions;
    }

    public function addPromotion(Promotion $promotion): static
    {
        if (!$this->promotions->contains($promotion)) {
            $this->promotions->add($promotion);
            $promotion->setPrestation($this);
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): static
    {
        if ($this->promotions->removeElement($promotion)) {
            // Unset the owning side of the relation if necessary
            if ($promotion->getPrestation() === $this) {
                $promotion->setPrestation(null);
            }
        }

        return $this;
    }

    public function getActivePromotion(): ?Promotion
    {
        foreach ($this->promotions as $promotion) {
            if ($promotion->isPromotionActive()) {
                return $promotion;
            }
        }

        return null;
    }

    public function getFinalPrice(): float
    {
        $activePromotion = $this->getActivePromotion();

        if ($activePromotion) {
            if ($activePromotion->getDiscountedPrice() !== null) {
                return $activePromotion->getDiscountedPrice();
            }
            if ($activePromotion->getDiscountPercentage() !== null) {
                return $this->price - ($this->price * ($activePromotion->getDiscountPercentage() / 100));
            }
        }

        return $this->price;
    }

    public function __toString(): string
    {
        return $this->title ?: 'No Title';
    }
}
