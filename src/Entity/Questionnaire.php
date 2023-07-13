<?php

namespace App\Entity;

use App\Repository\QuestionnaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionnaireRepository::class)]
class Questionnaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $partieConnecte = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\OneToMany(mappedBy: 'questionnaire', targetEntity: Question::class, cascade: ['persist', 'remove'])]
    private Collection $Questions;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $imagePresentation = null;

    #[ORM\Column]
    private array $roleConnecte = ['ROLE_USER'];

    #[ORM\OneToMany(mappedBy: 'questionnaire', targetEntity: Conseil::class, cascade: ['persist', 'remove'])]
    private Collection $conseils;

    public function __construct()
    {
        $this->Questions = new ArrayCollection();
        $this->conseils = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPartieConnecte(): ?int
    {
        return $this->partieConnecte;
    }

    public function setPartieConnecte(?int $partieConnecte): self
    {
        $this->partieConnecte = $partieConnecte;

        return $this;
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
    public function getQuestions(bool $OnlyPartieNonConnecte = false, ?CategorieQuestion $categorieQuestion = null): Collection
    {
        if ($OnlyPartieNonConnecte && 0 != $this->getPartieConnecte() && null != $this->getPartieConnecte()) {
            $criteria = Criteria::create()
                ->Where(Criteria::expr()->lt('numero', $this->getPartieConnecte()));

            return $this->Questions->matching($criteria);
        }
        if ($categorieQuestion) {
            $criteria = Criteria::create()
                ->where(Criteria::expr()->eq('categorieQuestion', $categorieQuestion));

            return $this->Questions->matching($criteria);
        }

        return $this->Questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->Questions->contains($question)) {
            $this->Questions->add($question);
            $question->setQuestionnaire($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->Questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getQuestionnaire() === $this) {
                $question->setQuestionnaire(null);
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

    public function getImagePresentation()
    {
        return $this->imagePresentation;
    }

    public function setImagePresentation($imagePresentation): self
    {
        $this->imagePresentation = $imagePresentation;

        return $this;
    }

    public function getRoleConnecte(): array
    {
        return $this->roleConnecte;
    }

    public function setRoleConnecte(?array $roleConnecte): self
    {
        $this->roleConnecte = $roleConnecte;

        return $this;
    }

    public function getNoteTotal(bool $OnlyPartieNonConnecte = false): int
    {
        $somme = 0;
        foreach ($this->getQuestions($OnlyPartieNonConnecte) as $question) {
            $somme += $question->getNoteMax();
        }

        return $somme;
    }

    public function getNoteTotalForEachCategorieQuestion(): array
    {
        $tableauCategories = [];
        foreach ($this->getQuestions() as $question) {
            $categorie = 'null';
            if (null != $question->getCategorieQuestion()) {
                $categorie = $question->getCategorieQuestion()->getNom();
            }
            if (isset($tableauCategories[$categorie])) {
                $tableauCategories[$categorie] += $question->getNoteMax();
            } else {
                $tableauCategories[$categorie] = $question->getNoteMax();
            }
        }

        return $tableauCategories;
    }

    public function checkIfReponsesIsOnAllQuestionnaire(array $reponses, ?array $roles = null): ?bool
    {
        $checkRole = !is_null($roles) && in_array($roles, $this->getRoleConnecte());
        if (is_null($this->getPartieConnecte()) ||
            0 == $this->getPartieConnecte() ||
            1 == $this->getPartieConnecte() ||
            $checkRole) {
            if (count($reponses) == count($this->getQuestions())) {
                return true;
            } else {
                return null;
            }
        }
        if (count($reponses) == count($this->getQuestions())) {
            return true;
        } elseif (count($reponses) == count($this->getQuestions(true))) {
            return false;
        }

        return null;
    }

    public function checkIfReponsesMatch(array $reponses, ?array $roles = null): ?bool
    {
        $allTheQuestionnaire = $this->checkIfReponsesIsOnAllQuestionnaire($reponses, $roles);
        $tabQuestion = [];
        if (is_null($allTheQuestionnaire)) {
            return null;
        }
        foreach ($this->getQuestions(!$allTheQuestionnaire) as $question) {
            $tabQuestion[] = $question->getId();
        }
        foreach ($reponses as $reponse) {
            if ($reponse instanceof Reponse) {
                if (in_array($reponse->getQuestion()->getId(), $tabQuestion)) {
                    unset($tabQuestion[array_search($reponse->getQuestion()->getId(), $tabQuestion)]);
                } else {
                    return null;
                }
            } else {
                return null;
            }
        }

        return $allTheQuestionnaire;
    }

    /**
     * @return Collection<int, Conseil>
     */
    public function getConseils(?CategorieQuestion $categorieQuestion = null): Collection
    {
        if ($categorieQuestion) {
            $criteria = Criteria::create()
                ->where(Criteria::expr()->eq('categorieQuestion', $categorieQuestion));

            return $this->conseils->matching($criteria);
        }

        return $this->conseils;
    }

    public function addConseil(Conseil $conseil): self
    {
        if (!$this->conseils->contains($conseil)) {
            $this->conseils->add($conseil);
            $conseil->setQuestionnaire($this);
        }

        return $this;
    }

    public function removeConseil(Conseil $conseil): self
    {
        if ($this->conseils->removeElement($conseil)) {
            // set the owning side to null (unless already changed)
            if ($conseil->getQuestionnaire() === $this) {
                $conseil->setQuestionnaire(null);
            }
        }

        return $this;
    }

    public function controlUpdateQuestionnaire(): void
    {
        $roleConnecte = $this->getRoleConnecte();
        if (count($roleConnecte) >= 2) {
            if (in_array('ROLE_USER', $roleConnecte)) {
                array_splice($roleConnecte, array_search('ROLE_USER', $roleConnecte), 1);
            }
        }
        if (0 == $this->getPartieConnecte() || is_null($this->getPartieConnecte())) {
            foreach ($this->getConseils() as $conseil) {
                $conseil->setPartieConnecte(null);
            }
        }
        $this->setRoleConnecte($roleConnecte);
    }
}
