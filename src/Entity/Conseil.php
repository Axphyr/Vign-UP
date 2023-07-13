<?php

namespace App\Entity;

use App\Repository\ConseilRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConseilRepository::class)]
class Conseil
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $txtConseil = null;

    #[ORM\Column]
    private ?int $noteMinimale = null;

    #[ORM\ManyToOne(inversedBy: 'conseils')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Questionnaire $questionnaire = null;

    #[ORM\ManyToOne(inversedBy: 'conseils')]
    private ?CategorieQuestion $categorieQuestion = null;

    #[ORM\Column(nullable: true)]
    private ?bool $partieConnecte = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTxtConseil(): ?string
    {
        return $this->txtConseil;
    }

    public function setTxtConseil(string $txtConseil): self
    {
        $this->txtConseil = $txtConseil;

        return $this;
    }

    public function getNoteMinimale(): ?int
    {
        return $this->noteMinimale;
    }

    public function setNoteMinimale(int $noteMinimale): self
    {
        $this->noteMinimale = $noteMinimale;

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

    public function getCategorieQuestion(): ?CategorieQuestion
    {
        return $this->categorieQuestion;
    }

    public function setCategorieQuestion(?CategorieQuestion $categorieQuestion): self
    {
        $this->categorieQuestion = $categorieQuestion;

        return $this;
    }

    public function isPartieConnecte(): ?bool
    {
        return $this->partieConnecte;
    }

    public function setPartieConnecte(?bool $partieConnecte): self
    {
        $this->partieConnecte = $partieConnecte;

        return $this;
    }
}
