<?php

namespace App\Entity;

use App\Repository\CalendarRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CalendarRepository::class)]
class Calendar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Team::class)]
    #[ORM\JoinColumn(name: "home_team", referencedColumnName: "id", nullable: false)]
    private ?Team $homeTeam = null;

    #[ORM\ManyToOne(targetEntity: Team::class)]
    #[ORM\JoinColumn(name: "away_team", referencedColumnName: "id", nullable: false)]
    private ?Team $awayTeam = null;

    #[ORM\ManyToOne(targetEntity: Stadium::class)]
    #[ORM\JoinColumn(name: "stadium_id", referencedColumnName: "id", nullable: false)]
    private ?Stadium $stadium = null;

    #[ORM\ManyToOne(targetEntity: Rounds::class, inversedBy: "calendars")]
    #[ORM\JoinColumn(name: "round_id", referencedColumnName: "id", nullable: false)]
    private ?Rounds $round = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $starting_at = null;

    #[ORM\Column(type: 'string', length: 50)]
    private string $status = 'scheduled'; // Mogelijke waarden: 'scheduled', 'finished', 'canceled'

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $homeScore = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $awayScore = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getHomeTeam(): ?Team
    {
        return $this->homeTeam;
    }

    public function setHomeTeam(?Team $homeTeam): static
    {
        $this->homeTeam = $homeTeam;
        return $this;
    }

    public function getAwayTeam(): ?Team
    {
        return $this->awayTeam;
    }

    public function setAwayTeam(?Team $awayTeam): static
    {
        $this->awayTeam = $awayTeam;
        return $this;
    }

    public function getStadium(): ?Stadium
    {
        return $this->stadium;
    }

    public function setStadium(?Stadium $stadium): static
    {
        $this->stadium = $stadium;
        return $this;
    }

    public function getRound(): ?Rounds
    {
        return $this->round;
    }

    public function setRound(?Rounds $round): static
    {
        $this->round = $round;
        return $this;
    }

    public function getStartingAt(): ?\DateTimeInterface
    {
        return $this->starting_at;
    }

    public function setStartingAt(\DateTimeInterface $starting_at): static
    {
        $this->starting_at = $starting_at;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getHomeScore(): ?int
    {
        return $this->homeScore;
    }

    public function setHomeScore(?int $homeScore): static
    {
        $this->homeScore = $homeScore;
        return $this;
    }

    public function getAwayScore(): ?int
    {
        return $this->awayScore;
    }

    public function setAwayScore(?int $awayScore): static
    {
        $this->awayScore = $awayScore;
        return $this;
    }
}
