<?php

namespace App\Entity;

use App\Repository\CategorieQuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategorieQuestionRepository::class)]
class CategorieQuestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 55)]
    private ?string $Nom = null;

    #[ORM\OneToMany(mappedBy: 'categorieQuestion', targetEntity: Question::class)]
    private Collection $Question;

    #[ORM\OneToMany(mappedBy: 'categorieQuestion', targetEntity: Conseil::class)]
    private Collection $conseils;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function __construct()
    {
        $this->Question = new ArrayCollection();
        $this->conseils = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestion(): Collection
    {
        return $this->Question;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->Question->contains($question)) {
            $this->Question->add($question);
            $question->setCategorieQuestion($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->Question->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getCategorieQuestion() === $this) {
                $question->setCategorieQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Conseil>
     */
    public function getConseils(): Collection
    {
        return $this->conseils;
    }

    public function addConseil(Conseil $conseil): self
    {
        if (!$this->conseils->contains($conseil)) {
            $this->conseils->add($conseil);
            $conseil->setCategorieQuestion($this);
        }

        return $this;
    }

    public function removeConseil(Conseil $conseil): self
    {
        if ($this->conseils->removeElement($conseil)) {
            // set the owning side to null (unless already changed)
            if ($conseil->getCategorieQuestion() === $this) {
                $conseil->setCategorieQuestion(null);
            }
        }

        return $this;
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
}
