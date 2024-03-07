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
    private ?string $Nick = null;

    #[ORM\Column(length: 255, nullable: true, unique: true)]
    private ?string $Phone = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $Email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Password = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $DateJoined = null;

    #[ORM\Column(nullable: true)]
    private ?int $Wins = null;

    #[ORM\Column(nullable: true)]
    private ?int $Defeats = null;

    #[ORM\Column(nullable: true)]
    private ?float $WhiteWinrate = null;

    #[ORM\Column(nullable: true)]
    private ?float $BlackWinrate = null;

    #[ORM\Column(nullable: true)]
    private ?float $WhiteGames = null;

    #[ORM\Column(nullable: true)]
    private ?float $BlackGames = null;

    public function getRoles(): array
    {
        return ['Default'];
    }

    public function getPassword(): string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): static
    {
        $this->Password = $Password;

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
        return $this->Nick;
    }

    public function getNick(): ?string
    {
        return $this->Nick;
    }

    public function setNick(string $Nick): static
    {
        $this->Nick = strtolower($Nick);

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->Phone;
    }

    public function setPhone(?string $Phone): static
    {
        $this->Phone = $Phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): static
    {
        $this->Email = strtolower($Email);

        return $this;
    }

    public function getDateJoined(): ?\DateTimeInterface
    {
        return $this->DateJoined;
    }

    public function setDateJoined(\DateTimeInterface $DateJoined): static
    {
        $this->DateJoined = $DateJoined;

        return $this;
    }

    public function getWins(): ?int
    {
        return $this->Wins;
    }

    public function setWins(?int $Wins): static
    {
        $this->Wins = $Wins;

        return $this;
    }

    public function getDefeats(): ?int
    {
        return $this->Defeats;
    }

    public function setDefeats(?int $Defeats): static
    {
        $this->Defeats = $Defeats;

        return $this;
    }

    public function getWhiteWinrate(): ?float
    {
        return $this->WhiteWinrate;
    }

    public function setWhiteWinrate(?float $WhiteWinrate): static
    {
        $this->WhiteWinrate = $WhiteWinrate;

        return $this;
    }

    public function getBlackWinrate(): ?float
    {
        return $this->BlackWinrate;
    }

    public function setBlackWinrate(?float $BlackWinrate): static
    {
        $this->BlackWinrate = $BlackWinrate;

        return $this;
    }

    public function getWhiteGames(): ?float
    {
        return $this->WhiteGames;
    }

    public function setWhiteGames(?float $WhiteGames): static
    {
        $this->WhiteGames = $WhiteGames;

        return $this;
    }

    public function getBlackGames(): ?float
    {
        return $this->BlackGames;
    }

    public function setBlackGames(?float $BlackGames): static
    {
        $this->BlackGames = $BlackGames;

        return $this;
    }

    /*public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('email', new Type(\EmailType::class));
    }*/
}
