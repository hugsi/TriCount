<?php

namespace App\Entity;

use App\Repository\JoinRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JoinRepository::class)
 * @ORM\Table(name="`join`")
 */
class Join
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Ardoise::class, inversedBy="joins")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ardoise;

    /**
     * @ORM\ManyToOne(targetEntity=Participant::class, inversedBy="joins")
     * @ORM\JoinColumn(nullable=false)
     */
    private $participant;


    public function getArdoise(): ?ardoise
    {
        return $this->ardoise;
    }

    public function setArdoise(?ardoise $ardoise): self
    {
        $this->ardoise = $ardoise;

        return $this;
    }

    public function getParticipant(): ?participant
    {
        return $this->participant;
    }

    public function setParticipant(?participant $participant): self
    {
        $this->participant = $participant;

        return $this;
    }
}
