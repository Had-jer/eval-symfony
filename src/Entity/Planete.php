<?php

namespace App\Entity;

use App\Repository\PlaneteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlaneteRepository::class)]
class Planete
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $surface = null;

    /**
     * @var Collection<int, Satellite>
     */
    #[ORM\OneToMany(targetEntity: Satellite::class, mappedBy: 'planete')]
    private Collection $satellites;

    public function __construct()
    {
        $this->satellites = new ArrayCollection();
    }

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

    public function getSurface(): ?int
    {
        return $this->surface;
    }

    public function setSurface(int $surface): static
    {
        $this->surface = $surface;

        return $this;
    }

    /**
     * @return Collection<int, Satellite>
     */
    public function getSatellites(): Collection
    {
        return $this->satellites;
    }

    public function addSatellite(Satellite $satellite): static
    {
        if (!$this->satellites->contains($satellite)) {
            $this->satellites->add($satellite);
            $satellite->setPlanete($this);
        }

        return $this;
    }

    public function removeSatellite(Satellite $satellite): static
    {
        if ($this->satellites->removeElement($satellite)) {
            // set the owning side to null (unless already changed)
            if ($satellite->getPlanete() === $this) {
                $satellite->setPlanete(null);
            }
        }

        return $this;
    }
}
