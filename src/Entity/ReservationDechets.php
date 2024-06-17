<?php

namespace App\Entity;

use App\Repository\ReservationDechetsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;  


#[ORM\Entity(repositoryClass: ReservationDechetsRepository::class)]
class ReservationDechets
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
   
   
    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Regex('/^\d+$/')]
    private ?int $quantite = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
   // #[Assert\Date]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $date = null;
    
   
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    //#[Assert\Date]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $date_ramassage = null;
   
   
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 4,minMessage: "veuillez avoir au minimum 4 caractere" )]
    #[Assert\Regex(
    pattern: '/\d/',
    match: false,
    message: 'Your name cannot contain a number',)]
    private ?string $nom_fournisseur = null;


  
    #[ORM\Column]
    #[Assert\NotBlank(message: "Le numéro de téléphone ne peut pas être vide.")]
    #[Assert\Type(type: 'numeric', message: "Le numéro de téléphone doit être un nombre.")]
    #[Assert\Length(
        min: 10, 
        max: 10, 
        exactMessage: "Le numéro de téléphone doit comporter exactement 10 chiffres."
    )]
    private ?string $numero_tell = null;    

    #[ORM\ManyToOne(inversedBy: 'reservationDechets')]
    private ?User $User = null;

    #[ORM\OneToMany(mappedBy: 'ReservationDechets', targetEntity: Dechets::class)]
    private Collection $dechets;

    
    public function __toString() {
    // Format the date_ramassage to a string. Adjust the format as needed.
    $dateRamassageFormatted = $this->date_ramassage ? $this->date_ramassage->format('Y-m-d') : 'No date';

    // Return a string representation combining nom_fournisseur and date_ramassage
    return $this->nom_fournisseur . ' - ' . $dateRamassageFormatted;
}


    public function __construct()
    {
        $this->dechets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getDateRamassage(): ?\DateTimeInterface
    {
        return $this->date_ramassage;
    }

    public function setDateRamassage(\DateTimeInterface $date_ramassage): static
    {
        $this->date_ramassage = $date_ramassage;

        return $this;
    }

    public function getNomFournisseur(): ?string
    {
        return $this->nom_fournisseur;
    }

    public function setNomFournisseur(string $nom_fournisseur): static
    {
        $this->nom_fournisseur = $nom_fournisseur;

        return $this;
    }

    public function getNumeroTell(): ?int
    {
        return $this->numero_tell;
    }

    public function setNumeroTell(int $numero_tell): static
    {
        $this->numero_tell = $numero_tell;

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

    /**
     * @return Collection<int, Dechets>
     */
    public function getDechets(): Collection
    {
        return $this->dechets;
    }

    public function addDechet(Dechets $dechet): static
    {
        if (!$this->dechets->contains($dechet)) {
            $this->dechets->add($dechet);
            $dechet->setReservationDechets($this);
        }

        return $this;
    }

    public function removeDechet(Dechets $dechet): static
    {
        if ($this->dechets->removeElement($dechet)) {
            // set the owning side to null (unless already changed)
            if ($dechet->getReservationDechets() === $this) {
                $dechet->setReservationDechets(null);
            }
        }

        return $this;
    }
    
 
}

