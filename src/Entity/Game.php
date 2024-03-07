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
    private ?string $Winner = null;

    #[ORM\Column(length: 255)]
    private ?string $Loser = null;

    #[ORM\Column(length: 255)]
    private ?string $WinnerColor = null;

    #[ORM\Column(length: 255)]
    private ?string $LoserColor = null;

    #[ORM\Column]
    private ?int $MoveCount = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $DateTime = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWinner(): ?string
    {
        return $this->Winner;
    }

    public function setWinner(string $Winner): static
    {
        $this->Winner = $Winner;

        return $this;
    }

    public function getLoser(): ?string
    {
        return $this->Loser;
    }

    public function setLoser(string $Loser): static
    {
        $this->Loser = $Loser;

        return $this;
    }

    public function getWinnerColor(): ?string
    {
        return $this->WinnerColor;
    }

    public function setWinnerColor(string $WinnerColor): static
    {
        $this->WinnerColor = $WinnerColor;

        return $this;
    }

    public function getLoserColor(): ?string
    {
        return $this->LoserColor;
    }

    public function setLoserColor(string $LoserColor): static
    {
        $this->LoserColor = $LoserColor;

        return $this;
    }

    public function getMoveCount(): ?int
    {
        return $this->MoveCount;
    }

    public function setMoveCount(int $MoveCount): static
    {
        $this->MoveCount = $MoveCount;

        return $this;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->DateTime;
    }

    public function setDateTime(\DateTimeInterface $DateTime): static
    {
        $this->DateTime = $DateTime;

        return $this;
    }
}
