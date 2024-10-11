<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotNull(message: "La date de commande ne peut pas être vide.")]
    private ?\DateTimeImmutable $dateCommande = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $client = null;

    #[ORM\ManyToOne(targetEntity: Prestation::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Prestation $prestation = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: "Le détail de la commande ne peut pas être vide.")]
    private ?string $detailCommande = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Assert\NotNull(message: "Le montant total ne peut pas être nul.")]
    #[Assert\Positive(message: "Le montant total doit être positif.")]
    private ?float $montantTotal = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Le statut de la commande ne peut pas être vide.")]
    private ?string $statut = null; // Ajout de la propriété statut

    public function __construct()
    {
        $this->dateCommande = new \DateTimeImmutable();
        $this->statut = 'en attente'; // Statut par défaut
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCommande(): ?\DateTimeImmutable
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeImmutable $dateCommande): static
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(User $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getPrestation(): ?Prestation
    {
        return $this->prestation;
    }

    public function setPrestation(Prestation $prestation): static
    {
        $this->prestation = $prestation;

        return $this;
    }

    public function getDetailCommande(): ?string
    {
        return $this->detailCommande;
    }

    public function setDetailCommande(string $detailCommande): static
    {
        $this->detailCommande = $detailCommande;

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
 
    public function getStatut(): ?string
    {
        return $this->statut;
    }
 
    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }
}
