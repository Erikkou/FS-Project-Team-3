<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar;

    #[ORM\Column(type: 'integer')]
    private int $scores = 0;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Prediction::class, cascade: ['persist', 'remove'])]
    private Collection $predictions;

    public function __construct()
    {
        $this->predictions = new ArrayCollection();
    }

    public function getPredictions(): Collection
    {
        return $this->predictions;
    }

    public function addPrediction(Prediction $prediction): self
    {
        if (!$this->predictions->contains($prediction)) {
            $this->predictions->add($prediction);
            $prediction->setUser($this);
        }
        return $this;
    }

    public function removePrediction(Prediction $prediction): self
    {
        if ($this->predictions->removeElement($prediction)) {
            if ($prediction->getUser() === $this) {
                $prediction->setUser(null);
            }
        }
        return $this;
    }


    public function getScores(): int
    {
        return $this->scores;
    }

    public function setScores(int $scores): self
    {
        $this->scores = $scores;
        return $this;
    }

    public function addScores(int $points): self
    {
        $this->scores += $points;
        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles(): array
    {
        return array_unique(array_merge($this->roles, [self::ROLE_USER]));
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }
}
