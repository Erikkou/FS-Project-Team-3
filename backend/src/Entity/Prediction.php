<?php

namespace App\Entity;

use App\Repository\PredictionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PredictionRepository::class)]
class Prediction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'predictions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Calendar::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Calendar $match = null;

    #[ORM\Column(type: Types::INTEGER)]
    private int $homeTeamScore;

    #[ORM\Column(type: Types::INTEGER)]
    private int $awayTeamScore;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $points = 0;

    #[ORM\Column(type: 'string', length: 20)]
    private string $status;


    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getMatch(): ?Calendar
    {
        return $this->match;
    }

    public function setMatch(Calendar $match): self
    {
        $this->match = $match;
        return $this;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;
        return $this;
    }

    public function setAwayTeamScore(int $awayTeamScore): void
    {
        $this->awayTeamScore = $awayTeamScore;
    }

    public function setHomeTeamScore(int $homeTeamScore): void
    {
        $this->homeTeamScore = $homeTeamScore;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function calculatePoints(int $actualHomeScore, int $actualAwayScore): void
    {
        if ($this->homeTeamScore === $actualHomeScore && $this->awayTeamScore === $actualAwayScore) {
            $this->points = 9; // Exacte score goed
        } elseif (
            ($this->homeTeamScore > $this->awayTeamScore && $actualHomeScore > $actualAwayScore) ||
            ($this->homeTeamScore < $this->awayTeamScore && $actualHomeScore < $actualAwayScore)
        ) {
            // Juiste winnaar, controleren of het doelsaldo ook klopt
            if (($this->homeTeamScore - $this->awayTeamScore) === ($actualHomeScore - $actualAwayScore)) {
                $this->points = 6; // Juiste winnaar, correcte doelsaldo, maar verkeerde score
            } else {
                $this->points = 3; // Juiste winnaar, maar volledig verkeerde score
            }
        } else {
            $this->points = 0; // Helemaal fout
        }
    }

}