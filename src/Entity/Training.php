<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrainingRepository")
 */
class Training
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     */
    private $start_date;

    /**
     * @ORM\Column(type="date")
     */
    private $end_date;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="boolean")
     */
    private $free;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Trainer", inversedBy="trainings")
     */
    private $trainer;

    /**
     * @ORM\OneToMany(targetEntity=MemberTraining::class, mappedBy="training")
     */
    private $memberTrainings;

    /**
     * @ORM\OneToMany(targetEntity=Rating::class, mappedBy="training")
     */
    private $ratings;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Rating;

    public function __construct()
    {
        $this->memberTrainings = new ArrayCollection();
        $this->ratings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getFree(): ?bool
    {
        return $this->free;
    }

    public function setFree(bool $free): self
    {
        $this->free = $free;

        return $this;
    }

    public function getTrainer(): ?trainer
    {
        return $this->trainer;
    }

    public function setTrainer(?trainer $trainer): self
    {
        $this->trainer = $trainer;

        return $this;
    }

    /**
     * @return Collection|MemberTraining[]
     */
    public function getMemberTrainings(): Collection
    {
        return $this->memberTrainings;
    }

    public function addMemberTraining(MemberTraining $memberTraining): self
    {
        if (!$this->memberTrainings->contains($memberTraining)) {
            $this->memberTrainings[] = $memberTraining;
            $memberTraining->setTraining($this);
        }

        return $this;
    }

    public function removeMemberTraining(MemberTraining $memberTraining): self
    {
        if ($this->memberTrainings->contains($memberTraining)) {
            $this->memberTrainings->removeElement($memberTraining);
            // set the owning side to null (unless already changed)
            if ($memberTraining->getTraining() === $this) {
                $memberTraining->setTraining(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Rating[]
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings[] = $rating;
            $rating->setTraining($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->contains($rating)) {
            $this->ratings->removeElement($rating);
            // set the owning side to null (unless already changed)
            if ($rating->getTraining() === $this) {
                $rating->setTraining(null);
            }
        }

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->Rating;
    }

    public function setRating(?float $Rating): self
    {
        $this->Rating = $Rating;

        return $this;
    }
}
