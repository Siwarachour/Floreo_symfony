<?php

namespace App\Entity;

use App\Repository\DonRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DonRepository::class)]
class Don
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;
   
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champ Type ne peut pas être vide.")]
    #[Assert\Length(max: 255, maxMessage: "Le champ Type ne peut pas dépasser 255 caractères.")]
    #[Assert\Regex(
        pattern: '/^\D+$/',
        message: "Le champ Type ne peut pas contenir des chiffres."
    )]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champ Description ne peut pas être vide.")]
    #[Assert\Length(
        min: 4,
        minMessage: "Le champ Description doit avoir au moins 4 caractères.",
        max: 255,
        maxMessage: "Le champ Description ne peut pas dépasser 255 caractères."
    )]
    private ?string $description = null;
   
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull(message: "Le champ Date de don ne peut pas être vide.")]
    private ?\DateTimeInterface $date_don = null;


    #[ORM\ManyToOne(inversedBy: 'dons')]
    private ?User $User = null;


    
    
    
    public function __toString(): string
    {
        return $this->type . ' - ' . $this->description;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

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

    public function getDateDon(): ?\DateTimeInterface
    {
        return $this->date_don;
    }

    public function setDateDon(\DateTimeInterface $date_don): static
    {
        $this->date_don = $date_don;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }

   /* public function getFacture(): ?FactureDon
    {
        return $this->facture;
    }

    public function setFacture(?FactureDon $facture): static
    {
        $this->facture = $facture;

        return $this;
    } */
    
}
