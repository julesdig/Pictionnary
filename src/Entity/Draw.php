<?php

namespace App\Entity;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
class Draw
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $word;

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $timestamp;
    #[ORM\Column(type: 'json')]
    private array $drawing;

}