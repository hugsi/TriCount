<?php

namespace App\Entity;

use App\Repository\ArdoiseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArdoiseRepository::class)
 */
class Ardoise
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Join::class, mappedBy="ardoise", orphanRemoval=true)
     */
    private $joins;

    public function __construct()
    {
        $this->joins = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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

    /**
     * @return Collection|Join[]
     */
    public function getJoins(): Collection
    {
        return $this->joins;
    }

    public function addJoin(Join $join): self
    {
        if (!$this->joins->contains($join)) {
            $this->joins[] = $join;
            $join->setArdoise($this);
        }

        return $this;
    }

    public function removeJoin(Join $join): self
    {
        if ($this->joins->contains($join)) {
            $this->joins->removeElement($join);
            // set the owning side to null (unless already changed)
            if ($join->getArdoise() === $this) {
                $join->setArdoise(null);
            }
        }

        return $this;
    }
}
