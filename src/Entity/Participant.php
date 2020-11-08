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
     * @ORM\Column(type="string", length=40)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Ardoise::class, mappedBy="participant")
     */
    private $ardoises;

    /**
     * @ORM\OneToMany(targetEntity=Join::class, mappedBy="participant")
     */
    private $joins;

    public function __construct()
    {
        $this->ardoises = new ArrayCollection();
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
     * @return Collection|Ardoise[]
     */
    public function getArdoises(): Collection
    {
        return $this->ardoises;
    }

    public function addArdoise(Ardoise $ardoise): self
    {
        if (!$this->ardoises->contains($ardoise)) {
            $this->ardoises[] = $ardoise;
            $ardoise->setParticipant($this);
        }

        return $this;
    }

    public function removeArdoise(Ardoise $ardoise): self
    {
        if ($this->ardoises->contains($ardoise)) {
            $this->ardoises->removeElement($ardoise);
            // set the owning side to null (unless already changed)
            if ($ardoise->getParticipant() === $this) {
                $ardoise->setParticipant(null);
            }
        }

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
