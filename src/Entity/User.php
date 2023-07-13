<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'Un compte est déjà enregistré avec cette adresse mail.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(length: 130)]
    private ?string $password = null;

    #[ORM\Column(length: 30)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 40)]
    private ?string $firstname = null;

    #[ORM\Column(length: 40)]
    private ?string $lastname = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $avatar = null;

    #[ORM\Column(length: 5)]
    private ?string $cp = null;

    #[ORM\Column(length: 100)]
    private ?string $address = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $telUser = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbTerrain = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Sujet::class)]
    private Collection $sujets;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Message::class)]
    private Collection $messages;

    #[ORM\ManyToMany(targetEntity: Reponse::class, mappedBy: 'Users')]
    private Collection $Reponses;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    public function __construct()
    {
        $this->sujets = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->Reponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstname;
    }

    public function setFirstName(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastname;
    }

    public function setLastName(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getTelUser(): ?string
    {
        return $this->telUser;
    }

    public function setTelUser(?string $telUser): self
    {
        $this->telUser = $telUser;

        return $this;
    }

    public function getNbTerrain(): ?int
    {
        return $this->nbTerrain;
    }

    public function setNbTerrain(?int $nbTerrain): self
    {
        $this->nbTerrain = $nbTerrain;

        return $this;
    }

    /**
     * @return Collection<int, Sujet>
     */
    public function getSujets(): Collection
    {
        return $this->sujets;
    }

    public function addSujet(Sujet $sujet): self
    {
        if (!$this->sujets->contains($sujet)) {
            $this->sujets->add($sujet);
            $sujet->setUser($this);
        }

        return $this;
    }

    public function removeSujet(Sujet $sujet): self
    {
        if ($this->sujets->removeElement($sujet)) {
            // set the owning side to null (unless already changed)
            if ($sujet->getUser() === $this) {
                $sujet->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, Reponse>
     */
    public function getReponses(): Collection
    {
        return $this->Reponses;
    }

    /**
     * @param int $idQuestionnaire
     * @return array
     */
    public function getReponsesOfAQuestionnaire(int $idQuestionnaire): array
    {
        $reponses = [];
        foreach ($this->getReponses() as $reponse) {
            if ($reponse->getQuestion()->getQuestionnaire()->getId()
                ==
                $idQuestionnaire) {
                $reponses[] = $reponse;
            }
        }

        return $reponses;
    }

    public function addReponse(Reponse $reponse): self
    {
        if (!$this->Reponses->contains($reponse)) {
            $this->Reponses->add($reponse);
            $reponse->addUser($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->Reponses->removeElement($reponse)) {
            $reponse->removeUser($this);
        }

        return $this;
    }

    /**
     * Suprime toutes les réponses d'un questionnaire précis enregistré sur l'utilisateur.
     *
     * @return $this
     */
    public function removeReponseOfAQuestionnaire(int $idQuestionnaire): self
    {
        foreach ($this->getReponses() as $reponse) {
            if ($reponse->getQuestion()->getQuestionnaire()->getId()
                ==
                $idQuestionnaire) {
                $this->removeReponse($reponse);
            }
        }

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

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}
