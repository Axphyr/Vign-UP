<?php

namespace App\Entity;

use App\Repository\TemoignageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TemoignageRepository::class)]
class Temoignage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 300)]
    private ?string $contenuTemoignage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenuTemoignage(): ?string
    {
        return $this->contenuTemoignage;
    }

    public function setContenuTemoignage(string $contenuTemoignage): self
    {
        $this->contenuTemoignage = $contenuTemoignage;

        return $this;
    }
}
