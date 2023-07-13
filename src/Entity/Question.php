<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $txtQuestion = null;

    #[ORM\ManyToOne(inversedBy: 'Questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Questionnaire $questionnaire = null;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Reponse::class, cascade: ['persist', 'remove'])]
    #[Assert\Count(min: 2)]
    private Collection $Reponses;

    #[ORM\Column]
    #[Assert\Positive]
    private ?int $numero = null;

    #[ORM\ManyToOne(inversedBy: 'Question')]
    private ?CategorieQuestion $categorieQuestion = null;

    public function __construct()
    {
        $this->Reponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTxtQuestion(): ?string
    {
        return $this->txtQuestion;
    }

    public function setTxtQuestion(string $txtQuestion): self
    {
        $this->txtQuestion = $txtQuestion;

        return $this;
    }

    public function getQuestionnaire(): ?Questionnaire
    {
        return $this->questionnaire;
    }

    public function setQuestionnaire(?Questionnaire $questionnaire): self
    {
        $this->questionnaire = $questionnaire;

        return $this;
    }

    /**
     * @return Collection<int, Reponse>
     */
    public function getReponses(): Collection
    {
        return $this->Reponses;
    }

    public function addReponse(Reponse $rPonse): self
    {
        if (!$this->Reponses->contains($rPonse)) {
            $this->Reponses->add($rPonse);
            $rPonse->setQuestion($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $rPonse): self
    {
        if ($this->Reponses->removeElement($rPonse)) {
            // set the owning side to null (unless already changed)
            if ($rPonse->getQuestion() === $this) {
                $rPonse->setQuestion(null);
            }
        }

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getCategorieQuestion(): ?CategorieQuestion
    {
        return $this->categorieQuestion;
    }

    public function setCategorieQuestion(?CategorieQuestion $categorieQuestion): self
    {
        $this->categorieQuestion = $categorieQuestion;

        return $this;
    }

    public function getNoteMax(): int
    {
        $max = 0;
        foreach ($this->getReponses() as $reponse) {
            if ($reponse->getNombrePoints() > $max) {
                $max = $reponse->getNombrePoints();
            }
        }

        return $max;
    }
}
