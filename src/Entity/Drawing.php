<?php

namespace App\Entity;

use App\Repository\DrawingRepository;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: DrawingRepository::class)]
class Drawing
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    private string $word;

    #[ORM\Column(type: 'boolean')]
    #[Assert\Type('bool')]
    private bool $recognized = false;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotNull]
    #[Assert\Type(DateTimeInterface::class)]
    private DateTimeInterface $timestamp;

    #[ORM\Column(type: 'string', length: 2)]
    #[Assert\Country]
    #[Assert\Length(exactly: 2)]
    private string $countryCode = 'FR';

    #[ORM\Column(type: 'json')]
    #[Assert\NotNull]
    #[Assert\Type('array')]
    #[Assert\Count(min: 1)]
    private array $drawing = [];

    #[ORM\ManyToOne(targetEntity: Game::class, inversedBy: 'drawings')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Game $game = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isStarted = false;

    public function __construct()
    {
        $this->timestamp = new DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getWord(): string
    {
        return $this->word;
    }

    public function setWord(string $word): self
    {
        $this->word = $word;
        return $this;
    }

    public function isRecognized(): bool
    {
        return $this->recognized;
    }

    public function setRecognized(bool $recognized): self
    {
        $this->recognized = $recognized;
        return $this;
    }

    public function getTimestamp(): DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    public function getDrawing(): array
    {
        return $this->drawing;
    }

    public function setDrawing(array $drawing): self
    {
        $this->drawing = $drawing;
        return $this;
    }

    public function isStarted(): bool
    {
        return $this->isStarted;
    }

    public function setIsStarted(bool $isStarted): void
    {
        $this->isStarted = $isStarted;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): void
    {
        $this->game = $game;
    }
}