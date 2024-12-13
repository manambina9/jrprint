<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use DateTimeImmutable;

#[ORM\Entity]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotNull(message: "La date de commande ne peut pas être vide.")]
    private ?DateTimeImmutable $dateCommande = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $client = null;

    #[ORM\ManyToMany(targetEntity: Prestation::class)]
    #[ORM\JoinTable(name: 'commande_prestation')]
    private Collection $prestations;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: "Le détail de la commande ne peut pas être vide.")]
    private ?string $detailCommande = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Assert\NotNull(message: "Le montant total ne peut pas être nul.")]
    #[Assert\Positive(message: "Le montant total doit être positif.")]
    private ?float $montantTotal = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Le statut de la commande ne peut pas être vide.")]
    private ?string $statut = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $dateDebutLocation = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $dateFinLocation = null;

    public function __construct()
    {
        $this->dateCommande = new DateTimeImmutable();
        $this->statut = 'en attente';
        $this->prestations = new ArrayCollection();
    }

    // Getters et Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCommande(): ?DateTimeImmutable
    {
        return $this->dateCommande;
    }

    public function setDateCommande(DateTimeImmutable $dateCommande): self
    {
        $this->dateCommande = $dateCommande;
        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;
        return $this;
    }

    public function getPrestations(): Collection
    {
        return $this->prestations;
    }

    public function addPrestation(Prestation $prestation): self
    {
        if (!$this->prestations->contains($prestation)) {
            $this->prestations[] = $prestation;
        }
        return $this;
    }

    public function removePrestation(Prestation $prestation): self
    {
        $this->prestations->removeElement($prestation);
        return $this;
    }

    public function getDetailCommande(): ?string
    {
        return $this->detailCommande;
    }

    public function setDetailCommande(string $detailCommande): self
    {
        $this->detailCommande = $detailCommande;
        return $this;
    }

    public function getMontantTotal(): ?float
    {
        return $this->montantTotal;
    }

    public function setMontantTotal(float $montantTotal): self
    {
        $this->montantTotal = $montantTotal;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function getDateDebutLocation(): ?DateTimeImmutable
    {
        return $this->dateDebutLocation;
    }

    public function setDateDebutLocation(?DateTimeImmutable $dateDebutLocation): self
    {
        $this->dateDebutLocation = $dateDebutLocation;
        return $this;
    }

    public function getDateFinLocation(): ?DateTimeImmutable
    {
        return $this->dateFinLocation;
    }

    public function setDateFinLocation(?DateTimeImmutable $dateFinLocation): self
    {
        $this->dateFinLocation = $dateFinLocation;
        return $this;
    }
}
