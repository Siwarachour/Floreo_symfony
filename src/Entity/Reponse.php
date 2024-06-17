<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo ;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Describtion ne doit pas être vide.')]
    #[Assert\Length(
        min: 5,
        minMessage: 'Il faut inserer au moins {{ limit }} characteres',
    )]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    
    #[Gedmo\Timestampable(on: "create")]
    private ?\DateTimeInterface $dateajout = null;
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]

    #[Gedmo\Timestampable(on: "update")]
    private ?\DateTimeInterface $datemodif = null;

    #[ORM\ManyToOne(inversedBy: 'reponses')]
    #[ORM\JoinColumn(name: 'reclamation_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ?Reclamation $Reclamation = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateajout(): ?\DateTimeInterface
    {
        return $this->dateajout;
    }

    public function setDateajout(\DateTimeInterface $dateajout): static
    {
        $this->dateajout = $dateajout;

        return $this;
    }
    public function getDatemodif(): ?\DateTimeInterface
    {
        return $this->datemodif;
    }

    public function setDatemodif(\DateTimeInterface $datemodif): static
    {
        $this->datemodif = $datemodif;

        return $this;
    }

    public function getReclamation(): ?Reclamation
    {
        return $this->Reclamation;
    }

    public function setReclamation(?Reclamation $Reclamation): static
    {
        $this->Reclamation = $Reclamation;

        return $this;
    }

    public function __construct()
{
    $this->dateajout = new \DateTime();
    $this->datemodif = new \DateTime();
}
}
