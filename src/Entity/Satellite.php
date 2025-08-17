<?php

namespace App\Entity;

use App\Repository\SatelliteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SatelliteRepository::class)]
class Satellite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $diameter = null;

    #[ORM\ManyToOne(inversedBy: 'satellites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Planete $planete = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDiameter(): ?int
    {
        return $this->diameter;
    }

    public function setDiameter(int $diameter): static
    {
        $this->diameter = $diameter;

        return $this;
    }

    public function getPlanete(): ?Planete
    {
        return $this->planete;
    }

    public function setPlanete(?Planete $planete): static
    {
        $this->planete = $planete;

        return $this;
    }
}
