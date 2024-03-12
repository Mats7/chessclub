<?php

namespace App\Entity;

use App\Repository\GameRepository;
use App\Entity\Profile;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $winner = null;

    #[ORM\Column(length: 255)]
    private ?string $loser = null;

    #[ORM\Column(length: 255)]
    private ?string $winnerColor = null;

    #[ORM\Column(length: 255)]
    private ?string $loserColor = null;

    #[ORM\Column]
    private ?int $moveCount = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateTime = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWinner(): ?string
    {
        return $this->winner;
    }

    public function setWinner(string $winner): static
    {
        $this->winner = $winner;

        return $this;
    }

    public function getLoser(): ?string
    {
        return $this->loser;
    }

    public function setLoser(string $loser): static
    {
        $this->loser = $loser;

        return $this;
    }

    public function getWinnerColor(): ?string
    {
        return $this->winnerColor;
    }

    public function setWinnerColor(string $winnerColor): static
    {
        $this->winnerColor = $winnerColor;

        return $this;
    }

    public function getLoserColor(): ?string
    {
        return $this->loserColor;
    }

    public function setLoserColor(string $loserColor): static
    {
        $this->loserColor = $loserColor;

        return $this;
    }

    public function getMoveCount(): ?int
    {
        return $this->moveCount;
    }

    public function setMoveCount(int $moveCount): static
    {
        $this->moveCount = $moveCount;

        return $this;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTimeInterface $dateTime): static
    {
        $this->dateTime = $dateTime;

        return $this;
    }
}
