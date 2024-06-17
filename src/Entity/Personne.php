<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PersonneRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * Personne
 *
 * @ORM\Table(name="personne")
 * @ORM\Entity(repositoryClass=PersonneRepository::class)
 * @UniqueEntity(fields={"mail"}, message="There is already an account with this email")
 */
class Personne implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups ("post:read")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     * @Assert\NotBlank(message="Le Champ Nom est obligatoire")
     * @Assert\Length(
     *     min=3,
     *     max=50,
     *     minMessage="doit contenir au moins 3 carcatères ",
     *     maxMessage="doit contenir au plus 20 carcatères"
     * )
     * @Groups ("post:read")
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=50, nullable=false)
     * @Assert\NotBlank(message="Le Champ Prenom est obligatoire")
     * @Assert\Length(
     *     min=3,
     *     max=50,
     *     minMessage="doit contenir au moins 3 carcatères ",
     *     maxMessage="doit contenir au plus 20 carcatères"
     * )
     * @Groups ("post:read")
     */
    private $prenom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datenaissance", type="date", nullable=false)
     * @Groups ("post:read")
     */
    private $datenaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=150, nullable=false)
     * @Assert\NotBlank(message="Adresse is required")
     * @Groups ("post:read")
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=100, nullable=false)
     * @Assert\NotBlank(message="Email is required")
     * @Assert\Email(message = "The email '{{ value }}' is not a valid
    email.")
     * @Groups ("post:read")
     */
    private $mail;

    /**
     * @var int
     *
     * @ORM\Column(name="tel", type="integer", nullable=false)
     * @Assert\NotBlank(message="Le Champ Telephhone est obligatoire")
     * @Assert\Length(
     *     min=8,
     *     minMessage="Le numéro de telephone doit au moins contenir 8 cacartères  "
     * )
     * @Groups ("post:read")
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=20, nullable=false)
     * @Groups ("post:read")
     */
    private $role;

    /**
     * @var string
     *
     * @var string The hashed password
     * @ORM\Column(name="mdp", type="string", length=50, nullable=false)
     * @Assert\NotBlank(message="Le Champ Mot de passe est obligatoire")
     * @Assert\Length(
     *     min=5,
     *     max=50,
     *     minMessage="doit contenir au moins 5 carcatères ",
     *     maxMessage="doit contenir au plus 20 carcatères"
     * )
     * @Groups ("post:read")
     */
    private $mdp;

    /**
     * @var string
     *
     * @ORM\Column(name="nomEquipe", type="string", length=50, nullable=false)
     * @Groups ("post:read")
     *
     */
    private $nomequipe;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=50, nullable=false)
     * @Groups ("post:read")
     */
    private $etat;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=300, nullable=false)
     * @Groups ("post:read")
     */
    private $image;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(int $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }


    /**
     * @see UserInterface
     */

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): self
    {
        $this->mdp = $mdp;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }


    public function getNomequipe(): ?string
    {
        return $this->nomequipe;
    }

    public function setNomequipe(string $nomequipe): self
    {
        $this->nomequipe = $nomequipe;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }


    public function getRoles()
    {
        return array($this->getRole());
    }
    /**
     *  @see UserInterface
     */
    public function getPassword():string
    {
        return (string)$this->mdp;
    }
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername():string
    {
        return (string) $this->mail;
    }
    public function supportsClass($class)
    {
        return $class === Personne::class;
    }
    // captcha
    protected $captchaCode;

    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }

    public function setCaptchaCode($captchaCode)
    {
        $this->captchaCode = $captchaCode;
    }
}