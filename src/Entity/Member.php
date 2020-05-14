<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MemberRepository")
 */
class Member implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $number;

    /**
     * @ORM\OneToMany(targetEntity=MemberTraining::class, mappedBy="member")
     */
    private $memberTrainings;

    public function __construct()
    {
        $this->memberTrainings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): ?array
    {
        return array();
    }

    public function setRoles(array $roles): self
    {
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

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
            $memberTraining->setMember($this);
        }

        return $this;
    }

    public function removeMemberTraining(MemberTraining $memberTraining): self
    {
        if ($this->memberTrainings->contains($memberTraining)) {
            $this->memberTrainings->removeElement($memberTraining);
            // set the owning side to null (unless already changed)
            if ($memberTraining->getMember() === $this) {
                $memberTraining->setMember(null);
            }
        }

        return $this;
    }
}
