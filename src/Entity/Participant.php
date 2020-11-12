<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 */
class Participant
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
     * @ORM\OneToMany(targetEntity=Join::class, mappedBy="participant", orphanRemoval=true)
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
            $join->setParticipant($this);
        }

        return $this;
    }

    public function removeJoin(Join $join): self
    {
        if ($this->joins->contains($join)) {
            $this->joins->removeElement($join);
            // set the owning side to null (unless already changed)
            if ($join->getParticipant() === $this) {
                $join->setParticipant(null);
            }
        }

        return $this;
    }
}
