<?php

namespace App\Entity;

use App\Repository\JoinRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="assoc")
     */
    private $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

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

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setAssoc($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->contains($transaction)) {
            $this->transactions->removeElement($transaction);
            // set the owning side to null (unless already changed)
            if ($transaction->getAssoc() === $this) {
                $transaction->setAssoc(null);
            }
        }

        return $this;
    }
}
