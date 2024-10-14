<?php

namespace App\Entity;

use App\Repository\PromotionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use DateTimeImmutable;

#[ORM\Entity(repositoryClass: PromotionRepository::class)]
class Promotion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2, nullable: true)]
    #[Assert\PositiveOrZero(message: "Le pourcentage de réduction doit être zéro ou positif.")]
    private ?float $discountPercentage = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    #[Assert\Positive(message: "Le prix réduit doit être positif.")]
    private ?float $discountedPrice = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotBlank(message: "La date de début de la promotion ne peut pas être vide.")]
    private ?DateTimeImmutable $promotionStart = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotBlank(message: "La date de fin de la promotion ne peut pas être vide.")]
    private ?DateTimeImmutable $promotionEnd = null;

    #[ORM\ManyToOne(targetEntity: Prestation::class, inversedBy: "promotions")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Prestation $prestation = null;

    // Getters et setters...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiscountPercentage(): ?float
    {
        return $this->discountPercentage;
    }

    public function setDiscountPercentage(?float $discountPercentage): static
    {
        $this->discountPercentage = $discountPercentage;

        return $this;
    }

    public function getDiscountedPrice(): ?float
    {
        return $this->discountedPrice;
    }

    public function setDiscountedPrice(?float $discountedPrice): static
    {
        $this->discountedPrice = $discountedPrice;

        return $this;
    }

    public function getPromotionStart(): ?DateTimeImmutable
    {
        return $this->promotionStart;
    }

    public function setPromotionStart(DateTimeImmutable $promotionStart): static
    {
        $this->promotionStart = $promotionStart;

        return $this;
    }

    public function getPromotionEnd(): ?DateTimeImmutable
    {
        return $this->promotionEnd;
    }

    public function setPromotionEnd(DateTimeImmutable $promotionEnd): static
    {
        $this->promotionEnd = $promotionEnd;

        return $this;
    }

    public function getPrestation(): ?Prestation
    {
        return $this->prestation;
    }

    public function setPrestation(?Prestation $prestation): static
    {
        $this->prestation = $prestation;

        return $this;
    }

    public function isPromotionActive(): bool
    {
        $now = new DateTimeImmutable();

        return $this->promotionStart !== null && $this->promotionEnd !== null &&
               $now >= $this->promotionStart && $now <= $this->promotionEnd;
    }
}
