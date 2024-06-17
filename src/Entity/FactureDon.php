<?php

namespace App\Entity;

use App\Repository\FactureDonRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FactureDonRepository::class)]
class FactureDon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champ Nom du donateur ne peut pas être vide.")]
    #[Assert\Length(max: 255, maxMessage: "Le champ Nom du donateur ne peut pas dépasser 255 caractères.")]
    #[Assert\Regex(
        pattern: '/^\D+$/',
        message: "Le champ Nom du donateur ne peut pas contenir des chiffres."
    )]
    private ?string $nom_donateur = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champ Prénom du donateur ne peut pas être vide.")]
    #[Assert\Length(max: 255, maxMessage: "Le champ Prénom du donateur ne peut pas dépasser 255 caractères.")]
    #[Assert\Regex(
        pattern: '/^\D+$/',
        message: "Le champ Prénom du donateur ne peut pas contenir des chiffres."
    )]
    private ?string $prenom_donateur = null;
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champ Email ne peut pas être vide.")]
    #[Assert\Email(message: "L'email '{{ value }}' n'est pas valide.")]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champ Adresse ne peut pas être vide.")]
    #[Assert\Length(max: 255, maxMessage: "Le champ Adresse ne peut pas dépasser 255 caractères.")]
    private ?string $adresses = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(message: "Le champ Numéro de téléphone ne peut pas être vide.")]
    #[Assert\Type(type: 'integer', message: "Le numéro de téléphone doit être un entier.")]
    private ?int $numero_telephone = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champ Description ne peut pas être vide.")]
    #[Assert\Length(
        min: 4,
        minMessage: "Le champ Description doit avoir au moins 4 caractères.",
        max: 255,
        maxMessage: "Le champ Description ne peut pas dépasser 255 caractères."
    )]

    
    private ?string $description = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Don $don = null;

  

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomDonateur(): ?string
    {
        return $this->nom_donateur;
    }

    public function setNomDonateur(string $nom_donateur): static
    {
        $this->nom_donateur = $nom_donateur;

        return $this;
    }

    public function getPrenomDonateur(): ?string
    {
        return $this->prenom_donateur;
    }

    public function setPrenomDonateur(string $prenom_donateur): static
    {
        $this->prenom_donateur = $prenom_donateur;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getAdresses(): ?string
    {
        return $this->adresses;
    }

    public function setAdresses(string $adresses): static
    {
        $this->adresses = $adresses;

        return $this;
    }

    public function getNumeroTelephone(): ?int
    {
        return $this->numero_telephone;
    }

    public function setNumeroTelephone(int $numero_telephone): static
    {
        $this->numero_telephone = $numero_telephone;

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

    // private ?Don $Don = null;

   /* public function getDon(): ?Don
    {
        return $this->Don;
    }

    public function setDon(?Don $Don): static
    {
        $this->Don = $Don;

        return $this;
    }*/

   public function getDon(): ?Don
   {
       return $this->don;
   }

   public function setDon(?Don $don): static
   {
       $this->don = $don;

       return $this;
   }

    
}
