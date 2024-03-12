<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $nick = null;

    #[ORM\Column(length: 255, nullable: true, unique: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateJoined = null;

    #[ORM\Column(nullable: true)]
    private ?int $wins = null;

    #[ORM\Column(nullable: true)]
    private ?int $defeats = null;

    #[ORM\Column(nullable: true)]
    private ?float $whiteWinrate = null;

    #[ORM\Column(nullable: true)]
    private ?float $blackWinrate = null;

    #[ORM\Column(nullable: true)]
    private ?float $whiteGames = null;

    #[ORM\Column(nullable: true)]
    private ?float $blackGames = null;

    public function getRoles(): array
    {
        return ['Default'];
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt()
    {
        // leaving blank - I don't need/have a password!
    }
    public function eraseCredentials(): void
    {
        // leaving blank - I don't need/have a password!
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return $this->nick;
    }

    public function getNick(): ?string
    {
        return $this->nick;
    }

    public function setNick(string $nick): static
    {
        $this->nick = strtolower($nick);

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = strtolower($email);

        return $this;
    }

    public function getdateJoined(): ?\DateTimeInterface
    {
        return $this->dateJoined;
    }

    public function setdateJoined(\DateTimeInterface $dateJoined): static
    {
        $this->dateJoined = $dateJoined;

        return $this;
    }

    public function getWins(): ?int
    {
        return $this->wins;
    }

    public function setWins(?int $wins): static
    {
        $this->wins = $wins;

        return $this;
    }

    public function getDefeats(): ?int
    {
        return $this->defeats;
    }

    public function setDefeats(?int $defeats): static
    {
        $this->defeats = $defeats;

        return $this;
    }

    public function getWhiteWinrate(): ?float
    {
        return $this->whiteWinrate;
    }

    public function setWhiteWinrate(?float $whiteWinrate): static
    {
        $this->whiteWinrate = $whiteWinrate;

        return $this;
    }

    public function getBlackWinrate(): ?float
    {
        return $this->blackWinrate;
    }

    public function setBlackWinrate(?float $blackWinrate): static
    {
        $this->blackWinrate = $blackWinrate;

        return $this;
    }

    public function getWhiteGames(): ?float
    {
        return $this->whiteGames;
    }

    public function setWhiteGames(?float $whiteGames): static
    {
        $this->whiteGames = $whiteGames;

        return $this;
    }

    public function getBlackGames(): ?float
    {
        return $this->blackGames;
    }

    public function setBlackGames(?float $blackGames): static
    {
        $this->blackGames = $blackGames;

        return $this;
    }
}
