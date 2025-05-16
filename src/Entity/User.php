<?php

namespace App\Entity;

use App\Model\Enum\SecurityRoleEnum;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\PasswordStrength;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity('email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;
    #[Assert\Email(message: "user.email.type")]
    #[Assert\NotBlank(message: "user.email.not_blank")]
    #[ORM\Column(length: 180)]
    private string $email ;

    #[Assert\NotBlank(message: "user.firstname.not_blank")]
    #[Assert\Length(min: 3, max: 50, minMessage: 'user.firstname.min')]
    #[ORM\Column(type: 'text', length: 50, nullable: false)]
    private string $firstName ;
    #[Assert\NotBlank(message: "user.lastname.not_blank")]
    #[Assert\Length(min: 3, max: 50, minMessage: 'user.lastname.min')]
    #[ORM\Column(type: 'text', length: 50, nullable: false)]
    private string $lastName;
    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];
    #[Assert\Length(min: 12, max: 255, minMessage: 'user.password.min')]
    #[Assert\PasswordStrength(minScore: PasswordStrength::STRENGTH_VERY_STRONG)]
    #[Assert\NotCompromisedPassword]
    #[Assert\Regex(
        pattern: '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.* )(?=.*[^a-zA-Z0-9]).{12,}$/',
        message: 'user.password.regex'
    )]
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $password;

    /**
     * @var Collection<int, Game>
     */
    #[ORM\OneToMany(targetEntity: Game::class, mappedBy: 'User')]
    private Collection $games;

    public function __construct()
    {
        $this->games = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * A visual identifier that represents this user.
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        if(empty($roles)) {
            return [SecurityRoleEnum::User->value];
        }


        return $roles;
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }


    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): static
    {
        if (!$this->games->contains($game)) {
            $this->games->add($game);
            $game->setUser($this);
        }

        return $this;
    }

    public function removeGame(Game $game): static
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getUser() === $this) {
                $game->setUser(null);
            }
        }

        return $this;
    }
}
