<?php

namespace App\Entity;

use App\Repository\VSLRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VSLRepository::class)]
class VSL implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $latitude = null;

    #[ORM\Column]
    private ?float $longitude = null;

    #[ORM\Column]
    private ?float $superficie = null;

    #[ORM\Column(length: 150)]
    private ?string $description = null;

    #[ORM\Column]
    private int $approved = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getApproved(): ?int
    {
        return $this->approved;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSuperficie(): ?float
    {
        return $this->superficie;
    }

    public function setSuperficie(float $superficie): self
    {
        $this->superficie = $superficie;

        return $this;
    }

    public function setApproved(int $approved): self
    {
        $this->approved = $approved;

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude(),
            'description' => $this->getDescription(),
        ];
    }
}
