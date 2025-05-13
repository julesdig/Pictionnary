<?php

namespace App\Entity;

use App\Repository\GameRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $score = 0;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User = null;

    /**
     * @var Collection<int, Drawing>
     */
    #[ORM\ManyToMany(targetEntity: Drawing::class, inversedBy: 'games')]
    private Collection $Drawing;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $date = null;

    public function __construct()
    {
        $this->Drawing = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }

    /**
     * @return Collection<int, Drawing>
     */
    public function getDrawing(): Collection
    {
        return $this->Drawing;
    }

    public function addDrawing(Drawing $drawing): static
    {
        if (!$this->Drawing->contains($drawing)) {
            $this->Drawing->add($drawing);
        }

        return $this;
    }

    public function removeDrawing(Drawing $drawing): static
    {
        $this->Drawing->removeElement($drawing);

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }
}
