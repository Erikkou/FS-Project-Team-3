<?php

namespace App\Entity;

use App\Repository\CalendarRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CalendarRepository::class)]
class Calendar
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $home_team = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $away_team = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $stadium_id = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $round_id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $starting_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getHomeTeam(): ?int
    {
        return $this->home_team;
    }

    public function setHomeTeam(?int $home_team): static
    {
        $this->home_team = $home_team;

        return $this;
    }

    public function getAwayTeam(): ?int
    {
        return $this->away_team;
    }

    public function setAwayTeam(?int $away_team): static
    {
        $this->away_team = $away_team;

        return $this;
    }

    public function getStadiumId(): ?int
    {
        return $this->stadium_id;
    }

    public function setStadiumId(?int $stadium_id): static
    {
        $this->stadium_id = $stadium_id;

        return $this;
    }

    public function getRoundId(): ?int
    {
        return $this->round_id;
    }

    public function setRoundId(?int $round_id): static
    {
        $this->round_id = $round_id;

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
}
