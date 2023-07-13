<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $txtReponse = null;

    #[ORM\ManyToOne(inversedBy: 'Reponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'Reponses')]
    private Collection $Users;

    #[ORM\Column(options: ['default' => 0])]
    private ?int $nombrePoints = null;

    public function __construct()
    {
        $this->Users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTxtReponse(): ?string
    {
        return $this->txtReponse;
    }

    public function setTxtReponse(string $txtReponse): self
    {
        $this->txtReponse = $txtReponse;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->Users;
    }

    public function addUser(User $user): self
    {
        if (!$this->Users->contains($user)) {
            $this->Users->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->Users->removeElement($user);

        return $this;
    }

    public function getNombrePoints(): ?int
    {
        return $this->nombrePoints;
    }

    public function setNombrePoints(int $nombrePoints): self
    {
        $this->nombrePoints = $nombrePoints;

        return $this;
    }
}
