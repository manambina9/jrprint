<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Le numéro de facture ne peut pas être vide.")]
    private ?string $numero = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotNull(message: "La date ne peut pas être vide.")]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Assert\NotNull(message: "Le client doit être spécifié.")]
    private ?User $client = null;

    #[ORM\ManyToMany(targetEntity: Prestation::class)]
    private Collection $prestations;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?float $montantTotal = null;

    public function __construct()
    {
        $this->prestations = new ArrayCollection();
    }

    // Getters et setters...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getPrestations(): Collection
    {
        return $this->prestations;
    }

    public function addPrestation(Prestation $prestation): static
    {
        if (!$this->prestations->contains($prestation)) {
            $this->prestations[] = $prestation;
        }

        return $this;
    }

    public function removePrestation(Prestation $prestation): static
    {
        $this->prestations->removeElement($prestation);

        return $this;
    }

    public function getMontantTotal(): ?float
    {
        return $this->montantTotal;
    }

    public function setMontantTotal(float $montantTotal): static
    {
        $this->montantTotal = $montantTotal;

        return $this;
    }

    public function calculerMontantTotal(): void
    {
        $total = 0;
        foreach ($this->prestations as $prestation) {
            $total += $prestation->getPrice();
        }
        $this->setMontantTotal($total);
    }

}