<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert; 
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100, nullable: false, unique: true)]
    #[Assert\NotBlank(message: 'Email is required')]
    #[Assert\Email(message: 'The email "{{ value }}" is not a valid email.')]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private ?string $password = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank(message: 'Le Champ name est obligatoire')]
    #[Assert\Length(min: 3, max: 50, minMessage: 'Doit contenir au moins 3 caractères', maxMessage: 'Doit contenir au plus 50 caractères')]
    #[Groups('post:read')]
    private ?string $name = null;

    #[ORM\Column(type: 'string', length: 50, nullable: false)]
    #[Assert\NotBlank(message: 'Le Champ Lastname est obligatoire')]
    #[Assert\Length(min: 3, max: 50, minMessage: 'doit contenir au moins 3 caractères', maxMessage: 'doit contenir au plus 50 caractères')]
    #[Groups('post:read')]
    private ?string $lastname = null;

    #[ORM\Column(type: 'integer', nullable: false)]
    #[Assert\NotBlank(message: 'Le Champ Telephhone est obligatoire')]
    #[Assert\Length(min: 8, minMessage: 'Le numéro de telephone doit au moins contenir 8 caractères')]
    #[Groups('post:read')]
    private ?int $number = null;

    #[ORM\Column]
    private array $roles = [];
    
    #[ORM\Column(type: "string", length: 180, nullable: true)]
     private  $reset_token =null;
 
     #[ORM\Column(name:"image", type:"string", length:300, nullable:false)]
    
    private  $image;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Commande::class)]
    private Collection $commandes;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: Reclamation::class)]
    private Collection $reclamations;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: Don::class)]
    private Collection $dons;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: ReservationDechets::class)]
    private Collection $reservationDechets;

    #[ORM\ManyToMany(targetEntity: Evenement::class, mappedBy: 'User')]
    private Collection $evenements;

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;
    #[ORM\Column]
    private ?bool $isBanned = null;
    

    public function __construct()
    {
        
        $this->roles = ['ROLE_USER']; // Par défaut, tous les utilisateurs ont le rôle ROLE_USER
        $this->commandes = new ArrayCollection();
        $this->reclamations = new ArrayCollection();
        $this->dons = new ArrayCollection();
        $this->reservationDechets = new ArrayCollection();
        $this->evenements = new ArrayCollection();
        
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
    
    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }
    public function isIsBanned(): ?bool
    {
        return $this->isBanned;
    }

    public function setIsBanned(bool $isBanned): static
    {
        $this->isBanned = $isBanned;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;
        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }
    
    public function setRoles(?array $roles): self
    {
        // Vérifier si $roles est null, sinon, affectez le tableau des rôles
        $this->roles = $roles ?? [];
        return $this;
    }

    // Méthodes pour les autres relations et propriétés...

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;
        return $this;
    }
    public function getResetToken()
    {
        return $this->reset_token;
    }

    /**
     * @param mixed $reset_token
     */
    public function setResetToken($reset_token): void
    {
        $this->reset_token = $reset_token;
    }
    
    #[ORM\Column(name: "datenaissance", type: "date", nullable: false)]
    #[Groups("post:read")]
    private \DateTimeInterface $datenaissance;

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }
    public function getDatenaissance(): ?\DateTimeInterface
{
    return $this->datenaissance;
}

public function setDatenaissance(\DateTimeInterface $datenaissance): self
{
    $this->datenaissance = $datenaissance;

    return $this;
}
}
