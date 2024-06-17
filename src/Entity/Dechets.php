<?php

namespace App\Entity;

use App\Repository\DechetsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



    
    #[ORM\Entity(repositoryClass: DechetsRepository::class)]
    class Dechets
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;
    
        #[ORM\Column(length: 255)]
        #[Assert\NotBlank(message: 'Le type de déchet est obligatoire')]
        #[Assert\Length(min: 4, minMessage: 'Le type de déchet doit avoir au moins 4 caractères')]
        #[Assert\Regex(pattern: '/[0-9]/', match: false, message: 'Le type de déchet ne peut pas être un nombre')]
        private ?string $type = null;
    
        #[ORM\Column(type: Types::DATE_MUTABLE)]
        #[Assert\NotBlank(message: 'La date d\'entrée est obligatoire')]
        private ?\DateTimeInterface $date_entre = null;
    
        #[ORM\Column]
        #[Assert\NotBlank(message: 'La quantité est obligatoire')]
        #[Assert\GreaterThanOrEqual(value: 0, message: 'La quantité doit être supérieure ou égale à 0')]
        #[Assert\Type('numeric')]
        private ?int $quantite = null;
    
        #[ORM\Column(length: 255)]
        #[Assert\NotBlank(message: 'La description est obligatoire')]
        #[Assert\Regex(pattern: '/[0-9]/', match: false, message: 'La description ne peut pas contenir de chiffres')]
        private ?string $description = null;
    
        #[ORM\ManyToOne(inversedBy: 'dechets')]
        private ?ReservationDechets $ReservationDechets = null;

        #[ORM\Column(length: 255)]
        private ?string $image = null;
    
        // ...
    
    
    public function __toString() {
        
       // $dateRamassageFormatted = $this->date_ramassage ? $this->date_ramassage->format('Y-m-d') : 'No date';
        return $this->type;
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

    public function getDateEntre(): ?\DateTimeInterface
    {
        return $this->date_entre;
    }

    public function setDateEntre(\DateTimeInterface $date_entre): static
    {
        $this->date_entre = $date_entre;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getReservationDechets(): ?ReservationDechets
    {
        return $this->ReservationDechets;
    }

    public function setReservationDechets(?ReservationDechets $ReservationDechets): static
    {
        $this->ReservationDechets = $ReservationDechets;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

  

 
    
}
