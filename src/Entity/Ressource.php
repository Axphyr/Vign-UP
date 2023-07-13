<?php

namespace App\Entity;

use App\Repository\RessourceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RessourceRepository::class)]
class Ressource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $typeRessource = null;

    #[ORM\Column(length: 30)]
    private ?string $nomRessource = null;

    #[ORM\Column(length: 150)]
    private ?string $descriptif = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $url = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeRessource(): ?string
    {
        return $this->typeRessource;
    }

    public function setTypeRessource(string $typeRessource): self
    {
        $this->typeRessource = $typeRessource;

        return $this;
    }

    public function getNomRessource(): ?string
    {
        return $this->nomRessource;
    }

    public function setNomRessource(string $nomRessource): self
    {
        $this->nomRessource = $nomRessource;

        return $this;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
