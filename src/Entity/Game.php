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
    private ?User $user = null;

    /**
     * @var Collection<int, Drawing>
     */
    #[ORM\OneToMany(targetEntity: Drawing::class, mappedBy: 'game', cascade: ['persist', 'remove'])]
    private Collection $drawings;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $date = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'games')]
    private ?Category $category = null;

    public function __construct()
    {
        $this->drawings = new ArrayCollection();
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
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDrawings(): Collection
    {
        return $this->drawings;
    }

    public function addDrawing(Drawing $drawing): self
    {
        if (!$this->drawings->contains($drawing)) {
            dump("je suis ici");
            $this->drawings->add($drawing);
        }
        return $this;
    }

    public function removeDrawing(Drawing $drawing): self
    {
        if (!$this->drawings->contains($drawing)) {
            $this->drawings->removeElement($drawing);
        }
        return $this;

    }


    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): void
    {
        $this->category = $category;
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
