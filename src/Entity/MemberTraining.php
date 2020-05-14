<?php

namespace App\Entity;

use App\Repository\MemberTrainingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MemberTrainingRepository::class)
 */
class MemberTraining
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Member::class, inversedBy="memberTrainings")
     */
    private $member;

    /**
     * @ORM\ManyToOne(targetEntity=Training::class, inversedBy="memberTrainings")
     */
    private $training;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $finished_training;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(?Member $member): self
    {
        $this->member = $member;

        return $this;
    }

    public function getTraining(): ?Training
    {
        return $this->training;
    }

    public function setTraining(?Training $training): self
    {
        $this->training = $training;

        return $this;
    }

    public function getFinishedTraining(): ?bool
    {
        return $this->finished_training;
    }

    public function setFinishedTraining(?bool $finished_training): self
    {
        $this->finished_training = $finished_training;

        return $this;
    }
}
