<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datecmd = null;

    #[ORM\Column(length: 255)]
    private ?string $lieucmd = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Produit::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(name: 'produit_id', referencedColumnName: 'id')]
    private ?Produit $produit = null;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDatecmd(): ?\DateTimeInterface
    {
        return $this->datecmd;
    }

    public function setDatecmd(\DateTimeInterface $datecmd): static
    {
        $this->datecmd = $datecmd;

        return $this;
    }

    public function getLieucmd(): ?string
    {
        return $this->lieucmd;
    }

    public function setLieucmd(string $lieucmd): static
    {
        $this->lieucmd = $lieucmd;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): static
    {
        $this->user = $user;

        return $this;
    }


    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }
    // public function __toString(): string
    // {
    //     // Customize the string representation of the Commande object here
    //     return (string) $this->id; // Assuming 'id' is a property of the Commande class
    // }
}

